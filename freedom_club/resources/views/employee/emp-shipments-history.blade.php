@extends('layouts.emp-app')
@section('content')
<style>
    th, td{
        text-align: center;
    }
</style>
@php
    $headers = ['Receipt #', 'Date Received', 'Shipping Fee', 'Created At', 'Updated At'];  
@endphp
<div style="display: flex; align-items:center; justify-content:space-between; width:100%; height: 6%;">
    <x-emp-header title="SHIPMENT HISTORY"></x-emp-header>                       
    <x-emp-button-link title="Back to Shipments" link="{{ route('dashboard.shipments') }}"></x-emp-button-link>
    {{-- <x-emp-button-link title="Add Material To Supplier" toggle='true' target='addItemToSupplier'></x-emp-button-link>         --}}
</div>

<div style="margin-top:20px; width:100%;">
    <x-emp-table :dataCollection="$shipments" :headers="$headers" title="Shipment History"/>  
</div>
@endsection