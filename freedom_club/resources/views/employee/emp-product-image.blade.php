@extends('layouts.emp-app')
@section('content')
@php
    use Illuminate\Support\Facades\DB;       
    use Illuminate\Support\Facades\URL;    
    $error = null;  
    $currentPage = null;  
    $uri = explode('/',$_SERVER['REQUEST_URI']);  
    if(strlen($uri[count($uri) - 1]) > 1){
      $currentPage = explode('page=', $_SERVER['REQUEST_URI']);
      $currentPage = $currentPage[1];
    }
    else{
      $currentPage = null;
    }
    //$currentPage = explode('page=', $_SERVER['REQUEST_URI']);
    //dd($currentPage[1]);
    if(count($product_images) == 1 && $product_images[0]->product_image == 'no-image.jpg'){
        $disabled = 'true';
    }
    else{
      $checker = false;      
        foreach(DB::table('product_images')->get() as $image){
        if($image->image_main == false){
          $checker = true;         
          $disabled = 'true';
        }
        else{
          $checker = false;          
          $disabled = 'false';
          break;          
        }
      }
       
      if($checker){
        $error = 'Please assign a default picture before adding!';
      }
      else{
        $error = null;
      }         
    }
@endphp

<div style="display: flex; align-items:center; justify-content:space-between; width:35%; height: 6%;">
    <x-emp-header title="PRODUCT IMAGE"></x-emp-header>                       
    {{-- <x-emp-button-link title="View All Transactions" link="{{ route('dashboard.purchases') }}"></x-emp-button-link> --}}
    <x-emp-button-link title="Add Image" :disabled="$disabled" toggle='true' target='addProductImage' :dataCollection='$product_images'></x-emp-button-link>            
    {{-- <x-emp-button-link title="View Product Images" :disabled='$disabled' link="{{ route('product.images') }}" :dataCollection="$dataCollections"></x-emp-button-link>         --}}
</div>
<ul>
    @error('product_image')
        <li>{{ $message }}</li>
    @enderror
    @if (session()->has('error'))
        <li>{{ session('error') }}</li>
    @endif
    @if ($error != null)
        <li>{{ $error }}</li>
    @endif
</ul>
<div style="height: 80%;  width:100%; position: relative;">
    <div class="row" style="margin-top:20px">
        @foreach ($product_images as $index => $image)           
        <div class="col-4" style="display:flex; justify-content:center; margin-bottom:50px">
            <div class="card" style="width: 21rem;">
                <a href="" data-bs-target="#exampleModal" data-bs-toggle="modal"><img src="{{ asset('storage/product_images/'. $image->product_image) }}" alt="" srcset="" width="100%"></a>         
                <div class="card-body" style="display: flex; justify-content: space-between; align-items:center">
                    <form action="{{ url('/dashboard/product/images/update/status/'. $image->id) }}" method="post">
                        @csrf
                        @method("PUT")
                        <input type="text" value="{{ $image->prod_name_color_id }}" name="prod_name_color_id" hidden>
                        @if ($image->image_main == 0)                                                
                        <input type="submit" class="form-control btn btn-success" value="Set as default">  
                        @else
                        <button type="button" disabled class="form-control btn btn-secondary" style="font-family:Montserrat">Current default</button>                  
                        @endif                  
                    </form>                                     
                  <form action="{{ url('/dashboard/product/images/delete/'.$image->id) }}" method="post">     
                    @csrf
                    @method("DELETE")
                    <input type="text" value="{{ $image->id }}" name="product_images_id" hidden>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                  </form>                   
                </div>
              </div>              
        </div>                                 
        @endforeach
       <div style="position: absolute; right:1%; bottom:0%; width:300px; padding:0; margin:0; justify-content:flex-end; display:flex">
        {{ $product_images->onEachSide(5)->links() }} 
       </div>
       <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Edit Picture</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div style="height: 100%; width:100%; display:flex; flex-direction:column; justify-content:space-around;">
                    <form action="{{ url('/dashboard/product/images/update/image/'.$image->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <input type="text" hidden value="{{ $image->prod_name_color_id }}" name="prod_name_color_id">
                        <label for="product_image" style="margin-bottom: 10px">Select an image:</label>
                        <input type="file" name="product_image" id="" class="form-control">                                                                                                                                                                                                                      
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </form> 
            </div>
          </div>
        </div>
      </div>                
    </div>
</div>

@endsection