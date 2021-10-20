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
    <div style="background-color: grey; padding:100px 40px">
        <h1 style="text-align: center; font-family:Akira">FREEDOM CLUB</h1>
        <h3 style="text-align: center">Password Reset</h3>
        <br>
        <h3 style="text-align: center">Hello!</h3>
        <p style="text-align: center">If you've lost your password or wish to reset it,</p>     
        <p style="text-align: center">use the link below to get started.</p>     
        <a href="{{ url('/reset-password/' .$profile->token) }}" style="position: absolute; top:39%; left:39%"><button style="margin-left:70px; padding:12px; color:white; border-radius:5px; border:0px; background-color:blue" class="btn btn-primary" type="button">Reset Your Password</button></a>
        <br>
        <br>        
        <p style="text-align: center">If you did not request a password reset,  you can safely ignore this email. Only a person</p>
        <p style="text-align: center">with access to your email can reset your password account.</p>
    </div>
    <br>
    <div style="margin-left:50px;width:40%;">
        
       
        
        <br><br>    
        
    </div>
</body>
</html>