<?php

namespace App\Http\Controllers;

use App\Mail\RemindPayment;
use App\Models\Expense;
use App\Models\Material;
use App\Models\MaterialTransaction;
use App\Models\Shipment;
use App\Models\SuppTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\RedirectController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class SupplierTransactionController extends Controller
{
    public function store(Request $request)
    {
        //dd($request);
        $this->validate($request, [
            // 'receipt_num' => 'required|unique:App\Models\SuppTransaction,receipt_num',
            //'receipt_number' => 'required|unique:App\Models\SuppTransaction,receipt_num',
            'supplier_id' => 'required',
            'material_id' => 'required',
            'shipping_fee' => 'required',
            'material_quantity' => 'required'
        ]);
        if ($request->shipping_fee == 0) {
            return redirect()->route('dashboard.purchases')->with('error', 'The shipping fee field is required');
        }
        if ($request->material_quantity == 0) {
            return redirect()->route('dashboard.purchases')->with('error', 'The material quantity field is required');
        }
        //dd($request->input('material_id1') == null);
        $materialIDArray = [];
        $materialQtyArray = [];
        $totalCost = 0;
        //dd(array_keys($request->except(['_token', 'receipt_number', 'supplier_id', 'shipping_fee', 'material_id', 'material_quantity', 'datePaid'])));
        foreach ($request->except(['_token', 'receipt_number', 'supplier_id', 'shipping_fee', 'material_id', 'material_quantity', 'datePaid']) as $array_key => $value) {
            $key_name = str_split($array_key);
            $a = array_pop($key_name);
            $key_name = join($key_name);
            if ($key_name == 'material_id') {
                array_push($materialIDArray, $request->input($array_key));
            } elseif ($key_name == 'material_qty' && $request->input($array_key) != 0) {
                array_push($materialQtyArray, $request->input($array_key));
            } else {
                return redirect()->route('dashboard.purchases')->with('error', 'One of the fields was left empty!');
            }
        }
        if (count($materialIDArray) != count($materialQtyArray)) {
            return redirect()->route('dashboard.purchases')->with('error', 'One of the fields was left empty!');
        }
        array_unshift($materialIDArray, $request->material_id);
        array_unshift($materialQtyArray, $request->material_quantity);
        //dd($materialIDArray . $materialQtyArray);
        //dd($materialQtyArray);
        //dd($materialIDArray[0]);

        for ($i = 0; $i < count($materialIDArray); $i++) {
            $totalCost = $totalCost + (Material::find($materialIDArray[$i])->material_price * $materialQtyArray[$i]);
        }
        $totalCost = $totalCost + $request->shipping_fee;


        // $totalCost = $totalCost + (Material::find($request->material_id)->material_price * $request->material_qty);

        $supplierTransaction = SuppTransaction::create([
            'receipt_num' => rand(),
            'supplier_id' => $request->supplier_id,
            'totalCost' => $totalCost,
            //'datePaid' => $request->datePaid,
        ]);

        if ($request->datePaid == 'true') {
            $supplierTransaction->datePaid = Carbon::now();
        }
        $supplierTransaction->save();

        $shipment = Shipment::create([
            'supp_transactions_id' => $supplierTransaction->id,
            'receipt_number' => $supplierTransaction->receipt_num,
            'shipping_fee' => $request->shipping_fee,
        ]);




        for ($i = 0; $i < count($materialIDArray); $i++) {
            $material_id = Material::find($materialIDArray[$i]);
            //$materialTransaction = $supplierTransaction->materials()->attach($material_id);
            $supplierTransaction->materials()->attach($material_id);
            //dd($materialTransaction);
            // $materialTransaction->material_type = $material_id->material_type;
            // $materialTransaction->material_size = $material_id->material_size;
            // $materialTransaction->material_color = $material_id->material_color;
            // $materialTransaction->material_qty = $materialQtyArray[$i];
            // $materialTransaction->material_price = $material_id->material_price;
            //$id = MaterialTransaction::find($materialTransaction->id);

        }

        foreach (DB::table('material_transaction')->select('*')->where('supp_transactions_id', '=', $supplierTransaction->id)->get() as $index => $materialTransaction) {
            DB::table('material_transaction')->where('id', $materialTransaction->id)->update([
                'material_number' => Material::find($materialTransaction->material_id)->material_number,
                'material_type' => Material::find($materialTransaction->material_id)->material_type,
                'material_size' => Material::find($materialTransaction->material_id)->material_size,
                'material_color' => Material::find($materialTransaction->material_id)->material_color,
                'material_qty' => $materialQtyArray[$index],
                'material_price' => Material::find($materialTransaction->material_id)->material_price,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        if ($request->datePaid == 'true') {
            //dd(DB::table('material_transaction')->where('supp_transactions_id', $supplierTransaction->id)->get());            
            Expense::create([
                'shipment_id' => $shipment->id,
                'receipt_number' => $supplierTransaction->receipt_num,
                'expense_categories_id' => 1,
                'description' => 'Raw Material Purchase + Shipping Fee',
                'computed_expenses' => $totalCost,
            ]);
        }
        // else {
        //     Mail::to(auth()->user->employee->emp_email)->send(new RemindPayment(auth()->user->employee));
        // }

        return redirect()->route('dashboard.purchases');
    }

    public function update(Request $request, $id)
    {
        //dd($request);
        //dd(SuppTransaction::find(1)->supplierTransactionMaterial);
        $this->validate($request, [
            'datePaid' => 'required'
        ]);
        $supplierTransaction = SuppTransaction::find($id);
        if ($request->datePaid == 'true') {
            $supplierTransaction->datePaid = Carbon::now();
            $supplierTransaction->save();
            Expense::create([
                'shipment_id' => Shipment::where('supp_transactions_id', $supplierTransaction->id)->first()->id,
                'receipt_number' => Shipment::where('supp_transactions_id', $supplierTransaction->id)->first()->receipt_number,
                'expense_categories_id' => 1,
                'description' => 'Raw Material Purchase + Shipping Fee',
                'computed_expenses' => $supplierTransaction->totalCost
            ]);
        } else {
            return redirect()->route('dashboard.purchases')->with('error', 'The link has already been used!');
        }
        return redirect()->route('dashboard.purchases');
    }
    public function updateFromEmail($id)
    {
        if (SuppTransaction::find($id)->datePaid != null) {
            return redirect()->route('dashboard.purchases')->with('error', 'Link has been already used!');
        } else {
            SuppTransaction::find($id)->update([
                'datePaid' => Carbon::now()
            ]);
            $supplierTransaction = SuppTransaction::find($id);
            Expense::create([
                'shipment_id' => Shipment::where('supp_transactions_id', $supplierTransaction->id)->first()->id,
                'receipt_number' => Shipment::where('supp_transactions_id', $supplierTransaction->id)->first()->receipt_number,
                'expense_categories_id' => 1,
                'description' => 'Raw Material Purchase + Shipping Fee',
                'computed_expenses' => $supplierTransaction->totalCost
            ]);
            return redirect()->route('dashboard.purchases');
        }
    }
    public function delete($id)
    {
        SuppTransaction::find($id)->forceDelete();
        return redirect()->route('dashboard.purchases');
    }

    public function indexItems()
    {
        return view('employee.emp-purchases-list');
    }
    public function updateItems(Request $request, $id)
    {
        $this->validate($request, [
            'material_id' => 'required',
            'material_qty' => 'required',
        ]);

        if ($request->material_qty == 0) {
            return redirect()->route('purchases.indexItems')->with('error', 'Quantity should not be zero!');
        }

        $material_price = Material::find($request->material_id)->material_price;

        DB::table('material_transaction')->where('id', $id)->update([
            'material_qty' => $request->material_qty,
            'material_price' => $material_price,
            'updated_at' => Carbon::now()
        ]);
        $costs = [];
        foreach (DB::table('material_transaction')->select('*')->where('supp_transactions_id', $request->supp_transactions_id)->get() as $item) {
            array_push($costs, ($item->material_price * $item->material_qty));
        }
        $totalCost = 0;
        foreach ($costs as $cost) {
            $totalCost = $totalCost + $cost;
        }
        // DB::table('supp_transactions')->where('id', $request->supp_transactions_id)->update([
        //     'totalCost' => $totalCost,
        //     'updated_at' => Carbon::now()
        // ]);

        $transaction = SuppTransaction::find($request->supp_transactions_id);
        $transaction->totalCost = $totalCost;
        $transaction->save();

        return redirect()->route('purchases.indexItems');
    }

    public function deleteItems(Request $request, $id)
    {

        DB::table('material_transaction')->where('id', $id)->delete();
        // SuppTransaction::find($id)->forceDelete();

        $costs = [];

        foreach (DB::table('material_transaction')->select('*')->where('supp_transactions_id', $request->user_id)->get() as $item) {
            array_push($costs, ($item->material_price * $item->material_qty));
        }
        //dd($costs);
        $totalCost = 0;
        foreach ($costs as $cost) {
            $totalCost = $totalCost + $cost;
        }

        $transaction = SuppTransaction::find($request->user_id);
        $transaction->totalCost = $totalCost;
        $transaction->save();

        return redirect()->route('purchases.indexItems');
    }
}
