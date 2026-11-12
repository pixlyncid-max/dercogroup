<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Company\Enums\CompanyStatus;
use App\Domain\Company\Enums\CompanyTheme;
use App\Domain\Company\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'parent_id' => null,
            'slug' => $this->faker->unique()->slug(),
            'name' => $this->faker->company(),
            'theme' => CompanyTheme::Holding,
            'status' => CompanyStatus::Published,
            'sort_order' => 1,
            'tagline' => [
                'id' => $this->faker->sentence(),
                'en' => $this->faker->sentence(),
            ],
            'about_short' => [
                'id' => $this->faker->paragraph(),
                'en' => $this->faker->paragraph(),
            ],
            'logo_alt' => [
                'id' => 'Logo Perusahaan',
                'en' => 'Company Logo',
            ],
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CompanyStatus::Draft,
        ]);
    }

    public function subsidiary(Company $parent): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parent->id,
            'theme' => CompanyTheme::KorinBara,
        ]);
    }
}
