<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['name','phone_number','address','email'];

    public function purchases()
    {
        return $this->hasMany('App\Models\Purchase', 'supplier_id');
    }
}
