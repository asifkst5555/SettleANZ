<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\DirectoryListing;
use App\Models\SiteSetting;
use App\Models\User;
use App\Support\SiteDefaults;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@SettleANZ.com'],
            [
                'name' => 'SettleANZ Admin',
                'password' => Hash::make('admin@1234'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
        );

        foreach (SiteDefaults::blogPosts() as $post) {
            BlogPost::query()->updateOrCreate(['slug' => $post['slug']], $post);
        }

        foreach (SiteDefaults::directoryListings() as $listing) {
            DirectoryListing::query()->updateOrCreate(['slug' => $listing['slug']], $listing);
        }

        foreach (SiteDefaults::siteSettings() as $key => $value) {
            SiteSetting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
