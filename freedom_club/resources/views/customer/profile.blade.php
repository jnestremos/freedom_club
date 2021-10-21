@extends('layouts.app')
@section('content')
@php
    use App\Models\User;
@endphp
<style>
    #password_click:hover{
        cursor: pointer;
    }
    #purchase_history:hover{        
        text-decoration: underline;
    }
    #deliveries:hover{        
        text-decoration: underline;
    }
    #return:hover{        
        text-decoration: underline;
    }
    
</style>
<div style="position: absolute; right:30%; top:50%; color:white; font-family:Bahnschrift">
    <ul>    
        @if (session()->has('error'))
            <li>{{ session('error') }}</li>
        @endif
        @error ('cust_firstName')
            <li>{{ $message }}</li>
        @enderror
        @error ('cust_lastName')
            <li>{{ $message }}</li>
        @enderror
        @error ('cust_email')
            <li>{{ $message }}</li>
        @enderror            
        @error ('cust_birthDate')
            <li>{{ $message }}</li>
        @enderror
        @error ('old_password')
            <li>{{ $message }}</li>
        @enderror
        @error ('password')
            <li>{{ $message }}</li>
        @enderror
        @if (auth()->user()->customer->cust_address === null && auth()->user()->customer->cust_phoneNum === null)            
            <li>Please type in your current address and phone number!</li>         
        @endif
    </ul>  
</div>
    <div style="width: 100%; height:90vh; background-color: black; display:flex; justify-content:center; align-items:center; font-family: Bahnschrift">           
        <div style="width:90%; height:90%; display:flex; padding-top:30px">
            <form method="POST" action="{{ url('/profile/'. auth()->user()->id) }}" style="width:60%; position: relative;">
                @csrf
                <h1 style="color: white">MY PROFILE</h1>                                                        
                <div style="display: flex">
                    <div>
                        <label for="" style="color: white">First Name:</label>
                        <input type="text" value="{{ auth()->user()->customer->cust_firstName }}" class="form-control" style="width: 200px; margin-right:20px" name="cust_firstName"> 
                    </div>
                    <div>
                        <label for="" style="color: white">Last Name:</label>
                        <input type="text" value="{{ auth()->user()->customer->cust_lastName }}" class="form-control" style="width: 200px" name="cust_lastName">       
                    </div>
                </div>   
                <br>
                @if (auth()->user()->customer->cust_address)
                <label for="" style="color: white">Address:</label>                              
                <input type="address" value="{{ auth()->user()->customer->cust_address }}" class="form-control" style="width: 250px" name="cust_address">   
                @else
                <label for="" style="color: white">Address:</label>                              
                <input type="address" value="" class="form-control" style="width: 250px" name="cust_address">  
                @endif                    
                <br>    
                <label for="" style="color: white">Email:</label>                              
                <input type="email" value="{{ auth()->user()->customer->cust_email }}" class="form-control" style="width: 250px" name="cust_email">
                <br>  
                @if (auth()->user()->customer->cust_phoneNum)
                <label for="" style="color: white">Phone Num:</label>                              
                <input type="tel" value="{{ auth()->user()->customer->cust_phoneNum }}" class="form-control" style="width: 250px" name="cust_phoneNum">
                @else
                <label for="" style="color: white">Phone Num:</label>                              
                <input type="tel" class="form-control" style="width: 250px" name="cust_phoneNum">
                @endif
                <br>                             
                <label for="" style="color: white">Birthdate:</label>
                <input type="date" class="form-control" value="{{ auth()->user()->customer->cust_birthDate }}" style="width: 250px" name="cust_birthDate">
                <br>
                <button type="submit" class="btn btn-success">Save</button>                                
                <div id="old_new_confirm" style="position: absolute; right:0%; top:20%; color:white" hidden>
                    <div style="display: flex">
                        <label for="old_password" style="width:50%">Old Password:</label>
                        <input type="password" style="width:50%" name="old_password" id="old_password" disabled>
                    </div>
                    <br>
                    <div style="display: flex">
                        <label for="password" style="width:50%">New Password:</label>
                        <input type="password" style="width:50%" name="password" id="password" disabled>
                    </div>
                    <br>
                    <div style="display: flex">
                        <label for="password_confirmation" style="width:50%">Confirm Password:</label>
                        <input type="password" style="width:50%" name="password_confirmation" id="password_confirmation" disabled>
                    </div>
                </div>
            </form> 
            <form method="POST" action="{{ url('/profile/'.auth()->user()->id) }}" style="position: absolute; bottom:26.5%; left: 10%;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Deactivate</button>
            </form>
            <div style="width:40%; display:flex; flex-direction:column; align-items:flex-end">
                <a href="{{ route('customer.showHistory') }}" style="text-decoration: none"><h6 id="purchase_history" style="color: white">
                    Purchase History
                </h6></a>
                <h6 style="color: white">
                    Wish List
                </h6>
                <div>
                    <div style="width: 120px; height:3px; background-color:blue"></div>
                </div>
                <h6 style="color: white">
                    Need Help?
                </h6>
                <a href="{{ route('customer.return') }}" style="text-decoration: none"><h6 id="return" style="color: white">
                    Return & Refund
                </h6></a>
                <h6 style="color: white">
                    Products
                </h6>
                <a href="{{ route('customer.showDeliveries') }}" style="text-decoration: none"><h6 id="deliveries" style="color: white">
                    Delivery
                </h6></a>
                              
            </div>         
            {{-- <input type="text"> --}}
        </div>
        
        
    </div>
    <script>
        var password_click = document.getElementById('password_click')
        var old_new_confirm = document.getElementById('old_new_confirm')
        var old_password = document.getElementById('old_password')
        var password = document.getElementById('password')
        var password_confirmation = document.getElementById('password_confirmation')
        password_click.addEventListener('click', function(){
            old_new_confirm.hidden = false
            old_password.disabled = false
            password.disabled = false
            password_confirmation.disabled = false
        })
    </script>
@endsection

{{-- <h2>MY PROFILE</h2>  
        <form action="">
            <input type="text" value="{{ auth()->user()->customer->cust_firstName }}">
            <input type="text" value="{{ auth()->user()->customer->cust_lastName }}">
            <input type="text" value="{{ auth()->user()->customer->cust_gender }}">
            <input type="text" value="{{ auth()->user()->customer->cust_email }}">
            <input type="date" value="{{ auth()->user()->customer->cust_birthDate }}">            
            <input type="submit">
        </form> --}}