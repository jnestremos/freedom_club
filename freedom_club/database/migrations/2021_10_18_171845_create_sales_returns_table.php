<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 'invoice_number' => $request->invoice_number,
        //             'acc_name' => $request->acc_name,
        //             'acc_number' => $request->acc_number,
        //             'payment_method' => $request->payment_method,
        //             'prod_name' => $request->prod_name,
        //             'quantity' => $request->quantity,
        Schema::create('sales_returns', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->string('receipt_number');
            $table->string('acc_name');
            $table->string('acc_number');
            $table->string('payment_method');
            $table->string('product_number');
            $table->integer('quantity');
            $table->boolean('status')->nullable();
            $table->string('image');
            $table->timestamps();
            $table->date('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_returns');
    }
}
