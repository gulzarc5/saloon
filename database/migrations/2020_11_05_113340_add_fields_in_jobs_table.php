<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->char('is_man', 1)->default(1)->comment('1 = No, 2 = Yes')->after('price');
            $table->char('is_woman', 1)->default(1)->comment('1 = No, 2 = Yes')->after('is_man');
            $table->char('is_kids', 1)->default(1)->comment('1 = No, 2 = Yes')->after('is_woman');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            //
        });
    }
}
