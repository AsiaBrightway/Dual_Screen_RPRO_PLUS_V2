<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockBalance extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id',
        'balance_date',
        'closing_balance',
    ];

    public static function syncClosingBalance($itemId, $date, $quantityChange)
    {
        if ($quantityChange == 0) return;
        
        $existingBalance = self::where('item_id', $itemId)
            ->whereDate('balance_date', $date)
            ->first();
            
        if ($existingBalance) {
            $existingBalance->closing_balance += $quantityChange;
            $existingBalance->save();
        }
    }
}
