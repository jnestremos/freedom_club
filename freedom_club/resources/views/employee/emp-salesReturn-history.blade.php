@extends('layouts.emp-app')
@section('content')
<style>
    th, td{
        text-align: center;
    }
</style>
@php
    $headers = ['Receipt #', 'Account Name', 'Account Number', 'Payment Method', 'Product #', 'Quantity', 'Status', 'Created At', 'Updated At'];        
@endphp
<div style="display: flex; align-items:center; justify-content:space-between; width:100%; height: 6%;">
    <x-emp-header title="SALES RETURN HISTORY"></x-emp-header>                       
    <x-emp-button-link title="Back to Sales Returns" link="{{ route('orders.showReturn') }}"></x-emp-button-link>
    {{-- <x-emp-button-link title="Add Material To Supplier" toggle='true' target='addItemToSupplier'></x-emp-button-link>         --}}
</div>

<div style="margin-top:20px; width:100%;">
    <x-emp-table :dataCollection="$returns" :headers="$headers" title="Sales Returns History"/>  
</div>
@endsection