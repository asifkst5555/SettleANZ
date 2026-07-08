<?php

namespace Database\Factories;

use App\Models\EbookTag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EbookTagFactory extends Factory
{
    protected $model = EbookTag::class;

    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
        ];
    }
}
