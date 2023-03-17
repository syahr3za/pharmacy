<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    use HasFactory;

    protected $table = 'stock_outs';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function items()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
}
