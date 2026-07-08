<?php

namespace Database\Factories;

use App\Models\Ebook;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EbookFactory extends Factory
{
    protected $model = Ebook::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraph(),
            'file_path' => 'ebooks/' . Str::random(40) . '.pdf',
            'file_name' => $title . '.pdf',
            'file_type' => 'pdf',
            'file_size' => fake()->numberBetween(100000, 5000000),
            'status' => 'draft',
            'author' => fake()->name(),
            'current_version' => 1,
            'language' => 'en',
            'download_count' => 0,
            'lead_count' => 0,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }
}
