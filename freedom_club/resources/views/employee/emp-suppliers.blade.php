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
        

        var edit_credentials = document.getElementById('edit_credentials');
        var edit_credential_form = document.getElementById('edit_credential_form');
        var add_supplier = document.getElementById('add_supplier');
        var add_supplier_form = document.getElementById('add_supplier_form');
        var attach_item = document.getElementById('attach_item');
        var attach_item_form = document.getElementById('attach_item_form');
        var supplier_delete = document.getElementById('supplier_delete');
        var supplier_delete_form = document.getElementById('supplier_delete_form');
        var item_delete = document.getElementById('item_delete');
        var item_delete_form = document.getElementById('item_delete_form');
        var edit_item = document.getElementById('edit_item');
        var edit_item_form = document.getElementById('edit_item_form');
        edit_credentials.addEventListener('click', function(){
            edit_credentials.disabled = true
            edit_credential_form.submit()
        })
        add_supplier.addEventListener('click', function(){
            add_supplier.disabled = true
            add_supplier_form.submit()
        })
        attach_item.addEventListener('click', function(){
            attach_item.disabled = true
            attach_item_form.submit()
        })
        supplier_delete.addEventListener('click', function(){
            supplier_delete.disabled = true
            supplier_delete_form.submit()
        })
        item_delete.addEventListener('click', function(){
            item_delete.disabled = true
            item_delete_form.submit()
        })
        edit_item.addEventListener('click', function(){
            edit_item.disabled = true
            edit_item_form.submit()
        })


    </script>
    
@endsection