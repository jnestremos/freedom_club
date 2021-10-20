@extends('layouts.app')
@section('content')
    @php
    use App\Models\Cart;
    use App\Models\Checkout;
        // if($IDArrayyJoin == null){
        //     return redirect()->route('home')->with('error', 'Checkout session has already expired!');
        // }
        // else{
        //     $array = explode('|',$IDArrayyJoin);
        //     foreach($array as $id){
        //         if(!(Cart::find($id))){
        //             return redirect()->route('home')->with('error', 'Checkout session has already expired!');
        //         }
        //     }
        // }
        // $array = explode('|', $IDArrayJoin);
        // foreach($array as $id){
        //     $invoice_num = Checkout::where('cart_id', $id)->first()->invoice_number;
        // }
    @endphp
    <div style="width: 100%; color:white; background-color:black; height:80vh;  display:flex; align-items:center; justify-content:center">
        <div style="height:90%; width:90%; display:flex; position: relative;">
            <div style="width: 50%">
                <h2 style="margin-bottom: 20px">Checkout</h2>
                <form action="{{ url('/checkout/confirm/'.$IDArrayJoin) }}" method="POST">
                    @csrf
                    @method("PUT")
                    <input type="text" hidden value="{{ $checkout->id }}" name="checkout_id">
                    <div style="width:100%; display:flex;">
                        <label for="" style="width:50%">Account Holder Name:</label>
                        <label for="" style="width:50%">Account Number/Phone Number:</label>
                    </div>                    
                    <div style="display: flex">                        
                        <input type="text" name="acc_name" class="form-control">                                            
                        <input type="text" name="acc_num" class="form-control">                        
                    </div>
                    <br>
                    <div style="display: flex; width:80%; justify-content:space-between;">
                        <label for="">Select your prefered mode of payment: </label>                                        
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="exampleRadios1" value="GCash" checked>
                            <label class="form-check-label" for="exampleRadios1">
                              GCash
                            </label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="exampleRadios2" value="BDO">
                            <label class="form-check-label" for="exampleRadios2">
                              BDO
                            </label>
                          </div>    
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="exampleRadios3" value="BPI">
                            <label class="form-check-label" for="exampleRadios3">
                              BPI
                            </label>
                          </div>    
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="exampleRadios4" value="Palawan Express">
                            <label class="form-check-label" for="exampleRadios4">
                              Palawan Express
                            </label>
                          </div>    
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="exampleRadios5" value="COD">
                            <label class="form-check-label" for="exampleRadios5">
                              COD
                            </label>
                          </div>    
                    </div>    
                    {{-- <button type="button" class="btn btn-secondary">Continue Shopping</button> --}}
                    <button type="submit" class="btn btn-success">Checkout</button>
                </form>
            </div>
            
            {{-- <div style="position: absolute; right: 20%; bottom:50%">
                @if (session()->has('error'))
                    <ul>
                        <li>{{ session('error') }}</li>
                    </ul>
                @endif
            </div> --}}
        </div>        
    </div> 
@endsection