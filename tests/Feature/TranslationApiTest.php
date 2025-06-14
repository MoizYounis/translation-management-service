<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Translation;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TranslationApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Set up test DB
        $this->artisan('migrate');
    }

    public function test_user_can_create_translation()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/translations', [
            'locale' => 'en',
            'key' => 'greeting',
            'value' => 'Hello',
            'tags' => ['web'],
            'cdn_ready' => true,
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['key' => 'greeting']);
    }

    public function test_user_can_view_translation()
    {
        Sanctum::actingAs(User::factory()->create());

        $translation = Translation::factory()->create();

        $this->getJson('/api/translations/' . $translation->id)
            ->assertStatus(200)
            ->assertJsonFragment(['key' => $translation->key]);
    }

    public function test_user_can_update_translation()
    {
        Sanctum::actingAs(User::factory()->create());

        $translation = Translation::factory()->create([
            'locale' => 'en',
            'key' => 'greeting',
            'value' => 'Hello',
        ]);

        $response = $this->putJson("/api/translations/{$translation->id}", [
            'locale' => 'en',
            'key' => 'greeting',
            'value' => 'Hello Updated',
            'cdn_ready' => true,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'value' => 'Hello Updated',
                'cdn_ready' => true,
            ]);
    }

    public function test_user_can_delete_translation()
    {
        Sanctum::actingAs(User::factory()->create());

        $translation = Translation::factory()->create([
            'locale' => 'en',
            'key' => 'bye',
            'value' => 'Goodbye',
        ]);

        $response = $this->deleteJson("/api/translations/{$translation->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('translations', [
            'id' => $translation->id,
        ]);
    }

    public function test_user_can_search_by_key()
    {
        Sanctum::actingAs(User::factory()->create());

        Translation::factory()->create(['key' => 'dashboard_home']);

        $this->getJson('/api/translations?key=dashboard_home')
            ->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    public function test_export_performance()
    {
        Sanctum::actingAs(User::factory()->create());

        Translation::factory()->count(2000)->create();

        $start = microtime(true);

        $response = $this->getJson('/api/translations-export');

        $end = microtime(true);
        $duration = ($end - $start) * 1000;

        $response->assertStatus(200);
        $this->assertLessThan(500, $duration, 'Export took too long');
    }
}
