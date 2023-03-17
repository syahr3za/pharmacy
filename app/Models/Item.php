<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name','form_id','classification_id','sell_price', 'qty', 'buy_price'];

    public function Classification()
    {
        return $this->belongsTo('App\Models\Classification', 'classification_id');
    }

    public function Form()
    {
        return $this->belongsTo('App\Models\Form', 'form_id');
    }

    /*public function Sale()
    {
        return $this->belongsToMany('App\Models\Sale', 'sale_details');
    }

    public function stock_outs()
    {
        return $this->hasMany('App\Models\StockOut', 'item_id');
    }

    public function Purchase()
    {
        return $this->belongsTo('App\Models\Purchase', 'item_id');
    }*/
}
