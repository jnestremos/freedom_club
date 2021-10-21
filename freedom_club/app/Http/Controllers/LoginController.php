<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laratrust\Laratrust;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function redirectToFacebook()
    {

        return Socialite::driver('facebook')->fields([
            'first_name', 'last_name', 'email', 'gender', 'birthday'
        ])->scopes(['user_gender', 'user_birthday, user_location'])->redirect();
    }

    public function handleFacebookCallback()
    {

        $user = Socialite::driver('facebook')->fields(['first_name', 'last_name', 'email', 'gender', 'birthday', 'location'])->user();
        //dd($user);
        $this->socMedLoginOrRegister($user);
        return redirect()->route('home');
    }

    protected function socMedLoginOrRegister($data)
    {

        $user = User::where('provider_id', '=', $data->id)->first();
        if (!$user) {
            //dd($data->user['location']['name']);
            $birthDateArray = explode('/', $data->user['birthday']);
            $month = $birthDateArray[0];
            $day = $birthDateArray[1];
            $year = $birthDateArray[2];
            $birthDate = $year . "-" . $month . "-" . $day;
            $user = new User();
            $user->provider_id = $data->id;
            //$user->profile_pic = 'no-image.jpg';
            $user->save();
            if (str_contains($data->user['location']['name'], 'City')) {
                Customer::create([
                    'user_id' => $user->id,
                    'cust_firstName' => $data->user['first_name'],
                    'cust_lastName' => $data->user['last_name'],
                    'cust_email' => $data->email,
                    'cust_gender' => $data->user['gender'],
                    'cust_birthDate' => $birthDate,
                    'cust_profile_pic' => 'no_image.jpg',
                    'cust_city' => $data->user['location']['name'],
                ]);
            } else {
                Customer::create([
                    'user_id' => $user->id,
                    'cust_firstName' => $data->user['first_name'],
                    'cust_lastName' => $data->user['last_name'],
                    'cust_email' => $data->email,
                    'cust_gender' => $data->user['gender'],
                    'cust_birthDate' => $birthDate,
                    'cust_profile_pic' => 'no_image.jpg',
                    'cust_province' => $data->user['location']['name'],
                ]);
            }

            $user->attachRole('customer');
        }
        Auth::login($user);
        return redirect()->route('home');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $previousUri = url()->previous();
        $previousUriArray = explode("/", $previousUri);
        $role = $previousUriArray[count($previousUriArray) - 1];


        $user = User::where('username', '=', $request->username)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password) && $role == 'customer') {
                if ($user->hasRole('customer')) {
                    Auth::login($user);
                    return redirect()->route('home');
                } else {
                    return back()->with('status', 'Invalid login details');
                }
            } elseif (Hash::check($request->password, $user->password) && $role == 'employee') {
                if (($user->hasRole('store_owner') || $user->hasRole('warehouse_manager') || $user->hasRole('product_manager'))) {
                    Auth::login($user);
                    return redirect()->route('dashboard.home');
                } else {
                    return back()->with('status', 'Invalid login details');
                }
            } elseif ($role == 'employee') {
                if ($user->hasRole('admin')) {
                    Auth::login($user);
                    return redirect()->route('admin.adminView');
                } else {
                    return back()->with('status', 'Invalid login details');
                }
            } else {
                return back()->with('status', 'Invalid login details');
            }
        } else {
            return back()->with('status', 'Invalid login details');
        }


        // if (Auth::attempt(['username' => $request->username, 'password' => $request->password]) && $role == 'customer') {
        //     return redirect()->route('dashboard.index');
        // } elseif (Auth::attempt(['username' => $request->username, 'password' => $request->password]) && $role == 'employee') {
        //     return redirect()->route('welcome');
        // } else {
        //     return back()->with('status', 'Invalid login details');
        // }
    }
}
