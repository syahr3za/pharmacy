<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';
    protected $primaryKey = 'sale_id';
    protected $guarded = [];

    // public function items()
    // {
    //     return $this->belongsToMany('App\Models\Item', 'sale_details', 'sale_id', 'item_id');
    // }

    // public function Customer()
    // {
    //     return $this->belongsTo('App\Models\Customer', 'customer_id');
    // }
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function customers()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
}
