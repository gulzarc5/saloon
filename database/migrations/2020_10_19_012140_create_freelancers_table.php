<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFreelancersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('mobile')->unique();
            $table->string('email')->unique()->nullable();
            $table->integer('otp')->nullable();
            $table->bigInteger('service')->unsigned()->nullable();
            $table->string('work_experience')->nullable();
            $table->bigInteger('state')->nullable();
            $table->bigInteger('city')->nullable();
            $table->mediumText('address')->nullable();
            $table->string('image')->nullable();
            $table->string('gst')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->char('clientType', 1)->comment('1=Freelancer, 2=ShopKeeper')->default(1);
            $table->tinyInteger('status')->comment('1=Enable,2=Disable')->default(1);
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
        Schema::dropIfExists('freelancers');
    }
}
