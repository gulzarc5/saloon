<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnqueryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enquery', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('type',1)->default(1)->comment('1 = Customer , 2 = Vendor');
            $table->string('name',256)->nullable();
            $table->string('mobile',256)->nullable();
            $table->string('subject',500)->nullable();
            $table->longText('message')->nullable();
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
        Schema::dropIfExists('enquery');
    }
}
