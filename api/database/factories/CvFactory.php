<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\CV\Enums\CvStatus;
use App\Domain\CV\Models\Cv;
use App\Domain\Identity\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Cv> */
class CvFactory extends Factory
{
    protected $model = Cv::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'user_id' => User::factory(),
            'template_id' => null,
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(6),
            'status' => CvStatus::Draft,
            'data' => [],
            'is_public' => false,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (): array => [
            'status' => CvStatus::Published,
            'published_at' => now(),
        ]);
    }
}
