<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $fillable = [
        'date',
        'type',
        'amount',
        'sale_id',
    ];
}
