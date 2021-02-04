<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('category_id')->nullable();
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('man')->comment('1=Yes, 2 =No')->default(2);
            $table->tinyInteger('woman')->comment('1=Yes, 2 =No')->default(2);
            $table->tinyInteger('kids')->comment('1=Yes, 2 =No')->default(2);
            $table->tinyInteger('status')->comment('1=Enable, 2=No')->default(1);
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
        Schema::dropIfExists('sub_categories');
    }
}
