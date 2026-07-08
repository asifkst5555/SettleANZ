<?php

namespace Tests\Feature;

use App\Enums\EbookStatus;
use App\Models\Ebook;
use App\Models\EbookCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EbookApiTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_can_view_ebooks_list(): void
    {
        Ebook::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get('/admin/ebooks');
        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_admin(): void
    {
        $response = $this->get('/admin/ebooks');
        $response->assertStatus(302);
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_create_ebook_via_http(): void
    {
        $category = EbookCategory::factory()->create();
        $file = UploadedFile::fake()->create('ebook.pdf', 500);

        $response = $this->actingAs($this->admin)->post('/admin/ebooks', [
            'title' => 'HTTP Created Ebook',
            'description' => 'Created via HTTP test',
            'category_id' => $category->id,
            'file' => $file,
            'status' => 'draft',
            'author' => 'Test Author',
            'language' => 'en',
        ]);

        $response->assertSessionHas('status', 'Ebook created successfully.');
        $this->assertDatabaseHas('ebooks', ['title' => 'HTTP Created Ebook']);
    }

    public function test_admin_can_update_ebook_status(): void
    {
        $ebook = Ebook::factory()->draft()->create();

        $response = $this->actingAs($this->admin)
            ->from(route('admin.ebooks.edit', $ebook))
            ->post(route('admin.ebooks.publish', $ebook));

        $response->assertRedirect(route('admin.ebooks.edit', $ebook));
        $this->assertEquals('published', $ebook->fresh()->status);
    }
}
