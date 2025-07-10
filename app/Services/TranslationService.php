<?php

namespace App\Services;

use App\Models\Translation;
use App\Repositories\TranslationRepositoryInterface;

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
}