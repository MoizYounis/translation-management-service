<?php

namespace App\Contracts;

use App\Models\Translation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TranslationContract
{
    public function index($perPage, $data): LengthAwarePaginator;
    public function store($data): Translation;
    public function show($id): Translation;
    public function update($id, $data): Translation;
    public function destroy($id): bool;
    public function export(): array;
}
