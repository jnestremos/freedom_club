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
    <div style="width: 100%; height:90vh; background-color: black; display:flex; justify-content:center; align-items:center; font-family: Bahnschrift">   
        
        <div style="width:90%; height:90%; display:flex; padding-top:30px">
            <form method="POST" action="{{ route('customer.updateReturn') }}" style="width:60%; position: relative;" enctype="multipart/form-data">
                @csrf
                <h1 style="color: white">RETURN FORM</h1>
                <label for="" style="color: white">Receipt Number:</label>
                <div style="display:flex; justify-content:space-between; align-items:center">
                    <input type="text" name="receipt_number" class="form-control" style="width: 200px">                    
                </div>
                <br>                                           
                <div style="display: flex">
                    <div>
                        <label for="" style="color: white">Account Holder Name:</label>
                        <input type="text" class="form-control" style="width: 200px; margin-right:20px" name="acc_name"> 
                    </div>
                    <div>
                        <label for="" style="color: white">Account Number/Phone Number:</label>
                        <input type="text" class="form-control" style="width: 200px" name="acc_number">       
                    </div>
                </div>   
                <br>    
                <label for="" style="color: white">Payment Method Used:</label>  
                <div style="display:flex; justify-content:space-between; width:50%; color:white">
                    <div class="form-check">
                        <input class="form-check-input radio" type="radio" name="payment_method" id="exampleRadios1" value="GCash" checked>
                        <label class="form-check-label" for="exampleRadios1">
                          GCash
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input radio" type="radio" name="payment_method" id="exampleRadios2" value="BDO">
                        <label class="form-check-label" for="exampleRadios2">
                            BDO
                        </label>
                    </div> 
                    <div class="form-check">
                        <input class="form-check-input radio" type="radio" name="payment_method" id="exampleRadios1" value="BPI">
                        <label class="form-check-label" for="exampleRadios1">
                          BPI
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input radio" type="radio" name="payment_method" id="exampleRadios2" value="Palawan Express">
                        <label class="form-check-label" for="exampleRadios2">
                            Palawan Express
                        </label>
                    </div> 
                    <div class="form-check">
                        <input class="form-check-input radio" type="radio" name="payment_method" id="exampleRadios2" value="COD">
                        <label class="form-check-label" for="exampleRadios2">
                            COD
                        </label>
                    </div> 
                </div>                                            
                <br>
                <label for="" style="color: white">Product #:</label>
                <div style="display:flex; justify-content:space-between; align-items:center">
                    <input type="text" name="product_number" class="form-control" style="width: 200px">                    
                </div>
                <br>
                <label for="" style="color: white">Quantity:</label>
                <div style="display:flex; justify-content:space-between; align-items:center">
                    <input type="number" name="quantity" class="form-control" style="width: 200px" id="quantity" step="1" min="1">                    
                </div>     
                <br>
                <label for="" style="color: white">Photo of Defective Product:</label>
                <div style="display:flex; justify-content:space-between; align-items:center">
                    <input type="file" name="image" class="form-control" style="width: 200px" id="quantity" step="1" min="1">                    
                </div>  
                <br>              
                <button type="submit" class="btn btn-success">Send</button>                             
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
                <br><br>
                <ul style="color:white">    
                    @if (session()->has('error'))
                        <li>{{ session('error') }}</li>
                    @endif
                    @error ('receipt_number')
                        <li>{{ $message }}</li>
                    @enderror
                    @error ('acc_name')
                        <li>{{ $message }}</li>
                    @enderror
                    @error ('acc_number')
                        <li>{{ $message }}</li>
                    @enderror            
                    @error ('payment_method')
                        <li>{{ $message }}</li>
                    @enderror
                    @error ('prod_name')
                        <li>{{ $message }}</li>
                    @enderror
                    @error ('quantity')
                        <li>{{ $message }}</li>
                    @enderror
                    @error ('image')
                        <li>{{ $message }}</li>
                    @enderror
                </ul>             
            </div>         
            {{-- <input type="text"> --}}
        </div>
        
        <script>
            var quantity = document.getElementById('quantity')
            quantity.addEventListener('change', function(){
                if(quantity.value < 0){
                    quantity.value = 1
                }
                else{
                    quantity.value = parseInt(quantity.value)
                }
            })
        </script>
    </div>    
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