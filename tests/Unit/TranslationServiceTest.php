<?php

namespace Tests\Unit;

use App\Models\Translation;
use App\Services\TranslationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TranslationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TranslationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TranslationService();
    }

    public function test_it_can_store_translation()
    {
        $data = [
            'locale' => 'en',
            'key' => 'welcome',
            'value' => 'Welcome',
            'tags' => ['web'],
            'cdn_ready' => true,
        ];

        $translation = $this->service->store($data);

        $this->assertDatabaseHas('translations', [
            'locale' => 'en',
            'key' => 'welcome',
            'value' => 'Welcome',
            'cdn_ready' => true,
        ]);
    }

    public function test_it_can_update_translation()
    {
        $translation = Translation::factory()->create([
            'locale' => 'en',
            'key' => 'home',
            'value' => 'Home',
        ]);

        $updated = $this->service->update($translation->id, [
            'value' => 'Homepage',
        ]);

        $this->assertEquals('Homepage', $updated->value);
        $this->assertDatabaseHas('translations', [
            'id' => $translation->id,
            'value' => 'Homepage',
        ]);
    }

    public function test_it_can_delete_translation()
    {
        $translation = Translation::factory()->create();

        $result = $this->service->destroy($translation->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('translations', [
            'id' => $translation->id,
        ]);
    }

    public function test_it_can_export_translations_from_redis()
    {
        // Seed Redis manually
        Redis::sadd("translations_locales", 'en');
        Redis::hset("translations_export_en", 'greeting', 'Hello');

        $data = $this->service->export();

        $this->assertArrayHasKey('en', $data);
        $this->assertEquals('Hello', $data['en']['greeting']);
    }
}
