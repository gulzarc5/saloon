<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_commissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('from_amount',10, 2)->default(0);
            $table->double('to_amount',10, 2)->default(0);
            $table->double('charge_amount',10, 2)->default(0);
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
        Schema::dropIfExists('admin_commissions');
    }
}
