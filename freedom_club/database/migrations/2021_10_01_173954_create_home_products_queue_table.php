<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeProductsQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_products_queue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prod_name_color_id')->constrained('prod_name_color')->onUpdate('cascade')->onDelete('cascade');
            $table->string('prod_name');
            $table->string('prod_type');
            $table->string('product_image');
            $table->string('prod_color')->nullable();
            $table->boolean('isUsed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_products_queue');
    }
}
