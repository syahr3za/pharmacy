<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\StockOut;
use Illuminate\Support\Facades\Auth;
use PDF;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {       
        $firstDate = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $endDate = date('Y-m-d');

        if ($request->has('first_date') && $request->first_date != "" && $request->has('end_date') && $request->end_date) {
            $firstDate = $request->first_date;
            $endDate = $request->end_date;
        }

        return view('admin.report.index', compact('firstDate', 'endDate'));
    }

    public function getData($first, $last)
    {
        $no = 1;
        $data = array();
        $income = 0;
        $total_income = 0;

        while (strtotime($first) <= strtotime($last)) {
            $date = $first;
            $first = date('Y-m-d', strtotime("+1 day", strtotime($first)));

            $total_sale = Sale::where('created_at', 'LIKE', "%$date%")->sum('payment');
            $total_purchase = Purchase::where('created_at', 'LIKE', "%$date%")->sum('payment');
            $total_stockOut = StockOut::where('created_at', 'LIKE', "%$date")->sum('price');

            $income = $total_sale - $total_purchase - $total_stockOut;
            $total_income += $income;

            $row = array();
            $row['DT_RowIndex'] = $no++;
            $row['date'] = en_date($date, false);
            $row['sale'] = format_uang($total_sale);
            $row['purchase'] = format_uang($total_purchase);
            $row['stock_out'] = format_uang($total_stockOut);
            $row['income'] = format_uang($income);

            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'date' => '',
            'sale' => '',
            'purchase' => '',
            'stock_out' => 'Total Income',
            'income' => format_uang($total_income),
        ];

        return $data;
    }

    public function data($first, $last)
    {
        $data = $this->getData($first, $last);

        return datatables()
            ->of($data)
            ->make(true);
    }

    public function exportPDF($first, $last)
    {
        $data = $this->getData($first, $last);
        $pdf = PDF::loadView('admin.report.pdf', compact('first', 'last', 'data'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Income-report-'. date('Y-m-d-his') .'.pdf');
    }
}
