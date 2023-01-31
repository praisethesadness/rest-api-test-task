<?php

namespace App\Jobs;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class FetchQuotes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * How many pages to fetch at a single time
     * 
     * @var int
     */
    private int $pages = 5;

    /**
     * Fetch The Lord Of The Rings quotes (https://the-one-api.dev/documentation)
     *
     * @return void
     */
    public function handle()
    {
        $response = $this->makeRequest();
        $this->handleResponse($response);
    }

    private function makeRequest()
    {        
        return Http::withToken(config('api.key'))->get('https://the-one-api.dev/v2/quote', [            
            'page' => $this->pages,
        ])->throw();
    }

    private function handleResponse($response)
    {        
        Quote::insert($this->getQuotesFromResponse($response));
    }

    private function getQuotesFromResponse($response)
    {
        $quotes = collect($response['docs'])->unique('dialog');

        $quotes = $quotes->map(function ($value) {
            return ['text' => $value['dialog']];
        })->toArray();

        return $quotes;
    }
}
