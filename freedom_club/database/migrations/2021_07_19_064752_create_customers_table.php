<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('cust_firstName');
            $table->string('cust_lastName');
            $table->string('cust_email')->unique();
            $table->string('cust_region')->nullable();
            $table->string('cust_province')->nullable();
            $table->string('cust_address')->nullable();
            $table->string('cust_municipality')->nullable();
            $table->string('cust_city')->nullable();
            $table->string('cust_barangay')->nullable();
            $table->string('cust_phoneNum')->nullable();
            $table->char('cust_gender')->nullable();
            $table->char('cust_profile_pic');
            $table->date('cust_birthDate')->nullable();
            $table->boolean('cust_notifyNews')->nullable();
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
        Schema::dropIfExists('customers');
    }
}
