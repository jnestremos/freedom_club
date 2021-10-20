@extends('layouts.emp-app')
@section('content')
<style>
    th, td{
        text-align: center;
    }
</style>

    @php
        use App\Models\Shipment;                      
        use App\Models\SuppTransaction;                       
        use Illuminate\Support\Facades\DB;
        $dataCollections = [Shipment::all(), SuppTransaction::all(), SuppTransaction::class, DB::table('material_transaction')->get()]; 
        //dd($dataCollections[3]::find(1));            
        $headers = ['Receipt #', 'Date Received', 'Shipping Fee', 'Created At', 'Updated At'];                      
    @endphp
    @if (count($dataCollections) != 0)           
        @php                      
            $disabled = 'false';
        @endphp
    @else    
        @php                     
            $disabled = 'true';
        @endphp
    @endif

    <div style="display: flex; align-items:center; justify-content:space-between; width:100%; height: 6%;">
        <x-emp-header title="SHIPMENTS"></x-emp-header>                       
        <x-emp-button-link title="View Shipment History" link="{{ route('shipments.history') }}"></x-emp-button-link>
        {{-- <x-emp-button-link title="Add Material To Supplier" toggle='true' target='addItemToSupplier'></x-emp-button-link>         --}}
    </div>
    <ul>
        @if (session()->has('error'))
            <li>{{ session('error') }}</li>
        @endif
        @error('material_id')
            <li>{{ $message }}</li>
        @enderror
        @error('quantity')
            <li>{{ $message }}</li>
        @enderror
        @error('return')
            <li>{{ $message }}</li>
        @enderror
    </ul>  
    <div style="margin-top:20px; width:100%;">
        <x-emp-table :dataCollection="$dataCollections" :headers="$headers" title="Edit Shipment"/>  
    </div>

    <script>
        var slot = document.querySelectorAll('.slot')        
        slot.forEach(function(s){
            var radios = s.parentElement.previousElementSibling.previousElementSibling.querySelectorAll('.radio')            
            radios.forEach(function(radio){  
                radio.addEventListener('change', function(){                    
                   if(radio.value == 'false' && radio.id == 'exampleRadios2'){
                        s.parentElement.hidden = false
                        s.firstElementChild.nextElementSibling.disabled = false
                        s.nextElementSibling.lastElementChild.disabled = false
                        s.nextElementSibling.nextElementSibling.firstElementChild.nextElementSibling.firstElementChild.disabled = false
                        s.nextElementSibling.nextElementSibling.firstElementChild.nextElementSibling.nextElementSibling.firstElementChild.disabled = false
                   }
                   else if(radio.value == 'true' && radio.id == 'exampleRadios1'){
                        s.parentElement.hidden = true
                        s.firstElementChild.nextElementSibling.disabled = true
                        s.nextElementSibling.lastElementChild.disabled = true
                        s.nextElementSibling.lastElementChild.value = ''
                        s.nextElementSibling.nextElementSibling.firstElementChild.nextElementSibling.firstElementChild.disabled = true
                        s.nextElementSibling.nextElementSibling.firstElementChild.nextElementSibling.nextElementSibling.firstElementChild.disabled = true                    
                   }
               })             
           })
        })        
        
         
        

    </script>
@endsection