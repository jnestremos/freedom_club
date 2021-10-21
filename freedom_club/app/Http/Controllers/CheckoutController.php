<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmPayment;
use App\Mail\CustomerReturn;
use App\Mail\ReceiptOrder;
use App\Models\Cart;
use App\Models\Checkout;
use App\Models\Customer;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use ourcodeworld\NameThatColor\ColorInterpreter;

class CheckoutController extends Controller
{
    public function index()
    {
        if (count(Cart::where('user_id', auth()->user()->id)->where('checkout_id', null)->where('pending', null)->get()) == 0) {
            return redirect()->route('home');
        } else {
            Checkout::where('payment_method', null)->where('acc_name', null)->where('acc_num', null)->forceDelete();
            return view('customer.checkout', ['cart_items' => Cart::where('user_id', auth()->user()->id)->where('checkout_id', null)->where('pending', null)->simplePaginate(3)]);
        }
    }

    public function pending(Request $request)
    {
        //dd($request);        
        $IDArray = [];
        $invoice_num = rand();
        //$check = true;    
        $checkout = Checkout::create([
            'user_id' => auth()->user()->id,
            'total' => $request->subtotal,
            'invoice_number' => $invoice_num,
        ]);
        foreach ($request->except('_token', 'subtotal') as $value) {
            if (Cart::where('user_id', auth()->user()->id)->where('id', $value)->where('checkout_id', $checkout->id)->first() == null) {
                //dd($checkout->id);                
                array_push($IDArray, $value);
                // $checkout->carts()->attach($value);
                // DB::table('cart_checkout')->where('cart_id', $value)->where('checkout_id', $checkout->id)->update([
                //     'user_id' => auth()->user()->id
                // ]);
            } else {
                $checkout->delete();
                $checkout->save();
                return back()->with('error', 'This order has been made already! Please check your email to confirm your request!');
            }
        }
        $IDArrayJoin = '';
        $index = 0;
        //dd($IDArrayy);
        foreach ($IDArray as $id) {
            if ($index == 0) {
                $IDArrayJoin = $id;
            } else {
                $IDArrayJoin = $IDArrayJoin . '|' . $id;
            }
            $index = $index + 1;
        }
        //dd($IDArrayJoin);
        //dd($IDArrayyJoin);
        //Mail::to(auth()->user()->customer->cust_email)->send(new ConfirmPayment($IDArrayyJoin));        
        return view('customer.checkout-form', ['IDArrayJoin' => $IDArrayJoin, 'checkout' => $checkout]);
        //return redirect()->route('cart.index');
        //Mail::to($request->user())->send(new ConfirmPayment($checkout));
    }


