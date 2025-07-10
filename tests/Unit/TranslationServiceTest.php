<?php

namespace Tests\Unit;

use App\Models\Translation;
use App\Repositories\TranslationRepository;
use App\Repositories\TranslationRepositoryInterface;
use App\Services\TranslationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\TestCase;

class TranslationServiceTest extends TestCase
{
    use RefreshDatabase;

    private TranslationService $service;
    private TranslationRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        app()->bind(TranslationRepositoryInterface::class, TranslationRepository::class);

        $this->repository = app(TranslationRepositoryInterface::class);
        $this->service = new TranslationService($this->repository);
    }

    public function test_create_translation(): void
    {
        $data = [
            'key' => 'test_key',
            'locale' => 'en',
            'content' => 'Test content',
            'tags' => ['web'],
        ];

        $translation = $this->service->createTranslation($data);

        $this->assertInstanceOf(Translation::class, $translation);
        $this->assertEquals($data['key'], $translation->key);
        $this->assertEquals($data['locale'], $translation->locale);
        $this->assertEquals($data['content'], $translation->content);
    }

    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }
}
