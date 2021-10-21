<?php

namespace App\Http\Controllers;

use App\Mail\VerifyUser;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('employee.emp-register');
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

    public function adminView()
    {
        return view('admin.admin');
    }

    public function setAdmin(Request $request, $id)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required|min:10'
        ]);

        $user = User::find($id);
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->detachRole('admin');
        $user->attachRole('store_owner');
        $user->save();
        return redirect()->route('dashboard.home');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'firstName' => 'required|max:255|alpha',
            'lastName' => 'required|max:255|alpha',
            'birthDate' => 'required|date',
            'gender' => 'required',
            'role' => 'required',
            'username' => 'required|alpha_num|unique:App\Models\User,username',
            'email' => 'required|email|unique:App\Models\Employee,emp_email|unique:App\Models\Customer,cust_email',
            'password' => 'required|confirmed|min:10',
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'verification_token' => Str::random(60),
            'profile_pic' => 'no-image.jpg',

        ]);
        $role = $request->role;

        Employee::create([
            'user_id' => $user->id,
            'emp_firstName' => $request->firstName,
            'emp_lastName' => $request->lastName,
            'emp_birthDate' => $request->birthDate,
            'emp_gender' => $request->gender,
            'emp_email' => $request->email,
        ]);


        $user->attachRole($role);
        //Mail::to($request->email)->send(new VerifyUser($user));
        //event(new Registered($user));


        return redirect()->route('dashboard.employees');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('employee.emp-profile', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request, $id)
    {
        //     "_token" => "ikdxeO5iIfCqHb7E8OFlnXyhT6ip1erGt2X7fU2e"
        //   "_method" => "PUT"
        //   "firstName" => "admin"
        //   "lastName" => "admin"
        //   "birthDate" => "2021-09-24"
        //   "emp_gender" => "Female"
        //   "role" => "3"
        //   "username" => "giqoduv"
        //   "email" => "admin@admin.com"

        $this->validate($request, [
            'firstName' => 'required|max:255|alpha',
            'lastName' => 'required|max:255|alpha',
            'birthDate' => 'required|date',
            'emp_gender' => 'required',
            'username' => 'required|min:7|alpha_num',
            'email' => 'required|email',
            'old_password' => 'nullable',
            'password' => 'min:10|confirmed',
        ]);
        //dd($request);
        $targetEmployee = User::find($id);
        //dd($targetEmployee->employee->emp_firstName);
        $wrongEmployeeCollection = DB::table('employees')->select('*')->where('user_id', '!=',  $targetEmployee->id)->get();
        $validator = true;
        foreach ($wrongEmployeeCollection as $employee) {
            if ((strtolower($request->firstName) == strtolower($employee->emp_firstName)) || (strtolower($request->lastName) == strtolower($employee->emp_lastName))
                || ($request->username == User::find($employee->user_id)->username) || ($request->email == $employee->emp_email)
                || (strtolower($request->firstName) == 'admin') || (strtolower($request->lastName) == 'admin') || (strtolower($request->username) == 'admin')
            ) {
                $validator = false;
                break;
            }
        }
        if ($validator) {
            User::find($id)->update([
                'username' => $request->username
            ]);
            Employee::where('user_id', $targetEmployee->id)->update([
                'emp_firstName' => $request->firstName,
                'emp_lastName' => $request->lastName,
                'emp_email' => $request->email,
                'emp_gender' => $request->emp_gender,
                'emp_birthDate' => $request->birthDate,
            ]);
            $targetEmployee = User::find($id);
            if ($request->has('old_password') && $request->has('password')) {
                if (Hash::check($request->old_password, User::find($id)->password)) {
                    User::find($id)->update([
                        'password' => Hash::make($request->password)
                    ]);
                    if ($targetEmployee->hasRole('admin')) {
                        $targetEmployee->detachRole('admin');
                        $targetEmployee->attachRole('store_owner');
                    }
                    return view('employee.emp-profile', ['user' => $targetEmployee]);
                } else {
                    return redirect('/dashboard/profile/' . $id)->with(['error' => 'Old password incorrect! Please try again!']);
                    //return view('employee.emp-profile', ['user' => $targetEmployee])->with(['error' => 'Old password incorrect! Please try again!']);
                }
            }
            if ($targetEmployee->hasRole('admin')) {
                $targetEmployee->detachRole('admin');
                $targetEmployee->attachRole('store_owner');
            }
            return view('employee.emp-profile', ['user' => $targetEmployee]);
        } else {
            return redirect('/dashboard/profile/' . $id)->with(['error' => 'Update failed! Please try again!']);
            //return view('employee.emp-profile', ['user' => $targetEmployee])->with();
        }
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
        //dd(auth()->user()->hasRole('store_owner'));
        $this->validate($request, [
            'emp_firstName' => 'required',
            'emp_lastName' => 'required',
            'emp_email' => 'required|email',
            'emp_gender' => 'required',
            'emp_birthDate' => 'required',
        ]);

        $targetEmployee = User::find($id)->employee;
        $wrongEmployeeCollection = Employee::where('user_id', '!=',  $targetEmployee->user_id)->get();
        $validator = true;

        foreach ($wrongEmployeeCollection as $employee) {
            if (($request->emp_firstName == $employee->emp_firstName) || ($request->emp_lastName == $employee->emp_lastName)
                || ($request->emp_email == $employee->emp_email) || ($request->emp_gender == $employee->emp_gender)
                || ($request->emp_birthDate == $employee->emp_birthDate)
            ) {
                $validator = false;
                break;
            }
        }

        if ($validator) {
            DB::table('employees')->where('user_id', '=', $id)->update([
                'emp_firstName' => $request->emp_firstName,
                'emp_lastName' => $request->emp_lastName,
                'emp_email' => $request->emp_email,
                'emp_gender' => $request->emp_gender,
                'emp_birthDate' => $request->emp_birthDate,

            ]);
            return redirect()->route('dashboard.employees');
        } else {
            return redirect()->route('dashboard.employees')->with('error', 'Update not successful! Please do it again!');
        }
    }

    public function delete($id)
    {
        Employee::where('user_id', $id)->delete();
        User::find($id)->delete();
        return redirect()->route('dashboard.employees');
    }
    public function restore($id)
    {
        Employee::where('user_id', $id)->restore();
        User::find($id)->restore();
        return redirect()->route('dashboard.employees');
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
