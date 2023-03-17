<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name','phone_number','address','email'];

    public function sales()
    {
        return $this->hasMany('App\Models\Sale', 'customer_id');
    }
}
