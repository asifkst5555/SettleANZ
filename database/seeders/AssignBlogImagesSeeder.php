<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class AssignBlogImagesSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'how-to-rent-in-australia-as-a-new-immigrant' => 'How to Rent in Australia as a New Immigrant.webp',
            'how-to-read-an-australian-rental-listing' => 'How to Read an Australian Rental Listing.webp',
        ];

        foreach ($map as $slug => $image) {
            BlogPost::query()->where('slug', $slug)->update(['image' => $image]);
        }
    }
}
