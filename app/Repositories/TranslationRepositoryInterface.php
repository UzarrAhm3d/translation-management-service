<?php

namespace App\Repositories;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TranslationRepositoryInterface 
{
    public function create(array $data): Translation;
    public function update(Translation $translation, array $data): Translation;
    public function delete(Translation $translation): bool;
    public function findByKeyAndLocale(string $key, string $locale): ?Translation;
    public function search(array $filters, int $perPage = 15): LengthAwarePaginator;
    public function getByLocale(string $locale): Collection;
    public function bulkCreate(array $translations): bool;
}