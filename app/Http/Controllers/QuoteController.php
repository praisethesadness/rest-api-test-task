<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuoteCollection;
use App\Models\Quote;

class QuoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index');
    }

    public function index()
    {
        return new QuoteCollection(Quote::paginate(15));
    }
}
