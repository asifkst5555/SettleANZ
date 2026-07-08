<?php
namespace Tests\Feature;

use App\Models\Ebook;
use App\Models\Lead;
use App\Models\DownloadToken;
use App\Models\EmailLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoadmapFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_roadmap_form_submission(): void
    {
        $ebook = Ebook::factory()->published()->create([
            'slug' => 'settleanZ-new-arrival-checklist',
        ]);

        $response = $this->post(route('roadmap.claim'), [
            'name' => 'John Smith',
            'email' => 'john@example.com',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('leads', [
            'email' => 'john@example.com',
            'form_type' => 'homepage_roadmap',
        ]);

        $lead = Lead::where('email', 'john@example.com')->first();
        $this->assertNotNull($lead);

        $this->assertDatabaseHas('download_tokens', [
            'lead_id' => $lead->id,
            'ebook_id' => $ebook->id,
        ]);

        $this->assertDatabaseHas('email_logs', [
            'lead_id' => $lead->id,
        ]);

        $this->assertDatabaseHas('admin_notifications', [
            'lead_id' => $lead->id,
            'type' => 'lead',
        ]);
    }
}
