<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice')->unique();
            $table->string('cashier_name')->default('No Name');
            $table->string('customer_name')->default('No Name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->enum('payment_status', ['Paid', 'Unpaid']);

            $table->integer('total_qty')->min(0);
            $table->integer('subtotal')->min(0)->default(0);
            $table->boolean('is_discount')->default(false);
            $table->integer('price_discount')->min(0)->default(0);
            $table->integer('percent_discount')->min(0)->max(100)->default(0);
            $table->integer('pb01')->default(0);
            $table->integer('service')->default(0);
            $table->integer('total')->default(0);
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
        Schema::dropIfExists('orders');
    }
}
