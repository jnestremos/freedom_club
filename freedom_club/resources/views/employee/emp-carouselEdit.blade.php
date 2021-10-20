@extends('layouts.emp-app')
@section('content')
<style>
    th, td{
        text-align: center;
    }
</style>

    @php
        use Illuminate\Support\Facades\DB;       
        $dataCollections = DB::table('carousel_images')->select('*')->get();
        $images = [];
        $created_ats = [];
        $updated_ats = [];
        foreach($dataCollections as $image){
            array_push($images, $image->carousel_image);
            array_push($created_ats ,$image->created_at);
            array_push($updated_ats, $image->updated_at);
        }
        DB::table('carousel_images')->truncate();
        for($i = 0; $i < count($images); $i++){
            DB::table('carousel_images')->insert([
                'carousel_image' => $images[$i],
                'created_at' => $created_ats[$i],
                'updated_at' => $updated_ats[$i],
            ]);
        }
        //$headers = ['Expense ID', 'Material ID', 'Category ID', 'Description', 'Computed Expenses', 'Created At', 'Updated At']              
    @endphp
   
        
    <div style="display: flex; align-items:center; justify-content:space-between; width:80%; height: 2%;">
        <x-emp-header title="CAROUSEL IMAGES"></x-emp-header>    
        <form action="{{ route('carousel.clear') }}" method="post">  
          @csrf               
          <button type="submit" style="background-color: black; color:white; border:0; text-decoration:none; width:200px; height:50px; border-radius:10px; display:flex; justify-content:center; align-items:center; font-family:Bahnschrift">CLEAR ALL PICTURES</button>
        </form>   
        <x-emp-button-link title="Add Picture" toggle='true' target='addPicture' :dataCollection="$dataCollections"></x-emp-button-link>        
    </div>
    <ul>
        @if (session()->has('error'))
            <li>{{ session('error') }}</li>
        @endif        
        @error('carousel_image')
            <li>{{$message}}</li>
        @enderror
        
        
    </ul>
    @if (count($dataCollections) != 0)
    <div style="width: 100%; height:85%;padding-top:50px">
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach ($dataCollections as $index => $image)
                @if ($index == 0)
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $index }}" class="active" aria-current="true" aria-label="Slide {{ $index + 1 }}"></button>
                @else
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $index }}" aria-current="true" aria-label="Slide {{ $index + 1 }}"></button>
                @endif        
                @endforeach
            </div>
            <div class="carousel-inner" style="height: 40rem">
                @foreach ($dataCollections as $index => $image)
                @if ($index == 0)
                <div class="carousel-item active" style="height:100%; width: 100%;">
                  <img src="{{ asset('storage/carousel_images/'.$image->carousel_image) }}" style="object-fit:cover; object-position:top" class="d-block w-100 h-100 img-responsive" alt="...">
                  <div class="carousel-caption d-none d-md-block">
                    <form action="{{ url('/dashboard/carousel-images/'.$image->id) }}" method="POST">
                        @csrf
                        @method("DELETE")
                        <h5>1st Picture</h5>
                        <input type="text" name="user_id" value="{{ $image->id }}" hidden>  
                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                    </form>
                  </div>
                </div>
                @else
                <div class="carousel-item" style="height:100%; width: 100%;">
                  <img src="{{ asset('storage/carousel_images/'.$image->carousel_image) }}" style="object-fit: cover; object-position:top" class="d-block w-100 h-100 img-responsive" alt="...">
                  <div class="carousel-caption d-none d-md-block">
                    <form action="{{ url('/dashboard/carousel-images/'.$image->id) }}" method="POST">
                        @csrf
                        @method("DELETE")
                        @if ($index == 1)
                            <h5>2nd Picture</h5>
                        @else
                            @if($index == 2)
                            <h5>3rd Picture</h5>
                            @else
                            <h5>{{ $index + 1 }}th Picture</h5>
                            @endif
                        @endif                        
                        <input type="text" name="user_id" value="{{ $image->id }}" hidden>  
                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                    </form>
                  </div>
                </div>
                @endif
            @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
    </div>   
    @endif
    
    {{-- <div style="width: 100%; height:83.55%">
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="{{ asset('storage/carousel_images/1.jpg') }}" class="d-block w-100" alt="...">
              </div>
              <div class="carousel-item">
                <img src="{{ asset('storage/carousel_images/1.jpg') }}" class="d-block w-100" alt="...">
              </div>
              <div class="carousel-item">
                <img src="{{ asset('storage/carousel_images/1.jpg') }}" class="d-block w-100" alt="...">
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
    </div> --}}
    
    
        {{-- <div style="display: flex; flex-wrap:wrap; width:100%; background-color:red;">
            @foreach ($dataCollections as $picture)
            <div class="col-6 d-flex align-items-center" style="margin-top:20px;">
                <form action="{{ url('/carousel-images/'.$picture->id) }}" method="POST">
                    @csrf
                    @method("DELETE")
                    <button type="submit" class="btn btn-danger" style="margin-right: 20px"><i class="fas fa-trash"></i></button>
                    <input type="text" name="user_id" value="{{ $picture->id }}" hidden>   
                </form>
                <img src="{{ asset('storage/carousel_images/'.$picture->carousel_image) }}" alt="" style="width: 40rem; height:20rem">    
            </div>            
            @endforeach
        </div> --}}
    
     
@endsection