<?php

declare(strict_types=1);

namespace App\Support;

use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasWebpConversions
{
    use InteractsWithMedia;

    /**
     * Register automatic WebP conversions across standard responsive widths (DESIGN.md Section 11).
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $widths = [
            'sm' => 480,
            'md' => 768,
            'lg' => 1200,
            'xl' => 1920,
        ];

        foreach ($widths as $name => $width) {
            $this->addMediaConversion($name)
                ->format('webp')
                ->width($width)
                ->nonQueued();
        }
    }
}