    public function confirm(Request $request, $IDArray)
    {
        //dd($request);
        $color = new ColorInterpreter();
        $IDArrayy = explode('|', $IDArray);
        if ($request->acc_name == null || $request->acc_num == null) {
            Checkout::find(Cart::find($IDArrayy[0])->checkout_id)->delete();
            return redirect()->route('home')->with('error', 'Input Error! Your Checkout request has been removed!');
        } else {
            //&& !(preg_match('([0][9]\d\d\d\d\d\d\d\d\d)', $request->acc_num))
            //dd(($request->payment_method == 'Palawan Express' || $request->payment_method == 'COD' || $request->payment_method == 'GCash') && !(preg_match('([0][9]\d\d\d\d\d\d\d\d\d)', $request->acc_num)));
            if (($request->payment_method == 'Palawan Express' || $request->payment_method == 'COD' || $request->payment_method == 'GCash') && !(preg_match('([0][9]\d\d\d\d\d\d\d\d\d)', $request->acc_num))) {
                return redirect()->route('home')->with('error', 'Input Error! Your Checkout request has been removed!');
            } else if (($request->payment_method == 'BDO' || $request->payment_method == 'BPI') && !(preg_match('(\d\d\d\d\s\d\d\d\d\s\d\d\d\d\s\d\d\d\d)', $request->acc_num))) {
                return redirect()->route('home')->with('error', 'Input Error! Your Checkout request has been removed!');
            } else {
                $token = Str::random(60);
                //$array = explode('|', $IDArray);
                //dd($array);
                $items = [];
                foreach ($IDArrayy as $id) {
                    if (Product::find(Cart::find($id)->product_id)->prod_qty <= 0) {
                        $prod_name = Product::find(Cart::find($id)->product_id)->prod_name;
                        $prod_color = Product::find(Cart::find($id)->product_id)->prod_color;
                        array_push($items, $prod_name . '-' . $color->name($prod_color)['name']);
                    }
                }
                if (!empty($items)) {
                    return redirect()->route('home')->with(['error' => 'Selected items have not been recorded!', 'items' => $items]);
                } else {
                    foreach ($IDArrayy as $id) {
                        Cart::find($id)->update([
                            'pending' => true,
                            'checkout_id' => $request->checkout_id
                        ]);
                        //dd(Cart::find($id)->quantity);
                        $qty = Product::find(Cart::find($id)->product_id)->prod_qty;
                        Product::find(Cart::find($id)->product_id)->update([
                            'prod_qty' => $qty - Cart::find($id)->quantity
                        ]);
                    }
                    Checkout::find(Cart::find($IDArrayy[0])->checkout_id)->update([
                        'confirm_token' => $token,
                        'acc_name' => $request->acc_name,
                        'acc_num' => $request->acc_num,
                        'payment_method' => $request->payment_method,
                    ]);
                    // foreach ($array as $id) {
                    //     Checkout::where('cart_id', $id)->update([
                    //         'confirm_token' => $token,
                    //         'acc_name' => $request->acc_name,
                    //         'acc_num' => $request->acc_num,
                    //         'payment_method' => $request->payment_method
                    //     ]);
                    // }
                    return redirect('/checkout/' . $IDArray . '/' . $token);
                }
            }
        }
    }
    public function showReturn()
    {
        return view('employee.emp-salesReturn');
    }
    public function updateReturn(Request $request, $receipt_number)
    {
        if ($request->setStatus == 'true') {
            $quantity = DB::table('sales_returns')->where('id', $request->return_id)->first()->quantity;
            $product_number = DB::table('sales_returns')->where('id', $request->return_id)->first()->product_number;
            $prod_type = Product::where('product_number', $product_number)->first()->prod_type;
            DB::table('sales_returns')->where('id', $request->return_id)->update([
                'status' => true,
                'updated_at' => Carbon::now(),
                'deleted_at' => Carbon::now()
            ]);
            $total = Product::where('product_number', $product_number)->first()->prod_price * $quantity;
            $salesTotal = DB::table('sales')->where('sales_category', $prod_type . ' Sales')->first()->total;
            DB::table('sales')->where('sales_category', $prod_type . ' Sales')->update([
                'total' => $salesTotal - $total
            ]);
            $checkout = Checkout::where('receipt_number', $receipt_number)->first();
            $customer = Customer::where('user_id', $checkout->user_id)->first();
            $salesReturn = DB::table('sales_returns')->where('id', $request->return_id)->first();
            Mail::to($customer->cust_email)->send(new CustomerReturn($salesReturn));
        } else {
            DB::table('sales_returns')->where('id', $request->return_id)->update([
                'status' => false,
                'updated_at' => Carbon::now(),
            ]);
        }
        return redirect()->route('orders.showReturn');
    }
    public function update(Request $request, $invoice_number)
    {
        if ($request->setStatus == 'false') {
            $setStatus = false;
            foreach (Cart::where('checkout_id', Checkout::where('invoice_number', $invoice_number)->first()->id)->get() as $item) {
                $qty = Product::find($item->product_id)->prod_qty;
                Product::find($item->product_id)->update([
                    'prod_qty' => $qty + $item->quantity
                ]);
            }
        } else {
            $this->validate($request, [
                'tracking_number' => 'required',
                'shipping_service' => 'required',
            ]);
            $setStatus = true;
            Checkout::where('invoice_number', $invoice_number)->update([
                'receipt_number' => rand(),
                'tracking_number' => $request->tracking_number,
                'shipping_service' => $request->shipping_service,
            ]);
            $checkout = Checkout::where('invoice_number', $invoice_number)->first();
            Mail::to(Customer::where('user_id', $checkout->user_id)->first()->cust_email)->send(new ReceiptOrder($checkout));
            foreach (Cart::where('checkout_id', Checkout::where('invoice_number', $invoice_number)->first()->id)->get() as $item) {
                if (DB::table('sales')->where('sales_category', '=', Product::find($item->product_id)->prod_type . ' Sales')->first() == null) {
                    DB::table('sales')->insert([
                        'sales_category' => Product::find($item->product_id)->prod_type . ' Sales',
                        'total' => $item->subtotal,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                } else {
                    $total = DB::table('sales')->where('sales_category', '=', Product::find($item->product_id)->prod_type . ' Sales')->first()->total;
                    DB::table('sales')->where('sales_category', '=', Product::find($item->product_id)->prod_type . ' Sales')->update([
                        'total' => $total + $item->subtotal,
                        'updated_at' => Carbon::now()
                    ]);
                }
            }
        }
        Checkout::where('invoice_number', $invoice_number)->update([
            'status' => $setStatus,
        ]);
        return redirect()->route('dashboard.orders');
    }
}
