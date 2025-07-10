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
}
