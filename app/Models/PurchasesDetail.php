<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasesDetail extends Model
{
    use HasFactory;

    protected $table = 'purchases_details';
    protected $primaryKey = 'purchase_detail_id';
    protected $guarded = [];

    public function items()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
}
