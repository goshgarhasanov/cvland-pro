<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Catalog\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Template> */
class TemplateFactory extends Factory
{
    protected $model = Template::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $name = fake()->words(2, true);

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name) . '-' . Str::random(4),
            'description' => fake()->paragraph(),
            'preview_path' => null,
            'category' => fake()->randomElement(['professional', 'creative', 'modern', 'classic', 'student']),
            'price_minor' => fake()->randomElement([0, 199, 299, 499, 699]),
            'currency' => 'AZN',
            'is_active' => true,
            'metadata' => [],
        ];
    }

    public function free(): static
    {
        return $this->state(fn (): array => ['price_minor' => 0]);
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => ['is_active' => false]);
    }
}
