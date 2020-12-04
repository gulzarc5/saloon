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
            $table->dropColumn('mrp');
            $table->dropColumn('price');
            $table->char('is_man', 1)->default(1)->comment('1 = No, 2 = Yes')->after('price');
            $table->char('man_mrp', 10,2)->default(0)->after('is_man');
            $table->char('man_price', 10,2)->default(0)->after('man_mrp');
            $table->char('is_woman', 1)->default(1)->comment('1 = No, 2 = Yes')->after('man_price');
            $table->char('woman_mrp', 10,2)->default(0)->after('is_woman');
            $table->char('woman_price', 10,2)->default(0)->after('woman_mrp');
            $table->char('is_kids', 1)->default(1)->comment('1 = No, 2 = Yes')->after('woman_price');
            $table->char('kids_mrp', 10,2)->default(0)->after('is_kids');
            $table->char('kids_price', 10,2)->default(0)->after('kids_mrp');
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
