<?php

namespace App\Services;

use App\Models\Quote;
use App\Models\QuoteUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavouriteQuotesService
{
    public function addToFavourites(int $quoteId)
    {
        QuoteUser::create([
            'user_id' => auth()->id(),
            'quote_id' => $quoteId,
        ]);
    }

    public function removeFromFavourites(int $quoteId)
    {
        QuoteUser::where('user_id', auth()->id())
                 ->where('quote_id', $quoteId)
                 ->first()
                 ->delete();
    }

    public function getNonFavouriteQuotes(Request $request)
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