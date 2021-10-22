@extends('layouts.emp-app')
@section('content')
<style>
    th, td{
        text-align: center;
    }
</style>

    @php
        use App\Models\Product;                      
        use App\Models\Stock;
        use Illuminate\Support\Facades\DB;                       
        $dataCollections = [Stock::all(), Product::all(), Stock::class];
        $arr = [];
        foreach($dataCollections[0] as $stock){
            array_push($arr, $stock->stock_size);
        }
        //dd($arr);
        $headers = ['Stock #', 'Material #' ,'Supplier Name', 'Stock Type', 'Stock Size', 'Stock Color', 'Stock Qty', 'Stock Price', 'Created At', 'Updated At'];    
    @endphp
    @if (count($dataCollections[0]) != 0 && count($dataCollections[1]) != 0)           
        @php                      
            $disabled = 'false';
        @endphp
    @else    
        @php                     
            $disabled = 'true';
        @endphp
    @endif

    <div style="display: flex; align-items:center; justify-content:space-between; width:35%; height: 6%;">
        <x-emp-header title="STOCK INVENTORY"></x-emp-header>                       
        {{-- <x-emp-button-link title="View All Transactions" link="{{ route('dashboard.purchases') }}"></x-emp-button-link> --}}
        <div id="stock_transfer">
            <x-emp-button-link title="Request Stock Transfer" :disabled='$disabled' toggle='true' target='requestStockTransfer' :dataCollection="$dataCollections"></x-emp-button-link>        
        </div>        
    </div>
    <ul>
        @if (session()->has('error'))
            <li>{{ session('error') }}</li>
        @endif
        @error('stock_id')
            <li>{{ $message }}</li>
        @enderror
        @error('product_id')
            <li>{{ $message }}</li>
        @enderror
        @error('quantity_used')
            <li>{{ $message }}</li>
        @enderror
    </ul>  
    <div style="margin-top:20px; width:100%;">
        <x-emp-table :dataCollection="$dataCollections" :headers="$headers" title="View Material Details"/>  
    </div>

    <script>
        var stock_id = document.getElementById('stock_id')
        var prod_id = document.getElementById('product_id')
        var stock_type = document.getElementById('stock_type')
        var stock_size = document.getElementById('stock_size')
        var stock_color = document.getElementById('stock_color')
        var quantity_used = document.getElementById('quantity_used')
        
        var stock_IDArray = [
            @foreach($dataCollections[0] as $stock)
                '{{ $stock->id }}',
            @endforeach
        ]                
        var stock_NumberArray = [
            @foreach($dataCollections[0] as $stock)
                '{{ $stock->stock_number }}',
            @endforeach
        ]                
        var stock_qtyArray = [
            @foreach($dataCollections[0] as $stock)
                '{{ $stock->stock_qty }}',
            @endforeach
        ]
              
        var stock_typeArray = [
            @foreach($dataCollections[0] as $stock)
                '{{ $stock->stock_type }}',
            @endforeach
        ]
        var stock_typeSet = new Set()
        for(var i = 0; i < stock_typeArray.length; i++){
            stock_typeSet.add(stock_typeArray[i])
        }  

        var stock_sizeArray = [
            @foreach($dataCollections[0] as $stock)
                '{{ $stock->stock_size }}',
            @endforeach
        ]
        var stock_colorArray = [
            @foreach($dataCollections[0] as $stock)
                '{{ $stock->stock_color }}',
            @endforeach
        ]

        var prod_IDArray = [
            @foreach($dataCollections[1] as $product)
                '{{ $product->id }}',
            @endforeach
        ]
        var prod_NameArray = [
            @foreach($dataCollections[1] as $product)
                '{{ $product->prod_name }}',
            @endforeach
        ]
        var prod_NumberArray = [
            @foreach($dataCollections[1] as $product)
                '{{ $product->product_number }}',
            @endforeach
        ]
        var prod_colorArray = [
            @foreach($dataCollections[1] as $product)
                '{{ $product->prod_color }}',
            @endforeach
        ]
        var prod_typeArray = [
            @foreach($dataCollections[1] as $product)
                '{{ $product->prod_type }}',
            @endforeach
        ]
        var prod_sizeArray = [
            @foreach($dataCollections[1] as $product)
                '{{ $product->prod_size }}',
            @endforeach
        ]        
        var prod_typeSet = new Set() 
        for(var i = 0; i < prod_typeArray.length; i++){
            prod_typeSet.add(prod_typeArray[i])
        }                   
        //console.log(prod_typeSet)
        
        prod_typeSet.forEach(function(prod_type){                
                for(var i = 0; i < stock_IDArray.length; i++){
                    //console.log(stock_typeArray[i] == prod_type)
                    if(stock_typeArray[i] == prod_type){
                        for(var ii = 0; ii < prod_IDArray.length; ii++){
                            //console.log(stock_colorArray[i] == prod_colorArray[ii])
                            if(stock_sizeArray[i] == prod_sizeArray[ii] && stock_qtyArray[i] > 0 && stock_colorArray[i] == prod_colorArray[ii]){
                                var option = document.createElement('option');
                                var optionTextNode = document.createTextNode(stock_NumberArray[i])
                                option.value = stock_IDArray[i]
                                option.appendChild(optionTextNode)
                                stock_id.appendChild(option)
                                break
                            }
                        }                        
                    }
                }
            })
        var checker = 0
        //console.log(stock_typeArray)   
        var stock_transfer = document.getElementById('stock_transfer')   
        //console.log(stock_transfer)     
        window.addEventListener('load', function(){
            if(stock_id.childElementCount > 1){
                stock_transfer.firstElementChild.disabled = false
                stock_transfer.firstElementChild.style.opacity = 1
            }
            else{
                stock_transfer.firstElementChild.disabled = true
                stock_transfer.firstElementChild.style.opacity = 0.5
            }
        })
        stock_id.addEventListener('change', function(){            
            
            prod_id.disabled = false
            prod_id.value = "None"
            quantity_used.disabled = true
            quantity_used.value = ""
            
            var options1 = stock_id.querySelectorAll('option')
            
            
            var options2 = prod_id.querySelectorAll('option') 
            options2.forEach(function(option, i){
                //console.log(i)
                if(option.value != 'None'){
                    option.remove()
                }
                //console.log(options1.value)
            })
                
            
            if(checker == 0){
            //     prod_typeSet.forEach(function(prod_type){                
            //     for(var i = 0; i < stock_IDArray.length; i++){
            //         //console.log(stock_typeArray[i] == prod_type)
            //         if(stock_typeArray[i] == prod_type){
            //             for(var ii = 0; ii < prod_IDArray.length; ii++){
            //                 if(stock_sizeArray[i] == prod_sizeArray[ii]){
            //                     var option = document.createElement('option');
            //                     var optionTextNode = document.createTextNode(stock_IDArray[i])
            //                     option.value = stock_IDArray[i]
            //                     option.appendChild(optionTextNode)
            //                     stock_id.appendChild(option)
            //                     break
            //                 }
            //             }                        
            //         }
            //     }
            // })
            checker = 1
        }
            
                                                
           
            for(var i = 0; i < stock_IDArray.length; i++){
                if(stock_IDArray[i] == stock_id.value){
                    stock_type.value = stock_typeArray[i]
                    stock_size.value = stock_sizeArray[i]
                    stock_color.value = stock_colorArray[i]
                    break
                }
            }
            // for(var i = 0; i < prod_IDArray.length; i++){
            //     if(prod_typeArray[i] == stock_type.value){
            //         for(var ii = 0; ii < stock_IDArray.length; ii++){
            //             //stock_sizeArray[i] == prod_sizeArray[ii] && stock_qtyArray[i] > 0 && stock_colorArray[i] == prod_colorArray[ii]
                        
            //             if(stock_sizeArray[ii] == prod_sizeArray[i] && stock_qtyArray[ii] > 0 && stock_colorArray[ii] == prod_colorArray[i]){
            //                 var option = document.createElement('option');
            //                 var optionTextNode = document.createTextNode(prod_IDArray[i])
            //                 option.value = prod_IDArray[i]
            //                 option.appendChild(optionTextNode)
            //                 prod_id.appendChild(option)
            //                 break
            //             }
            //         }
            //     }
            // }
            for(var i = 0; i < prod_IDArray.length; i++){
                if(prod_typeArray[i] == stock_type.value && prod_colorArray[i] == stock_color.value && prod_sizeArray[i] == stock_size.value){
                    var option = document.createElement('option');
                    var optionTextNode = document.createTextNode(prod_NameArray[i])
                    option.value = prod_IDArray[i]
                    option.appendChild(optionTextNode)
                    prod_id.appendChild(option)                    
                }
            }            
        })
        prod_id.addEventListener('change', function(){
            quantity_used.disabled = false
            quantity_used.value = 1
        })
        var max = 0
        quantity_used.addEventListener('change', function(){
            for(var i = 0; i < stock_IDArray.length; i++){
                if(stock_IDArray[i] == stock_id.value){
                    max = stock_qtyArray[i]
                }
            }
            if(quantity_used.value <= 0 || quantity_used.value == '' || quantity_used.value == null || quantity_used.value > max){
                quantity_used.value = 1
            }            
        })

       
    </script>
@endsection