<?php

declare(strict_types=1);

namespace App\Domain\Page\Blocks;

use App\Domain\Page\Enums\BlockType;
use App\Support\FilamentTranslatable;
use App\Support\ImageSource;
use Closure;
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

    public static function isToggleable(): bool
    {
        return true;
    }

    /**
     * Single declarative field schema for this block type.
     *
     * Supported field types:
     * - translatable_text (alias: text)
     * - translatable_textarea (alias: paragraph)
     * - translatable_richtext (alias: rich_text_limited)
     * - text (single language / technical)
     * - link (internal "page:<key>[#fragment]" reference or absolute/relative URL)
     * - number
     * - boolean (alias: switch)
     * - select (with 'options' => array<string, string>)
     * - image (automatically enforces alt text)
     * - repeater (alias: list, with 'fields' => list<array<string, mixed>>)
     *
     * @return list<array<string, mixed>>
     */
    abstract public static function fields(): array;

    /**
     * Generate complete default data payload for this block type in both languages.
     *
     * @return array<string, mixed>
     */
    public static function defaultData(): array
    {
        return self::buildDefaultsFromFields(static::fields());
    }

    /**
     * Alias for defaultData().
     *
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return static::defaultData();
    }

    /**
     * Generate TypeScript interface definition for this block type.
     */
    public static function toTypeScript(): string
    {
        $typeName = str_replace(' ', '', ucwords(str_replace('_', ' ', static::type()->value))).'BlockData';
        $lines = ["export interface {$typeName} {"];

        foreach (static::fields() as $field) {
            $name = (string) ($field['name'] ?? $field['key'] ?? '');
            $type = self::normalizeFieldType((string) ($field['type'] ?? 'text'));
            $required = (bool) ($field['required'] ?? false);
            $optional = $required ? '' : '?';

            $tsType = match ($type) {
                'translatable_text', 'translatable_textarea', 'translatable_richtext' => 'Record<\'id\' | \'en\', string>',
                'text', 'link' => 'string',
                'number' => 'number',
                'boolean' => 'boolean',
                'select' => 'string',
                'image' => 'string | null',
                'repeater' => 'Array<Record<string, unknown>>',
                default => 'unknown',
            };

            $lines[] = "    {$name}{$optional}: {$tsType};";
            if ($type === 'image') {
                $lines[] = "    {$name}_alt{$optional}: Record<'id' | 'en', string>;";
            }
        }

        $lines[] = '}';

        return implode("\n", $lines);
    }

    protected static function normalizeFieldType(string $type): string
    {
        return match ($type) {
            'paragraph' => 'translatable_textarea',
            'rich_text_limited' => 'translatable_richtext',
            'switch' => 'boolean',
            'list' => 'repeater',
            default => $type,
        };
    }

    /**
     * @param  list<array<string, mixed>>  $fields
     * @return array<string, mixed>
     */
    public static function buildDefaultsFromFields(array $fields): array
    {
        $defaults = [];

        foreach ($fields as $field) {
            $name = (string) ($field['name'] ?? $field['key'] ?? '');
            $type = self::normalizeFieldType((string) ($field['type'] ?? 'text'));

            if (array_key_exists('default', $field)) {
                $defaults[$name] = $field['default'];

                continue;
            }

            switch ($type) {
                case 'translatable_text':
                case 'translatable_textarea':
                case 'translatable_richtext':
                    $defaults[$name] = [
                        'id' => (string) ($field['default_id'] ?? ''),
                        'en' => (string) ($field['default_en'] ?? ''),
                    ];
                    break;

                case 'text':
                case 'link':
                    $defaults[$name] = (string) ($field['default_id'] ?? '');
                    break;

                case 'number':
                    $defaults[$name] = 0;
                    break;

                case 'boolean':
                    $defaults[$name] = (bool) ($field['default'] ?? false);
                    break;

                case 'select':
                    $options = is_array($field['options'] ?? null) ? $field['options'] : [];
                    $defaults[$name] = array_key_first($options) ?? '';
                    break;

                case 'image':
                    $defaults[$name] = null;
                    $defaults["{$name}_alt"] = [
                        'id' => (string) ($field['default_id'] ?? ''),
                        'en' => (string) ($field['default_en'] ?? ''),
                    ];
                    break;

                case 'repeater':
                    $defaults[$name] = [];
                    break;
            }
        }

        return $defaults;
    }

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
     * Image paths become public URLs (+ srcset) and link fields pass through $linkResolver.
     *
     * @param  array<string, mixed>  $data
     * @param  (Closure(string): string)|null  $linkResolver
     * @return array<string, mixed>
     */
    public static function localizeData(array $data, string $locale = 'id', ?Closure $linkResolver = null): array
    {
        return self::localizeFields(static::fields(), $data, $locale, $linkResolver);
    }

    /**
     * @param  list<array<string, mixed>>  $fields
     * @return array<string, mixed>
     */
    private static function buildRulesFromFields(array $fields, string $prefix = ''): array
    {
        $rules = [];

        foreach ($fields as $field) {
            $name = (string) ($field['name'] ?? $field['key'] ?? '');
            $path = $prefix !== '' ? "{$prefix}.{$name}" : $name;
            $type = self::normalizeFieldType((string) ($field['type'] ?? 'text'));
            $required = (bool) ($field['required'] ?? false);

            switch ($type) {
                case 'translatable_text':
                case 'translatable_textarea':
                case 'translatable_richtext':
                    $rules[$path] = [$required ? 'required' : 'nullable', 'array'];
                    $idRules = [$required ? 'required' : 'nullable', 'string'];
                    $enRules = ['nullable', 'string'];
                    if (isset($field['max_chars'])) {
                        $idRules[] = 'max:'.(int) $field['max_chars'];
                        $enRules[] = 'max:'.(int) $field['max_chars'];
                    }
                    $rules["{$path}.id"] = $idRules;
                    $rules["{$path}.en"] = $enRules;
                    break;

                case 'text':
                    $textRules = [$required ? 'required' : 'nullable', 'string'];
                    if (isset($field['max_chars'])) {
                        $textRules[] = 'max:'.(int) $field['max_chars'];
                    }
                    $rules[$path] = $textRules;
                    break;

                case 'link':
                    $rules[$path] = [
                        $required ? 'required' : 'nullable',
                        'string',
                        'max:2048',
                        'regex:/^(page:[a-z_]+(#[A-Za-z0-9_-]+)?|\/.*|https?:\/\/.+|mailto:.+|tel:.+|#.+)$/',
                    ];
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
                    $requireAltBoth = (bool) ($field['require_alt_both'] ?? false);
                    $rules["{$altKey}.id"] = [
                        $required ? 'required' : "required_with:{$path}",
                        'string',
                    ];
                    $rules["{$altKey}.en"] = [
                        $requireAltBoth || $required ? ($required ? 'required' : "required_with:{$path}") : 'nullable',
                        'string',
                    ];
                    break;

                case 'repeater':
                    /** @var list<array<string, mixed>> $subFields */
                    $subFields = is_array($field['fields'] ?? null) ? $field['fields'] : [];
                    $repeaterRule = [$required ? 'required' : 'nullable', 'array'];
                    if (isset($field['min'])) {
                        if ($required) {
                            $repeaterRule[] = 'min:'.(int) $field['min'];
                        } else {
                            $minLimit = (int) $field['min'];
                            $repeaterRule[] = function (string $attribute, mixed $value, Closure $fail) use ($minLimit): void {
                                if (is_array($value) && count($value) > 0 && count($value) < $minLimit) {
                                    $fail("The {$attribute} field must have at least {$minLimit} items.");
                                }
                            };
                        }
                    } elseif ($required) {
                        $repeaterRule[] = 'min:1';
                    }
                    if (isset($field['max'])) {
                        $repeaterRule[] = 'max:'.(int) $field['max'];
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
            $name = (string) ($field['name'] ?? $field['key'] ?? '');
            $path = $prefix !== '' ? "{$prefix}.{$name}" : $name;
            $label = (string) ($field['label'] ?? $name);
            $type = self::normalizeFieldType((string) ($field['type'] ?? 'text'));
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

                case 'link':
                    $components[] = TextInput::make($path)
                        ->label($label)
                        ->helperText('Tautan internal: page:about, page:business, page:contact#form. Tautan lain: /path atau https://…')
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
                    $repeater = Repeater::make($path)
                        ->label($label)
                        ->schema(self::buildFilamentComponents($subFields, ''))
                        ->columnSpanFull();

                    if (isset($field['min'])) {
                        $repeater->minItems((int) $field['min']);
                    }
                    if (isset($field['max'])) {
                        $repeater->maxItems((int) $field['max']);
                    }

                    $components[] = $repeater;
                    break;
            }
        }

        return $components;
    }

    /**
     * @param  list<array<string, mixed>>  $fields
     * @param  array<string, mixed>  $data
     * @param  (Closure(string): string)|null  $linkResolver
     * @return array<string, mixed>
     */
    private static function localizeFields(array $fields, array $data, string $locale, ?Closure $linkResolver = null): array
    {
        $resolved = $data;

        foreach ($fields as $field) {
            $name = (string) $field['name'];
            $type = (string) $field['type'];

            if (in_array($type, ['translatable_text', 'translatable_textarea', 'translatable_richtext'], true)) {
                $resolved[$name] = self::extractTranslatedValue($data[$name] ?? null, $locale);

                continue;
            }

            if ($type === 'link') {
                $href = is_string($data[$name] ?? null) ? trim($data[$name]) : '';
                $resolved[$name] = $href !== '' && $linkResolver !== null ? $linkResolver($href) : ($href ?: null);

                continue;
            }

            if ($type === 'image') {
                $altKey = "{$name}_alt";
                $source = ImageSource::resolve(is_string($data[$name] ?? null) ? $data[$name] : null);
                $resolved[$name] = $source['url'];
                $resolved["{$name}_srcset"] = $source['srcset'];
                $resolved[$altKey] = self::extractTranslatedValue($data[$altKey] ?? null, $locale);

                continue;
            }

            if ($type === 'repeater' && is_array($data[$name] ?? null)) {
                /** @var list<array<string, mixed>> $subFields */
                $subFields = is_array($field['fields'] ?? null) ? $field['fields'] : [];
                $items = [];
                foreach ($data[$name] as $item) {
                    if (is_array($item)) {
                        $items[] = self::localizeFields($subFields, $item, $locale, $linkResolver);
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
