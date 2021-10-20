@extends('layouts.emp-app')
@section('content')
<style>
    th, td{
        text-align: center;
    }
</style>
@php
    $headers = ['Invoice #', 'Receipt #', 'Account Name', 'Account Number', 'Payment Method', 'Shipping Service', 'Tracking Number', 'Status', 'Total', 'Date of Checkout', 'Updated At'];
@endphp
<div style="display: flex; align-items:center; justify-content:space-between; width:100%; height: 6%;">
    <x-emp-header title="ORDERS HISTORY"></x-emp-header>                       
    <x-emp-button-link title="Back to Orders" link="{{ route('dashboard.orders') }}"></x-emp-button-link>
    {{-- <x-emp-button-link title="Add Material To Supplier" toggle='true' target='addItemToSupplier'></x-emp-button-link>         --}}
</div>

<div style="margin-top:20px; width:100%;">
    <x-emp-table :dataCollection="$orders" :headers="$headers" title="Orders History"/>  
</div>
@endsection