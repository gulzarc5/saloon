<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',256);
            $table->bigInteger('category_id')->nullable();
            $table->bigInteger('sub_category_id')->nullable();
            $table->bigInteger('third_category_id')->nullable();
            $table->char('range_type')->default(1)->comment('1 = limited, 2 = date range');
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->integer('total_user')->default(0);
            $table->integer('offer_received_user')->default(0);
            $table->text('description');
            $table->char('status')->default(1)->comment('1 = enabled, 2 = disabled');
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
        Schema::dropIfExists('offer');
    }
}
