<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsSubCategoryToJobCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_categories', function (Blueprint $table) {
            $table->tinyInteger('is_subcategory')->after('kids')->comment('1=No,2=Yes')->default(1);
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
            //
        });
    }
}
