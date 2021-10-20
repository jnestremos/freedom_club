@extends('layouts.app')
@section('content')
@php
    use Illuminate\Support\Facades\DB;
    use App\Models\Product;    
    use Carbon\Carbon;
    use ourcodeworld\NameThatColor\ColorInterpreter;
    $color = new ColorInterpreter();
    DB::table('home_products_queue')->truncate();
    $carousel_images = DB::table('carousel_images')->select('*')->get();
    $prod_name_colors = DB::table('prod_name_color')->get();
    $products = Product::where('prod_status', true)->where('prod_qty', '>', '0')->orderBy('prod_size', 'asc')->get();
    $product_images = DB::table('product_images')->get();    
    foreach($products as $product){
      foreach($prod_name_colors as $prod_name_color){              
        if($product->prod_name == $prod_name_color->prod_name && $product->prod_type == $prod_name_color->prod_type 
        && $product->prod_color == $prod_name_color->prod_color && $product->prod_qty > 0){
          $product_image = DB::table('product_images')->where('prod_name_color_id', $prod_name_color->id)->where('image_main', 1)->first()->product_image;        
          DB::table('home_products_queue')->insert([
            'prod_name_color_id' => $prod_name_color->id,
            'prod_name' => $prod_name_color->prod_name,
            'prod_type' => $prod_name_color->prod_type,
            'product_image' => DB::table('product_images')->where('prod_name_color_id', $prod_name_color->id)->where('image_main', 1)->first()->product_image,
            'prod_color' => $prod_name_color->prod_color,
            'isUsed' => false,
        ]);        
        }
      }
    }  
    //dd(DB::table('home_products_queue')->get());
    $product_image_array = [];
    $product_type_array = [];
    $product_name_array = [];
    $product_color_array = [];
    $prod_name_color_id_array = [];
    //dd(DB::table('home_products_queue')->get());
    foreach(DB::table('home_products_queue')->get() as $index => $products){      
      // dd(DB::table('home_products_queue')->get()); 
      //var_dump('index'.$index);
      //var_dump('count '.count(DB::table('home_products_queue')->get()));
      for($i = $index; $i < count(DB::table('home_products_queue')->get()); $i++){
        //var_dump('i'.$i);
        //var_dump(count(DB::table('home_products_queue')->where('prod_name_color_id', $products->prod_name_color_id)->get()) > 1);
        if(count(DB::table('home_products_queue')->where('prod_name_color_id', $products->prod_name_color_id)->get()) > 1){     
          //var_dump('1');
          // array_push($product_image_array, $products->product_image);          
          // array_push($product_type_array, $products->prod_type);          
          // array_push($product_name_array, $products->prod_name);          
          // array_push($product_color_array, $products->prod_color);          
          // array_push($prod_name_color_id_array, $products->prod_name_color_id);
          $prod_name = DB::table('home_products_queue')->where('prod_name_color_id', $products->prod_name_color_id)->first()->prod_name;
          $product_image = DB::table('home_products_queue')->where('prod_name_color_id', $products->prod_name_color_id)->first()->product_image;
          $prod_type = DB::table('home_products_queue')->where('prod_name_color_id', $products->prod_name_color_id)->first()->prod_type;
          $prod_color = DB::table('home_products_queue')->where('prod_name_color_id', $products->prod_name_color_id)->first()->prod_color;
          $prod_name_color_id = DB::table('home_products_queue')->where('prod_name_color_id', $products->prod_name_color_id)->first()->prod_name_color_id;
          DB::table('home_products_queue')->where('prod_name_color_id', $products->prod_name_color_id)->delete();
          DB::table('home_products_queue')->insert([
            'prod_name_color_id' => $prod_name_color_id,
            'prod_name' => $prod_name,
            'prod_type' => $prod_type,
            'product_image' => $product_image,
            'prod_color' => $prod_color,
            'isUsed' => true,
        ]);
          // break;          
        }
        else{
          // array_push($product_image_array, $products->product_image);          
          // array_push($product_type_array, $products->prod_type);          
          // array_push($product_name_array, $products->prod_name);          
          // array_push($product_color_array, $products->prod_color);          
          // array_push($prod_name_color_id_array, $products->prod_name_color_id);
        }        
      }
    }
    //dd($product_color_array);
    // DB::table('home_products_queue')->truncate();
    // for($i = 0; $i < count($prod_name_color_id_array); $i++){
    //   DB::table('home_products_queue')->insert([
    //     'prod_name_color_id' => $prod_name_color_id_array[$i],
    //     'prod_name' => $product_name_array[$i],
    //     'prod_type' => $product_type_array[$i],
    //     'product_image' => $product_image_array[$i],
    //     'prod_color' => $product_color_array[$i], 
    //     'isUsed' => true,       
    // ]);
    // }
    $home_products = DB::table('home_products_queue')->orderBy('prod_name_color_id', 'asc')->simplePaginate(9);
    //dd($carousel_images);
