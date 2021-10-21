<?php

namespace App\Mail;

use App\Models\Cart;
use App\Models\Checkout;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ourcodeworld\NameThatColor\ColorInterpreter;

class ReceiptOrder extends Mailable
{
    use Queueable, SerializesModels;

    public $checkout;
    public $items = [];
    public $subtotals = [];
    public $total = 0;
    public $product_ids = [];
    public $product_qtys = [];
    public $images = [];
    public $email;
    public $address;
    public $invoice_num;
    public $color;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($checkout)
    {
        $this->color = new ColorInterpreter();
        $this->checkout = $checkout;
        $this->email = Customer::where('user_id', $checkout->user_id)->first()->cust_email;
        $this->address = Customer::where('user_id', $checkout->user_id)->first()->cust_address;
        $this->invoice_num = $checkout->invoice_number;
        $this->total = $checkout->total;
        foreach (Cart::where('checkout_id', $checkout->id)->get() as $item) {
            array_push($this->product_ids, $item->product_id);
            array_push($this->product_qtys, $item->quantity);
            $prod_name_color_id = DB::table('prod_name_color')->where('product_id', $item->product_id)->first()->id;
            $image = DB::table('product_images')->where('prod_name_color_id', $prod_name_color_id)->first()->product_image;
            array_push($this->images, $image);
            array_push($this->items, Product::find($item->product_id)->prod_name . '-' . $this->color->name(Product::find($item->product_id)->prod_color)['name']);
            array_push($this->subtotals, $item->subtotal);
        }
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.ReceiptOrder');
    }
}
