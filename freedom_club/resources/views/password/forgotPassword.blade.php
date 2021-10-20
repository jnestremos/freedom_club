@extends('layouts.app')
@section('content')

    <div style="width: 500px; height:700px; border:2px solid white; position: absolute; right:10%; top:15%; display:flex; flex-wrap:wrap; justify-content:center;">
        <div style="width:100%; height:15%; background-color:white; display:flex; justify-content: center; align-items:center">
            <h1>Forgot Password</h1>
        </div>        
        @if (session()->has('status'))      
        <div style="width:100%; height:10px; display:flex; justify-content:center; align-items:center; color:green;">
            <h4>{{session('status')}}</h4>
        </div> 
        @else
            @if (session()->has('error'))
            <div style="width:100%; height:10px; display:flex; justify-content:center; align-items:center; color:red;">
                <h4>{{session('error')}}</h4>
            </div>
            @endif                   
        @endif                
        <div style="width: 100%; height:400px; display:flex; justify-content: center; align-items:center">
            <form action="{{ route('password.request') }}" method="POST" style="margin-top:10px;">
                @csrf   
                <label for="email" class="form-label text-white">Email</label>
                <input type="email" class="form-control mb-2" name="email" value="{{old('email')}}" style="width: 400px" @error('email') style="border: 2px solid red;" @enderror>
                @error('email')
                    <div class="mb-4" style="color:red">{{$message}}</div>
                @enderror                
                <div style="width: 100%; height:250px; display:flex; justify-content:center; align-items:center;">
                    <input type="submit" value="Confirm" class="btn btn-submit" style="height:100px; width:300px; border:2px solid white; color:grey; margin-top:50px">
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