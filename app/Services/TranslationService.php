<?php

namespace App\Services;

use App\Models\Translation;
use App\Repositories\TranslationRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class TranslationService
{
    public function __construct(
        private TranslationRepositoryInterface $translationRepository
    ) {}

    public function createTranslation(array $data): Translation 
    {
        return $this->translationRepository->create($data);
    }

    public function updateTranslation(string $key, string $locale, array $data): Translation
    {
        $translation = $this->translationRepository->findByKeyAndLocale($key, $locale);

        if(!$translation) {
            throw new \Exception('Translation not found');
        }

        return $this->translationRepository->update($translation, $data);
    }

    public function deleteTranslation(string $key, string $locale): bool
    {
        $translation = $this->translationRepository->findByKeyAndLocale($key, $locale);

        if(!$translation) {
            throw new \Exception('Translation not found');
        }

        return $this->translationRepository->delete($translation);
    }

    public function getTranslation(string $key, string $locale): ?Translation 
    {
        return $this->translationRepository->findByKeyAndLocale($key, $locale);
    }

    public function searchTranslations(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->translationRepository->search($filters, $perPage);
    }

    public function getTranslationsForLocale(string $locale): array
    {
        $translations = $this->translationRepository->getByLocale($locale);
        
        return $translations->pluck('content', 'key')->toArray();
    }

    public function bulkCreateTranslations(array $translations): bool
    {
        $formattedTranslations = [];
        $timestamp = now();

        foreach ($translations as $translation) {
            $formattedTranslations[] = [
                'key' => $translation['key'],
                'locale' => $translation['locale'],
                'content' => $translation['content'],
                'tags' => json_encode($translation['tags'] ?? []),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }

        return $this->translationRepository->bulkCreate($formattedTranslations);
    }
}