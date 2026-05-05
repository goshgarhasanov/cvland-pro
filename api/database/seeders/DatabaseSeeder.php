<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Catalog\Models\Template;
use App\Domain\Identity\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Demo Admin',
            'email' => 'admin@cvland.pro',
            'password' => Hash::make('Admin123!'),
            'email_verified_at' => now(),
        ]);

        $templates = [
            ['name' => 'Executive Pro', 'slug' => 'executive-pro', 'category' => 'professional', 'price_minor' => 699],
            ['name' => 'Modern Clean', 'slug' => 'modern-clean', 'category' => 'modern', 'price_minor' => 499],
            ['name' => 'Creative Bold', 'slug' => 'creative-bold', 'category' => 'creative', 'price_minor' => 599],
            ['name' => 'Classic Simple', 'slug' => 'classic-simple', 'category' => 'classic', 'price_minor' => 0],
            ['name' => 'Student Fresh', 'slug' => 'student-fresh', 'category' => 'student', 'price_minor' => 199],
        ];

        foreach ($templates as $row) {
            Template::factory()->create($row);
        }
    }
}
