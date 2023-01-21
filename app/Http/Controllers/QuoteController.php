<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Services\FavouriteQuotesService;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    private $quotesService;

    public function __construct(FavouriteQuotesService $service)
    {
        $this->middleware('auth:api')->except('index');
        $this->quotesService = $service;
    }

    public function index()
    {
        return Quote::paginate(15);
    }

    public function addToFavourites($id)
    {
        try {
            $this->quotesService->addToFavourites($id);
        } catch (\Throwable $th) {
            return response(
                ['error' => 'INTERNAL_ERROR'],
                status: 500
            );
        }
    }

    public function removeFromFavourites($id)
    {
        try {
            $this->quotesService->removeFromFavourites($id);
        } catch (\Throwable $th) {
            return response(
                ['error' => 'INTERNAL_ERROR'],
                status: 500
            );
        }
        
    }

    public function nonFavourites(Request $request)
    {
        try {
            return $this->quotesService->getNonFavouriteQuotes($request);
        } catch (\Throwable $th) {
            return response(
                ['error' => 'INTERNAL_ERROR'],
                status: 500
            );
        }        
    }
}
