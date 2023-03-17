<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Customer;
use App\Models\Classification;
use App\Models\Supplier;
use App\Models\Form;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\StockOut;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $suppliers = Supplier::count();
        $customers = Customer::count();
        $items = Item::count();
        $sales = Sale::count();

        $first_date = date('Y-m-01');
        $end_date = date('Y-m-d');

        $date_data = array();
        $income_data = array();

        while (strtotime($first_date) <= strtotime($end_date)) {
            $date_data[] = (int) substr($first_date, 8, 2);

            $sales_total = Sale::where('created_at', 'LIKE', "%$first_date%")->sum('payment');
            $purchases_total = Purchase::where('created_at', 'LIKE', "%$first_date%")->sum('payment');
            $stockOuts_total = StockOut::where('created_at', 'LIKE', "%$first_date%")->sum('price');

            $income = $sales_total - $purchases_total - $stockOuts_total;
            $income_data[] += $income;

            $first_date = date('Y-m-d', strtotime("+1 day", strtotime($first_date)));
        }

        $first_date = date('Y-m-01');


        return view('admin.dashboard', compact('suppliers', 'customers', 'items', 'sales', 'first_date', 'end_date', 'date_data', 'income_data'));
    }

    public function spatie()
    {
        // $role = Role::create(['name' => 'cashier']);
        // $permission = Permission::create(['name' => 'transaction']);

        // $role->givePermissionTo($permission);
        // $permission->assignRole($role);

        // $user = auth()->user('id', 1)->first();
        // $user->assignRole('cashier');
        // return $user;

        //$user = User::with('roles')->get();
        //return $user;

        // $user = User::where('id', 2)->first();
        // $user->removeRole('cashier');
        // return $user;
    }
}
