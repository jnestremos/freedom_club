<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;


class ResetPasswordController extends Controller
{
    public function emailVerify(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|email'
        ]);
        $customer = Customer::where('cust_email', $request->email)->first();
        $provider_id = $customer->user->provider_id;
        if ($customer && !$provider_id) {
            if (DB::table('customer_password_resets')->select('*')->where('email', '=', $request->email)->first()) {
                DB::table('customer_password_resets')->where('email', '=', $request->email)->update([
                    'token' => Str::random(60)
                ]);
            } else {
                DB::table('customer_password_resets')->insert([
                    'email' => $request->email,
                    'token' => Str::random(60)
                ]);
            }
            $profile = DB::table('customer_password_resets')->select('*')->where('email', '=', $request->email)->first();
            Mail::to($request->email)->send(new ResetPassword($profile));
            return back()->with([
                'status' => 'Please check your email for reset password'
            ]);
        } else {
            return back()->with([
                'error' => 'Email not recognized'
            ]);
        }

        // $status = Password::sendResetLink(
        //     $request->only('email')
        // );

        // if ($status === Password::RESET_LINK_SENT) {
        //     return back()->with(['status' => __($status)]);
        // } else {
        //     return back()->withErrors(['email' => __($status)]);
        // }
    }

    public function resetPassword(Request $request)
    {

        $this->validate($request, [
            'token' => 'required',
            'password' => 'required|min:10|confirmed',
        ]);

        $customer = Customer::where('cust_email', '=', $request->email)->first();

        $user = User::find($customer->user_id);
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('loginCustomer.index');

        // $status = Password::reset(
        //     $request->only('email', 'password', 'password_confirmation', 'token'),
        //     function ($user, $password) {
        //         $user->forceFill([
        //             'password' => Hash::make($password)
        //         ])->setRememberToken(Str::random(60));

        //         $user->save();

        //         event(new PasswordReset($user));
        //     }
        // );

        // if ($status === Password::PASSWORD_RESET) {
        //     return redirect()->route('loginCustomer.index')->with('status', __($status));
        // } else {
        //     return back()->withErrors(['email' => [__($status)]]);
        // }
    }
}
