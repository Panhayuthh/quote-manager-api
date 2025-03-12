<?php

namespace App\Repositories;

use App\Interfaces\QuoteRepositoryInterface;
use App\Models\Quote;
use Illuminate\Support\Facades\Http;

class QuoteRepository implements QuoteRepositoryInterface
{
    public function all()
    {
        return Quote::all();
    }

    public function generate()
    {
        try {
            $response = Http::get('http://api.quotable.io/random');
            return [
                'content' => $response['content'],
                'author' => $response['author'],
            ];
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function store($data, $userId)
    {
        return Quote::create([
            'content' => $data['content'],
            'author' => $data['author'],
            'user_id' => $userId,
        ]);
    }

    public function delete($id)
    {
        return Quote::destroy($id);
    }
}
