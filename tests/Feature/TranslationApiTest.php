<?php

namespace Tests\Feature;

use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TranslationApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.env' => 'testing']);
        $_ENV['API_TOKENS'] = "my-api-token-0000000";
    }

    protected function authHeaders(): array
    {
        return ['Authorization' => 'Bearer my-api-token-0000000'];
    }

    public function test_can_create_translation(): void
    {
        $data = [
            'key' => 'welcome_message_v2',
            'locale' => 'en',
            'content' => 'Welcome to our application again',
            'tags' => ['web', 'mobile'],
        ];

        $response = $this->postJson('/api/translations', $data, $this->authHeaders());

        $response->assertStatus(201)
                ->assertJson([
                    'message' => 'Translation created successfully',
                    'data' => $data
                ]);

        $data['tags'] = json_encode(['web', 'mobile']);

        $this->assertDatabaseHas('translations', $data);
    }

    public function test_can_get_translation(): void
    {
        $translation = Translation::factory()->create([
            'key' => 'test_key',
            'locale' => 'en',
        ]);

        $response = $this->getJson("/api/translations/{$translation->key}/{$translation->locale}", $this->authHeaders());

        $response->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'key' => $translation->key,
                        'locale' => $translation->locale,
                        'content' => $translation->content,
                    ],
                ]);
    }

    public function test_can_update_translation(): void
    {
        $translation = Translation::factory()->create();

        $updateData = [
            'content' => 'Updated content',
            'tags' => ['updated'],
        ];

        $response = $this->putJson("/api/translations/{$translation->key}/{$translation->locale}", $updateData, $this->authHeaders());

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Translation updated successfully',
                ]);

        $this->assertDatabaseHas('translations', [
            'key' => $translation->key,
            'locale' => $translation->locale,
            'content' => 'Updated content',
        ]);
    }

    public function test_can_delete_translation(): void
    {
        $translation = Translation::factory()->create();

        $response = $this->deleteJson("/api/translations/{$translation->key}/{$translation->locale}", [], $this->authHeaders());

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Translation deleted successfully',
                ]);

        $this->assertDatabaseMissing('translations', [
            'key' => $translation->key,
            'locale' => $translation->locale,
        ]);
    }
}
