@extends('layouts.app')
@section('content')
@php
    use Illuminate\Support\Facades\DB;
    use App\Models\Product;
    use App\Models\Cart;
    use ourcodeworld\NameThatColor\ColorInterpreter;
    $color = new ColorInterpreter();
    $images = [];
    $total = 0;
    foreach(Cart::where('user_id', auth()->user()->id)->where('pending', null)->get() as $cart_item){
        $total = $total + $cart_item->subtotal;
    }
    foreach(Cart::where('user_id', auth()->user()->id)->where('pending', null)->simplePaginate(2) as $cart_item){
        $product = Product::find($cart_item->product_id);  
        $image = DB::table('home_products_queue')->where('prod_name', $product->prod_name)->where('prod_type', $product->prod_type)->first()->product_image;
        array_push($images, $image);        
    }  
    $items = Cart::where('user_id', auth()->user()->id)->where('pending' , null)->simplePaginate(2);     
@endphp
    <div style="width:100%; height:90vh; display:flex; flex-direction:column; justify-content:center; align-items:center; font-family: Bahnschrift">        
        <div style="width: 90%; border:3px solid black">
            <div style="width: 100%; ">
                <h3 style="text-align:center; background-color:black; color:white; padding-top:20px; padding-bottom:20px; font-family: MontserratExtraBold">My Cart</h3>
                <h4 style="text-align:center; padding-top:10px; padding-bottom:10px; ">Total Items ({{ count(Cart::where('user_id', auth()->user()->id)->where('pending' , null)->get()) }})</h4>
                @if (count(Cart::where('user_id', auth()->user()->id)->where('pending' , null)->get()) == 0)              
                    <div style="display: flex; justify-content:space-between; border-top:3px solid black; justify-content:center; align-items:center; width:100%; padding-bottom:30px; padding-top:30px;">
                        <h1 style="text-align: center">NO ITEMS</h1>
                    </div>
                @else
                @foreach ($items as $index => $cart_item)
                    <div style="display: flex; justify-content:space-around; border-top:3px solid black; align-items:center; width:100%; padding-bottom:30px; padding-top:30px; padding-left:30px; ">
                        <div style="height: 100%; display:flex; position: relative; width:100%;">
                            <div style="width:250px; margin-right:20px; border:3px solid black">
                                <img src="{{ asset('storage/product_images/'. $images[$index]) }}" alt="" width="100%">
                            </div>
                            <div>
                                <input type="text" hidden value="{{ Product::find($cart_item->product_id)->id }}" name="id{{ $index + 1 }}">
                                <input type="text" hidden value="{{ $cart_item->id }}" name="cart_id{{ $cart_item->id }}">
                                <h2>{{ Product::find($cart_item->product_id)->prod_name }}</h2>
                                <h5 style="color:grey">Color: {{ $color->name(Product::find($cart_item->product_id)->prod_color)['name'] }}</h5>
                                <h5 style="color:grey">Size: {{ Product::find($cart_item->product_id)->prod_size }}</h5>
                                <form method="POST" action="{{ url('/cart/'. $cart_item->id) }}" style="position: absolute; bottom:0%">
                                    @csrf
                                    @method("DELETE")                                    
                                    <button type="submit" style="border:0px; background-color:transparent;">Remove</button>
                                </form>
                            </div>
                        </div>                    
                        <form method="POST" class="form" action="{{ url('/cart/'. $cart_item->id) }}" style="width:100%; display:flex; justify-content:center">
                            @method("PUT")
                            @csrf
                            <input type="number" class="form-control prod_qty" name="prod_qty" id="prod_qty{{ $index + 1 }}" min="1" step="1" style="width:100px" value="{{ $cart_item->quantity }}">
                        </form>                    
                        <div style="height: 100%; width:100%; display:flex; justify-content:flex-end; margin-right:20px">
                            <h1 id="price">Php {{ Product::find($cart_item->product_id)->prod_price }}</h1>
                        </div>                    
                    </div>
                @endforeach                                                                        
                @endif               
            </div>
            
        </div>
        <div style="width: 90%;  padding-bottom:30px; padding-top:30px; padding-left:30px;">
            <div>
                <input type="text" hidden  id="sub" name="total">
                <h4 id="total">Total: {{ $total }}</h4>
            </div>
            <div>
                <h6 style="color:grey; margin-bottom:30px">
                    Excluding taxes and shipping
                </h6>
            </div> 
            <div style="display: flex; align-items:center; justify-content:space-between">
                <div style="display:flex">
                   <a href="{{ route('checkout.index') }}"><button type="button" class="btn btn-dark" style="margin-right:20px; width:200px; height:50px">Checkout</button></a>
                    <button type="button" class="btn btn-secondary" style="width:200px; height:50px">Continue Shopping</button>                
                </div>
                <div>
                    {{ $items->links() }}
                </div>
            </div>               
        </div>        
    </div>
    <script>
        var prod_qty = document.querySelectorAll('.prod_qty')    
        var total = document.getElementById('total')               
        var sub = document.getElementById('sub')               
        var prices = []
        var result = 0
        var forms = document.querySelectorAll('.form')
        
        prod_qty.forEach(function(qty, index){             
            qty.addEventListener('change', function(){ 
                
                if(parseInt(qty.value) < 1 || isNaN(parseInt(qty.value))){
                    qty.value = parseInt(1)                   
                }
                else{
                    qty.value = parseInt(qty.value)  
                    forms[index].submit()        
                }  
                // result = 0                                  
                // var price = qty.parentElement.nextElementSibling.firstElementChild.innerHTML.split('Php ')[1]                
                // prices[qty.id[qty.id.length - 1] - 1] = price * parseInt(qty.value)                    
                // prices.forEach(function(price){                        
                //     result = result + price
                // })                
                // total.innerText = 'Total: ' + result
                // sub.value = result                  
                })           
            })
    </script>
@endsection