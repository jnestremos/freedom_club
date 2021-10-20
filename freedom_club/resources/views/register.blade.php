@extends('layouts.app')
@section('content')

    <div class="container-fluid position-relative" style="height: 90vh; width:100vw;">
        <h1 class="position-absolute text-white" style="top:3%; left:10%">Register</h1>
        <form action="{{ route('registerCustomer.store') }}" method="post">
            @csrf
            <div class='position-absolute' style="top:10%; left:10%; width:80%;">
                <label for="firstName" class="form-label text-white">Your Name <span style="color: red">*</span></label>
                <div class="d-flex mb-3">
                    <input type="text" class="form-control me-2" placeholder="First Name" value="{{old('firstName')}}" name="firstName" @error('firstName') style="border: 2px solid red" @enderror style="width:30%">
                    <input type="text" class="form-control" placeholder="Last Name" name="lastName" value="{{old('lastName')}}" @error('lastName') style="border: 2px solid red" @enderror style="width:30%">
                </div>
                
                <label for="address" class="form-label text-white">Address <span style="color: red">*</span></label>
                <input type="address" class="form-control mb-2" name="address" aria-describedby="address" value="{{old('address')}}" @error('address') style="border: 2px solid red" @enderror style="width:60%">
                <label for="birthDate" class="form-label text-white">Date of Birth <span style="color: red">*</span></label>
                <input type="date" class="form-control" name="birthDate" aria-describedby="birthDate" value="{{old('birthDate')}}" @error('birthDate') style="border: 2px solid red" @enderror style="width:60%">
                
                <div class="form-text mb-1" id="birthDate">We collect date of birth to comply with our <a href="#" style="color:grey">Privacy Policy</a href="#"></div>
                <div style="display:flex; width:60%">
                    <label for="gender" class="form-label text-white" style="width: 33%">Gender <span style="color: red">*</span></label>
                    <label for="region" class="form-label text-white" style="width: 33%">Region <span style="color: red">*</span></label>
                    <label for="province" class="form-label text-white" style="width: 33%">Province <span style="color: red">*</span></label>                    
                </div>                    
                <div style="display: flex; width:60%">                                        
                    <select name="gender" id="gender" class="form-select mb-3" @error('gender') border: 2px solid red; @enderror" style="width: 33%">
                        <option value="" disabled hidden selected>None</option>
                        <option value="Male">M</option>
                        <option value="Female">F</option>
                    </select>  
                    <select placeholder="Region" class="form-select mb-3" id="region" name="region" required style="width: 33%">  
                    </select>                
                    <select class="form-select mb-3" id="province" name="province" required style="width: 33%">
                    </select>                                        
                </div>
                <div style="display:flex; width:60%">                   
                    <label for="city" class="form-label text-white" style="width: 33%">Municipality/City <span style="color: red">*</span></label>
                    <label for="barangay" class="form-label text-white" style="width: 33%">Barangay <span style="color: red">*</span></label>
                    <label for="phone_num" class="form-label text-white" style="width: 33%">Phone Number <span style="color: red">*</span></label>
                </div> 
                <div style="display: flex; width:60%">
                    <select id="city" placeholder="City" class="form-select mb-3" name="municipalityCity" required style="width: 33%">
                    </select>
                    <select id="barangay" placeholder="Barangay" class="form-select mb-3" name="barangay" required style="width: 33%">
                    </select>
                    <input type="tel" placeholder="Phone: 09*********" class="form-control mb-3" placeholder="09*********" name="phone_num" required style="width: 33%">
                </div>   
    
                <label for="username" class="form-label text-white">Login Details <span style="color: red">*</span></label>
                <div class="d-flex mb-3">
                    <input type="text" class="form-control me-2" placeholder="Username" name="username"  value="{{old('username')}}" @error('username') style="border: 2px solid red" @enderror style="width:30%">
                    <input type="email" class="form-control" placeholder="Email" name="email"  value="{{old('email')}}" @error('email') style="border: 2px solid red" @enderror style="width:30%">
                </div>
                <input type="password" class="form-control mb-3" placeholder="Password" name="password" @error('password') style="border: 2px solid red" @enderror style="width:60%">
                <input type="password" class="form-control mb-3" placeholder="Confirm Password" name="password_confirmation" @error('password_confirmation') style="border: 2px solid red" @enderror style="width:60%">
    
                <div class="form-check mb-1">
                    <input class="form-check-input" type="checkbox" id="notifyNews" name="notifyNews">
                    <label class="form-check-label text-white" for="notifyNews">
                      By clicking the "Sign Up" button, I agree to receive Freedom Club news by e-mail. See our <a href="#" style="color:grey">Privacy Policy</a href="#"> for further information.
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="terms" name="terms">
                    <label class="form-check-label text-white" for="terms">
                      I have read and accepted the <a href="#" style="color:grey">Terms and Conditions</a href="#"> and the <a href="#" style="color:grey">Privacy Policy</a href="#"><span style="color: red">*</span>
                    </label>
                </div>
            </div>
            <div class="d-flex position-absolute" style=" right:15%; top:13%">
                <ul style="background-color: red">
                    @error('firstName')
                        <li>{{$message}}</li>
                    @enderror
                    @error('lastName')
                        <li>{{$message}}</li>
                    @enderror
                    @error('birthDate')
                        <li>{{$message}}</li>
                    @enderror
                    @error('gender')
                        <li>{{$message}}</li> 
                    @enderror
                    @error('username')
                        <li>{{$message}}</li>
                    @enderror
                    @error('email')
                        <li>{{$message}}</li>
                    @enderror
                    @error('password')
                        <li>{{$message}}</li>
                    @enderror
                    @error('password_confirmation')
                        <li>{{$message}}</li>
                    @enderror
                    @error('terms')
                        <li>{{$message}}</li>
                    @enderror
                    @error('municipalityCity')
                        <li>{{$message}}</li>
                    @enderror
                    @error('barangay')
                        <li>{{$message}}</li>
                    @enderror
                    @error('region')
                        <li>{{$message}}</li>
                    @enderror
                    @error('province')
                        <li>{{$message}}</li>
                    @enderror
                    @error('address')
                        <li>{{$message}}</li>
                    @enderror
                    @error('phone_num')
                        <li>{{$message}}</li>
                    @enderror                   
                </ul>
            </div> 
            <div class="position-absolute" style="bottom:0%; left:37%; width:500px; height:300px">
                <div class="position-relative" style="width: 100%; height:100%;">
                    <div class="position-absolute" style="width: 200px; height:70px; left:15%; top:67%; background-color:white"></div>
                    <input type="submit" value="Register" class="text-white position-absolute btn btn-submit d-flex justify-content-center align-items-center" style="width: 200px; height:70px; left:17%; top:70%; background-color:black; border:2px solid white">        
                </div>
            </div>                       
        </form>        
    </div>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.js"></script>
    <script src="https://f001.backblazeb2.com/file/buonzz-assets/jquery.ph-locations-v1.0.0.js"></script>
    <script>
       var my_handlers = {

fill_provinces:  function(){
    
    var region_code = $(this).val();
    if($('#region').val() == null){
        document.getElementById('province').disabled = true
    }
    else{
        document.getElementById('province').disabled = false
        $('#province').ph_locations('fetch_list', [{"region_code": region_code}]);
    }
    
    
},

fill_cities: function(){

    var province_code = $(this).val();
    if($('#province').val() == null){
        document.getElementById('city').disabled = true
    }
    else{
        document.getElementById('city').disabled = false
        $('#city').ph_locations( 'fetch_list', [{"province_code": province_code}]);
    }    
},


fill_barangays: function(){

    var city_code = $(this).val();
    if($('#city').val() == null){
        document.getElementById('barangay').disabled = true
    }
    else{
        document.getElementById('barangay').disabled = false
        $('#barangay').ph_locations('fetch_list', [{"city_code": city_code}]);
    }  
}
};
document.getElementById('region').addEventListener('change', function(){
    document.getElementById('province').value = ''
    document.getElementById('city').value = ''
    document.getElementById('barangay').value = ''
})
document.getElementById('province').addEventListener('change', function(){    
    document.getElementById('city').value = ''
    document.getElementById('barangay').value = ''
})
document.getElementById('city').addEventListener('change', function(){        
    document.getElementById('barangay').value = ''
})
$(function(){
$('#region').on('change', my_handlers.fill_provinces);
$('#province').on('change', my_handlers.fill_cities);
$('#city').on('change', my_handlers.fill_barangays);

$('#region').ph_locations({'location_type': 'regions'});
$('#province').ph_locations({'location_type': 'provinces'});
$('#city').ph_locations({'location_type': 'cities'});
$('#barangay').ph_locations({'location_type': 'barangays'});

$('#region').ph_locations('fetch_list');
});


    </script>
@endsection