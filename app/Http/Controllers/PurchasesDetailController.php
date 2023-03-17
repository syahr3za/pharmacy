<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchasesDetail;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchasesDetailController extends Controller
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
        $purchase_id = session('purchase_id');
        $items = Item::orderBy('name')->get();
        $suppliers = Supplier::find(session('supplier_id'));
        $diskon = Purchase::find($purchase_id)->diskon ?? 0;

        if (! $suppliers) {
            abort(404);
        }

        return view('admin.purchase_detail.index', compact('purchase_id', 'items', 'suppliers', 'diskon'));
    }

    public function data($id)
    {
        $detail = PurchasesDetail::with('items')
            ->where('purchase_id', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['id']        = $item->purchase_id;
            $row['item_name'] = $item->items['name'];
            $row['buy_price'] = 'Rp. '. format_uang($item->buy_price);
            $row['qty']       = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->purchase_detail_id .'" value="'. $item->qty .'">';
            $row['subtotal']  = 'Rp. '. format_uang($item->subtotal);
            $row['action']    = '<div class="btn-group">
                                <button onclick="deleteData(`'. route('purchases_details.destroy', $item->purchase_detail_id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash">Delete</i></button>
                                </div>';
            $data[] = $row;

            $total += $item->buy_price * $item->qty;
            $total_item += $item->qty;
        }
        $data[] = [
            'id' =>'<div class="total hide">'. $total .'</div>
                    <div class="total_item hide">'. $total_item .'</div>',
            'item_name' => '',
            'buy_price' => '',
            'qty'       => '',
            'subtotal'  => '',
            'action'    => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['action','qty','id'])
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
        $item = Item::where('id', $request->id)->first();
        if (! $item) {
            return response()->json('Data gagal disimpan', 400);
        }

        $detail = new PurchasesDetail();
        $detail->purchase_id = $request->purchase_id;
        $detail->item_id = $item->id;
        $detail->buy_price = $item->buy_price;
        $detail->qty = 1;
        $detail->subtotal = $item->buy_price;
        $detail->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchasesDetail  $purchasesDetail
     * @return \Illuminate\Http\Response
     */
    public function show(PurchasesDetail $purchasesDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchasesDetail  $purchasesDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchasesDetail $purchasesDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchasesDetail  $purchasesDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id /*PurchasesDetail $purchasesDetail*/)
    {
        $detail = PurchasesDetail::find($id);
        $detail->qty = $request->qty;
        $detail->subtotal = $detail->buy_price * $request->qty;
        $detail->update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchasesDetail  $purchasesDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(/*PurchasesDetail $purchasesDetail*/$id)
    {
        $detail = PurchasesDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($diskon, $total)
    {
        $payment = $total - ($diskon / 100 * $total);
        $data  = [
            'totalrp' => format_uang($total),
            'payment' => $payment,
            'pay' => format_uang($payment),
            'terbilang' => ucwords(terbilang($payment). ' Rupiah')
        ];

        return response()->json($data);
    }
}
