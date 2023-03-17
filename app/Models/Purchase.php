<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'purchases';
    protected $primaryKey = 'purchase_id';
    protected $guarded = [];
    // protected $fillable = ['supplier_id', 'date'];

    /*public function items()
    {
        return $this->belongsToMany('App\Models\Item', 'purchases_details', 'purchase_id', 'item_id');
    }*/

    public function suppliers()
    {
        return $this->belongsTo('App\Models\Supplier', 'supplier_id');
    }

    /*public function detail_pivot()
    {
        return $this->belongsToMany('App\Models\Item', 'purchases_details', 'purchase_id', 'item_id')
        ->withPivot([
            'qty'
        ])->withTimestamps();
    }*/
}
