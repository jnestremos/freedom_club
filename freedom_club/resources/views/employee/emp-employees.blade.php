@extends('layouts.emp-app')
@section('content')
<style>
    th, td{
        text-align: center;
    }
</style>

    @php
        use App\Models\Employee;
        $roles = DB::table('roles')->get();                        
        $role_users = DB::table('role_user')->get();         
        $employeeData = Employee::all();                      
        $headers = ['Employee ID', 'First Name', 'Last Name', 'Email', 'Gender', 'Birthdate', 'Created At', 'Updated At']              
    @endphp

    <div style="display: flex; align-items:center; justify-content:space-between; width:35%; height: 6%;">
        <x-emp-header title="EMPLOYEE PROFILES"></x-emp-header>                       
        <x-emp-button-link title="Register Employee" toggle='true' target='registerEmployee'></x-emp-button-link>        
    </div>
    <ul>
        @if (session()->has('error'))
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

    <div style="margin-top:20px; width:100%;">
        <x-emp-table :dataCollection="$employeeData" :headers="$headers" title="Edit Employee"/>  
    </div>    

    <script>        
        var modals = document.querySelectorAll('.edit_employee')        
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
        //console.log(roles)       
        modals.forEach(function(modal){
            modal.addEventListener('show.bs.modal', function(){
                var emp_id = modal.firstElementChild.firstElementChild.lastElementChild.previousElementSibling.firstElementChild.value
                //console.log(emp_id)
                if(modal.firstElementChild.firstElementChild.lastElementChild.previousElementSibling.lastElementChild.lastElementChild.lastElementChild.childElementCount != 0){
                    modal.firstElementChild.firstElementChild.lastElementChild.previousElementSibling.lastElementChild.lastElementChild.lastElementChild.innerHTML = ''

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
                        modal.firstElementChild.firstElementChild.lastElementChild.previousElementSibling.lastElementChild.lastElementChild.lastElementChild.appendChild(option)                        
                    }                                        
                } 
                                                                                                             
            })
        })
        
        
    </script>

@endsection