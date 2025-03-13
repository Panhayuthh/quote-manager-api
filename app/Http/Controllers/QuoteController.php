<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\StoreQuoteRequest;
use App\Interfaces\QuoteRepositoryInterface;
use App\Models\Quote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuoteController extends Controller
{
    private QuoteRepositoryInterface $quoteRepository;

    // dependency injection is used to inject the QuoteRepositoryInterface into the QuoteController
    public function __construct(QuoteRepositoryInterface $quoteRepository)
    {
        $this->quoteRepository = $quoteRepository;
    }

    public function generate()
    {
        $quote = $this->quoteRepository->generate();
        return ApiResponseClass::sendResponse($quote, 'Quote generated successfully');
    }

    public function index()
    {
        $quotes = $this->quoteRepository->all();
        return ApiResponseClass::sendResponse($quotes, 'Quotes retrieved successfully');
    }

    public function store(StoreQuoteRequest $request)
    {
        $data = [
            'content' => $request->content,
            'author' => $request->author,

        ];
        DB::beginTransaction();
        try {
            $quote = $this->quoteRepository->store($data, Auth::id()); # does this work with api 
            DB::commit();
            return ApiResponseClass::sendResponse($quote, 'Quote created successfully');
        } catch (\Exception $e) {
            ApiResponseClass::rollback($e, 'An error occurred while creating quote');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $this->quoteRepository->delete($id);
            DB::commit();
            return ApiResponseClass::sendResponse([], 'Quote deleted successfully');
        } catch (\Exception $e) {
            ApiResponseClass::rollback($e, 'An error occurred while deleting quote');
        }
    }
}
