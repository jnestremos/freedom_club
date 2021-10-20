@extends('layouts.app')
@section('content')
    <div class="container-fluid position-relative" style="height: 90vh; width:100vw;">
        <h1 class="position-absolute text-white" style="top:5%; left:10%">Register as Employee</h1>
        <form action="{{ route('registerEmployee.store') }}" method="post">
            @csrf
            <div class='position-absolute' style="top:13%; left:10%">
                <label for="firstName" class="form-label text-white">Your Name <span style="color: red">*</span></label>
                <div class="d-flex mb-3">
                    <input type="text" class="form-control me-2" placeholder="First Name" value="{{old('firstName')}}" name="firstName" @error('firstName') style="border: 2px solid red" @enderror>
                    <input type="text" class="form-control" placeholder="Last Name" name="lastName" value="{{old('lastName')}}" @error('lastName') style="border: 2px solid red" @enderror>
                </div>
                
                <label for="birthDate" class="form-label text-white">Date of Birth <span style="color: red">*</span></label>
                <input type="date" class="form-control" name="birthDate" aria-describedby="birthDate" value="{{old('birthDate')}}" @error('birthDate') style="border: 2px solid red" @enderror>
                
                <div class="form-text mb-3" id="birthDate">We collect date of birth to comply with our <a href="#" style="color:grey">Privacy Policy</a href="#"></div>
                <div style="display: flex; justify-content:space-between; width:350px;">
                    <div>
                        <label for="gender" class="form-label text-white">Gender <span style="color: red">*</span></label> 
                        <select name="gender" id="gender" class="form-select mb-3" style="width: 100px; @error('gender') border: 2px solid red; @enderror" >
                            <option value="" disabled hidden selected>None</option>
                            <option value="Male">M</option>
                            <option value="Female">F</option>
                        </select>
                    </div>
                    <div>
                        <label for="role" class="form-label text-white">Role <span style="color: red">*</span></label>
                        <select name="role" id="role" class="form-select mb-3" style="width: 200px; @error('role') border: 2px solid red; @enderror" >
                            <option value="" disabled hidden selected>None</option>
                            <option value="store_owner">Store Owner</option>
                            <option value="warehouse_manager">Warehouse Manager</option>
                            <option value="product_manager">Product Manager</option>
                        </select>
                    </div>
                </div>
                <label for="username" class="form-label text-white">Login Details <span style="color: red">*</span></label>
                <div class="d-flex mb-3">
                    <input type="text" class="form-control me-2" placeholder="Username" name="username"  value="{{old('username')}}" @error('username') style="border: 2px solid red" @enderror>
                    <input type="email" class="form-control" placeholder="Email" name="email"  value="{{old('email')}}" @error('email') style="border: 2px solid red" @enderror>
                </div>
                <input type="password" class="form-control mb-3" placeholder="Password" name="password" @error('password') style="border: 2px solid red" @enderror>
                <input type="password" class="form-control mb-3" placeholder="Confirm Password" name="password_confirmation" @error('password_confirmation') style="border: 2px solid red" @enderror>
    
                
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
                    @error('role')
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
                </ul>
            </div>
            <div class="position-absolute" style="bottom:0%; left:20%; width:500px; height:300px">
                <div class="position-relative" style="width: 100%; height:100%;">
                    <div class="position-absolute" style="width: 300px; height:100px; left:15%; top:38%; background-color:white"></div>
                    <input type="submit" value="Register" class="text-white position-absolute btn btn-submit d-flex justify-content-center align-items-center" style="width: 300px; height:100px; left:17%; top:41%; background-color:black; border:2px solid white">        
                </div>
            </div>
            
        </form>
        <div class="position-absolute" style="color: white; bottom:1%; left:1%;">&copy; Freedom Club. All Rights Reserved.</div>
    </div>
@endsection