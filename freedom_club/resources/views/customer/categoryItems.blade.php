@extends('layouts.app')
@section('content')
@php
    use App\Models\Product;
@endphp
<div style="width:100%; height:40rem">
    <img src="{{ asset('/storage/shop_images/1800x6001.jpg') }}" width="100%" alt="">
</div>
<div class="product-section" style="width:100%; background-color:white;">
    <div style="width: 100%; display:flex; justify-content:center; margin-top: 20px">
        <h2 style="color: black">ALL PRODUCTS</h2>            
    </div>
    <div class="product-cards" style="width: 100%; display:flex; flex-wrap:wrap">
        <div class="row mt-4" style="width:100%; display:flex;">
          @foreach ($products as $index => $product) 
          @php
              $prod_size = Product::where('prod_name', $product->prod_name)->where('prod_type', $product->prod_type)->where('prod_color', $product->prod_color)->orderBy('prod_size', 'asc')->first()->prod_size;
          @endphp                          
          <div class="col-4" style="display:flex; justify-content:space-around; margin-top:20px">
            <div class="card" style="width: 27rem; margin-bottom:20px">
                <img src="{{ asset('storage/product_images/'. $product->product_image) }}" class="card-img-top img-responsive h-100 w-100" alt="...">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->prod_name }}</h5>
                    {{-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> --}}
                    <a href="{{ url('/show/'.$product->prod_type.'/'.$product->prod_name.'/'.explode('#',$product->prod_color)[1].'/'.$prod_size) }}"><button class="btn btn-success">View</button></a>
                </div>
            </div>
          </div>  
          @endforeach                                
        </div>                        
    </div>
    <div style="width: 100%; display:flex; justify-content:flex-end; padding:20px 20px 20px 0;">
      {{ $products->links() }}
    </div>
  </div>
@endsection