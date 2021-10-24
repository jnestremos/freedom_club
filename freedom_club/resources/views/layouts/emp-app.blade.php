<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Freedom Club</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js" integrity="sha512-Wt1bJGtlnMtGP0dqNFH1xlkLBNpEodaiQ8ZN5JLA5wpc1sUlk/O5uuOMNgvzddzkpvZ9GLyYNa8w2s7rqiTk5Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- <link rel="stylesheet" type="text/css" href="assets{{'css/app.css'}}"> --}}
        
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
    .dataTables_wrapper {
    font-family: Montserrat;
    
}
</style>
<body>
    
    <div style="height: 100vh; width:100vw; display:flex;">
        <div style="height: 100%; width:20%; padding-top: 20px; background-color:grey; display:flex; flex-direction: column; align-items:center; justify-content:space-around;">
            {{-- <a href="{{ url('/dashboard/profile/' . auth()->user()->id) }}">
                <div style="background-color: white; width:200px; height:200px; display:flex; justify-content:center; align-items:center;">
                    img
                </div>
            </a> --}}
            @php
                $selected = Route::currentRouteName();
                $routeNames = ['dashboard.home', 'dashboard.suppliers', 'dashboard.employees', 'dashboard.stocks', 'dashboard.products', 
                'dashboard.shipments', 'dashboard.sales', 'dashboard.expenses', 'dashboard.balance', 'dashboard.orders', 'dashboard.transfer', 
                'dashboard.purchases',] ;
                $pages = ['Home', 'Supplier Profiles', 'Employee Profiles', 'Raw Materials Inventory', 'Finished Products Inventory', 'Shipments', 'Sales', 
                'Expenses', 'Balance Sheet', 'Orders', 'Stock Transfer Requests', 'Supplier Purchase Records'];
            @endphp
            @for ($i = 0; $i < count($routeNames); $i++)
                <a style="text-decoration:none; font-family:Bahnschrift" href="{{ route($routeNames[$i]) }}"> <x-emp-side-button page="{{ $pages[$i] }}" :selected="$selected"/> </a>
            @endfor
                                    
        </div>
        <div style="height: 100%; width:80%; background-color:white;">
            @if (auth()->user()->employee->emp_firstName != 'admin' && auth()->user()->employee->emp_lastName != 'admin')
            <div style="background-color: black; color:white; display:flex; align-items:center; justify-content:flex-end; height:4%; width:100%;">
            @else
            <div style="background-color: black; color:white; display:flex; align-items:center; justify-content:space-between; height:4%; width:100%;">
            @endif
                @if (auth()->user()->employee->emp_firstName == 'admin' && auth()->user()->employee->emp_lastName == 'admin')
                <div>
                    <h6 style="color: red; font-size:14px; margin-left:20px; margin-top:5px; font-family: Verdana">Please change your account credentials by clicking the profile icon!</h6>
                </div>
                @endif            
                <div style="display:flex; justify-content:space-around; align-items:center; width:150px; margin-right:30px;">
                    <div style="font-size:14px; font-family:Verdana">Help                
                    </div>
                    <div style="font-size:14px;"> |                  
                    </div>
                    <div>
                        <form action="{{ route('logout.store') }}" method="POST"> 
                            @csrf 
                            <input type="submit" value="Logout" style="font-size:14px; font-family:Verdana; background-color:transparent; color:white; border:0"> 
                        </form>
                    </div>   
                </div>                 
            </div>
            <div style="height: 96%; width:100%; padding:20px">
                <div style="height:7%; display:flex; align-items:center; justify-content:space-between; margin-bottom:15px">
                    <h1 style="font-family: Akira;">FRDM</h1>
                    <div style="margin-right:30px; display:flex; align-items:center; justify-content:space-around; height:40px; width:200px;">
                        <h6 style="font-family:Montserrat;"><b>{{auth()->user()->employee->emp_firstName. " " .auth()->user()->employee->emp_lastName}}</b></h6>
                        <a href="{{ url('/dashboard/profile/'.auth()->user()->id) }}" style="text-decoration: none; color:black;"><i style="font-size:35px;" class="far fa-user-circle"></i></a>
                    </div>                    
                </div>                
                @yield('content')
            </div>
        </div>

    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>  

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdn.datatables.net/datetime/1.1.1/js/dataTables.dateTime.min.js"></script>
    
    
    <script>
        $(document).ready( function () {
            $('#example').DataTable({
                'responsive':true,
                "scrollY":"500px",
                "scrollCollapse": true,                           
            });
        } );
    </script> 
    
    
</html>