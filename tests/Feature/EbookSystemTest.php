<?php

namespace Tests\Feature;

use App\DTOs\EbookDTO;
use App\DTOs\LeadDTO;
use App\DTOs\DownloadTokenDTO;
use App\Enums\EbookStatus;
use App\Models\Ebook;
use App\Models\EbookCategory;
use App\Models\EbookTag;
use App\Models\User;
use App\Services\DownloadService;
use App\Services\EbookService;
use App\Services\LeadCaptureService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EbookSystemTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private EbookService $ebookService;
    private LeadCaptureService $leadCaptureService;
    private DownloadService $downloadService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->ebookService = $this->app->make(EbookService::class);
        $this->leadCaptureService = $this->app->make(LeadCaptureService::class);
        $this->downloadService = $this->app->make(DownloadService::class);

        Storage::fake('local');
    }

    public function test_admin_can_create_ebook(): void
    {
        $category = EbookCategory::factory()->create();
        $tag = EbookTag::factory()->create();

        $file = UploadedFile::fake()->create('ebook.pdf', 1000);

        $dto = new EbookDTO(
            title: 'Test Ebook',
            slug: 'test-ebook',
            description: 'A test ebook description',
            file: $file,
            status: EbookStatus::Published,
            categoryIds: [$category->id],
            tagIds: [$tag->id],
            author: 'Test Author',
            pageCount: 50,
            language: 'en',
        );

        $ebook = $this->ebookService->create($dto);

        $this->assertDatabaseHas('ebooks', [
            'id' => $ebook->id,
            'title' => 'Test Ebook',
            'slug' => 'test-ebook',
            'author' => 'Test Author',
            'status' => 'published',
            'page_count' => 50,
        ]);

        $this->assertDatabaseHas('ebook_ebook_tag', [
            'ebook_id' => $ebook->id,
            'ebook_tag_id' => $tag->id,
        ]);
    }

    public function test_lead_capture_creates_download_token(): void
    {
        $ebook = Ebook::factory()->published()->create();

        $dto = new LeadDTO(
            name: 'John Smith',
            email: 'john@example.com',
            phone: '+61400000000',
            company: 'Test Corp',
            country: 'Australia',
            ebookId: $ebook->id,
            consent: true,
        );

        $lead = $this->leadCaptureService->capture($dto);

        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'email' => 'john@example.com',
            'ebook_id' => $ebook->id,
        ]);

        $downloadToken = $this->downloadService->createToken(
            new DownloadTokenDTO(
                ebookId: $ebook->id,
                leadId: $lead->id,
            )
        );

        $this->assertDatabaseHas('download_tokens', [
            'id' => $downloadToken->id,
            'ebook_id' => $ebook->id,
            'lead_id' => $lead->id,
        ]);
    }

    public function test_download_token_validation(): void
    {
        $ebook = Ebook::factory()->published()->create();
        $lead = \App\Models\Lead::factory()->create(['ebook_id' => $ebook->id]);

        $token = $this->downloadService->createToken(
            new DownloadTokenDTO(
                ebookId: $ebook->id,
                leadId: $lead->id,
                maxDownloads: 3,
                expiryHours: 72,
            )
        );

        $validated = $this->downloadService->validateToken($token->token);
        $this->assertNotNull($validated);
        $this->assertTrue($validated->isValid());
    }

    public function test_homepage_returns_successful(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_admin_login_page_loads(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
    }

    public function test_ebook_landing_page_not_found_for_missing_ebook(): void
    {
        $response = $this->get('/ebook/non-existent-ebook');
        $response->assertStatus(404);
    }
}
