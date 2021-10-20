@extends('layouts.emp-app')
@section('content')
<style>
    th, td{
        text-align: center;
    }
</style>

    @php                            
        use Illuminate\Support\Facades\DB;                          
        $dataCollections = [DB::table('sales')->where('deleted_at', null)->get()];            
        $headers = ['Sales Category', 'Total', 'Created At', 'Updated At'];                  
    @endphp   

    <div style="display: flex; align-items:center; justify-content:space-between; width:100%; height: 6%;">
        <x-emp-header title="SALES"></x-emp-header>        
        <x-emp-button-link title="View Sales History" link="{{ route('sales.history') }}"></x-emp-button-link>
        {{-- <x-emp-button-link title="Add Transaction" toggle='true' target='addTransaction' :disabled='$disabled' :dataCollection="$dataCollections" ></x-emp-button-link>         --}}
    </div>
    <ul>
        @if (session()->has('error'))
            <li>{{ session('error') }}</li>
        @endif
        @error('receipt_number')
            <li class="">{{ $message }}</li>
        @enderror
        @error('supplier_id')
            <li class="">{{ $message }}</li>
        @enderror
        @error('material_id')
            <li class="">{{ $message }}</li>
        @enderror
        @error('material_quantity')
            <li class="">{{ $message }}</li>
        @enderror
        @error('shipping_fee')
            <li class="">{{ $message }}</li>
        @enderror
        @error('datePaid')
            <li class="">{{ $message }}</li>
        @enderror
    </ul>  
    <div style="margin-top:20px; width:100%;">
        <x-emp-table :dataCollection="$dataCollections" :headers="$headers" title="View Sales"/>  
    </div>
@endsection