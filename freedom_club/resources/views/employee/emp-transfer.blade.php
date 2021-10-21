@extends('layouts.emp-app')
@section('content')
<style>
    th, td{
        text-align: center;
    }
</style>

    @php
        use App\Models\Product;                      
        use App\Models\Stock;                       
        use App\Models\StockTransfer;
        use Illuminate\Support\Facades\DB;                               
        $dataCollections = [Stock::all(), Product::all(), DB::table('stock_transfers')->select('*')->get()]; 
        //dd($dataCollections[3]::find(1));            
        $headers = ['Transfer #', 'Stock #', 'Product #', 'Quantity', 'Confirmed', 'Created At', 'Updated At'];                      
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
        <x-emp-header title="TRANSFERS"></x-emp-header>                       
        <x-emp-button-link title="View Transfer History" link="{{ route('transfer.history') }}"></x-emp-button-link>
        {{-- <x-emp-button-link title="Request Stock Transfer" :disabled='$disabled' toggle='true' target='requestStockTransfer' :dataCollection="$dataCollections"></x-emp-button-link>         --}}
    </div>
    <ul>
        @if (session()->has('error'))
            <li>{{ session('error') }}</li>
        @endif
        @error('stock_id')
            {{ $message }}
        @enderror
        @error('quantity_used')
            {{ $message }}
        @enderror
    </ul>  
    <div style="margin-top:20px; width:100%;">
        <x-emp-table :dataCollection="$dataCollections" :headers="$headers" title="View Transfer Request"/>  
    </div>

    
@endsection