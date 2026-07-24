<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLeadBulkActionTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_bulk_status_update_works_with_transaction(): void
    {
        $leads = Lead::factory()->count(3)->create(['status' => 'new']);
        $leadIds = $leads->pluck('id')->toArray();

        $response = $this->actingAs($this->admin)->post('/admin/leads/bulk-action', [
            'lead_ids' => $leadIds,
            'action' => 'status',
            'value' => 'contacted',
        ]);

        $response->assertStatus(302);
        $this->assertEquals(3, Lead::where('status', 'contacted')->count());
    }

    public function test_bulk_assign_staff_works_with_transaction(): void
    {
        $leads = Lead::factory()->count(3)->create(['assigned_to' => null]);
        $leadIds = $leads->pluck('id')->toArray();
        $staff = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($this->admin)->post('/admin/leads/bulk-action', [
            'lead_ids' => $leadIds,
            'action' => 'assign',
            'value' => (string) $staff->id,
        ]);

        $response->assertStatus(302);
        $this->assertEquals(3, Lead::where('assigned_to', $staff->id)->count());
    }

    public function test_bulk_archive_works_with_transaction(): void
    {
        $leads = Lead::factory()->count(3)->create(['is_archived' => false]);
        $leadIds = $leads->pluck('id')->toArray();

        $response = $this->actingAs($this->admin)->post('/admin/leads/bulk-action', [
            'lead_ids' => $leadIds,
            'action' => 'archive',
        ]);

        $response->assertStatus(302);
        $this->assertEquals(3, Lead::where('is_archived', true)->count());
    }

    public function test_bulk_delete_works_with_transaction(): void
    {
        $leads = Lead::factory()->count(3)->create();
        $leadIds = $leads->pluck('id')->toArray();

        $response = $this->actingAs($this->admin)->post('/admin/leads/bulk-action', [
            'lead_ids' => $leadIds,
            'action' => 'delete',
        ]);

        $response->assertStatus(302);
        $this->assertEquals(0, Lead::whereIn('id', $leadIds)->count());
    }

    public function test_bulk_action_decodes_json_stringified_ids(): void
    {
        $leads = Lead::factory()->count(2)->create(['is_archived' => false]);
        $leadIds = $leads->pluck('id')->toArray();

        // Simulate stringified JSON array submitted from client JS
        $response = $this->actingAs($this->admin)->post('/admin/leads/bulk-action', [
            'lead_ids' => json_encode($leadIds),
            'action' => 'archive',
        ]);

        $response->assertStatus(302);
        $this->assertEquals(2, Lead::where('is_archived', true)->count());
    }

    public function test_bulk_action_resolves_select_all_matching_leads(): void
    {
        Lead::factory()->count(10)->create(['status' => 'new']);
        
        $response = $this->actingAs($this->admin)->post('/admin/leads/bulk-action', [
            'select_all_matching' => 'true',
            'status' => 'new',
            'action' => 'status',
            'value' => 'contacted',
        ]);

        $response->assertStatus(302);
        $this->assertEquals(10, Lead::where('status', 'contacted')->count());
    }

    public function test_lead_export_supports_targeted_ids(): void
    {
        $leads = Lead::factory()->count(5)->create();
        $targetIds = $leads->take(2)->pluck('id')->toArray();

        $response = $this->actingAs($this->admin)->get('/admin/leads/export?' . http_build_query([
            'format' => 'csv',
            'lead_ids' => json_encode($targetIds),
        ]));

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        
        // CSV should only return the header row + 2 data rows = 3 lines total
        $lines = array_filter(explode("\n", trim($response->getContent())));
        $this->assertCount(3, $lines);
    }
}
