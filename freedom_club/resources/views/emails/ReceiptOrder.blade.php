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
    @php
        use App\Models\Product;
    @endphp
    {{-- Click the link <a href="{{ url('/checkout/confirm/'.$IDArrayJoin) }}">here</a> to update your payment status. --}}
    <div style="display: flex; width:80%;">
        <h2 style="width:90%; margin-left:20px; font-family:Akira">FRDM</h2>
        <h2>Receipt</h2>
    </div>
    <div style="display: flex; flex-wrap:wrap; width:80%; padding:20px; background-color:grey;">
        <div style="width:50%">
            <div>
                <h4>Email</h4>
                <p>{{ $email }}</p>
            </div>            
            <div>
                <h4>Invoice Date</h4>
                <p>{{ $checkout->created_at }}</p>
            </div>
            <div>
                <h4>Invoice #</h4>
                <p>{{ $invoice_num }}</p>
            </div>
        </div>
        <div style="width:50%;">
            <h4>Receipt #{{ $checkout->receipt_number }}</h4>
            <h4>Billed To</h4>            
            <p>{{ $checkout->acc_name }}</p>
            <p>{{ $checkout->acc_number }}</p>
            <p>{{ $address }}</p>
            <p>PHL</p>
            <br>
            <h4>Delivery Service: {{ $checkout->shipping_service }}</h4>
            <h4>Tracking # {{ $checkout->tracking_number }}</h4>
        </div>
    </div>
    <br>
   <div style="background-color:grey; width:83%; position: relative;">
    <div style="display: flex; flex-wrap:wrap; width:100%; padding-bottom:30px; padding-top:30px; padding-left:30px;">
        @foreach ($product_ids as $index => $id)
        <div style="height: 100%; display:flex; flex-wrap:wrap; position: relative; width:85%;">
            <div style="width:200px; margin-right:20px;">
                <img src="{{ asset('storage/product_images/'. $images[$index]) }}" alt="" width="100%">
            </div>
            <div>
                <h2>#{{ Product::find($id)->product_number }}</h2>                
                <h2>{{ $items[$index] }}</h2>
                <h5>Color: {{ $color->name(Product::find($id)->prod_color)['name'] }}</h5>
                <h5>Size: {{ Product::find($id)->prod_size }}</h5>                
                <h5>Qty: {{ $product_qtys[$index] }}</h5>                
            </div>
        </div>                                            
        <div style="height: 100%; display:flex; text-align:right; padding-right:30px; margin-top:20px;">
            <h1 id="price">Php {{ $subtotals[$index] }}</h1>
        </div>  
        @endforeach        
    </div>    
        <div style="display: flex; width:100%; border-top:2px solid white">
            <div style="width: 80%; text-align:right; padding-right:10px">
                <h2>TOTAL</h2>
            </div>
            <div style="width: 20%; position: relative; border-left:2px solid white">
                <h2 style="position: absolute; right:40%; top:0%;">Php {{ $total }}</h2>
            </div>
        </div>
        <div style="position: absolute; bottom:10%; right:5%">
            <div style="width: 20rem; display:flex;">
                <h4 style="margin-right: 40px">Shipping Fee: </h4>
                <h4>Php 500</h4>
            </div>
        </div>
   </div>
    
</body>
</html>