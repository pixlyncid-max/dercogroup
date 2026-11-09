<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Generates lightweight, valid PDF 1.4 sample documents for placeholder certifications.
 */
class SamplePdfGenerator
{
    /**
     * Ensure a sample PDF exists on the public disk and return its relative path.
     */
    public static function ensureDocument(string $relativePath, string $title, string $subtitle): string
    {
        if (! Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->put($relativePath, self::buildPdfBytes($title, $subtitle));
        }

        return $relativePath;
    }

    /**
     * Build minimal valid PDF 1.4 binary content with two lines of text.
     */
    public static function buildPdfBytes(string $title, string $subtitle): string
    {
        $safeTitle = self::escapePdfText($title);
        $safeSubtitle = self::escapePdfText($subtitle);

        $stream = "BT /F1 16 Tf 50 750 Td ({$safeTitle}) Tj 0 -24 Td /F1 11 Tf ({$safeSubtitle}) Tj ET";
        $streamLength = strlen($stream);

        $objects = [
            "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n",
            "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n",
            "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>\nendobj\n",
            "4 0 obj\n<< /Length {$streamLength} >>\nstream\n{$stream}\nendstream\nendobj\n",
            "5 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n",
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [];

        foreach ($objects as $object) {
            $offsets[] = strlen($pdf);
            $pdf .= $object;
        }

        $xrefOffset = strlen($pdf);
        $count = count($objects) + 1;
        $pdf .= "xref\n0 {$count}\n";
        $pdf .= "0000000000 65535 f \n";

        foreach ($offsets as $offset) {
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }

        $pdf .= "trailer\n<< /Size {$count} /Root 1 0 R >>\n";
        $pdf .= "startxref\n{$xrefOffset}\n%%EOF\n";

        return $pdf;
    }

    private static function escapePdfText(string $text): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }
}
