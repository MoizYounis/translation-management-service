<?php

namespace App\Contracts;

interface TranslationContract
{
    public function index($perPage, $data);
    public function store($data);
    public function show($id);
    public function update($id, $data);
    public function destroy($id);
    public function export();
}
