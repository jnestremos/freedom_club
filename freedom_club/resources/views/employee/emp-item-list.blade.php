@extends('layouts.emp-app')
@section('content')
<style>
    th, td{
        text-align: center;
    }
</style>

    @php
        use App\Models\Material;        
        $materialData = Material::all();                      
        $headers = ['Material Number','Supplier ID', 'Material Type', 'Material Size', 'Material Color', 'Price', 'Created At', 'Updated At']              
    @endphp

    <div style="display: flex; align-items:center; justify-content:space-between; width:30%; height: 6%;">
        <x-emp-header title="ITEM LIST"></x-emp-header>        
        <x-emp-button-link link="{{ route('dashboard.suppliers') }}" title="View All Suppliers"></x-emp-button-link>                
    </div>
    <ul>
        @if (session()->has('error'))
            <li>{{ session('error') }}</li>
        @endif
        @error('material_type')
            <li class="">{{ $message }}</li>
        @enderror
        @error('material_size')
            <li class="">{{ $message }}</li>
        @enderror
        @error('material_color')
            <li class="">{{ $message }}</li>
        @enderror
        @error('material_price')
            <li class="">{{ $message }}</li>
        @enderror
    </ul>

    <div style="margin-top:20px; width:100%;">
        <x-emp-table :dataCollection="$materialData" :headers="$headers" title="Item List"/>  
    </div>
    
   
    <script>
        var modal_item_lists = document.querySelectorAll('.modal_item_list')
        var material_IDArray = [
            @foreach($materialData as $material)
            '{{ $material->id }}',
            @endforeach
        ]
        var material_sizeArray = [
            @foreach($materialData as $material)
            '{{ $material->material_size }}',
            @endforeach
        ]
        //console.log(material_IDArray)
        modal_item_lists.forEach(function(modal_item_list){
            modal_item_list.addEventListener('show.bs.modal', function(){                                           
                var material_options = modal_item_list.firstElementChild.firstElementChild.lastElementChild.previousElementSibling.firstElementChild.nextElementSibling.firstElementChild.nextElementSibling.lastElementChild.children                                        
                var material_type = modal_item_list.firstElementChild.firstElementChild.lastElementChild.previousElementSibling.firstElementChild.nextElementSibling.firstElementChild.lastElementChild.value
                var material_id = modal_item_list.firstElementChild.firstElementChild.lastElementChild.previousElementSibling.firstElementChild.value
                console.log(material_id)
                for (var i = 0; i < material_options.length; i++){
                    //console.log(material_options[i].parentElement.value)
                    material_options[i].hidden = true                                       
                    if(material_type == 'Hat'){
                        if(material_options[i].value == "None"  || material_options[i].value == 16 || material_options[i].value == 18 || material_options[i].value == 20 || material_options[i].value == 22){
                            material_options[i].hidden = false
                            if(material_options[i].value == "None"){
                                material_options[i].disabled = false
                            }   
                        } 
                    }
                    else{
                        if(material_type == 'Bag'){
                            if(material_options[i].value == "None" || material_options[i].value == 16 || material_options[i].value == 18 || material_options[i].value == 20 || material_options[i].value == 22){
                                material_options[i].hidden = false
                                if(material_options[i].value == "None"){
                                    material_options[i].disabled = false
                                }
                            } 
                        }
                        else{
                            if(material_type == 'Shirt'){
                                    if(material_options[i].value != 'None'){
                                        material_options[i].hidden = false
                                } 
                            }
                        }
                    }                    
                }                                                      
            })
            //var material_type = modal_item_list.getElementById('material_type1')            
        })
    </script>
    
@endsection