<?php

namespace App\Mail;

use App\Models\Checkout;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ourcodeworld\NameThatColor\ColorInterpreter;


class ConfirmPayment extends Mailable
{
    use Queueable, SerializesModels;

    public $IDArrayJoin;
    public $total = 0;
    public $invoice_num;
    public $acc_name;
    public $acc_number;
    public $created_at;
    public $prod_names = [];
    public $prod_subtotals = [];
    public $color;
    public $images = [];
    public $product_ids = [];
    public $product_qtys = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($IDArrayyJoin)
    {
        $this->color = new ColorInterpreter();
        $this->IDArrayJoin = $IDArrayyJoin;
        $array = explode('|', $this->IDArrayJoin);
        $this->acc_name = Checkout::find(Cart::find($array[0])->checkout_id)->acc_name;
        $this->acc_number = Checkout::find(Cart::find($array[0])->checkout_id)->acc_num;
        $this->invoice_num = Checkout::find(Cart::find($array[0])->checkout_id)->invoice_number;
        $this->total = Checkout::find(Cart::find($array[0])->checkout_id)->total;
        $this->created_at = Checkout::find(Cart::find($array[0])->checkout_id)->created_at;
        foreach ($array as $id) {
            $product_id = Cart::find($id)->product_id;
            array_push($this->product_ids, $product_id);
            array_push($this->product_qtys, Cart::find($id)->quantity);
            $prod_name_color_id = DB::table('prod_name_color')->where('product_id', $product_id)->first()->id;
            $image = DB::table('product_images')->where('prod_name_color_id', $prod_name_color_id)->first()->product_image;
            array_push($this->images, $image);
            array_push($this->prod_names, Product::find(Cart::find($id)->product_id)->prod_name . '-' . $this->color->name(Product::find(Cart::find($id)->product_id)->prod_color)['name']);
            array_push($this->prod_subtotals, Cart::find($id)->subtotal);
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.ConfirmPayment');
    }
}
