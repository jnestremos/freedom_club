@extends('layouts.emp-app')
@section('content')
<style>
    th, td{
        text-align: center;
    }
</style>
    @php                             
        use App\Models\Checkout;      
        use App\Models\Product;      
        use App\Models\Cart;      
        use Illuminate\Support\Facades\DB;    
        $error = [];               
        $dataCollections = [Checkout::all(), Product::all(), Cart::all()]; 
        //dd($dataCollections[3]::find(1));            
        $headers = ['Invoice #', 'Receipt #', 'Account Name', 'Account Number', 'Payment Method', 'Shipping Service', 'Tracking Number', 'Status', 'Total', 'Date of Checkout', 'Date Delivered', 'Updated At'];                                    
        if(count(DB::table('sales_returns')->get()) == 0){
            $disabled = 'true';
        }
        else{
            $disabled = 'false';
        }
    @endphp
    
   
    <div style="display: flex; align-items:center; justify-content:space-between; width:100%; height: 6%;">
        <x-emp-header title="ORDERS"></x-emp-header>                               
        {{-- <x-emp-button-link title="Add New Product" toggle='true' target='addProduct' :dataCollection="$dataCollections"></x-emp-button-link>         --}}
        <x-emp-button-link title="View Sales Returns" :disabled='$disabled' link="{{ route('orders.showReturn') }}" :dataCollection="$dataCollections"></x-emp-button-link>        
        <x-emp-button-link title="View Order History" link="{{ route('orders.history') }}"></x-emp-button-link>
    </div>
    <ul style="margin-top: 20px">
        @if (session()->has('error'))
            <li>{{ session('error') }}</li>
        @endif
        @error('shipping_service')
            <li>{{ $message }}</li>
        @enderror
        @error('tracking_number')
        <li>{{ $message }}</li>
        @enderror
    </ul>  
    <div style="margin-top:10px; width:100%;" id="asd">
        <x-emp-table :dataCollection="$dataCollections" :headers="$headers" title="Update Order"/>  
    </div>
    <script>        
        var toggle_ship = document.querySelectorAll('.toggle_ship')
        toggle_ship.forEach(function(ship){
           var radios = ship.nextElementSibling.nextElementSibling.querySelectorAll('.radio')
           radios.forEach(function(radio){    
               console.log(radio)           
               radio.addEventListener('change', function(){                   
                    if(radio.value == 'true' && radio.id == 'status1'){
                        ship.hidden = false
                        ship.firstElementChild.firstElementChild.nextElementSibling.disabled = false
                        ship.firstElementChild.nextElementSibling.lastElementChild.disabled = false
                        ship.firstElementChild.nextElementSibling.lastElementChild.value = ''
                    }
                    else if(radio.value == 'false' && radio.id == 'status2'){
                        ship.hidden = true
                        ship.firstElementChild.firstElementChild.nextElementSibling.disabled = true
                        ship.firstElementChild.nextElementSibling.lastElementChild.disabled = true                
                    }
                })               
           })
        })
        // var shipping_service = document.getElementById('shipping_service')
        // var tracking_number = document.getElementById('tracking_number')
        // radios.forEach(function(r){
        //   r.addEventListener('change', function(){
        //       if(r.value == 'true'){
        //           toggle_ship.hidden = false
        //           tracking_number.value = ''
        //           shipping_service.disabled = false
        //           tracking_number.disabled = false
        //       }
        //       else{
        //           toggle_ship.hidden = true
        //           shipping_service.disabled = true
        //           tracking_number.disabled = true
        //       }
        //   })
        // })
    </script>
    
@endsection