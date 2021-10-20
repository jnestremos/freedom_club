<?php

namespace App\Http\Controllers;

use App\Mail\TransferRequest;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockTransfer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

//use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    public function store(Request $request)
    {
        //dd($request);
        $this->validate($request, [
            'stock_id' => 'required',
            'product_id' => 'required',
            'quantity_used' => 'required',
        ]);
        $stock = Stock::find($request->stock_id);
        $product = Product::find($request->product_id);
        if ($request->quantity_used > $stock->stock_qty) {
            return redirect()->route('dashboard.stocks')->with('error', 'Quantity placed is invalid!');
        } else {
            //dd($product->id);
            $stock->products()->attach($product->id);
            $stock->stock_qty = $stock->stock_qty - $request->quantity_used;
            $stock->save();
            $resultQuery =  DB::table('stock_transfers')->where('stock_id', $stock->id)->where('product_id', $product->id)->get();
            //dd($resultQuery);
            foreach ($resultQuery as $result) {
                if ($result->created_at == null) {
                    $id = $result->id;
                    DB::table('stock_transfers')->where('id', $id)->update([
                        'transfer_number' => rand(),
                        'stock_number' => $stock->stock_number,
                        'product_number' => $product->product_number,
                        'quantity' => $request->quantity_used,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                    $stockTransfer = DB::table('stock_transfers')->where('id', $id)->first();
                    Mail::to(auth()->user()->employee->emp_email)->send(new TransferRequest($stockTransfer));
                    break;
                }
            }
        }
        return redirect()->route('dashboard.stocks');
    }

    public function update(Request $request, $id)
    {

        //dd(DB::table('prod_name_color')->where('product_id', $request->product_id)->first());
        //dd($request->quantity);
        if ($request->setStatus == 'false') {
            $setStatus = 'False';
        } else {
            if ($request->setStatus == 'true') {
                $setStatus = 'True';
            }
        }
        //dd($request->setStatus);
        if ($setStatus == 'False') {
            DB::table('stock_transfers')->where('id', $request->transfer_id)->update([
                'confirmed' => $setStatus,
            ]);
            Stock::find($request->stock_id)->update([
                'stock_qty' => Stock::find($request->stock_id)->stock_qty + $request->quantity
            ]);
            return redirect()->route('dashboard.transfer');
        } else {
            DB::table('stock_transfers')->where('id', $id)->update([
                'confirmed' => $setStatus,
            ]);
            if ($setStatus == 'True') {
                if (Product::find($request->product_id)->prod_qty == null) {
                    Product::find($request->product_id)->update([
                        'prod_qty' => $request->quantity,
                    ]);
                } else {
                    $prod_qty = Product::find($request->product_id)->prod_qty;
                    Product::find($request->product_id)->update([
                        'prod_qty' => $request->quantity + $prod_qty
                    ]);
                }
            }
            return redirect()->route('dashboard.transfer');
        }
    }
}
