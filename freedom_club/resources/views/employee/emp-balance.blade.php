@extends('layouts.emp-app')
@section('content')
<style>
    th, td{
        text-align: center;
    }
</style>

    @php    
        use Illuminate\Support\Facades\DB;                               
        $dataCollections = [DB::table('balance_sheet')->orderBy('id', 'asc')->get()]; 
        //dd($dataCollections[3]::find(1));            
        $headers = ['Description', 'Debit Amount', 'Credit Amount', 'Total Balance', 'Created At', 'Updated At'];                      
    @endphp

    <div style="display: flex; align-items:center; justify-content:space-between; width:100%; height: 6%;">
        <x-emp-header title="BALANCE SHEET"></x-emp-header>                       
        {{-- <x-emp-button-link title="View Transfer History" link="{{ route('transfer.history') }}"></x-emp-button-link> --}}
        {{-- <x-emp-button-link title="Request Stock Transfer" :disabled='$disabled' toggle='true' target='requestStockTransfer' :dataCollection="$dataCollections"></x-emp-button-link>         --}}
    </div>    
    <div style="margin-top:20px; width:100%;">
        <x-emp-table :dataCollection="$dataCollections" :headers="$headers" title="Balance Sheet"/>  
    </div>

    
@endsection