<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

<style>
    @font-face {        
    font-family: Akira;
    src: url('{{ asset('storage/fonts/Akira Expanded Demo.otf') }}');
    }
</style>
<body style="font-family: Arial">
    <div>
        <h1 style="text-align: center; font-family:Akira">FREEDOM CLUB</h1>
    </div>
    <br>
    <div style="margin-left:50px;width:40%;">
        <h3>Hello! {{ $user->customer->cust_firstName . ' ' . $user->customer->cust_lastName }}</h3>
        <p>Please click the button below to verify your email address.</p>
        <a href="{{ url('/users/verify/' .$user->verification_token) }}"><button style="margin-left:70px; padding:12px; color:white; border-radius:5px; border:0px; background-color:blue" class="btn btn-primary" type="button">Verify Email Address</button></a>
        <br><br>    
        <p>If you did not create an account, no further action is required.</p>
        <br>
        <p>Regards,</p>
        <p>Freedom Club</p>
    </div>
    
    
   
    
</body>
</html>
   
