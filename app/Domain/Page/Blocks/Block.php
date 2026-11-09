<?php

declare(strict_types=1);

namespace App\Domain\Page\Blocks;

use App\Domain\Page\Enums\BlockType;
use App\Support\FilamentTranslatable;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Single source of truth for a page block's schema, validation rules, and Filament form components (FR-CMS-3).
 */
abstract class Block
{
    abstract public static function type(): BlockType;

    /**
     * Single declarative field schema for this block type.
     *
     * Supported field types:
     * - translatable_text
     * - translatable_textarea
     * - translatable_richtext
     * - text
     * - number
     * - boolean
     * - select (with 'options' => array<string, string>)
     * - image (automatically enforces required alt_text when image is uploaded)
     * - repeater (with 'fields' => list<array<string, mixed>>)
     *
     * @return list<array<string, mixed>>
     */
    abstract public static function fields(): array;

    /**
     * Generate Laravel validation rules from static::fields().
     *
     * @return array<string, mixed>
     */
    public static function rules(string $prefix = ''): array
    {
        return self::buildRulesFromFields(static::fields(), $prefix);
    }

    /**
     * Validate and normalize block payload before persistence.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     *
     * @throws ValidationException
     */
    public static function validateAndNormalize(array $data): array
    {
        $merged = self::applyDefaults(static::fields(), $data);
        $rules = static::rules();

        if ($rules === []) {
            return $merged;
        }

        Validator::make($merged, $rules)->validate();

        return $merged;
    }

    /**
     * Generate Filament form schema from static::fields().
     *
     * @return list<Component>
     */
    public static function filamentSchema(string $statePathPrefix = 'data'): array
    {
        return self::buildFilamentComponents(static::fields(), $statePathPrefix);
    }

    /**
     * Resolve localized values from raw block JSON data for the active locale (with fallback to 'id').
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function localizeData(array $data, string $locale = 'id'): array
    {
        return self::localizeFields(static::fields(), $data, $locale);
    }

    /**
     * @param  list<array<string, mixed>>  $fields
     * @return array<string, mixed>
     */
    private static function buildRulesFromFields(array $fields, string $prefix = ''): array
    {
        $rules = [];

        foreach ($fields as $field) {
            $name = (string) $field['name'];
            $path = $prefix !== '' ? "{$prefix}.{$name}" : $name;
            $type = (string) $field['type'];
            $required = (bool) ($field['required'] ?? false);

            switch ($type) {
                case 'translatable_text':
                case 'translatable_textarea':
                case 'translatable_richtext':
                    $rules[$path] = [$required ? 'required' : 'nullable', 'array'];
                    $rules["{$path}.id"] = [$required ? 'required' : 'nullable', 'string'];
                    $rules["{$path}.en"] = ['nullable', 'string'];
                    break;

                case 'text':
                    $rules[$path] = [$required ? 'required' : 'nullable', 'string'];
                    break;

                case 'number':
                    $rules[$path] = [$required ? 'required' : 'nullable', 'numeric'];
                    break;

                case 'boolean':
                    $rules[$path] = [$required ? 'required' : 'nullable', 'boolean'];
                    break;

                case 'select':
                    /** @var array<string, string> $options */
                    $options = is_array($field['options'] ?? null) ? $field['options'] : [];
                    $rules[$path] = [
                        $required ? 'required' : 'nullable',
                        'string',
                        Rule::in(array_keys($options)),
                    ];
                    break;

                case 'image':
                    $altKey = "{$path}_alt";
                    $rules[$path] = [$required ? 'required' : 'nullable', 'string'];
                    $rules[$altKey] = ['nullable', 'array'];
                    $rules["{$altKey}.id"] = [
                        $required ? 'required' : "required_with:{$path}",
                        'nullable',
                        'string',
                    ];
                    $rules["{$altKey}.en"] = ['nullable', 'string'];
                    break;

                case 'repeater':
                    /** @var list<array<string, mixed>> $subFields */
                    $subFields = is_array($field['fields'] ?? null) ? $field['fields'] : [];
                    $min = isset($field['min']) ? (int) $field['min'] : ($required ? 1 : 0);
                    $repeaterRule = [$required ? 'required' : 'nullable', 'array'];
                    if ($min > 0 && $required) {
                        $repeaterRule[] = "min:{$min}";
                    }
                    $rules[$path] = $repeaterRule;
                    $subRules = self::buildRulesFromFields($subFields, "{$path}.*");
                    $rules = array_merge($rules, $subRules);
                    break;
            }
        }

        return $rules;
    }

