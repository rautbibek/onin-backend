<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->string('name');
            $table->string('slug');
            $table->boolean('last_child')->default(true);
            $table->string('meta_title')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('icon')->nullable();
            $table->string('cover')->nullable();
            $table->string('has_color')->default(false);
            $table->string('has_size')->default(false);
            $table->integer('lvl')->default(1)->after('slug');
            $table->boolean('status')->default(true)->after('slug');
            $table->softDeletes();
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
        Schema::dropIfExists('categories');
    }
}
