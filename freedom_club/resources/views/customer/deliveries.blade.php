@extends('layouts.app')
@section('content')
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
<div style="width: 100%; height:90vh; background-color: black; display:flex; justify-content:center; align-items:center">   
    <div style="width:90%; height:90%; display:flex; padding-top:30px">
        <div style="width:50%">            
            <h1 style="color: white">DELIVERIES</h1>
            <div style="display: flex; width:100%; justify-content:space-between; align-items:center; color:white">
                <div style="width: 20%">Invoice Number</div>
                <div style="width: 20%">Receipt Number</div>
                <div style="width: 20%">Shipping Service</div>
                <div style="width: 20%;">Tracking Number</div>
                <div style="width: 20%">Date Confirmed</div>
                <div style="width: 20%">Total</div>
            </div>    
            <br>        
            @if (count($cart_items) == 0)
                <div style="color: white">NO DELIVERIES</div> 
            @else
            @foreach ($cart_items as $item)          
            <div style="display: flex; width:100%; justify-content:space-between; align-items:center; color:white">                                     
                <div style="width: 20%;">{{ $item->invoice_number }}</div>
                <div style="width: 20%;">{{ $item->receipt_number }}</div>
                <div style="width: 20%;">{{ $item->shipping_service }}</div>
                <div style="width: 20%;">{{ $item->tracking_number }}</div>
                <div style="width: 20%;">{{ $item->updated_at }}</div>
                <div style="width: 20%;">{{ $item->total }}</div>                                                   
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