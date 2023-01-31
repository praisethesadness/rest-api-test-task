<?php

namespace App\Http\Controllers;

use App\Services\FavouriteQuotesService;
use Illuminate\Http\Request;

class FavouriteQuoteController extends Controller
{
    private FavouriteQuotesService $quotesService;

    public function __construct(FavouriteQuotesService $service)
    {
        $this->middleware('auth:api');
        $this->quotesService = $service;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->quotesService->addToFavourites($request->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        return $this->quotesService->removeFromFavourites($id);
    }

    /**
     * Get all quotes not added to 'favourite'
     *
     * @param  mixed $request
     * @return void
     */
    public function nonFavourites(Request $request)
    {
        return $this->quotesService->getNonFavouriteQuotes($request->query('loaderType'));
    }
}
