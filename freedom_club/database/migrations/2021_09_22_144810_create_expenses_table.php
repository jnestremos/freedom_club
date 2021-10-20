<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->nullable()->constrained('shipments')->onUpdate('cascade')->onDelete('cascade');
            $table->string('receipt_number')->nullable()->unique();
            $table->foreignId('expense_categories_id')->nullable()->constrained('expense_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->string('description');
            $table->double('computed_expenses');
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
        Schema::dropIfExists('expenses');
    }
}
