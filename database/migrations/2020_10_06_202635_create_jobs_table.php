<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id')->nullable();
            $table->string('job_category')->nullable();
            $table->mediumText('description')->nullable();
            $table->string('main_image')->nullable();
            $table->double('mrp', 10, 2)->default(0);
            $table->double('price', 10, 2)->default(0);
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
        Schema::dropIfExists('jobs');
    }
}
