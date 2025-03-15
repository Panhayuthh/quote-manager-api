<?php

namespace App\Repositories;

use App\Interfaces\QuoteRepositoryInterface;
use App\Models\Quote;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;

class QuoteRepository implements QuoteRepositoryInterface
{
    public function all($id)
    {
        return Quote::where('user_id', $id)->get();
    }

    public function generate()
    {
        try {

            // use zenquotes.io instead of the previous API (quotable.io) due to the latter's unreliability
            $response = Http::get('https://zenquotes.io/api/random')->json();
            return [
                'content' => $response[0]["q"],
                'author' => $response[0]["a"],
            ];
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function store($data, $userId)
    {
        $existingQuote = Quote::where('content', $data['content'])
                        ->where('author', $data['author'])->first();

        if ($existingQuote) {
            throw new HttpException(409, 'Quote already exists');
        }

        $quote = Quote::create([
            'content' => $data['content'],
            'author' => $data['author'],
            'user_id' => $userId,
        ]);

        return [
            'content' => $quote->content,
            'author' => $quote->author,
        ];
    }

    public function delete($id)
    {
        return Quote::destroy($id);
    }
}
