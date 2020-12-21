<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->double('amount', 10, 2)->default(0);
            $table->string('name',256);
            $table->string('bank_name',256);
            $table->string('ac_no',256);
            $table->string('ifsc',256);
            $table->string('branch_name',256);
            $table->char('refund_status',1)->default(1)->comment("1= pending,2 = done");
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
        Schema::dropIfExists('refund_infos');
    }
}
