<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\PurchasesDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
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
        $suppliers = Supplier::orderBy('name')->get();

        return view('admin.purchase.index', compact('suppliers'));
    }
    public function data()
    {
        $purchases = Purchase::orderBy('purchase_id', 'desc')->get();

        return datatables()
            ->of($purchases)
            ->addIndexColumn()
            ->addColumn('total_item', function ($purchases) {
                return format_uang($purchases->total_item);
            })
            ->addColumn('total_price', function ($purchases) {
                return 'Rp. '. format_uang($purchases->total_price);
            })
            ->addColumn('payment', function ($purchases) {
                return 'Rp. '. format_uang($purchases->payment);
            })
            ->addColumn('date', function ($purchases) {
                return en_date($purchases->created_at, false);
            })
            ->addColumn('supplier', function ($purchases) {
                return $purchases->suppliers->name;
            })
            ->editColumn('diskon', function ($purchases) {
                return $purchases->diskon . '%';
            })
            ->addColumn('action', function ($purchases) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('purchases.show', $purchases->purchase_id) .'`)" class="btn btn-xs btn-success btn-flat"><i class="fa fa-eye">detail</i></button>
                    <button onclick="deleteData(`'. route('purchases.destroy', $purchases->purchase_id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash">delete</i></button>
                </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $purchases = new Purchase();
        $purchases->supplier_id = $id;
        $purchases->total_item  = 0;
        $purchases->total_price = 0;
        $purchases->diskon      = 0;
        $purchases->payment     = 0;
        $purchases->save();

        session(['purchase_id' => $purchases->purchase_id]);
        session(['supplier_id' => $purchases->supplier_id]);

        return redirect()->route('purchases_details.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $purchases = Purchase::findOrFail($request->purchase_id);
        $purchases->total_item = $request->total_item;
        $purchases->total_price = $request->total;
        $purchases->payment = $request->payment;
        $purchases->diskon = $request->diskon;
        $purchases->update();

        $detail = PurchasesDetail::where('purchase_id', $purchases->purchase_id)->get();
        foreach ($detail as $item) {
            $items = Item::find($item->item_id);
            $items->qty += $item->qty;
            $items->update();
        }

        return redirect()->route('purchases.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(/*Purchase $purchase*/$id)
    {
        $detail = PurchasesDetail::with('items')->where('purchase_id', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('id', function ($detail) {
                return '<span class="label label-success">'. $detail->items->id .'</span>';
            })
            ->addColumn('item_name', function ($detail) {
                return $detail->items->name;
            })
            ->addColumn('buy_price', function ($detail) {
                return 'Rp. '. format_uang($detail->buy_price);
            })
            ->addColumn('qty', function ($detail) {
                return format_uang($detail->qty);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. '. format_uang($detail->subtotal);
            })
            ->rawColumns(['id'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(/*Purchase $purchase*/$id)
    {
        $purchases = Purchase::find($id);
        $detail    = PurchasesDetail::where('purchase_id', $purchases->purchase_id)->get();
        foreach ($detail as $item) {
            $items = Item::find($item->item_id);
            if ($items) {
                $items->qty -= $item->qty;
                $items->update();
            }
            $item->delete();
        }

        $purchases->delete();

        return response(null, 204);
    }
}
