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
<div style="width: 100%; height:90vh; background-color: black; display:flex; justify-content:center; align-items:center; font-family: Bahnschrift">   
    <div style="width:90%; height:90%; display:flex; padding-top:30px">
        <div style="width:70%">            
            <h1 style="color: white">DELIVERIES</h1>
            <div style="display: flex; width:100%; justify-content:space-between; align-items:center; color:white">
                <div style="width: 14%">Invoice Number</div>
                <div style="width: 14%">Receipt Number</div>
                <div style="width: 14%">Shipping Service</div>
                <div style="width: 14%;">Tracking Number</div>
                <div style="width: 14%">Date Confirmed</div>
                <div style="width: 14%">Total w/Shipping Fee</div>
                <div style="width: 14%">Date Delivered</div>
            </div>    
            <br>        
            @if (count($cart_items) == 0)
                <div style="color: white">NO DELIVERIES</div> 
            @else
            @foreach ($cart_items as $item)          
            <div style="display: flex; width:100%; justify-content:space-between; align-items:center; color:white">                                     
                <div style="width: 14%;">{{ $item->invoice_number }}</div>
                <div style="width: 14%;">{{ $item->receipt_number }}</div>
                <div style="width: 14%;">{{ $item->shipping_service }}</div>
                <div style="width: 14%;">{{ $item->tracking_number }}</div>
                @if ($item->updated_at == $item->created_at)
                <div style="width: 14%;">PENDING</div>
                @else
                <div style="width: 14%;">{{ $item->updated_at }}</div>
                @endif                
                <div style="width: 14%;">{{ $item->total }}</div>  
                @if ($item->dateReceived === null)
                    <form action="{{ url('/deliveries/'.$item->id) }}" method="POST" style="width: 14%">
                        @csrf
                        @method("PUT")
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form> 
                @else
                <div style="width: 14%;">{{ $item->dateReceived }}</div>  
                @endif                                                                              
            </div>            
            <br>
            @endforeach  
            @endif            
        </div> 
        <div style="width:30%; display:flex; flex-direction:column; align-items:flex-end">
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
                Refund
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