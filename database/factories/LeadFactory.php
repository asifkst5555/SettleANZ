<?php

namespace Database\Factories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'form_type' => 'ebook_download',
            'status' => 'new',
            'consent' => true,
        ];
    }

    public function downloaded(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'downloaded',
        ]);
    }
}
