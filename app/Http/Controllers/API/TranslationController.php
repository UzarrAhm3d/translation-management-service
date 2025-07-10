<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTranslationRequest;
use App\Http\Requests\UpdateTranslationRequest;
use App\Services\TranslationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function __construct(
        private TranslationService $translationService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['locale', 'key', 'tags', 'search']);
        $perPage = $request->integer('per_page', 15);

        $translations = $this->translationService->searchTranslations($filters, $perPage);

        return response()->json([
            'data' => $translations->items(),
            'meta' => [
                'current_page' => $translations->currentPage(),
                'per_page' => $translations->perPage(),
                'total' => $translations->total(),
                'last_page' => $translations->lastPage(),
            ],
        ]);
    }

    public function store(CreateTranslationRequest $request): JsonResponse
    {
        try {
            $translation = $this->translationService->createTranslation($request->validated());

            return response()->json([
                'message' => 'Translation created successfully',
                'data' => $translation,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create translation',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function show(string $key, string $locale): JsonResponse 
    {
        $translation = $this->translationService->getTranslation($key, $locale);

        if (!$translation) {
            return response()->json([
                'message' => 'Translation not found'
            ]);
        }

        return response()->json([
            'data' => $translation
        ]);
    }

    public function update(UpdateTranslationRequest $request, string $key, string $locale): JsonResponse
    {
        try {
            $translation = $this->translationService->updateTranslation($key, $locale, $request->validated());
            
            return response()->json([
                'message' => 'Translation updated successfully',
                'data' => $translation,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update translation',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function destroy(string $key, string $locale): JsonResponse
    {
        try {
            $this->translationService->deleteTranslation($key, $locale);
            
            return response()->json([
                'message' => 'Translation deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete translation',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function export(string $locale): JsonResponse
    {
        $startTime = microtime(true);
        
        $translations = $this->translationService->getTranslationsForLocale($locale);
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;

        return response()->json([
            'locale' => $locale,
            'translations' => $translations,
            'meta' => [
                'count' => count($translations),
                'execution_time_ms' => round($executionTime, 2),
            ],
        ]);
    }
}
