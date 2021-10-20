@extends('layouts.app')
@section('content')
@php
    use App\Models\Product;
    use App\Models\Checkout;
@endphp
<style>
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
        <div style="width:50%">            
            <h1 style="color: white">PURCHASE HISTORY</h1>
            <div style="display: flex; width:100%; justify-content:space-between; align-items:center; color:white">
                <div style="width: 25%">Product #</div>
                <div style="width: 25%">Product Name</div>
                <div style="width: 25%">Invoice Number</div>
                <div style="width: 25%; padding-left:10px">Status</div>
                <div style="width: 25%">Quantity</div>
                <div style="width: 25%">Subtotal</div>
            </div>    
            <br>        
            @if (count($cart_items) == 0)
                <div style="color: white">NO ITEMS</div> 
            @else
            @foreach ($cart_items as $item)          
            <div style="display: flex; width:100%; justify-content:space-between; align-items:center; color:white">                
                @if (Checkout::find($item->checkout_id) !== null)          
                    <div style="width: 25%;">{{ Product::find($item->product_id)->product_number }}</div>
                    <div style="width: 25%;">{{ Product::find($item->product_id)->prod_name }}</div>
                    <div style="width: 25%; padding-left:20px">{{ Checkout::find($item->checkout_id)->invoice_number }}</div>
                    <div style="width: 25%;">
                        @if (Checkout::find($item->checkout_id)->status === null)
                            PENDING
                        @elseif(Checkout::find($item->checkout_id)->status == 0)
                            REJECTED
                        @else
                            CONFIRMED
                        @endif
                    </div>
                    <div style="width: 25%; padding-left:30px">{{ $item->quantity }}</div>
                    <div style="width: 25%; padding-left:20px">{{ $item->subtotal }}</div>               
                @endif
            </div>
            <br>
            @endforeach  
            @endif
            
        </div> 
        <div style="width:50%; display:flex; flex-direction:column; align-items:flex-end">
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
    
    <div style="position: absolute; bottom:10%; left:5%">
        {{ $cart_items->links() }}
    </div>
</div>
@endsection