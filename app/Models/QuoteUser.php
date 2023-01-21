<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteUser extends Model
{
    use HasFactory;

    protected $table = 'quote_user';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'quote_id',
    ];
}
