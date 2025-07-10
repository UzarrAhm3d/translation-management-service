<?php

namespace App\Repositories;

use App\Models\Translation;

use App\Repositories\TranslationRepositoryInterface;

class TranslationRepository implements TranslationRepositoryInterface
{
    public function create(array $data): Translation
    {
        return Translation::create($data);
    }

    public function update(Translation $translation, array $data): Translation
    {
        $translation->update($data);
        return $translation->fresh();
    }

    public function delete(Translation $translation): bool
    {
        return $translation->delete();
    }

    public function findByKeyAndLocale(string $key, string $locale): Translation
    {
        return Translation::byKey($key)->byLocale($locale)->first();
    }
}