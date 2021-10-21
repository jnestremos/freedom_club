<?php

namespace App\Http\Controllers;

use App\Mail\VerifyUser;
use App\Models\Cart;
use App\Models\Checkout;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PSGC\Barangay;
use PSGC\City;
use PSGC\Municipality;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Psy\VersionUpdater\Checker;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('register');
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
        //dd($request);
        //"municipalityCity" => "045601"
        $this->validate($request, [
            'firstName' => 'required|max:255|alpha',
            'lastName' => 'required|max:255|alpha',
            'birthDate' => 'required|date',
            'gender' => 'required',
            'username' => 'required|alpha_num|unique:App\Models\User,username',
            'email' => 'required|email|unique:App\Models\Customer,cust_email',
            'password' => 'required|confirmed|min:10',
            'terms' => 'required',
            'municipalityCity' => 'required',
            'barangay' => 'required',
            'region' => 'required',
            'province' => 'required',
            'address' => 'required',
            'phone_num' => 'required|unique:App\Models\Customer,cust_phoneNum|digits:11|regex:([0][9]\d\d\d\d\d\d\d\d\d)',
        ]);

        $check = false;
        $barangay = new Barangay();
        foreach ($barangay->find($request->barangay) as $index => $location) {
            if ($index == 'municipality') {
                $check = true;
                break;
            } else {
                $check = false;
            }
        }
        if ($check) {
            $municipality = $barangay->find($request->barangay)->municipality->name;
        } else {
            $city = new City();
            $m = new Municipality();
            if ($m->find($request->municipalityCity . '000') == null) {
                $cityy = $m->find($request->municipalityCity . '000');
            } else {
                $cityy = $city->find($request->municipalityCity . '000')->name;
            }
        }

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'verification_token' => Str::random(60),
        ]);

        if ($request->has('notifyNews')) {
            if ($check) {
                Customer::create([
                    'user_id' => $user->id,
                    'cust_firstName' => $request->firstName,
                    'cust_lastName' => $request->lastName,
                    'cust_birthDate' => $request->birthDate,
                    'cust_gender' => $request->gender,
                    'cust_email' => $request->email,
                    'cust_address' => $request->address,
                    'cust_region' => $barangay->find($request->barangay)->region->name,
                    'cust_province' => $barangay->find($request->barangay)->province->name,
                    'cust_municipality' => $municipality,
                    'cust_barangay' => $barangay->find($request->barangay)->name,
                    'cust_notifyNews' => true,
                    'cust_phoneNum' => $request->phone_num,
                    'cust_profile_pic' => 'no-image.jpg',
                ]);
            } else {
                Customer::create([
                    'user_id' => $user->id,
                    'cust_firstName' => $request->firstName,
                    'cust_lastName' => $request->lastName,
                    'cust_birthDate' => $request->birthDate,
                    'cust_gender' => $request->gender,
                    'cust_email' => $request->email,
                    'cust_address' => $request->address,
                    'cust_region' => $barangay->find($request->barangay)->region->name,
                    'cust_province' => $barangay->find($request->barangay)->province->name,
                    'cust_city' => $cityy,
                    'cust_barangay' => $barangay->find($request->barangay)->name,
                    'cust_notifyNews' => true,
                    'cust_phoneNum' => $request->phone_num,
                    'cust_profile_pic' => 'no-image.jpg',
                ]);
            }
        } else {
            if ($check) {
                Customer::create([
                    'user_id' => $user->id,
                    'cust_firstName' => $request->firstName,
                    'cust_lastName' => $request->lastName,
                    'cust_birthDate' => $request->birthDate,
                    'cust_gender' => $request->gender,
                    'cust_email' => $request->email,
                    'cust_address' => $request->address,
                    'cust_region' => $barangay->find($request->barangay)->region->name,
                    'cust_province' => $barangay->find($request->barangay)->province->name,
                    'cust_municipality' => $municipality,
                    'cust_barangay' => $barangay->find($request->barangay)->name,
                    'cust_notifyNews' => true,
                    'cust_phoneNum' => $request->phone_num,
                    'cust_profile_pic' => 'no-image.jpg',
                ]);
            } else {
                Customer::create([
                    'user_id' => $user->id,
                    'cust_firstName' => $request->firstName,
                    'cust_lastName' => $request->lastName,
                    'cust_birthDate' => $request->birthDate,
                    'cust_gender' => $request->gender,
                    'cust_email' => $request->email,
                    'cust_address' => $request->address,
                    'cust_region' => $barangay->find($request->barangay)->region->name,
                    'cust_province' => $barangay->find($request->barangay)->province->name,
                    'cust_city' => $cityy,
                    'cust_barangay' => $barangay->find($request->barangay)->name,
                    'cust_notifyNews' => true,
                    'cust_phoneNum' => $request->phone_num,
                    'cust_profile_pic' => 'no-image.jpg',
                ]);
            }
        }

        $user->attachRole('customer');
        Mail::to($request->email)->send(new VerifyUser($user));
        //event(new Registered($user));
        Auth::login($user);


        return redirect()->route('home');
    }

    public function verifyEmail($token)
    {
        $verifiedUser = User::where('verification_token', '=', $token)->first();

        if ($verifiedUser) {
            $verifiedUser->email_verified_at = Carbon::now();
            $verifiedUser->save();
            return redirect()->route('home');
        } else {
            return redirect()->route('home')->with('error', 'The designated validation code has already expired! Please update your account with the latest email verification message.');
        }
    }

    public function resendVerify($id)
    {
        $user = User::find($id);
        $user->verification_token = Str::random(60);
        $user->save();
        Mail::to($user->customer->cust_email)->send(new VerifyUser($user));
        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('customer.profile');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showHistory()
    {
        return view('customer.purchase-history', ['cart_items' => Cart::where('user_id', auth()->user()->id)->where('checkout_id', '!=', null)->simplePaginate(10)]);
    }

    public function showDeliveries()
    {
        return view('customer.deliveries', ['cart_items' => Checkout::where('user_id', auth()->user()->id)->where('shipping_service', '!=', 'null')->simplePaginate(6)]);
    }
    public function updateDeliveries($id)
    {
        Checkout::find($id)->update([
            'dateReceived' => Carbon::now(),
        ]);
        return redirect()->route('customer.showDeliveries');
    }

    public function return()
    {
        return view('customer.return-form');
    }

    public function updateReturn(Request $request)
    {
        // "_token" => "zUKwM3iaBEdT3n4KEWq6QtzIAIU38DansYQdY4rf"2
        // "receipt_number" => null
        // "acc_name" => null
        // "acc_number" => null
        // "payment_method" => "GCash"


        //dd($request);
        if ($request->payment_method == 'COD' || $request->payment_method == 'Palawan Express' || $request->payment_method == 'GCash') {
            $this->validate($request, [
                'receipt_number' => 'required',
                'acc_name' => 'required',
                'acc_number' => 'required|digits:11|regex:([0][9]\d\d\d\d\d\d\d\d\d)',
                'payment_method' => 'required',
                'product_number' => 'required',
                'quantity' => 'required',
                'image' => 'required|image',
            ]);
        } else {
            $this->validate($request, [
                'receipt_number' => 'required',
                'acc_name' => 'required',
                'acc_number' => 'required|regex:(\d\d\d\d\s\d\d\d\d\s\d\d\d\d\s\d\d\d\d)',
                'payment_method' => 'required',
                'product_number' => 'required',
                'quantity' => 'required',
                'image' => 'required|image',
            ]);
        }


        if (Checkout::where('receipt_number', $request->receipt_number)->first() != null) {
            $checkout = Checkout::where('receipt_number', $request->receipt_number)->first();
            if ($checkout->user_id == auth()->user()->id && $checkout->dateReceived != null) {
                $checkoutDate = new Carbon($checkout->created_at);
                $dateToday = Carbon::now();
                if ($checkoutDate->addWeeks(3)->greaterThanOrEqualTo($dateToday)) {
                    if (
                        $request->payment_method == $checkout->payment_method
                        && strtolower($request->acc_name) == strtolower($checkout->acc_name) && $request->acc_number == $checkout->acc_num
                    ) {
                        if (
                            DB::table('sales_returns')->where('receipt_number', $request->receipt_number)->where('product_number', $request->product_number)->where('quantity', $request->quantity)->first() == null
                            && Cart::where('checkout_id', Checkout::where('receipt_number', $request->receipt_number)->first()->id)->where('product_id', Product::where('product_number', $request->product_number)->first()->id)->first() != null
                            && $request->quantity <= Cart::where('checkout_id', Checkout::where('receipt_number', $request->receipt_number)->first()->id)->where('product_id', Product::where('product_number', $request->product_number)->first()->id)->first()->quantity
                        ) {
                            $fileNameWithExt = $request->file('image')->getClientOriginalName();
                            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                            $extension = $request->file('image')->getClientOriginalExtension();
                            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
                            $path = $request->file('image')->storeAs('public/return_images', $fileNameToStore);
                            DB::table('sales_returns')->insert([
                                'request_number' => rand(),
                                'receipt_number' => $request->receipt_number,
                                'acc_name' => $request->acc_name,
                                'acc_number' => $request->acc_number,
                                'payment_method' => $request->payment_method,
                                'product_number' => $request->product_number,
                                'quantity' => $request->quantity,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                                'image' => $fileNameToStore
                            ]);
                        } else {
                            return back()->with('error', 'Error!');
                        }
                    } else {
                        return back()->with('error', 'Record not recognized!');
                    }
                } else {
                    return back()->with('error', 'Order cannot be returned/refunded after 2 weeks!');
                }
            } else {
                return back()->with('error', 'Receipt # not recognized!/Order hasn\'t been received yet');
            }
        } else {
            return back()->with('error', 'Receipt # not recognized!');
        }
        return redirect()->route('home');
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
        if ($request->has('old_password')) {
            $this->validate($request, [
                'cust_firstName' => 'required',
                'cust_lastName' => 'required',
                'cust_email' => 'required|email',
                'cust_address' => 'required',
                'cust_phoneNum' => 'required|unique:App\Models\Customer,cust_phoneNum|digits:11|regex:([0][9]\d\d\d\d\d\d\d\d\d)',
                'cust_birthDate' => 'required',
                'old_password' => 'required',
                'password' => 'required|confirmed',
            ]);
        } else {
            $this->validate($request, [
                'cust_firstName' => 'required',
                'cust_lastName' => 'required',
                'cust_email' => 'required|email',
                'cust_address' => 'required',
                'cust_phoneNum' => 'required|unique:App\Models\Customer,cust_phoneNum|digits:11|regex:([0][9]\d\d\d\d\d\d\d\d\d)',
                'cust_birthDate' => 'required',
            ]);
        }


        $targetCustomer = User::find($id)->customer;
        $wrongCustomerCollection = Customer::where('user_id', '!=',  $targetCustomer->user_id)->get();
        $validator = true;

        foreach ($wrongCustomerCollection as $customer) {
            if (($request->cust_firstName == $customer->cust_firstName) || ($request->cust_lastName == $customer->cust_lastName)
                || ($request->cust_email == $customer->cust_email) || ($request->cust_birthDate == $customer->cust_birthDate)
                || ($request->cust_phoneNum == $customer->cust_phoneNum) || ($request->cust_address == $customer->cust_address)
            ) {
                $validator = false;
                break;
            }
        }

        if ($validator) {
            DB::table('customers')->where('user_id', '=', $id)->update([
                'cust_firstName' => $request->cust_firstName,
                'cust_lastName' => $request->cust_lastName,
                'cust_address' => $request->cust_address,
                'cust_phoneNum' => $request->cust_phoneNum,
                'cust_email' => $request->cust_email,
                'cust_gender' => $request->cust_gender,
                'cust_birthDate' => $request->cust_birthDate,

            ]);
            return redirect()->route('customer.profile');
        } else {
            return redirect()->route('customer.profile')->with('error', 'Update not successful! Please do it again!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deactivate(Request $request, $id)
    {
        User::find($id)->delete();
        Customer::where('user_id', $id)->delete();
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('loginCustomer.index');
    }
}
