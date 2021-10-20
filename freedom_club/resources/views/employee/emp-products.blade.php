@extends('layouts.emp-app')
@section('content')
<style>
    th, td{
        text-align: center;
    }
</style>
    @php                             
        use App\Models\Product;      
        use Illuminate\Support\Facades\DB;    
        $error = [];               
        $dataCollections = [Product::all(), Product::class, DB::table('prod_name_color')->get()]; 
        //dd($dataCollections[3]::find(1));            
        $headers = ['Product #', 'Product Name', 'Product Type', 'Product Size', 'Product Color', 'Product Status', 'Product Qty', 'Product Price', 'Created At', 'Updated At'];                      
        foreach(DB::table('prod_name_color')->get() as $item){
            $check = false;
            foreach(DB::table('product_images')->where('prod_name_color_id', $item->id)->get() as $image){
                if($image->image_main == false){
                    $check = false;
                }
                else{
                    $check = true;
                    break;
                }
            }
            if(!$check){                
                array_push($error, $item->prod_name . ' does not have a default picture! Click VIEW PRODUCT IMAGES to set default picture!');
            }
        }        
    @endphp
    @if (count($dataCollections[0]) > 0 || count($dataCollections[2]) > 0)           
        @php                      
            $disabled = 'false';
        @endphp
    @else    
        @php                     
            $disabled = 'true';
        @endphp
    @endif
    @php
        foreach(DB::table('prod_name_color')->get() as $product_name_color){
            if($product_name_color->prod_color){
                break;
                $disabled = 'false';
            }
            else{
                $disabled = 'true';
            }
        }
    @endphp
    <div style="display: flex; align-items:center; justify-content:space-between; width:70%; height: 6%;">
        <x-emp-header title="PRODUCT INVENTORY"></x-emp-header>                       
        {{-- <x-emp-button-link title="View All Transactions" link="{{ route('dashboard.purchases') }}"></x-emp-button-link> --}}
        <x-emp-button-link title="Add New Product" toggle='true' target='addProduct' :dataCollection="$dataCollections"></x-emp-button-link>        
        <x-emp-button-link title="View Product Images" :disabled='$disabled' link="{{ route('product.images') }}" :dataCollection="$dataCollections"></x-emp-button-link>        
    </div>
    <ul style="margin-top: 20px">
        @if (session()->has('error'))
            <li>{{ session('error') }}</li>
        @endif
        @error('product_name')
            <li>{{ $message }}</li>
        @enderror
        @error('product_type')
        <li>{{ $message }}</li>
        @enderror
        @error('product_size')
            <li>{{ $message }}</li>
        @enderror
        @error('product_price')
            <li>{{ $message }}</li>
        @enderror
        @error('product_image')
            <li>{{ $message }}</li>
        @enderror
        @if (count($error) > 0)
            @foreach ($error as $e)
            <li>{{ $e }}</li>
            @endforeach            
        @endif

    </ul>  
    <div style="margin-top:10px; width:100%;" id="asd">
        <x-emp-table :dataCollection="$dataCollections" :headers="$headers" title="View Product Details"/>  
    </div>
    <script>
        var prod_name = document.getElementById('prod_name')
        var prod_type = document.getElementById('prod_type')
        var prod_size = document.getElementById('prod_size')
        var prod_price = document.getElementById('prod_price') 
        var prod_status = document.getElementById('prod_status')   
        var asd = document.getElementById('asd')   
        var status1 = document.getElementById('status1')    
        var status2 = document.getElementById('status2')   
        //var product_image = document.getElementById('product_image')
        //console.log(asd.firstElementChild.firstElementChild.nextElementSibling.nextElementSibling.firstElementChild.nextElementSibling.firstElementChild.lastElementChild.firstElementChild.firstElementChild)
        prod_name.addEventListener('change', function(){
            prod_name.value = prod_name.value.trim()
            if(prod_name.value == '' || prod_name.value[0] == ' ' || prod_name.value.length <= 3){
                prod_type.value = ''
                prod_type.disabled = true                                
                prod_size.value = "None"
                prod_size.disabled = true
                prod_price.value = ''
                prod_price.disabled = true
                // product_image.disabled = true
                // product_image.value = ''
            }
            else{
                prod_name.value = prod_name.value.trim()
                //product_image.disabled = false
                prod_type.disabled = false                                               
            }
        })


        prod_type.addEventListener('change', function(){
            //prod_type.value = prod_type.value.trim()
            prod_size.disabled = false
            prod_size.value = "None"
            prod_price.disabled = true            
            prod_price.value = ''
            for(var i = 0; i < prod_size.children.length; i++){
                prod_size.children[i].hidden = true
                if(prod_type.value == 'Hat'){
                    if( prod_size.children[i].value == 16 || prod_size.children[i].value == 18 || prod_size.children[i].value == 20 || prod_size.children[i].value == 22){
                        prod_size.children[i].hidden = false
                        if(prod_size.children[i].value == "None"){
                            prod_size.children[i].disabled = false
                        }
                    } 
                }
                else{
                    if(prod_type.value == 'Bag'){
                        if( prod_size.children[i].value == 16 || prod_size.children[i].value == 18 || prod_size.children[i].value == 20 || prod_size.children[i].value == 22){
                            prod_size.children[i].hidden = false
                            if(prod_size.children[i].value == "None"){
                                prod_size.children[i].disabled = false
                            }
                        }
                    }
                    else{
                        if(prod_type.value == 'Shirt'){
                            prod_size.children[i].hidden = false
                            if(prod_size.children[i].value == "None"){
                                prod_size.children[i].disabled = true
                            }
                        }
                    }
                }
            }
            
        })


        prod_size.addEventListener('change', function(){
            prod_type.value = prod_type.value.trim()
            prod_price.disabled = false
            prod_price.value = ''
        })
        
        if(prod_status.innerText == 'INACTIVE'){
            status1.checked = false
            status2.checked = true
        }
        else{
            status1.checked = true
            status2.checked = false
        }


    </script>
    
@endsection