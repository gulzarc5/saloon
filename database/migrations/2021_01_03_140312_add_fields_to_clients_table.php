<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->char('ac',1)->default('1')->comment('1 = No, 2 = Yes')->after('job_status');
            $table->char('parking',1)->default('1')->comment('1 = No, 2 = Yes');
            $table->char('wifi',1)->default('1')->comment('1 = No, 2 = Yes');
            $table->char('music',1)->default('1')->comment('1 = No, 2 = Yes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            //
        });
    }
}
