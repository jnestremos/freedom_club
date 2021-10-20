<!--  LOGIN PAGE FOR ADMIN AND CUSTOMER   -->

@extends('layouts.app')
@section('content')

    <div style="width: 500px; height:700px; border:2px solid white; position: absolute; right:10%; top:15%; display:flex; flex-wrap:wrap; justify-content:center;">
        <div style="width:100%; height:15%; background-color:white; display:flex; justify-content: center; align-items:center">
            @if (Route::currentRouteName() == 'loginCustomer.index')
                <h1 style="font-family: Bahnschrift">SIGN-IN</h1>
            @elseif (Route::currentRouteName() == 'loginEmployee.index')
                <h1 style="font-family: Bahnschrift">Login as Employee</h1>
            @endif            
        </div>        
        @if (session()->has('status'))
        <div style="width:100%; height:10px; display:flex; justify-content:center; align-items:center; color:red;">
            <h4>{{session('status')}}</h4>
        </div>        
        @endif        
        <div style="width: 300px; height:400px; margin-top:20px">
            <form action="{{ route('login.store') }}" method="POST" style="margin-top:10px;">
                @csrf
                <label for="username" class="form-label text-white" style="font-family: Bahnschrift">Username</label>
                <input type="text" class="form-control mb-2" name="username" value="{{old('username')}}" @error('username') style="border: 2px solid red;" @enderror>
                @error('username')
                    <div class="mb-4" style="color:red">{{$message}}</div>
                @enderror
                <label for="password" class="form-label text-white" style="font-family: Bahnschrift">Password</label>
                <input type="password" class="form-control mb-2" name="password" value="{{old('password')}}" @error('password') style="border: 2px solid red;" @enderror>
                @error('password')
                    <div class="mb-2" style="color:red">{{$message}}</div>
                @enderror
                @if (Route::currentRouteName() == 'loginCustomer.index')
                <a href="{{ route('forgotPassword') }}" style="width:100%; color:grey; font-family: Bahnschrift">Forgot your password?</a>
                @endif                
                <div style="width: 100%; height:250px; display:flex; justify-content:center; align-items:center;">
                    <input type="submit" value="Login" class="btn btn-submit" style="height:100px; width:300px; border:2px solid white; color:grey; margin-top:50px; font-family: Bahnschrift">
                </div>                
            </form>            
        </div>
        <div style="width: 100%; position:relative;">
            @if (Route::currentRouteName() == 'loginCustomer.index')
            <h6 style="position:absolute; bottom:10%; left:3%; color:white; font-family: Verdana" id="admin_toggle"> <a href="{{ route('registerCustomer.index') }}">Register</a> </h6>
            <h6 style="position:absolute; bottom:10%; right:3%; color:white; font-family: Verdana" id="admin_toggle"> <a href="{{ route('loginEmployee.index') }}">Admin</a> </h6>
            @elseif (Route::currentRouteName() == 'loginEmployee.index')
            <h6 style="position:absolute; bottom:10%; left:3%; color:white; font-family: Verdana" id="admin_toggle"> <a href="{{ route('loginCustomer.index') }}">Customer</a> </h6>
            @endif                                                                        
        </div>
    </div>
    @if (Route::currentRouteName() == 'loginCustomer.index')
    <div style="position:absolute; width:300px; height:100px; bottom: 0%; right:15%; display:flex; justify-content:space-around; align-items:center; color:white;">     
        <div style="font-family: Bahnschrift">Login using: </div> <a href="{{ route('login.facebook') }}"><i class="fab fa-facebook" style="color: #4267B2; font-size:45px"></i></a>  <i class="fab fa-google" style="color: #D44638; font-size:40px"></i>   
    </div>        
    @endif
    
   
@endsection