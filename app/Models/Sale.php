<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'total_amount',
        'discount',
        'vat',
        'amount_paid',
        'due_amount',
    ];
    
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
