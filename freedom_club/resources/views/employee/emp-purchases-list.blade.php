@extends('layouts.emp-app')
@section('content')
<style>
    th, td{
        text-align: center;
    }
</style>

    @php
        use Illuminate\Support\Facades\DB;                       
        use App\Models\Supplier;                
        use App\Models\Material;                       
        use App\Models\SuppTransaction;                       
        $dataCollections = [DB::table('material_transaction')->select('*')->where('deleted_at', null)->get(), Supplier::all(), Material::all(), SuppTransaction::class]; 
        //dd($dataCollections[3]::find(1));            
        $headers = ['Transaction ID', 'Material Number', 'Material Type', 'Material Size', 'Material Color', 'Material Qty', 'Material Price', 'Created At', 'Updated At']                      
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
        <x-emp-header title="SUPPLIER PURCHASE ITEM LIST"></x-emp-header>                       
        <x-emp-button-link title="View All Transactions" link="{{ route('dashboard.purchases') }}"></x-emp-button-link>
        {{-- <x-emp-button-link title="Add Material To Supplier" toggle='true' target='addItemToSupplier'></x-emp-button-link>         --}}
    </div>
    <ul>
        @if (session()->has('error'))
            <li>{{ session('error') }}</li>
        @endif
    </ul>  
    <div style="margin-top:20px; width:100%;">
        <x-emp-table :dataCollection="$dataCollections" :headers="$headers" title="Edit Item List"/>  
    </div>

    <script>
        var material_id1 = document.getElementById('material_id99')
        var supplier_id1 = document.getElementById('supplier_id99')
        var supplierIDArray1 = [
            @foreach($dataCollections[1] as $supplier)
                "{{ $supplier->id }}",
            @endforeach
            ];
    
        var materialIDArray1 = [
            @foreach($dataCollections[2] as $material)
                "{{ $material->id }}",
            @endforeach
            ];
        var materialPriceArray1 = [
            @foreach($dataCollections[2] as $material)
                "{{ $material->material_price }}",
            @endforeach
            ];
            
        var supplierMaterialIDArray1 = [
            @foreach($dataCollections[2] as $material)
                "{{ $material->supplier_id }}",
            @endforeach
            ];
    
       
            

        for(var ii = 0; ii < supplierMaterialIDArray1.length; ii++){
            if(supplierMaterialIDArray1[ii] == supplier_id1.value){
                var option1 = document.createElement('option')
                option1.value = materialIDArray1[ii]
                var optionTextNode1 = document.createTextNode(materialIDArray1[ii])
                option1.appendChild(optionTextNode1)
                material_id1.appendChild(option1)
            }
        }
        var material_qty1 = document.getElementById('material_qty99')
        var totalCost1 = document.getElementById('totalCost99')
       
        //material_qty1.value = 0 
        
        material_id1.addEventListener('change', function(){
            material_qty1.value = 0
            totalCost1.value = 0
            
        })
    
        material_qty1.addEventListener('change', function(){
            totalCost1.parentElement.style.display = 'flex'
            totalCost1.value = 0
            if(material_qty1.value == '' || material_qty1.value == 0 || material_qty1.value == null || material_qty1.value < 0){
                material_qty1.value = 0            
            }
            else{
                for(var i = 0; i < materialIDArray1.length; i++){
                    if(materialIDArray1[i] == material_id1.value){
                        totalCost1.value = parseInt(totalCost1.value) + (materialPriceArray1[i] * parseInt(material_qty1.value))                
                    }
                }
            }
        })

    </script>
    
@endsection