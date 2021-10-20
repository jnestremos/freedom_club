@extends('layouts.app')
@section('content')
@php
    use ourcodeworld\NameThatColor\ColorInterpreter;
    $color = new ColorInterpreter();
    use App\Models\Cart;
    $subtotal = 0;
    foreach(Cart::where('user_id', auth()->user()->id)->where('pending', null)->get() as $item){        
        $subtotal = $subtotal + $item->subtotal;
    }
@endphp
    <div style="width: 100%; color:white; background-color:black; height:80vh;  display:flex; align-items:center; justify-content:center">
        <div style="height:90%; width:90%; display:flex; position: relative;">
            <div style="width: 50%">
                <h2 style="margin-bottom: 20px">Shipping Address</h2>
                <form action="{{ route('checkout.pending') }}" method="POST">
                    @csrf
                    <div style="width:100%; display:flex;">
                        <label for="" style="width:50%">First Name:</label>
                        <label for="" style="width:50%">Last Name:</label>
                    </div>                    
                    <div style="display: flex">                        
                        <h6 style="width: 50%">{{ auth()->user()->customer->cust_firstName }}</h6>
                        <h6 style="width: 50%">{{ auth()->user()->customer->cust_lastName }}</h6>
                    </div>
                    <br>
                    <label for="" style="width:50%">Address:</label>
                    <h6>{{ auth()->user()->customer->cust_address }}</h6>                                                                               
                    <br>
                    <label for="" style="width:50%">Phone Number:</label>
                    <h6>{{ auth()->user()->customer->cust_phoneNum }}</h6>
                    <br>
                    <label for="" style="width:50%">Email:</label>
                    <h6>{{ auth()->user()->customer->cust_email }}</h6>
                    <br>
                    <div id="slot">

                    </div>
                    <input type="number" hidden value="{{ $subtotal + 500 }}" name="subtotal">
                    <button type="button" class="btn btn-secondary">Continue Shopping</button>
                    <button type="submit" class="btn btn-success">Checkout</button>
                </form>
            </div>
            <div style="width: 40%; margin-left:70px; display:flex; flex-direction:column;">
                <div style="color:black; background-color:white; width:100%; height:15%; display:flex; align-items:center; padding-left:10px;"><h2>Order Summary</h2></div>
                <div style="display: flex; width:100%; justify-content:space-between;">
                    <div style="width:45%; border:2px solid white; padding:10px;">
                        <div style="width:100%;">
                            @foreach ($cart_items as $item)
                            <div style="display: flex; justify-content:space-between; width:100%;">
                                <h4>{{ $item->product->prod_name }}</h4>
                                <h5>{{ $item->subtotal }}</h5>
                            </div>
                            <div>Color: {{ $color->name($item->product->prod_color)['name'] }}</div> 
                            <div>Size: {{ $item->product->prod_size }}</div>
                            <div style="margin-bottom:20px">Qty: {{ $item->quantity}}</div>   
                            @endforeach                                   
                        </div>
                    </div>
                    <div style="width:50%; display:flex; flex-direction:column; padding:10px; border:2px solid white;">
                        <h5>Subtotal: Php {{ $subtotal }}</h5>
                        <h5>Shipping and Handling: Php 500</h5>     
                        <h5>Total: Php {{ $subtotal + 500 }}</h5>
                    </div>                    
                </div>
            </div>
            <div style="position: absolute; right: 20%; bottom:30%">
                {{ $cart_items->links() }}
            </div>
            <div style="position: absolute; right: 20%; bottom:50%">
                @if (session()->has('error'))
                    <ul>
                        <li>{{ session('error') }}</li>
                    </ul>
                @endif
            </div>
        </div>        
    </div> 
        <script>
            var slot = document.getElementById('slot')
            var cart_IDArray = [
                @foreach (Cart::where('user_id', auth()->user()->id)->where('pending', null)->get() as $item)
                '{{ $item->id }}',
                @endforeach
            ]
            cart_IDArray.forEach(function(id, i){
                var input = document.createElement('input')
                input.value = id
                input.name = 'id' + i
                input.hidden = true
                slot.appendChild(input)
            })
            
        </script>
    
@endsection