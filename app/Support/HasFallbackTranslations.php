<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\App;
use Spatie\Translatable\HasTranslations;

trait HasFallbackTranslations
{
    use HasTranslations;

    /**
     * Resolve a translated attribute with automatic fallback to 'id' when empty.
     */
    public function translateField(string $field, ?string $locale = null): string
    {
        $targetLocale = $locale ?? App::getLocale();
        $value = $this->getTranslation($field, $targetLocale, false);

        if (is_string($value) && trim($value) !== '') {
            return $value;
        }

        $fallback = $this->getTranslation($field, 'id', false);

        return is_string($fallback) ? $fallback : '';
    }

    /**
     * Determine whether any translatable attribute is missing its translation in the given locale.
     */
    public function hasMissingTranslation(string $locale = 'en'): bool
    {
        foreach ($this->getTranslatableAttributes() as $attribute) {
            $idValue = $this->getTranslation($attribute, 'id', false);
            $targetValue = $this->getTranslation($attribute, $locale, false);

            if (is_string($idValue) && trim($idValue) !== '' && (! is_string($targetValue) || trim($targetValue) === '')) {
                return true;
            }
        }

        return false;
    }
}
