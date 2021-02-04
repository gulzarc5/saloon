<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('wallet_id');
            $table->char('transaction_type',1)->default(1)->comment('1 = cr,2 = dr');
            $table->double('amount', 10, 2)->default(0);
            $table->double('total_amount', 10, 2)->default(0);
            $table->string('comment','500')->nullable();
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
        Schema::dropIfExists('wallet_history');
    }
}
