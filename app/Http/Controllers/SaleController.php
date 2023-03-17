<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use App\Models\SaleDetail;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SaleController extends Controller
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
        return view('admin.sale.index');
    }

    public function data()
    {
        $sales = Sale::orderBy('sale_id', 'asc')->get();

        return datatables()
            ->of($sales)
            ->addIndexColumn()
            ->addColumn('total_item', function ($sales) {
                return format_uang($sales->total_item);
            })
            ->addColumn('total_price', function ($sales) {
                return 'Rp. '. format_uang($sales->total_price);
            })
            ->addColumn('payment', function ($sales) {
                return 'Rp. '. format_uang($sales->payment);
            })
            ->addColumn('date', function ($sales) {
                return en_date($sales->created_at, false);
            })
            ->addColumn('customer', function ($sales) {
                return $sales->customers->name ?? '';
            })
            ->editColumn('diskon', function ($sales) {
                return $sales->diskon . '%';
            })
            ->editColumn('cashier', function ($sales) {
                return $sales->user->name ?? '';
            })
            ->addColumn('action', function ($sales) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('sale.show', $sales->sale_id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye">detail</i></button>
                    <button onclick="deleteData(`'. route('sale.destroy', $sales->sale_id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash">delete</i></button>
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
    public function create()
    {
        $sales = new Sale();
        $sales->customer_id = null;
        $sales->total_item = 0;
        $sales->total_price = 0;
        $sales->diskon = 0;
        $sales->payment = 0;
        $sales->receive = 0;
        $sales->user_id = auth()->id();
        $sales->save();

        session(['sale_id' => $sales->sale_id]);
        return redirect()->route('transaction.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sale = Sale::findOrFail($request->sale_id);
        $sale->customer_id = $request->customer_id;
        $sale->total_item = $request->total_item;
        $sale->total_price = $request->total;
        $sale->diskon = $request->diskon;
        $sale->payment = $request->payment;
        $sale->receive = $request->receive;
        $sale->update();

        $detail = SaleDetail::where('sale_id', $sale->sale_id)->get();
        foreach ($detail as $item) {
            $item->diskon = $request->diskon;
            $item->update();

            $items = Item::find($item->item_id);
            $items->qty -= $item->qty;
            $items->update();
        }

        return redirect()->route('transaction.finish');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function show(/*Sale $sale,*/ $id)
    {
        $detail = SaleDetail::with('items')->where('sale_id', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('id', function ($detail) {
                return '<span class="label label-success">'. $detail->items->id .'</span>';
            })
            ->addColumn('item_name', function ($detail) {
                return $detail->items->name;
            })
            ->addColumn('sell_price', function ($detail) {
                return 'Rp. '. format_uang($detail->sell_price);
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
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy(/*Sale $sale,*/ $id)
    {
        $sales  = Sale::find($id);
        $detail = SaleDetail::where('sale_id', $sales->sale_id)->get();
        foreach ($detail as $item) {
            $items = Item::find($item->item_id);
            if ($items) {
                $items->qty += $item->qty;
                $items->update();
            }
            $item->delete();
        }

        $sales->delete();

        return response(null, 204);
    }

    public function finish()
    {
        return view('admin.sale.finish');
    }

    public function receipt()
    {
        $sales = Sale::find(session('sale_id'));
        if (! $sales) {
            abort(404);
        }
        $detail = SaleDetail::with('items')
            ->where('sale_id', session('sale_id'))
            ->get();
        
        return view('admin.sale.receipt', compact('sales', 'detail'));
    }
}
