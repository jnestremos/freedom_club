@extends('layouts.app')
@section('content')

    <div style="width: 500px; height:700px; border:2px solid white; position: absolute; right:10%; top:15%; display:flex; flex-wrap:wrap; justify-content:center;">
        <div style="width:100%; height:15%; background-color:white; display:flex; justify-content: center; align-items:center">
            <h1>Login</h1>
        </div>        
        @if (session()->has('email'))
        <div style="width:100%; height:10px; display:flex; justify-content:center; align-items:center; color:green;">
            <h4>{{session('email')}}</h4>
        </div>            
        @endif                
        <div style="width: 300px; height:400px; margin-top:20px">
            <form action="{{ route('password.update') }}" method="POST" style="margin-top:10px;">                
                @csrf                               
                <label for="email" class="form-label text-white" hidden>Email</label>
                <input type="email" class="form-control mb-2" name="email" value="{{ $email }}" hidden>                
                <label for="password" class="form-label text-white" >New Password</label>
                <input type="password" class="form-control mb-2" name="password" @error('password')style="border; 2px solid red;"@enderror>
                @error('password')
                    <h4 style="color: red">{{ $message }}</h4>
                @enderror
                <label for="password_confirmation" class="form-label text-white">Confirm Password</label>
                <input type="password" class="form-control mb-2" name="password_confirmation" @error('password_confirmation')style="border; 2px solid red;"@enderror>
                @error('password_confirmation')
                    <h4 style="color: red">{{ $message }}</h4>
                @enderror           
                <div style="width: 100%; height:250px; display:flex; justify-content:center; align-items:center;">
                    <input type="submit" value="Confirm" class="btn btn-submit" style="height:100px; width:300px; border:2px solid white; color:grey;">
                </div>                
            </form>            
        </div>
        <div style="width: 100%; position:relative;">
            @if (Route::currentRouteName() == 'loginCustomer.index')
            <h6 style="position:absolute; bottom:10%; right:3%; color:white;" id="admin_toggle"> <a href="{{ route('loginEmployee.index') }}">Admin</a> </h6>
            @elseif (Route::currentRouteName() == 'loginEmployee.index')
            <h6 style="position:absolute; bottom:10%; left:3%; color:white;" id="admin_toggle"> <a href="{{ route('loginCustomer.index') }}">Customer</a> </h6>
            @endif                                                                        
        </div>
    </div>
    @if (Route::currentRouteName() == 'loginCustomer.index')
    <div style="position:absolute; width:300px; height:100px; bottom: 0%; right:15%; display:flex; justify-content:space-around; align-items:center; color:white;">     
        <div>Login using: </div> <a href="{{ route('login.facebook') }}"><i class="fab fa-facebook" style="color: #4267B2; font-size:45px"></i></a>  <i class="fab fa-google" style="color: #D44638; font-size:40px"></i>   
    </div>        
    @endif
    
   
@endsection