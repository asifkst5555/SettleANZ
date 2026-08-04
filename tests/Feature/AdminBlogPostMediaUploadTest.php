<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\BlogMedia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminBlogPostMediaUploadTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_can_upload_blog_image_to_public_storage_disk(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test-article-banner.png', 800, 600);

        $response = $this->actingAs($this->admin)->postJson('/admin/blog-posts/upload-image', [
            'image' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['filename', 'url']);

        $filename = $response->json('filename');
        $url = $response->json('url');

        $this->assertStringStartsWith('test-article-banner', $filename);

        // Verify physical existence on public storage disk
        Storage::disk('public')->assertExists('blog/' . $filename);

        // Verify BlogMedia helper resolves correctly
        $this->assertTrue(BlogMedia::exists($filename));
        $this->assertStringContainsString('/storage/blog/' . $filename, BlogMedia::url($filename));
    }

    public function test_upload_fails_for_invalid_file_type(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->admin)->postJson('/admin/blog-posts/upload-image', [
            'image' => $file,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['image']);
    }

    public function test_unauthenticated_user_cannot_upload_image(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('unauth.jpg');

        $response = $this->postJson('/admin/blog-posts/upload-image', [
            'image' => $file,
        ]);

        $response->assertStatus(401);
    }

    public function test_deleting_blog_post_removes_associated_image_file(): void
    {
        Storage::fake('public');

        Storage::disk('public')->put('blog/to-delete.webp', 'fake image content');
        $this->assertTrue(Storage::disk('public')->exists('blog/to-delete.webp'));

        $post = \App\Models\BlogPost::factory()->create([
            'image' => 'to-delete.webp',
        ]);

        $post->delete();

        $this->assertFalse(Storage::disk('public')->exists('blog/to-delete.webp'));
    }

    public function test_updating_blog_post_image_removes_old_image_file(): void
    {
        Storage::fake('public');

        Storage::disk('public')->put('blog/old-banner.webp', 'old content');
        Storage::disk('public')->put('blog/new-banner.webp', 'new content');

        $post = \App\Models\BlogPost::factory()->create([
            'image' => 'old-banner.webp',
        ]);

        $post->update(['image' => 'new-banner.webp']);

        $this->assertFalse(Storage::disk('public')->exists('blog/old-banner.webp'));
        $this->assertTrue(Storage::disk('public')->exists('blog/new-banner.webp'));
    }
}
