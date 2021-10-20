<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('confirm_token')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('receipt_number')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('acc_name')->nullable();
            $table->string('acc_num')->nullable();
            $table->boolean('status')->nullable();
            $table->boolean('emailStatus')->nullable();
            $table->double('total');
            $table->string('shipping_service')->nullable();
            $table->string('tracking_number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checkouts');
    }
}
