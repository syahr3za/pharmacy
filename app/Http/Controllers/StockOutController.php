<?php

namespace App\Http\Controllers;

use App\Models\StockOut;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StockOutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::all();

        return view('admin.stock_out.index', compact('items'));
    }
    public function api()
    {
        $stock_outs = StockOut::with('items');

        return datatables()->of($stock_outs)
                        ->addIndexColumn()
                        ->addColumn('created_at', function($stock_outs){
                            return en_date($stock_outs->created_at);
                        })
                        ->editColumn('item_id', function($stock_outs){
                            return $stock_outs->items->name;
                        })
                        ->addColumn('total_price', function ($stock_outs) {
                            return 'Rp. '. format_uang($stock_outs->price);
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $items = Item::where('id', $request->item_id)->first();
        // ke stock out tabel
        $stock_outs = new StockOut();
        $stock_outs->item_id = $request->item_id;
        $stock_outs->qty = $request->qty;
        $stock_outs->detail = $request->detail;
        $stock_outs->price = $items->buy_price * $stock_outs->qty;
        $stock_outs->save();

        // update qty di tabel item                  
        $item = Item::find($stock_outs->item_id);
        $item->qty -= $stock_outs->qty;
        $item->update();

        return redirect('stock_outs');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StockOut  $stockOut
     * @return \Illuminate\Http\Response
     */
    public function show(StockOut $stockOut)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockOut  $stockOut
     * @return \Illuminate\Http\Response
     */
    public function edit(StockOut $stockOut)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StockOut  $stockOut
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockOut $stockOut)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockOut  $stockOut
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockOut $stockOut, $id)
    {
        $stock_outs = StockOut::find($id);
        $items = Item::where('id', $stock_outs->item_id)->first();
        $items->qty += $stock_outs->qty;
        $items->update();
        
        $stock_outs->delete();

        return redirect('stock_outs');
    }
}
