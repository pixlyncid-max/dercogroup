<?php

declare(strict_types=1);

namespace App\Support;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class FilamentTranslatable
{
    /**
     * Build a pair of ID / EN tabs for a single translatable TextInput field.
     */
    public static function textInput(string $name, string $label, bool $requiredId = false, int $maxLength = 255): Tabs
    {
        return Tabs::make($label)
            ->tabs([
                Tab::make('Indonesia (ID)')
                    ->schema([
                        TextInput::make("{$name}.id")
                            ->label("{$label} (ID)")
                            ->required($requiredId)
                            ->maxLength($maxLength),
                    ]),
                Tab::make('English (EN)')
                    ->schema([
                        TextInput::make("{$name}.en")
                            ->label("{$label} (EN)")
                            ->maxLength($maxLength),
                    ]),
            ])
            ->columnSpanFull();
    }

    /**
     * Build a pair of ID / EN tabs for a single translatable Textarea field.
     */
    public static function textarea(string $name, string $label, bool $requiredId = false, int $rows = 3): Tabs
    {
        return Tabs::make($label)
            ->tabs([
                Tab::make('Indonesia (ID)')
                    ->schema([
                        Textarea::make("{$name}.id")
                            ->label("{$label} (ID)")
                            ->required($requiredId)
                            ->rows($rows),
                    ]),
                Tab::make('English (EN)')
                    ->schema([
                        Textarea::make("{$name}.en")
                            ->label("{$label} (EN)")
                            ->rows($rows),
                    ]),
            ])
            ->columnSpanFull();
    }

    /**
     * Build a pair of ID / EN tabs for a single translatable RichEditor field.
     */
    public static function richEditor(string $name, string $label, bool $requiredId = false): Tabs
    {
        return Tabs::make($label)
            ->tabs([
                Tab::make('Indonesia (ID)')
                    ->schema([
                        RichEditor::make("{$name}.id")
                            ->label("{$label} (ID)")
                            ->required($requiredId),
                    ]),
                Tab::make('English (EN)')
                    ->schema([
                        RichEditor::make("{$name}.en")
                            ->label("{$label} (EN)"),
                    ]),
            ])
            ->columnSpanFull();
    }

    /**
     * Group multiple translatable fields under a single ID / EN tab switcher.
     *
     * @param  callable(string $locale): array<int, Component>  $schemaBuilder
     */
    public static function group(string $label, callable $schemaBuilder): Tabs
    {
        return Tabs::make($label)
            ->tabs([
                Tab::make('Bahasa Indonesia (ID)')
                    ->schema($schemaBuilder('id')),
                Tab::make('English (EN)')
                    ->schema($schemaBuilder('en')),
            ])
            ->columnSpanFull();
    }
}
