@extends('layouts.emp-app')
@section('content')
<style>
    th, td{
        text-align: center;
    }
</style>
@php
    $headers = ['Employee ID', 'First Name', 'Last Name', 'Email', 'Gender', 'Birthdate', 'Created At', 'Updated At'];
@endphp
<div style="display: flex; align-items:center; justify-content:space-between; width:100%; height: 6%;">
    <x-emp-header title="REMOVED EMPLOYEES"></x-emp-header>                       
    <x-emp-button-link title="Back to Employees" link="{{ route('dashboard.employees') }}"></x-emp-button-link>
    {{-- <x-emp-button-link title="Add Material To Supplier" toggle='true' target='addItemToSupplier'></x-emp-button-link>         --}}
</div>

<div style="margin-top:20px; width:100%;">
    <x-emp-table :dataCollection="$employees" :headers="$headers" title="Removed Employees"/>  
</div>
<script>
    var dataID = document.getElementById('dataID')
    var restore_form = document.getElementById('restore_form')    
    dataID.addEventListener('click', function(){
        restore_form.submit()
    })
</script>
@endsection