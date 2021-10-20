<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Freedom Club</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <title>Document</title>
</head>
<style>
    @font-face {        
    font-family: Akira;
    src: url('{{ asset('storage/fonts/Akira Expanded Demo.otf') }}');
    }
    @font-face {        
    font-family: Montserrat;
    src: url('{{ asset('storage/fonts/Montserrat/Montserrat-Medium.ttf') }}');
    }
    @font-face {        
    font-family: Bahnschrift;
    src: url('{{ asset('storage/fonts/BAHNSCHRIFT.ttf') }}');
    }
    @font-face {        
  font-family: MontserratExtraBold;
  src: url('{{ asset('storage/fonts/Montserrat/Montserrat-ExtraBold.ttf') }}');
  }
    @font-face {        
    font-family: Verdana;
    src: url('{{ asset('storage/fonts/VERDANA.ttf') }}');
    }
    html{
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }
</style>
@if (Route::currentRouteName() == 'loginCustomer.index' || Route::currentRouteName() == 'loginEmployee.index' 
|| Route::currentRouteName() == 'registerCustomer.index' || Route::currentRouteName() == 'forgotPassword')
<body style="background-color: black">
@else
<body> 
@endif
    @if (auth()->user())
    <div class="container-fluid d-flex justify-content-between align-items-center" style="height: 3vh; color:white; background-color:black; position:-webkit-sticky; position:sticky; top:0; z-index:100"> 
    @else
    <div class="container-fluid d-flex justify-content-end align-items-center" style="height: 3vh; color:white; background-color:black; position:-webkit-sticky; position:sticky; top:0; z-index:100">
    @endif
        @if (auth()->user())
            <div style="font-family: Verdana">Welcome, <a href="{{ url('/profile') }}">{{ auth()->user()->customer->cust_firstName . " ". auth()->user()->customer->cust_lastName}}</a></div>
        @endif            
        <div style="display:flex; justify-content:space-between; width:200px;">
            <a href="" style="text-decoration: none; color:white; font-family:Verdana;">Help
            </a>             
            @auth | <form action="{{ route('logout.store') }}" method="POST"> 
            @csrf <input type="submit" value="Logout" style="background-color:transparent; color:white; border:0"> 
            </form> 
            @else 
            | <a href="{{ route('loginCustomer.index') }}" style="text-decoration: none; color:white; font-family:Verdana;">Sign-in</a>
            @endauth
        </div>
    </div>
    <div class="container-fluid d-flex justify-content-between align-items-center" style="height: 7vh; background-color:white; color:black; position:-webkit-sticky; position:sticky; top:3%; z-index:100">
        <div style="margin-left: 40px; font-family:Akira; font-size:40px;"><a href="{{route('home')}}" style="text-decoration:none; color:black">FRDM</a></div>
        <div>
            <ul class="d-flex justify-content-between ms-5" style="list-style: none; width:500px; height:10px; font-family:Montserrat; ">
                <li><a href="{{ route('home') }}" style="text-decoration: none; color:black">Home</a></li>
                <li><a href="{{ route('shop') }}" style="text-decoration: none; color:black">Shop</a></li>
                <li><a href="" style="text-decoration: none; color:black">Size Chart</a></li>
                <li><a href="" style="text-decoration: none; color:black">How to Order</a></li>
            </ul>
        </div>
        <div class="me-3">
            @if(!Route::has(['registerCustomer.index', 'loginCustomer.index']))
            Search  
            @endif
            @auth
            <a href="{{ route('cart.index') }}"><i class="fas fa-shopping-cart" style="text-decoration: none; color:black; font-size:25px"></i></a>
            @endauth
            
        </div>
    </div>
    @yield('content')
    @if (Route::currentRouteName() != 'loginCustomer.index' && Route::currentRouteName() != 'forgotPassword' && Route::currentRouteName() != 'customer.return' && Route::currentRouteName() != 'customer.showDeliveries' && Route::currentRouteName() != 'customer.showHistory' && Route::currentRouteName() != 'registerCustomer.index' && Route::currentRouteName() != 'loginEmployee.index' && Route::currentRouteName() != 'cart.index' && Route::currentRouteName() != 'customer.profile')
    <div class="footer" style="background-color:black; width:100%; height:10vh; position: relative; font-family: Bahnschrift">
        <div><h2 style="color: white; position: absolute; top:5%; left:1%; font-family: Akira">FRDM</h2></div>
        <div style="position: absolute; bottom:5%; left:1%; color:white;">&copy; 2018 Freedom Club. All Rights Reserved.</div>
        <div style=" width:20rem; height:5rem; position: absolute; top:5%; left:40%; color:white;">
            <div class="row" style="width: 100%; height:100%; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-around;">
                <div class="col-4" style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-around;">EXPLORE</div>
                <div class="col-4" style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-around;">LEGAL</div>
                <div class="col-4" style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-around;">FOLLOW</div>
                <div class="col-4" style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-around;">SUPPORT</div>
            </div>                                 
        </div>    
        <div style="position: absolute; right:10%; top:40%; color:white;">SIGN UP FOR FREEDOM CLUB UPDATES</div>
      </div> 
    @endif
    
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>