<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\StoreQuoteRequest;
use App\Interfaces\QuoteRepositoryInterface;
use App\Models\Quote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

    public function index(Request $request)
    {
        $id = $request->userId;
        $page = $request->page;

        $quotes = $this->quoteRepository->all($id);
        return ApiResponseClass::sendResponse($quotes, 'Quotes retrieved successfully');
    }

    public function store(StoreQuoteRequest $request)
    {
        $data = [
            'content' => $request->content,
            'author' => $request->author,
        ];
        $userId = $request->userId;

        DB::beginTransaction();
        try {
            $quote = $this->quoteRepository->store($data, $userId);
            DB::commit();
            return ApiResponseClass::sendResponse($quote, 'Quote created successfully', 201);
        } catch (HttpException $e) {
            DB::rollBack();
            ApiResponseClass::sendError($e, $e->getMessage(), $e->getStatusCode());
        } catch (\Exception $e) {
            ApiResponseClass::sendError($e, 'An error occurred while creating quote', 500);
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
            DB::rollBack();
            ApiResponseClass::sendError($e, 'An error occurred while deleting quote', 500);
        }
    }
}
