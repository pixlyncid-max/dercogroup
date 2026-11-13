<?php

declare(strict_types=1);

namespace App\Domain\Media\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProcessUploadedMediaAction
{
    public const MAX_IMAGE_SIZE_BYTES = 5 * 1024 * 1024; // 5MB

    public const MAX_DOCUMENT_SIZE_BYTES = 10 * 1024 * 1024; // 10MB

    public const ALLOWED_IMAGE_MIMES = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/svg+xml',
    ];

    public const ALLOWED_DOCUMENT_MIMES = [
        'application/pdf',
    ];

    /**
     * @return array{
     *     path: string,
     *     type: 'image'|'document',
     *     original_name: string,
     *     size: int,
     *     format: string
     * }
     *
     * @throws ValidationException
     */
    public function execute(UploadedFile $file): array
    {
        $mime = $file->getMimeType() ?: '';
        $size = $file->getSize() ?: 0;
        $extension = strtolower($file->getClientOriginalExtension());

        // 1. Validate Documents (PDF)
        if (in_array($mime, self::ALLOWED_DOCUMENT_MIMES, true) || $extension === 'pdf') {
            if ($size > self::MAX_DOCUMENT_SIZE_BYTES) {
                throw ValidationException::withMessages([
                    'file' => 'Ukuran dokumen melebihi batas maksimal 10MB.',
                ]);
            }

            $path = $file->store('documents', 'public');

            return [
                'path' => $path,
                'type' => 'document',
                'original_name' => $file->getClientOriginalName(),
                'size' => $size,
                'format' => 'pdf',
            ];
        }

        // 2. Validate Images (JPG, PNG, WebP, SVG)
        $isImageMime = in_array($mime, self::ALLOWED_IMAGE_MIMES, true);
        $isImageExt = in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'svg'], true);

        if (! $isImageMime && ! $isImageExt) {
            throw ValidationException::withMessages([
                'file' => 'Format file tidak didukung. Harap unggah format JPG, PNG, WebP, SVG, atau dokumen PDF.',
            ]);
        }

        if ($size > self::MAX_IMAGE_SIZE_BYTES) {
            throw ValidationException::withMessages([
                'file' => 'Ukuran gambar melebihi batas maksimal 5MB.',
            ]);
        }

        // SVG and WebP are stored directly
        if ($extension === 'svg' || $mime === 'image/svg+xml') {
            $path = $file->store('blocks', 'public');

            return [
                'path' => $path,
                'type' => 'image',
                'original_name' => $file->getClientOriginalName(),
                'size' => $size,
                'format' => 'svg',
            ];
        }

        if ($extension === 'webp' || $mime === 'image/webp') {
            $path = $file->store('blocks', 'public');

            return [
                'path' => $path,
                'type' => 'image',
                'original_name' => $file->getClientOriginalName(),
                'size' => $size,
                'format' => 'webp',
            ];
        }

        // Convert JPG/PNG to optimized WebP
        $convertedPath = $this->convertToWebp($file);

        return [
            'path' => $convertedPath,
            'type' => 'image',
            'original_name' => $file->getClientOriginalName(),
            'size' => $size,
            'format' => 'webp',
        ];
    }

    /**
     * Converts a JPG or PNG file to WebP with transparency support.
     */
    protected function convertToWebp(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $sourcePath = $file->getRealPath();

        if (function_exists('imagewebp') && file_exists($sourcePath)) {
            $gdImage = match ($extension) {
                'png' => @imagecreatefrompng($sourcePath),
                'jpg', 'jpeg' => @imagecreatefromjpeg($sourcePath),
                default => null,
            };

            if ($gdImage !== false && $gdImage !== null) {
                imagealphablending($gdImage, false);
                imagesavealpha($gdImage, true);

                $hash = md5(uniqid((string) mt_rand(), true));
                $relativeWebpPath = "blocks/{$hash}.webp";
                $tempDestination = tempnam(sys_get_temp_dir(), 'webp_');

                if ($tempDestination && imagewebp($gdImage, $tempDestination, 85)) {
                    imagedestroy($gdImage);
                    $content = file_get_contents($tempDestination);
                    if ($content !== false) {
                        Storage::disk('public')->put($relativeWebpPath, $content);
                        @unlink($tempDestination);

                        return $relativeWebpPath;
                    }
                }

                imagedestroy($gdImage);
            }
        }

        return $file->store('blocks', 'public');
    }
}

