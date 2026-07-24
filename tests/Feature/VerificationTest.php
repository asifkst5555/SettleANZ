<?php

namespace Tests\Feature;

use App\Models\Ebook;
use App\Models\Lead;
use App\Models\Conversation;
use App\Services\Verification\VerificationManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class VerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Setup default ebook for roadmap testing
        Ebook::factory()->published()->create([
            'slug' => 'settleanZ-new-arrival-checklist',
        ]);
    }

    public function test_math_verification_generates_correctly(): void
    {
        $manager = app(VerificationManager::class);
        $challenge = $manager->generate();

        $this->assertEquals('math', $challenge['driver']);
        $this->assertNotEmpty($challenge['question']);
        $this->assertNotEmpty($challenge['token']);
        
        $this->assertTrue(session()->has('verification_challenges'));
        $challenges = session('verification_challenges');
        $this->assertArrayHasKey($challenge['token'], $challenges);
    }

    public function test_correct_math_answer_allows_submission(): void
    {
        $manager = app(VerificationManager::class);
        $challenge = $manager->generate();
        $token = $challenge['token'];
        $challenges = session('verification_challenges');
        $answer = $challenges[$token]['answer'];

        $response = $this->withSession(['verification_challenges' => $challenges])
            ->post(route('roadmap.claim'), [
                'name' => 'Jane Doe',
                'email' => 'jane@example.com',
                'math_answer' => $answer,
                'verification_token' => $token,
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('leads', [
            'email' => 'jane@example.com',
        ]);
    }

    public function test_wrong_math_answer_is_blocked_with_validation_errors(): void
    {
        $manager = app(VerificationManager::class);
        $challenge = $manager->generate();
        $token = $challenge['token'];
        $challenges = session('verification_challenges');
        $correctAnswer = $challenges[$token]['answer'];
        $wrongAnswer = $correctAnswer + 2;

        $response = $this->withSession(['verification_challenges' => $challenges])
            ->post(route('roadmap.claim'), [
                'name' => 'Jane Doe',
                'email' => 'jane@example.com',
                'math_answer' => $wrongAnswer,
                'verification_token' => $token,
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['math_answer']);
        
        $errors = session('errors')->get('math_answer');
        $this->assertContains('The math verification answer is incorrect.', $errors);

        $this->assertDatabaseMissing('leads', [
            'email' => 'jane@example.com',
        ]);
    }

    public function test_empty_math_answer_is_blocked(): void
    {
        $manager = app(VerificationManager::class);
        $challenge = $manager->generate();
        $token = $challenge['token'];
        $challenges = session('verification_challenges');

        $response = $this->withSession(['verification_challenges' => $challenges])
            ->post(route('roadmap.claim'), [
                'name' => 'Jane Doe',
                'email' => 'jane@example.com',
                'math_answer' => '',
                'verification_token' => $token,
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['math_answer']);
        $this->assertDatabaseMissing('leads', [
            'email' => 'jane@example.com',
        ]);
    }

    public function test_ajax_submissions_receive_json_validation_errors(): void
    {
        $manager = app(VerificationManager::class);
        $challenge = $manager->generate();
        $token = $challenge['token'];
        $challenges = session('verification_challenges');
        $correctAnswer = $challenges[$token]['answer'];
        $wrongAnswer = $correctAnswer + 3;

        $response = $this->withSession(['verification_challenges' => $challenges])
            ->postJson(route('roadmap.claim'), [
                'name' => 'Jane Doe',
                'email' => 'jane@example.com',
                'math_answer' => $wrongAnswer,
                'verification_token' => $token,
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['math_answer']);
        $response->assertJsonFragment([
            'math_answer' => ['The math verification answer is incorrect.']
        ]);
    }

    public function test_verification_refresh_endpoint_returns_json(): void
    {
        $response = $this->getJson(route('verification.refresh'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'enabled',
            'driver',
            'question',
            'token',
        ]);
        $this->assertTrue($response->json('enabled'));
        $this->assertEquals('math', $response->json('driver'));
        $this->assertNotEmpty($response->json('question'));
        $this->assertNotEmpty($response->json('token'));
    }

    public function test_admin_login_does_not_require_math_verification(): void
    {
        $response = $this->post(route('admin.login.store'), [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionDoesntHaveErrors(['math_answer']);
    }

    public function test_honeypot_blocks_bot_submissions(): void
    {
        $manager = app(VerificationManager::class);
        $challenge = $manager->generate();
        $token = $challenge['token'];
        $challenges = session('verification_challenges');
        $answer = $challenges[$token]['answer'];

        // Encrypt dynamic honeypot field name
        $fieldName = 'hp_field_spam';
        $encryptedKey = Crypt::encryptString($fieldName);

        // Submit form with honeypot field filled (bot behavior)
        $response = $this->withSession(['verification_challenges' => $challenges])
            ->post(route('roadmap.claim'), [
                'name' => 'Jane Doe',
                'email' => 'jane@example.com',
                'math_answer' => $answer,
                'verification_token' => $token,
                'honeypot_key' => $encryptedKey,
                $fieldName => 'spam bot content', // filled honeypot
            ]);

        $response->assertStatus(400); // Middleware immediately aborts with 400
        $this->assertDatabaseMissing('leads', [
            'email' => 'jane@example.com',
        ]);
    }

    public function test_honeypot_allows_empty_human_submissions(): void
    {
        $manager = app(VerificationManager::class);
        $challenge = $manager->generate();
        $token = $challenge['token'];
        $challenges = session('verification_challenges');
        $answer = $challenges[$token]['answer'];

        $fieldName = 'hp_field_human';
        $encryptedKey = Crypt::encryptString($fieldName);

        // Submit form with empty honeypot field (human behavior)
        $response = $this->withSession(['verification_challenges' => $challenges])
            ->post(route('roadmap.claim'), [
                'name' => 'Jane Doe',
                'email' => 'jane@example.com',
                'math_answer' => $answer,
                'verification_token' => $token,
                'honeypot_key' => $encryptedKey,
                $fieldName => '', // empty honeypot
            ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('leads', [
            'email' => 'jane@example.com',
        ]);
    }

    public function test_multiple_tabs_work_independently(): void
    {
        $manager = app(VerificationManager::class);
        
        // Generate challenge in Tab A
        $challengeA = $manager->generate();
        $challenges = session('verification_challenges');
        
        // Generate challenge in Tab B (overwrites nothing, appends to challenges array)
        $challengeB = $manager->generate();
        $challenges = session('verification_challenges');

        $tokenA = $challengeA['token'];
        $tokenB = $challengeB['token'];
        $answerA = $challenges[$tokenA]['answer'];
        $answerB = $challenges[$tokenB]['answer'];

        $this->assertNotEquals($tokenA, $tokenB);

        // Submit Tab A
        $responseA = $this->withSession(['verification_challenges' => $challenges])
            ->post(route('roadmap.claim'), [
                'name' => 'User A',
                'email' => 'usera@example.com',
                'math_answer' => $answerA,
                'verification_token' => $tokenA,
            ]);

        $responseA->assertStatus(302);
        $this->assertDatabaseHas('leads', [
            'email' => 'usera@example.com',
        ]);

        // Submit Tab B (using same session context containing the remaining tokenB challenge)
        $updatedChallenges = session('verification_challenges');
        $responseB = $this->withSession(['verification_challenges' => $updatedChallenges])
            ->post(route('roadmap.claim'), [
                'name' => 'User B',
                'email' => 'userb@example.com',
                'math_answer' => $answerB,
                'verification_token' => $tokenB,
            ]);

        $responseB->assertStatus(302);
        $this->assertDatabaseHas('leads', [
            'email' => 'userb@example.com',
        ]);
    }

    public function test_chat_session_requires_human_verification(): void
    {
        // 1. Submit without verification - fails with 422 validation error
        $response = $this->postJson('/api/chat/session', [
            'channel' => 'website_widget',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['math_answer', 'verification_token']);

        // 2. Submit with correct verification - succeeds
        $manager = app(VerificationManager::class);
        $challenge = $manager->generate();
        $token = $challenge['token'];
        $challenges = session('verification_challenges');
        $answer = $challenges[$token]['answer'];

        $responseSuccess = $this->withSession(['verification_challenges' => $challenges])
            ->postJson('/api/chat/session', [
                'channel' => 'website_widget',
                'visitor_id' => 'test-visitor',
                'math_answer' => $answer,
                'verification_token' => $token,
            ]);

        $responseSuccess->assertStatus(201);
        $responseSuccess->assertJsonStructure(['conversation_id', 'status']);
        
        $conversationId = $responseSuccess->json('conversation_id');
        $this->assertDatabaseHas('conversations', [
            'id' => $conversationId,
            'status' => 'active',
        ]);
    }

    public function test_rate_limiting_blocks_excessive_submissions(): void
    {
        $manager = app(VerificationManager::class);
        RateLimiter::clear('public_forms:127.0.0.1');

        // Make 10 successful/failed attempts to trigger rate limiter
        for ($i = 0; $i < 10; $i++) {
            $challenge = $manager->generate();
            $token = $challenge['token'];
            $challenges = session('verification_challenges');

            $this->withSession(['verification_challenges' => $challenges])
                ->post(route('roadmap.claim'), [
                    'name' => 'Jane Doe',
                    'email' => 'jane@example.com',
                    'math_answer' => 0, // incorrect, doesn't matter
                    'verification_token' => $token,
                ]);
        }

        // The 11th request must receive 429 Throttle status
        $challenge = $manager->generate();
        $token = $challenge['token'];
        $challenges = session('verification_challenges');

        $response = $this->withSession(['verification_challenges' => $challenges])
            ->post(route('roadmap.claim'), [
                'name' => 'Jane Doe',
                'email' => 'jane@example.com',
                'math_answer' => 0,
                'verification_token' => $token,
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['math_answer']);
        $errors = session('errors')->get('math_answer');
        $this->assertContains('Too many submission attempts. Please try again in a minute.', $errors);
    }
}
