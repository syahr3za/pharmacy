<?php

namespace App\Http\Controllers;

use App\Models\SaleDetail;
use App\Models\Sale;
use App\Models\Item;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SaleDetailController extends Controller
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
        // $sale_id = session('sale_id');
        $items = Item::orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();

        //dd($items, $customers);
        //cek apakah ada transaksi yg sedang berjalan
        if ($sale_id = session('sale_id')) {
            $sale = Sale::find($sale_id);
            $diskon = Sale::find($sale_id)->diskon ?? 0;
            $customerSelected = $sale->customer ?? new Customer();
            return view('admin.sale_detail.index', compact('items', 'customers', 'sale_id', 'sale', 'customerSelected', 'diskon'));
        } else {
            return redirect()->route('transaction.new');
        }
        
    }

    public function data($id)
    {
        $detail = SaleDetail::with('items')
            ->where('sale_id', $id)
            ->get();

        // return $detail;

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['id']         = $item->sale_id;
            $row['item_name']  = $item->items['name'];
            $row['sell_price'] = 'Rp. '. format_uang($item->sell_price);
            $row['qty']        = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->sale_detail_id .'" value="'. $item->qty .'">';
            $row['diskon']     = $item->diskon . '%';
            $row['subtotal']   = 'Rp. '. format_uang($item->subtotal);
            $row['action']     = '<div class="btn-group">
                                <button onclick="deleteData(`'. route('transaction.destroy', $item->sale_detail_id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash">Delete</i></button>
                                </div>';
            $data[] = $row;

            $total += $item->sell_price * $item->qty - (($item->diskon * $item->qty) / 100 * $item->sell_price);;
            $total_item += $item->qty;
        }
        $data[] = [
            'id' =>'<div class="total hide">'. $total .'</div>
                    <div class="total_item hide">'. $total_item .'</div>',
            'item_name'  => '',
            'sell_price' => '',
            'qty'        => '',
            'diskon'     => '',
            'subtotal'   => '',
            'action'     => '',
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

        $detail = new SaleDetail();
        $detail->sale_id = $request->sale_id;
        $detail->item_id = $item->id;
        $detail->sell_price = $item->sell_price;
        $detail->qty = 1;
        $detail->diskon = $item->diskon;
        $detail->subtotal = $item->sell_price - ($item->diskon / 100 * $item->sell_price);;
        $detail->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SaleDetail  $saleDetail
     * @return \Illuminate\Http\Response
     */
    public function show(SaleDetail $saleDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SaleDetail  $saleDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(SaleDetail $saleDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SaleDetail  $saleDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, /*SaleDetail $saleDetail*/$id)
    {
        $detail = SaleDetail::find($id);
        $detail->qty = $request->qty;
        $detail->subtotal = $detail->sell_price * $request->qty - (($detail->diskon * $request->qty) / 100 * $detail->sell_price);;
        $detail->update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SaleDetail  $saleDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(/*SaleDetail $saleDetail*/$id)
    {
        $detail = SaleDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($diskon = 0, $total = 0, $receive = 0)
    {
        $payment = $total - ($diskon / 100 * $total);
        $change = ($receive != 0) ? $receive - $payment : 0;
        $data  = [
            'totalrp' => format_uang($total),
            'payment' => $payment,
            'pay' => format_uang($payment),
            'terbilang' => ucwords(terbilang($payment). ' Rupiah'),
            'changerp' => format_uang($change),
            'change_terbilang' => ucwords(terbilang($change). ' Rupiah'),
        ];

        return response()->json($data);
    }
}
