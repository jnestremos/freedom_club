<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierTransactionMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_transaction', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supp_transactions_id')->constrained('supp_transactions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade')->onUpdate('cascade');
            $table->string('material_number')->nullable();
            $table->string('material_type')->nullable();
            $table->string('material_size')->nullable();
            $table->string('material_color')->nullable();
            $table->string('material_qty')->nullable();
            $table->string('material_price')->nullable();
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
        Schema::dropIfExists('supplier_transaction_materials');
    }
}
