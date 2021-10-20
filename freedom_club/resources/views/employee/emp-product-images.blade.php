@extends('layouts.emp-app')
@section('content')
@php
    use Illuminate\Support\Facades\DB;
    use App\Models\SuppTransaction;
    use ourcodeworld\NameThatColor\ColorInterpreter;
    $color = new ColorInterpreter();
@endphp

<div style="display: flex; align-items:center; justify-content:space-between; width:70%; height: 6%;">
    <x-emp-header title="PRODUCT IMAGES"></x-emp-header>                       
    {{-- <x-emp-button-link title="View All Transactions" link="{{ route('dashboard.purchases') }}"></x-emp-button-link> --}}
    {{-- <x-emp-button-link title="Add New Product" :disabled='$disabled' toggle='true' target='addProduct' :dataCollection="$dataCollections"></x-emp-button-link>        
    <x-emp-button-link title="View Product Images" :disabled='$disabled' link="{{ route('product.images') }}" :dataCollection="$dataCollections"></x-emp-button-link>         --}}
</div>

<div style="height: 86%;  width:100%; position: relative; font-family:Montserrat; font-size:14px">
    <div class="row">
        @foreach ($products as $product)
        @php    
            //dd(DB::table('product_images')->where('prod_name_color_id', $product->id)->get());        
            $product_image = DB::table('product_images')->where('prod_name_color_id', $product->id)->where('image_main', 1)->first();            
        @endphp       
        <div class="col-4" style="display:flex; justify-content:center; margin-bottom:10px">
            <div class="card" style="width: 22rem">
                @if ($product_image == null)
                <img src="{{ asset('storage/product_images/no-image.jpg') }}" alt="" srcset="" width="100%">          
                @else
                <img src="{{ asset('storage/product_images/'. $product_image->product_image) }}" alt="" srcset="" width="100%">          
                @endif
                <div class="card-body" style="display: flex; justify-content: space-between; align-items:center">
                  <p class="card-text">{{ $product->prod_name ." - ". $color->name($product->prod_color)['name']}}</p>  
                  <a href="{{ url('/dashboard/product/images/'. $product->id) }}"><button class="btn btn-success">Edit Images</button></a>
                </div>
              </div>              
        </div>                                 
        @endforeach
       <div style="position: absolute; right:5%; bottom:5%; width:300px; padding:0; margin:0; justify-content:flex-end; display:flex">
        {{ $products->links() }} 
       </div>
                
    </div>
</div>

@endsection