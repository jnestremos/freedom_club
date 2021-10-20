@extends('layouts.app')
@section('content')
<style>
    .img{
        overflow: hidden;
    }
    .img img{
        transition: 0.2s all ease-in;
    }

    .img:hover img{
        transform: scale(1.2)
    }
</style>
<div style="width: 100%; height:5rem; display:flex; justify-content:center; align-items:center; background-color:black">
   <h2 style="color: white"><i>Collections</i></h2>
</div>
<div style="width:100%; display:flex; align-items-center; justify-content:center">
    <div style="display: flex; width:95%; justify-content:space-around; flex-wrap:wrap; align-items:center; padding:20px 0px;">
        <div style="width:30%; height:42rem; margin-bottom:30px; position: relative;" class="img">
            <a href="{{ url('/shop/Shirt') }}"><img src="{{ asset('/storage/shop_images/IMG_8452.jpg') }}" width="100%" height="100%" alt=""></a>
            <h3 style="position: absolute; top:50%; left:40%; color:white;"><i>Shirts</i></h3>
        </div>
        <div style="width:30%; height:42rem; margin-bottom:30px; position: relative;" class="img">
            <img src="{{ asset('/storage/shop_images/mousepad.jpg') }}" width="100%" height="100%" alt="">
            <h3 style="position: absolute; top:50%; left:38%; color:white;"><i>Mouse Pads</i></h3>
        </div>
        <div style="width:30%; height:42rem; margin-bottom:30px; position: relative;" class="img">
            <a href="{{ url('/shop/Accessories') }}"><img src="{{ asset('/storage/shop_images/hat.jpg') }}" width="100%" height="100%" alt=""></a>
            <h3 style="position: absolute; top:50%; left:40%; color:white;"><i>Accessories</i></h3>
        </div>
        <div style="width:30%; height:42rem; margin-bottom:30px;  position: relative;" class="img">
            <img src="{{ asset('/storage/shop_images/2 (1).jpg') }}" width="100%" height="100%" alt="">
            <h3 style="position: absolute; top:50%; left:40%; color:white;"><i>Shirts</i></h3>
        </div>
        <div style="width:30%; height:42rem; margin-bottom:30px; position: relative;" class="img">
            <img src="{{ asset('/storage/shop_images/IMG_8452.jpg') }}" width="100%" height="100%" alt="">
            <h3 style="position: absolute; top:50%; left:40%; color:white;"><i>Shirts</i></h3>
        </div>
    </div>
</div>
    
@endsection