@endphp
    @if (session()->has('error'))
        <div style="color:red;">{{ session('error') }}</div>
    @endif
    @auth
    @if (!auth()->user()->email_verified_at && !auth()->user()->provider_id)
    <div style="display: flex; color:red">We noticed that you still haven't confirmed your email yet, please check your email to verify your account. If you hadn't received a message, please click on this  
        <form action="{{  url('/users/resendVerify/'.auth()->user()->id)  }}" method="POST">
            @csrf
            @method('PUT')
            <input type="submit" value="link" style="background-color:transparent; border:0; text-decoration:underline; color:black;">
        </form> 
        to resend your validation code.
    </div>
    @endif
    @endauth
    <div style="background-color: black">
      <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" style="width: 100%; margin:0 auto;">
        <div class="carousel-indicators">
          @foreach ($carousel_images as $index => $image)        
          @if ($index == 0)
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $index }}" class="active" aria-current="true" aria-label="Slide {{ $index + 1 }}"></button>
          @else
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $index }}" aria-current="true" aria-label="Slide {{ $index + 1 }}"></button>
          @endif        
          @endforeach
        </div>
        <div class="carousel-inner" style="width: 100%; height:40rem;">
          @if (count($carousel_images) == 0)
          <div class="carousel-item active" style="height:100%; width: 100%;"  data-bs-interval="5000">
            <img src="{{ asset('storage/carousel_images/no-image.jpg') }}" style="object-fit: cover;" class="d-block w-100 h-100 img-responsive" alt="...">
          </div>
          @else
          @foreach ($carousel_images as $index => $image)
          @if ($index == 0)
          <div class="carousel-item active" style="height:100%; width: 100%;"  data-bs-interval="5000">
            <img src="{{ asset('storage/carousel_images/'.$image->carousel_image) }}" style="width: 100%; height:100%" class="d-block img-responsive" alt="...">
          </div>
          @else
          <div class="carousel-item" style="height:100%; width: 100%;" data-bs-interval="5000">
            <img src="{{ asset('storage/carousel_images/'.$image->carousel_image) }}" style="width: 100%; height:100%" class="d-block img-responsive" alt="...">
          </div>
          @endif
          @endforeach
          @endif
          
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
          {{-- <div class="carousel-indicators">          
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
          </div> --}}
          {{-- <div class="carousel-inner">
            <div class="carousel-item active" style="height:700px">
              <img src="{{ asset('storage/carousel_images/1.jpg') }}" style="object-fit: cover; object-position:top" class="d-block w-100 h-100 img-responsive" alt="...">
            </div>
            <div class="carousel-item" style="height:700px">
              <img src="{{ asset('storage/carousel_images/3.jpg') }}" style="object-fit: cover; object-position:center" class="d-block w-100 h-100 img-responsive" alt="...">
            </div>
            <div class="carousel-item" style="height:700px">
              <img src="{{ asset('storage/carousel_images/4.jpg') }}" style="object-fit: cover; object-position:center" class="d-block w-100 h-100 img-responsive" alt="...">
            </div>
          </div> --}}
          
        </div>
    </div>
    

      <div class="product-section" style="width:100%; background-color:white;">
        <div style="width: 100%; display:flex; justify-content:center; margin-top: 20px">
            <h2 style="color: black">ALL PRODUCTS</h2>            
        </div>
        <div class="product-cards" style="width: 100%; display:flex; flex-wrap:wrap">
            <div class="row mt-4" style="width:100%; display:flex;">
              @foreach ($home_products as $index => $product) 
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
          {{ $home_products->links() }}
        </div>
      </div>
      
@endsection