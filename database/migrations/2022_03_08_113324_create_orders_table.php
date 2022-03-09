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
            $table->integer('order_id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('quantity');
            $table->double('delivery_charge', 8, 2)->nullable();
            $table->double('total_price', 8, 2)->nullable();
            $table->jsonb('delivery_address')->nullable();
            $table->string('payment_type')->default('COD');
            $table->boolean('payment_status')->default(false);
            $table->enum('status',[1,2,3,4,5,6])->default(1);
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
