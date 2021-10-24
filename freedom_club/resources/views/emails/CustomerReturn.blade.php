<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    @font-face {        
    font-family: Akira;
    src: url('{{ asset('storage/fonts/Akira Expanded Demo.otf') }}');
    }
</style>
<body>
    Request #{{ $salesReturn->request_number }}
</body>
<body style="font-family: Arial">
    <div>
        <h1 style="text-align: center; font-family:Akira">FREEDOM CLUB</h1>
    </div>
    <br>
    <div style="margin-left:50px;width:40%;">
        <h3>Request #{{ $salesReturn->request_number }}</h3>
        <h3>Hello! {{ $name }}</h3>
        <p>The request has been {{ $msg }}. Please coordinate with our admin for further details</p>
        <br><br>                  
        <p>Regards,</p>
        <p>Freedom Club</p>
    </div>
    
    
   
    
</body>
</html>