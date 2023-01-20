<?php

namespace App\Console\Commands;

use App\Models\Quote;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchQuotes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quotes:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch The Lord Of The Rings quotes (https://the-one-api.dev/documentation)';

    /**
     * How much quotes to fetch at a single time
     *
     * @var int
     */
    private $quotesPerPage = 50;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {        
        try {
            $this->makeApiRequest();            
            return Command::SUCCESS;
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
            return Command::FAILURE;
        }                
    }

    private function makeApiRequest()
    {                
        $response = $this->makeRequest();
        $this->handleResponse($response);        
    }

    private function makeRequest()
    {        
        return Http::withToken(config('api.key'))->get('https://the-one-api.dev/v2/quote', [
            'limit' => $this->quotesPerPage,
            'page' => $this->pageToFetch(),
        ])->throw();
    }

    private function handleResponse($response)
    {        
        Quote::insert($this->getQuotesFromResponse($response));
        
        $this->info("fetched $this->quotesPerPage quotes (page {$this->pageToFetch()} of {$response['pages']})");
    }

    private function getQuotesFromResponse($response)
    {
        $quotes = collect($response['docs'])->unique('dialog');

        $quotes = $quotes->map(function ($value) {
            return ['text' => $value['dialog']];
        })->toArray();

        return $quotes;
    }

    private function pageToFetch()
    {
        return intdiv(Quote::all()->count(), $this->quotesPerPage) + 1;
    }
}
