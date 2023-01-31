<?php

namespace App\Services;

use App\Http\Resources\QuoteCollection;
use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use App\Models\QuoteUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FavouriteQuotesService
{
    public function addToFavourites(int $quoteId)
    {
        QuoteUser::create([
            'user_id' => auth()->id(),
            'quote_id' => $quoteId,
        ]);

        return new QuoteResource(Quote::find($quoteId));
    }

    public function removeFromFavourites(int $quoteId)
    {
        QuoteUser::where('user_id', auth()->id())
            ->where('quote_id', $quoteId)
            ->first()
            ->delete();

        return new QuoteResource(Quote::find($quoteId));
    }

    public function getNonFavouriteQuotes(string $loaderType)
    {
        return match ($loaderType) {
            'sql' => $this->nonFavouritesBySql(),
            'inMemory' => $this->nonFavouritesByMemory(),
        };
    }

    private function nonFavouritesBySql()
    {
        $nonFavourite = DB::table('quotes')->whereNotIn('id', function ($query) {
            $query->select('quote_id as id')
                ->from('quote_user')
                ->where('user_id', auth()->id());
        })->get();

        return new QuoteCollection($nonFavourite);
    }

    private function nonFavouritesByMemory()
    {
        $quotes = Quote::all();
        $favouriteQuotes = User::find(auth()->id())->favouriteQuotes;
        $nonFavourite = $quotes->diff($favouriteQuotes);

        return new QuoteCollection($nonFavourite);
    }
}
