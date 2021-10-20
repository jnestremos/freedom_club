@extends('layouts.emp-app')
@section('content')

@php
    use Illuminate\Support\Facades\Hash;    
    $roles = DB::table('roles')->get();                        
    $role_users = DB::table('role_user')->get();
@endphp
<div style="display: flex; align-items:center; justify-content:space-between; width:30%; height: 6%;">
    <x-emp-header title="EDIT PROFILE"></x-emp-header>        
    {{-- <x-emp-button-link link="{{ route('dashboard.suppliers') }}" title="View All Suppliers"></x-emp-button-link>                 --}}
</div>

<form action="{{ url('/dashboard/profile/'.auth()->user()->id) }}" method="post" enctype="multipart/form-data" style="height: 87%; width:100%; display:flex; font-family:Bahnschrift">
    @csrf
    @method("PUT")
    <div style="height: 100%; width:40%; display:flex; flex-direction:column; justify-content:space-around;">
        <input type="text" hidden value="{{ auth()->user()->id }}" id="emp_id">
            <div style="display: flex">
                <label style="width: 170px" for="firstName" class="form-label text-black">First Name: </label>
                <input type="text" class="form-control" value="{{ $user->employee->emp_firstName }}" name="firstName" placeholder="First Name" style="width:300px">
            </div>
            <div style="display: flex">
                <label style="width: 170px" for="lastName" class="form-label text-black">Last Name: </label>
                <input type="text" class="form-control" value="{{ $user->employee->emp_lastName }}" name="lastName" placeholder="Last Name" style="width:300px">
            </div>
            <div style="display: flex">
                <label style="width: 170px" for="birthDate" class="form-label text-black">Birth Date: </label>
                <input type="date" class="form-control" value="{{ $user->employee->emp_birthDate }}" name="birthDate" placeholder="Birth Date" style="width:300px">
            </div>
            <div style="display: flex">
                <label style="width: 170px" for="gender" class="form-label text-black">Gender: </label>
                <select name="emp_gender" class="emp_gender" style="width:70px; margin-right:50px">
                    @if ($user->employee->emp_gender == "Male")
                        <option value="Male" selected>M</option>
                        <option value="Female">F</option>
                    @else
                        <option value="Male">M</option>
                        <option value="Female" selected>F</option>
                    @endif
                </select>              
            </div>
            <div style="display: flex">
                <label style="width: 170px" for="role" class="form-label text-black">Role: </label>
                <select disabled name="role" id="role" style="width:200px">

                </select>
            </div>
            {{-- <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Holy guacamole!</strong> You should check in on some of those fields below.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div> --}}
            <div style="display: flex">
                <label style="width: 170px" for="username" class="form-label text-black">Username: </label>
                <input type="text" class="form-control" value="{{ $user->username }}" name="username" placeholder="Username" style="width:300px">
            </div>
            <div style="display: flex">
                <label style="width: 170px" for="email" class="form-label text-black">Email: </label>
                <input type="text" class="form-control" value="{{ $user->employee->emp_email }}" name="email" placeholder="Email" style="width:300px">
            </div>
            <div style="display: flex; width:100%; justify-content:space-around;">                
                <input type="submit" class="btn btn-primary" style="width: 200px" value="Edit Record">                
            </div>
            
    </div>
    <div style="height: 100%; width:60%;">  
        <div style="height: 50%; width:100%;  display:flex; flex-direction:column; justify-content:space-around;">
            <div style="display: flex">
                <label for="password" class="form-label text-black" style="margin-right: 20px;">Old Password: </label>                             
                <input type="password" class="form-control" placeholder="Password" id="password" name="old_password" style="width:300px">
            </div>
            <div style="display: flex">
                <label for="password" class="form-label text-black" style="margin-right: 20px;">New Password: </label>                             
                <input disabled type="password" id='new_pass' class="form-control" placeholder="Password" name="password" style="width:300px">
            </div>
            <div style="display: flex">
                <label for="password_confirmation" class="form-label text-black" style="margin-right: 20px">Confirm New Password: </label>  
                <input disabled type="password" class="form-control" id="new_pass_confirm" placeholder="Confirm New Password" name="password_confirmation" style="width:300px">
            </div>
        </div> 
        <div style="width:100%; height:50%;">          
            <ul style="width: 100%; height:100%; display:flex; flex-direction:column;">
                @if(session()->has('error'))               
                    <li>{{ session('error') }}</li>
                @endif
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
            </ul>
        </div>                           
    </div>
</form>

<script>

        var roles = [
            @foreach($roles as $role)
                '{{ $role->display_name }}',
            @endforeach
        ]
        var role_ids = [
            @foreach($roles as $role)
                '{{ $role->id }}',
            @endforeach
        ]
        var roleID_users = [
            @foreach($role_users as $role_user)
                '{{ $role_user->role_id }}',
            @endforeach
        ]
        var role_usersID = [
            @foreach($role_users as $role_user)
                '{{ $role_user->user_id }}',
            @endforeach
        ]  

        var password = document.getElementById('password')        

        window.addEventListener('load', function(){
            var emp_id = document.getElementById('emp_id')
            var role = document.getElementById('role')
            //console.log(emp_id)
            if(role.childElementCount != 0){
                role.innerHTML = ''

            }                
            for(var i = 0; i < roles.length; i++){                        
                if(roles[i] != 'Admin' && roles[i] != "Customer"){                            
                    var option = document.createElement('option')
                    var optionTextNode = document.createTextNode(roles[i])
                    option.value = role_ids[i]                        
                    for(var ii = 0; ii < roleID_users.length; ii++){
                            if(emp_id == role_usersID[ii] && roleID_users[ii] == role_ids[i]){
                                option.selected = true
                                break
                            }
                            else{
                                option.selected = false
                            }
                    } 
                    option.appendChild(optionTextNode)
                    role.appendChild(option)                        
                }                                        
            }                                                                                                          
        })        

        password.addEventListener('change', function(){
           // console.log(password.value.trim())
            if(password.value.trim() != ''){
                new_pass.disabled = false
                new_pass.value = ''
                new_pass_confirm.disabled = false
                new_pass_confirm.value = ''
            }
            else{
                new_pass.disabled = true
                new_pass.value = ''
                new_pass_confirm.disabled = true
                new_pass_confirm.value = ''
            }
        })


</script>
@endsection