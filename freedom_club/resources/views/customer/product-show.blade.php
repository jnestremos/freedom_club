@extends('layouts.app')
@section('content')
@php
    use Illuminate\Support\Facades\DB;
    use App\Models\Product;
    use ourcodeworld\NameThatColor\ColorInterpreter;
    $color = new ColorInterpreter();        
@endphp

   <div style="width: 100%; height:80vh;position: relative; font-family: Bahnschrift">
    <div style="width: 95%; height:85%; display:flex; justify-content:center; margin:0 auto">
        <div style="height: 100%; width:40%; ">
            <img src="{{ asset('/storage/product_images/'. $product_image) }}" alt="" style="width: 100%; height:100%; border: 5px solid black;">
        </div>
        <div style="height: 100%; width:60%;">
            <div style="background-color: black; padding-left:15px; height:5vh; color:white;font-size:25px; display:flex; font-family: MontserratExtraBold; align-items:center; margin-left:10px; margin-bottom:30px">
                {{ $product->prod_name . ' (' . $color->name($product->prod_color)['name'] . ')'}}
            </div>
            <div style="display: flex; height:100%;">
                <div style="margin-left:35px; width:96%;">
                    <div style="display: flex; justify-content:space-between; height:7vh; align-items:center;">
                        <h3>
                            Color:
                            <div style="display: flex; justify-content:space-around; margin-left:50px; margin-top:5px">
                                @foreach (DB::table('prod_name_color')->where('prod_name', $product->prod_name)->get() as $item)                                
                                    @if ($item->prod_color == $product->prod_color)
                                    <a style="width: 80px; height:40px; background-color:white; border:2px solid black; margin-right:10px; padding:5px;" href="{{ url('/'.$product->prod_type.'/'.$product->prod_name.'/'.explode('#',$item->prod_color)[1].'/'.$product->prod_size) }}">
                                        <div style="width: 100%; height:100%; background-color: {{ $product->prod_color }}">                                        
                                        </div>
                                    </a> 
                                    @else
                                    <a style="width: 80px; height:40px; background-color:{{ $item->prod_color }}; border:2px solid black; margin-right:10px;" href="{{ url('/'.$product->prod_type.'/'.$product->prod_name.'/'.explode('#',$item->prod_color)[1].'/'.$product->prod_size) }}"></a>
                                    @endif
                                @endforeach
                            </div>                            
                        </h3>
                        <h2>
                            Php {{ $product->prod_price }}
                        </h2>
                    </div>                    
                    <h3 style="padding-top: 25px">
                        Select size:
                        <div style=" margin-left:50px; width:40%; display:flex; flex-wrap:wrap; margin-top:15px">
                            @foreach (Product::where('prod_name', $product->prod_name)->where('prod_type', $product->prod_type)->where('prod_color', $product->prod_color)->orderBy('prod_size', 'asc')->get() as $item)
                            @if ($item->prod_size == $product->prod_size)
                            <a style="margin-right:30px; color:black" href="{{ url('/'.$product->prod_type.'/'.$product->prod_name.'/'.explode('#',$product->prod_color)[1].'/'.$item->prod_size) }}">{{ $item->prod_size }}</a>                                                            
                            @else   
                            <a style="margin-right:30px; color:blue" href="{{ url('/'.$product->prod_type.'/'.$product->prod_name.'/'.explode('#',$product->prod_color)[1].'/'.$item->prod_size) }}">{{ $item->prod_size }}</a>                                                                                                                   
                            @endif
                            @endforeach
                            {{-- <a style="margin-right:30px" href="">asd</a>
                            <a style="margin-right:30px" href="">asd</a>
                            <a style="margin-right:30px" href="">asd</a>
                            <a style="margin-right:30px" href="">asd</a>
                            <a style="margin-right:30px" href="">asd</a>
                            <a style="margin-right:30px" href="">asd</a> --}}
                        </div>
                    </h3>
                    <form action="{{ route('cart.store') }}" method="post"> 
                        @csrf
                        <input type="text" name="product_id" hidden value="{{ $product->id }}">
                        @auth
                        <input type="text" name="user_id" hidden value="{{ auth()->user()->id }}">
                        @endauth                       
                        <input type="text" name="subtotal" hidden id="subtotal">
                        <input type="text" hidden id="price" value="{{ Product::where('prod_name', $product->prod_name)->where('prod_type', $product->prod_type)->where('prod_color', $product->prod_color)->where('prod_size', $product->prod_size)->first()->prod_price }}">
                        <h3 style="display: flex; padding-top: 25px">
                            Quantity:
                            <input type="number" class="form-control" name="prod_qty" id="prod_qty" value="0" style="margin-left:20px; width:100px;" min="0" step="0" >                
                        </h3>                                                                       
                    <div style="margin-top:20px; padding-top:15px">
                        <input type="submit" style="height:70px; display:flex; justify-content:center; align-items:center; width:250px; border:5px solid black; border-radius:15px" value="Add to Cart">                       
                    </div>
                    </form>
                    <ul style="margin-top:20px">
                        @if (session()->has('error'))
                            <li>{{ session('error') }}</li>
                        @endif
                    </ul>
                    
                </div>                
            </div>
        </div>
    </div>
    <div style="position: absolute; height:150px; width:600px; bottom:3%; left:5%; display:flex; justify-content:space-around; flex-wrap:wrap">
        @foreach (DB::table('product_images')->where('prod_name_color_id', $prod_name_color_id)->get() as $index => $item)
        <div style="width: 150px; height:150px; border:2px solid black">
            <img src="{{ asset('storage/product_images/'. $item->product_image) }}" alt="" width="100%" height="100%" style="cursor: pointer" class="image {{ $index }}">
        </div>
        @endforeach                                                
    </div>
    {{-- <div style="position: absolute; width:150px; height:150px; left:4%; bottom:3%; border:5px solid black">
        <img src="" alt="">
    </div>
    <div style="position: absolute; width:150px; height:150px; border:5px solid black; left:13%; bottom:3%;">
        <img src="" alt="">
    </div> --}}
    
   </div>
   <script>
       var images = document.querySelectorAll('.image')
       var prod_qty = document.getElementById('prod_qty')
       var subtotal = document.getElementById('subtotal')
       var price = document.getElementById('price')
       var qty = '{{ $product->prod_qty }}'
       console.log(qty)
       prod_qty.addEventListener('change', function(){    
        subtotal.value = 0    
           prod_qty.value = parseInt(prod_qty.value)
           if(prod_qty.value < 0 || prod_qty.value > qty){
               prod_qty.value = 0
           }
           else{
            subtotal.value = price.value * prod_qty.value
           }
       })
   </script>
@endsection