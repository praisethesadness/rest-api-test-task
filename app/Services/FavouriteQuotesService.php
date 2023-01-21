<?php

namespace App\Services;

use App\Models\Quote;
use App\Models\QuoteUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavouriteQuotesService
{
    private $errorMessage;

    public function __construct()
    {
        $this->errorMessage = response(['error' => 'INTERNAL_ERROR'], 500);
    }

    public function addToFavourites(int $quoteId)
    {
        try {
            QuoteUser::create([
                'user_id' => auth()->id(),
                'quote_id' => $quoteId,
            ]);
        } catch (\Throwable $th) {
            return $this->errorMessage;
        }
        
        return Quote::find($quoteId);
    }

    public function removeFromFavourites(int $quoteId)
    {
        try {
            QuoteUser::where('user_id', auth()->id())
                     ->where('quote_id', $quoteId)
                     ->first()
                     ->delete();
        } catch (\Throwable $th) {
            return $this->errorMessage;
        }

        return Quote::find($quoteId);
    }

    public function getNonFavouriteQuotes(Request $request)
    {        
        try {
            return $this->tryToGetQuotes($request);
        } catch (\Throwable $th) {
            return $this->errorMessage;
        }   
    }

    private function tryToGetQuotes(Request $request)
    {
        return match ($request->query('loaderType')) {
            'sql' => $this->nonFavouritesBySql(),
            'inMemory' => $this->nonFavouritesByMemory(),
        };
    }

    private function nonFavouritesBySql()
    {
        return DB::table('quotes')->whereNotIn('id', function ($query) {
            $query->select('quote_id as id')
                  ->from('quote_user')
                  ->where('user_id', auth()->id());
        })->get();
    }

    private function nonFavouritesByMemory()
    {
        $quotes = Quote::all();
        $favouriteQuotes = User::find(auth()->id())->favouriteQuotes;

        return $quotes->diff($favouriteQuotes);
    }
}