<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',256)->nullable();
            $table->char('type',1)->default(1)->comment('1 = new user, 2 = Old user');
            $table->double('amount',10, 2)->default(0);
            $table->char('status',1)->default(1)->comment('1 = enable, 2 = Disable');
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
        Schema::dropIfExists('coupon');
    }
}
