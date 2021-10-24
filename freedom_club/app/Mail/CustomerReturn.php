<?php

namespace App\Mail;

use App\Models\Checkout;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerReturn extends Mailable
{
    use Queueable, SerializesModels;

    public $salesReturn;
    public $name;
    public $msg;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($salesReturn)
    {
        $this->salesReturn = $salesReturn;
        $user_id = Checkout::where('receipt_number', $salesReturn->receipt_number)->first()->user_id;
        $this->name = Customer::where('user_id', $user_id)->first()->cust_firstName . ' ' . Customer::where('user_id', $user_id)->first()->cust_lastName;
        if ($salesReturn->status == false) {
            $this->msg = 'rejected';
        } else {
            $this->msg = 'accepted';
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.CustomerReturn');
    }
}
