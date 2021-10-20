@extends('layouts.emp-app')
@section('content')
<style>
    th, td{
        text-align: center;
    }
</style>
    @php                                          
        use Illuminate\Support\Facades\DB;    
        $error = [];               
        $dataCollections = [DB::table('sales_returns')->where('deleted_at', null)->get()]; 
        //dd($dataCollections[3]::find(1));            
        $headers = ['Receipt #', 'Account Name', 'Account Number', 'Payment Method', 'Product #', 'Quantity', 'Status', 'Created At', 'Updated At'];        
    @endphp
    <div style="display: flex; align-items:center; justify-content:space-between; width:100%; height: 6%;">
        <x-emp-header title="SALES RETURNS"></x-emp-header>    
        <x-emp-button-link title="Back to Sales" link="{{ route('dashboard.orders') }}"></x-emp-button-link>                 
        <x-emp-button-link title="View Sales Return History" link="{{ route('salesReturn.history') }}"></x-emp-button-link>        
        {{-- <x-emp-button-link title="Add New Product" toggle='true' target='addProduct' :dataCollection="$dataCollections"></x-emp-button-link>         --}}
        {{-- <x-emp-button-link title="View Sales Returns" :disabled='$disabled' link="{{ route('orders.showReturn') }}" :dataCollection="$dataCollections"></x-emp-button-link> --}}
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
        <x-emp-table :dataCollection="$dataCollections" :headers="$headers" title="Sales Returns"/>  
    </div>
@endsection