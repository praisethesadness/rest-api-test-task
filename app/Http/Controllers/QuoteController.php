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
        return $this->quotesService->addToFavourites($id);
    }

    public function removeFromFavourites($id)
    {
        return $this->quotesService->removeFromFavourites($id);
        
    }

    public function nonFavourites(Request $request)
    {
        return $this->quotesService->getNonFavouriteQuotes($request);
    }
}
