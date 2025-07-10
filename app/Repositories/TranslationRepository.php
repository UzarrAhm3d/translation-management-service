<?php

namespace App\Repositories;

use App\Models\Translation;
use App\Repositories\TranslationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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

    public function search(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = Translation::query();

        if (!empty($filters['locale'])) {
            $query->byLocale($filters['locale']);
        }

        if (!empty($filters['key'])) {
            $query->byKey($filters['key']);
        }

        if (!empty($filters['tags'])) {
            $query->byTags($filters['tags']);
        }

        if (!empty($filters['search'])) {
            $query->searchContent($filters['search']);
        }

        return $query->orderBy('key')
                    ->orderBy('locale')
                    ->paginate($perPage);
    }

    public function getByLocale(string $locale): Collection
    {
        return Translation::byLocale($locale)->get();
    }

    public function bulkCreate(array $translations): bool
    {
        try {
            Translation::insert($translations);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}