    /**
     * @param  list<array<string, mixed>>  $fields
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function applyDefaults(array $fields, array $data): array
    {
        foreach ($fields as $field) {
            $name = (string) $field['name'];
            if (! array_key_exists($name, $data) && array_key_exists('default', $field)) {
                $data[$name] = $field['default'];
            }
        }

        return $data;
    }

    /**
     * @param  list<array<string, mixed>>  $fields
     * @return list<Component>
     */
    private static function buildFilamentComponents(array $fields, string $prefix = ''): array
    {
        $components = [];

        foreach ($fields as $field) {
            $name = (string) $field['name'];
            $path = $prefix !== '' ? "{$prefix}.{$name}" : $name;
            $label = (string) ($field['label'] ?? $name);
            $type = (string) $field['type'];
            $required = (bool) ($field['required'] ?? false);

            switch ($type) {
                case 'translatable_text':
                    $components[] = FilamentTranslatable::textInput($path, $label, $required);
                    break;

                case 'translatable_textarea':
                    $components[] = FilamentTranslatable::textarea($path, $label, $required);
                    break;

                case 'translatable_richtext':
                    $components[] = FilamentTranslatable::richEditor($path, $label, $required);
                    break;

                case 'text':
                    $components[] = TextInput::make($path)
                        ->label($label)
                        ->required($required);
                    break;

                case 'number':
                    $components[] = TextInput::make($path)
                        ->label($label)
                        ->numeric()
                        ->required($required);
                    break;

                case 'boolean':
                    $components[] = Toggle::make($path)
                        ->label($label)
                        ->default((bool) ($field['default'] ?? false));
                    break;

                case 'select':
                    /** @var array<string, string> $options */
                    $options = is_array($field['options'] ?? null) ? $field['options'] : [];
                    $components[] = Select::make($path)
                        ->label($label)
                        ->options($options)
                        ->required($required);
                    break;

                case 'image':
                    $components[] = FileUpload::make($path)
                        ->label($label)
                        ->image()
                        ->disk('public')
                        ->directory('blocks')
                        ->required($required);
                    $components[] = FilamentTranslatable::textInput(
                        "{$path}_alt",
                        "Teks Alternatif {$label}",
                        $required,
                    );
                    break;

                case 'repeater':
                    /** @var list<array<string, mixed>> $subFields */
                    $subFields = is_array($field['fields'] ?? null) ? $field['fields'] : [];
                    $components[] = Repeater::make($path)
                        ->label($label)
                        ->schema(self::buildFilamentComponents($subFields, ''))
                        ->columnSpanFull();
                    break;
            }
        }

        return $components;
    }

    /**
     * @param  list<array<string, mixed>>  $fields
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function localizeFields(array $fields, array $data, string $locale): array
    {
        $resolved = $data;

        foreach ($fields as $field) {
            $name = (string) $field['name'];
            $type = (string) $field['type'];

            if (in_array($type, ['translatable_text', 'translatable_textarea', 'translatable_richtext'], true)) {
                $resolved[$name] = self::extractTranslatedValue($data[$name] ?? null, $locale);

                continue;
            }

            if ($type === 'image') {
                $altKey = "{$name}_alt";
                $resolved[$altKey] = self::extractTranslatedValue($data[$altKey] ?? null, $locale);

                continue;
            }

            if ($type === 'repeater' && is_array($data[$name] ?? null)) {
                /** @var list<array<string, mixed>> $subFields */
                $subFields = is_array($field['fields'] ?? null) ? $field['fields'] : [];
                $items = [];
                foreach ($data[$name] as $item) {
                    if (is_array($item)) {
                        $items[] = self::localizeFields($subFields, $item, $locale);
                    }
                }
                $resolved[$name] = $items;
            }
        }

        return $resolved;
    }

    private static function extractTranslatedValue(mixed $raw, string $locale): string
    {
        if (is_string($raw)) {
            return $raw;
        }

        if (! is_array($raw)) {
            return '';
        }

        $target = $raw[$locale] ?? null;
        if (is_string($target) && trim($target) !== '') {
            return $target;
        }

        $fallback = $raw['id'] ?? null;

        return is_string($fallback) ? $fallback : '';
    }
}
