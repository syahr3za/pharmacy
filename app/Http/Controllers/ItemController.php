<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Form;
use App\Models\Classification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
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
        $forms = Form::all();
        $classifications = Classification::all();
        //$items = Item::with('form','classification')->get();
        //return $items;
        return view('admin.item.index', compact('forms','classifications'));
    }
    public function api()
    {
        $items = Item::with('form','classification');

        return datatables()->of($items)
                        ->addIndexColumn()
                        ->addColumn('date', function($item){
                            return convert_date($item->created_at);
                        })
                        ->addColumn('select_all', function($item){
                            return '
                                <input type="checkbox" name="id[]" value="'. $item->id .'">
                            ';
                        })                   
                        ->rawColumns(['select_all'])
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
        $request->validate([
            'name'=>'required',
            'form_id'=>'required',
            'classification_id'=>'required',
            'sell_price'=>'required',
            'buy_price'=>'required',
            'diskon'=>'required',
            'qty'=>'required',
        ]);
        $items = new Item();
        $items->diskon = $request->diskon;
        $items->name = $request->name;
        $items->form_id = $request->form_id;
        $items->classification_id = $request->classification_id;
        $items->sell_price = $request->sell_price;
        $items->buy_price = $request->buy_price;
        $items->qty = $request->qty;
        $items->save();

        // Item::create($request->all());

        return redirect('items');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name'=>'required',
            'form_id'=>'required',
            'classification_id'=>'required',
            'sell_price'=>'required',
            'buy_price'=>'required',
            'diskon'=>'required',
            'qty'=>'required',
        ]);

        $item->update($request->all());

        return redirect('items');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $item->delete();
        
        return redirect('items');
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->id as $id) {
            $items = Item::find($id);
            $items->delete();
        }

        return response(null, 204);
    }
}
