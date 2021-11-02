<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained("categories")->cascadeOnUpdate()->nullOnDelete();
            $table->unsignedBigInteger('product_type_id')->nullable();
            $table->foreign('product_type_id')->references('id')->on('product_types');
            $table->text('short_description');
            $table->text('description');
            $table->boolean('inventory_track')->default(false);
            $table->foreignId('brand_id')->nullable()->constrained("brands")->cascadeOnUpdate()->nullOnDelete();
            $table->jsonb('extra')->nullable();
            $table->jsonb('image')->nullable();
            $table->boolean('status')->default(false);
            $table->boolean('out_of_stock')->default(false);
            $table->text('meta_tag_title')->nullable();
            $table->text('meta_tag_description')->nullable();
            $table->string('meta_tag_keyword')->nullable();
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
        Schema::dropIfExists('products');
    }
}
