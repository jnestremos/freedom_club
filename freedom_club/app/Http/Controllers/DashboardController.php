<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\Expense;
use App\Models\Shipment;
use App\Models\Supplier;
use App\Models\SuppTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $requestUri = explode('/', $request->getRequestUri());
        $currentUri = $requestUri[count($requestUri) - 1];
        if (auth()->user()->hasRole('customer')) {
            return redirect()->route('home');
        } else {
            if (
                $currentUri == 'home' && Carbon::now()->day == 22 && count(Expense::all()) != 0
                && count(DB::table('sales')->where('deleted_at', null)->get()) != 0
            ) {
                $exp_description = [];
                $exp_id = [];
                $exp_total = [];
                foreach (Expense::get() as $expense) {
                    array_push($exp_description, $expense->description);
                    array_push($exp_id, $expense->id);
                    array_push($exp_total, $expense->computed_expenses);
                    $expense->delete();
                    $expense->save();
                }
                $prev_balance = null;
                foreach ($exp_id as $index => $expense) {
                    if (count(DB::table('balance_sheet')->get()) == 1) {
                        $starting_capital = DB::table('balance_sheet')->where('description', 'Starting Capital')->first()->total_balance;
                        DB::table('balance_sheet')->insert([
                            'expense_id' => $expense,
                            'description' => $exp_description[$index],
                            'credit_amount' => $exp_total[$index],
                            'total_balance' => $starting_capital - $exp_total[$index],
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                        $prev_balance = DB::table('balance_sheet')->where('expense_id', $expense)->first()->total_balance;
                    } else {
                        DB::table('balance_sheet')->insert([
                            'expense_id' => $expense,
                            'description' => $exp_description[$index],
                            'credit_amount' => $exp_total[$index],
                            'total_balance' => $prev_balance - $exp_total[$index],
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                        $prev_balance = DB::table('balance_sheet')->where('expense_id', $expense)->first()->total_balance;
                    }
                }
                $sales_categories = [];
                $sales_id = [];
                $sales_total = [];
                foreach (DB::table('sales')->get() as $sale) {
                    array_push($sales_categories, $sale->sales_category);
                    array_push($sales_id, $sale->id);
                    array_push($sales_total, $sale->total);
                }
                DB::table('sales')->whereIn('id', $sales_id)->update([
                    'deleted_at' => Carbon::now()
                ]);
                foreach ($sales_id as $index => $sale) {
                    DB::table('balance_sheet')->insert([
                        'sale_id' => $sale,
                        'description' => $sales_categories[$index],
                        'debit_amount' => $sales_total[$index],
                        'total_balance' => $prev_balance + $sales_total[$index],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                    $prev_balance = DB::table('balance_sheet')->where('sale_id', $sale)->first()->total_balance;
                }
                Checkout::where('status', true)->delete();
                $supp_transactions_id = [];
                foreach (SuppTransaction::where('datePaid', '!=', null)->get() as $transaction) {
                    array_push($supp_transactions_id, $transaction->id);
                }
                SuppTransaction::where('datePaid', '!=', null)->delete();
                foreach ($supp_transactions_id as $id) {
                    DB::table('material_transaction')->where('supp_transactions_id', $id)->update([
                        'deleted_at' => Carbon::now()
                    ]);
                    Shipment::where('supp_transactions_id', $id)->delete();
                }
                DB::table('stock_transfers')->where('confirmed', 'True')->update([
                    'deleted_at'  => Carbon::now()
                ]);
                DB::table('sales_returns')->where('status', true)->update([
                    'deleted_at'  => Carbon::now()
                ]);
            }
            return view('employee.emp-' . $currentUri);
        }
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
