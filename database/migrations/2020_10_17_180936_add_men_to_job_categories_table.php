<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenToJobCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_categories', function (Blueprint $table) {
            $table->char('man',1)->default(1)->comment('1 = yes,2 =No');
            $table->char('woman',1)->default(1)->comment('1 = yes,2 =No');
            $table->char('kids',1)->default(1)->comment('1 = yes,2 =No');
            $table->dropColumn('booking_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_categories', function (Blueprint $table) {
            $table->dropColumn('man');
            $table->dropColumn('woman');
            $table->dropColumn('kids');
        });
    }
}
