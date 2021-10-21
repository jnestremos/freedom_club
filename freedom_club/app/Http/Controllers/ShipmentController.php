<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Material;
use App\Models\Shipment;
use App\Models\Stock;
use App\Models\SuppTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShipmentController extends Controller
{
    public function update(Request $request, $id)
    {

        if ($request->isReceived == 'true') {
            $shipment = Shipment::find($id);
            $shipment->dateReceived = Carbon::now();
            $shipment->save();
            foreach (DB::table('material_transaction')->where('supp_transactions_id', Shipment::find($id)->supp_transactions_id)->where('material_qty', '>', 0)->get() as $index => $material) {
                //dd($material);
                if (Stock::where('material_id', $material->material_id)->first()) {
                    $stock_qty = Stock::where('material_id', $material->material_id)->first()->stock_qty;
                    Stock::where('material_id', $material->material_id)->update([
                        'stock_qty' => $stock_qty + $material->material_qty
                    ]);
                } else {
                    Stock::create([
                        'stock_number' => rand(),
                        'material_id' => $material->material_id,
                        'material_number' => $material->material_number,
                        'stock_type' => $material->material_type,
                        'stock_size' => $material->material_size,
                        'stock_color' => $material->material_color,
                        'stock_qty' => $material->material_qty,
                        'stock_price' => $material->material_price,
                    ]);
                }
            }
            // if(){

            // }
        } else {
            $this->validate($request, [
                'material_id' => 'required',
                'quantity' => 'required',
                'return' => 'required',
            ]);
            if ($request->quantity > DB::table('material_transaction')->where('supp_transactions_id', Shipment::find($id)->supp_transactions_id)->where('material_id', $request->material_id)->first()->material_qty) {
                return redirect()->route('dashboard.shipments')->with('error', 'Invalid quantity!');
            } else {
                if ($request->return == 'Replace') {
                    $qty = DB::table('material_transaction')->where('supp_transactions_id', Shipment::find($id)->supp_transactions_id)->where('material_id', $request->material_id)->first()->material_qty;
                    DB::table('material_transaction')->where('supp_transactions_id', Shipment::find($id)->supp_transactions_id)->where('material_id', $request->material_id)->update([
                        'material_qty' => $qty - $request->quantity
                    ]);

                    $suppTransaction = SuppTransaction::create([
                        'receipt_num' => "REPLACE" . Shipment::find($id)->receipt_number,
                        'supplier_id' => Material::find($request->material_id)->supplier->id,
                        'totalCost' => $request->quantity * DB::table('material_transaction')->where('supp_transactions_id', Shipment::find($id)->supp_transactions_id)->where('material_id', $request->material_id)->first()->material_price,
                        'datePaid' => Carbon::now()
                    ]);
                    Shipment::create([
                        'receipt_number' => 'REPLACE' . Shipment::find($id)->receipt_number,
                        'supp_transactions_id' => $suppTransaction->id,
                        'shipping_fee' => 0
                    ]);
                    DB::table('material_transaction')->insert([
                        'supp_transactions_id' => $suppTransaction->id,
                        'material_id' => $request->material_id,
                        'material_number' => Material::find($request->material_id)->material_number,
                        'material_type' => Material::find($request->material_id)->material_type,
                        'material_size' => Material::find($request->material_id)->material_size,
                        'material_color' => Material::find($request->material_id)->material_color,
                        'material_qty' => $request->quantity,
                        'material_price' => Material::find($request->material_id)->material_price,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                    return redirect()->route('dashboard.shipments');
                } else {
                    $totalToDeduct = DB::table('material_transaction')->where('supp_transactions_id', Shipment::find($id)->supp_transactions_id)->where('material_id', $request->material_id)->first()->material_price * $request->quantity;
                    $total = SuppTransaction::find(Shipment::find($id)->supp_transactions_id)->totalCost;
                    if (Expense::where('shipment_id', $id)->first() != null) {
                        Expense::where('shipment_id', $id)->update([
                            'computed_expenses' => $total - $totalToDeduct
                        ]);
                    }
                    $qty = DB::table('material_transaction')->where('supp_transactions_id', Shipment::find($id)->supp_transactions_id)->where('material_id', $request->material_id)->first()->material_qty;
                    DB::table('material_transaction')->where('supp_transactions_id', Shipment::find($id)->supp_transactions_id)->where('material_id', $request->material_id)->update([
                        'material_qty' => $qty - $request->quantity
                    ]);
                    SuppTransaction::find(Shipment::find($id)->supp_transactions_id)->update([
                        'totalCost' => $total - $totalToDeduct
                    ]);
                    return redirect()->route('dashboard.shipments');
                }
            }
        }
        return redirect()->route('dashboard.shipments');
    }
}
