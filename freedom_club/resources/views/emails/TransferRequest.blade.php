<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

   

<body style="font-family: Arial">
    <div>
        <h1 style="text-align: center">FREEDOM CLUB</h1>
    </div>
    <br>
    <div style="margin-left:50px;width:40%;">
        <h3>Stock Transfer #:{{ $stockTransfer->transfer_number }}</h3>        
        <p>Please click the button below to view your transfer requests.</p>
        <a href="{{ route('dashboard.transfer') }}"><button style="margin-left:70px; padding:12px; color:white; border-radius:5px; border:0px; background-color:blue" class="btn btn-primary" type="button">View Transfer Details</button></a>
        <br><br>       
        <p>Regards,</p>
        <p>Freedom Club</p>
    </div>
    
    
   
    
</body>
</html>