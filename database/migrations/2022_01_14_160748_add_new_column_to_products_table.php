<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('has_color')->default(false)->after('status');
            $table->boolean('search_text')->nullable()->after('slug');
            $table->boolean('has_size')->default(false)->after('status');
            $table->enum('discount_type',['flat', 'percent'])->nullable()->after('brand_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('has_color');
            $table->dropColumn('has_size');
            $table->dropColumn('sizes');
            $table->dropColumn('discount_type');
        });
    }
}
