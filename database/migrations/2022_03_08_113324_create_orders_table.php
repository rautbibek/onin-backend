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
            $table->string('order_identifier');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('visibility')->default(true);
            $table->double('delivery_charge', 8, 2)->nullable();
            $table->double('total_price', 15, 2)->nullable();
            $table->text('delivery_address')->nullable();
            $table->string('payment_type')->default('COD');
            $table->boolean('payment_status')->default(false);
            $table->boolean('return_status')->default(false);
            $table->boolean('refund_status')->default(false);
            $table->enum('status',[1,2,3,4,5,6,7,8,9,10])
            ->default(1)
            ->comment('1 initialize,2 processing,3 ready for delivery,4  on the way','5 order completed,6 cancelled,7 return');
            $table->text('comment')->comment('anything if product returned refund or refund ')->nullable();
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
