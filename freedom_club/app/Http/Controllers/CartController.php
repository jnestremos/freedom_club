<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('customer.cart');
    }
    public function store(Request $request)
    {
        // "_token" => "0TZzcxZ7DTffggPFwrC2VdlzRXIWZ2hqEe5179zt"
        // "product_id" => "2"
        // "user_id" => "2"
        // "subtotal" => null
        // "prod_qty" => "0"

        //dd($request);

        if ($request->subtotal == null || $request->prod_qty < 0 || $request->prod_qty == 0 || $request->prod_qty > Product::find($request->product_id)->prod_qty) {
            return back()->with('error', 'Quantity error!');
        } else {
            $order = Cart::where('user_id', $request->user_id)->where('product_id', $request->product_id)->first();
            if ($order != null && $order->pending == false) {
                Cart::where('user_id', $request->user_id)->where('product_id', $request->product_id)->update([
                    'quantity' => $order->quantity + $request->prod_qty,
                    'subtotal' => $request->subtotal + $order->subtotal
                ]);
            } else {
                Cart::create([
                    'user_id' => $request->user_id,
                    'product_id' => $request->product_id,
                    'subtotal' => $request->subtotal,
                    'quantity' => $request->prod_qty,
                ]);
            }
            return redirect()->route('cart.index');
        }
    }
    public function update(Request $request, $id)
    {
        //dd($id);
        $subtotal = Cart::find($id)->subtotal;
        $qty = Cart::find($id)->quantity;
        Cart::find($id)->update([
            'quantity' => $request->prod_qty,
            'subtotal' => ($subtotal / $qty) * $request->prod_qty
        ]);
        return redirect()->route('cart.index');
    }
}
