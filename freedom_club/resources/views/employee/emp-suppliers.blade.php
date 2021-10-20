@extends('layouts.emp-app')
@section('content')
<style>
    th, td{
        text-align: center;
    }
</style>

    @php
        use App\Models\Supplier;        
        $supplierData = Supplier::all();                      
        $headers = ['Supplier Name', 'Contact Number', 'Email', 'Created At', 'Updated At']              
    @endphp
    @if (count($supplierData) != 0)
        @php
            $disabled = 'false';
        @endphp
    @else
        @php
            $disabled = 'true';
        @endphp
    @endif
    <div style="display: flex; align-items:center; justify-content:space-between; width:100%; height: 6%;">
        <x-emp-header title="SUPPLIER PROFILES"></x-emp-header>               
        <x-emp-button-link link="{{ route('materials.index') }}" title="View All Items"/>
        <x-emp-button-link title="Add Supplier" toggle='true' target='addSupplier' />
        <x-emp-button-link title="Add Material To Supplier" toggle='true' target='addItemToSupplier' :dataCollection="$supplierData" :disabled="$disabled" />        
    </div>
    <ul>
        @if (session()->has('error'))
            <li>{{ session('error') }}</li>
        @endif
        @error('supplier_name')
            <li class="">{{ $message }}</li>
        @enderror
        @error('supplier_contactNumber')
            <li class="">{{ $message }}</li>
        @enderror
        @error('supplier_email')
            <li class="">{{ $message }}</li>
        @enderror
        @error('supplier_id')
            <li class="">{{ $message }}</li>
        @enderror
        @error('material_type')
            <li class="">{{ $message }}</li>
        @enderror
        @error('material_size')
            <li class="">{{ $message }}</li>
        @enderror
        @error('material_color')
            <li class="">{{ $message }}</li>
        @enderror
        @error('material_minimum_quantity')
            <li class="">{{ $message }}</li>
        @enderror
        @error('material_price')
            <li class="">{{ $message }}</li>
        @enderror
    </ul>

    <div style="margin-top:20px; width:100%;">
        <x-emp-table :dataCollection="$supplierData" :headers="$headers" title="Edit Supplier"/>  
    </div>
    
   
    <script>
        var material_size = document.getElementById('material_size');
        var material_options = document.querySelectorAll('#material_size option')
        var supplier_id = document.getElementById('supplier_id');
        var material_type = document.getElementById('material_type');        
        var material_color = document.getElementById('material_color');
        var material_price = document.getElementById('material_price');
        if(supplier_id.value == ''){
            material_size.disabled = true 
            //supplier_id.disabled = true
            material_type.disabled = true
            material_color.disabled = true
            material_price.disabled = true
        }        
        if(material_type.value == ''){
            material_size.disabled = true 
            //supplier_id.disabled = true
            //material_type.disabled = true
            material_color.disabled = true
            material_price.disabled = true
        }        
        supplier_id.addEventListener('change', function(){            
            //supplier_id.disabled = false
            material_type.disabled = false            
            material_size.disabled = true   
            material_color.disabled = true            
            material_price.disabled = true            
            material_type.value = ''
            material_size.value = 'None'
            material_color.value = '#000000'
            material_price.value = null
        })

        material_type.addEventListener('change', function(){
            material_size.disabled = false                  
            material_size.value = 'None'                  
            material_color.disabled = false                                                
            material_price.disabled = false                                                            
            material_options.forEach(function(material_option){
                material_option.hidden = true
                if(material_type.value == 'Hat'){
                    if( material_option.value == 16 || material_option.value == 18 || material_option.value == 20 || material_option.value == 22){
                        material_option.hidden = false
                        if(material_option.value == "None"){
                            material_option.disabled = false
                        }
                    } 
                }
                else{
                    if(material_type.value == 'Bag'){
                        if( material_option.value == 16 || material_option.value == 18 || material_option.value == 20 || material_option.value == 22){
                            material_option.hidden = false
                            if(material_option.value == "None"){
                                material_option.disabled = false
                            }
                        } 
                    }
                    else{
                        if(material_type.value == 'Shirt'){
                            material_option.hidden = false
                        }
                    }

                }               
            })            
        })
        material_size.addEventListener('change', function(){
            material_price.value = ''
        })
        material_price.addEventListener('change', function(){            
            material_price.value = parseFloat(material_price.value).toFixed(2)
        })
        
    </script>
    
@endsection