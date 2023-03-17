<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;

    protected $table = 'sale_details';
    protected $primaryKey = 'sale_detail_id';
    protected $guarded = [];

    public function items()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    // public function customers()
    // {
    //     return $this->hasOne(Customer::class, 'id', 'customer_id');
    // }

}
