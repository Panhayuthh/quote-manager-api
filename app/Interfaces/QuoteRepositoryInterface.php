<?php

namespace App\Interfaces;

interface QuoteRepositoryInterface
{
    public function all($id);
    public function generate();
    public function store(array $data, int $userId);
    public function delete(int $id);
}
