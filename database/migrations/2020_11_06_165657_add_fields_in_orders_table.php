<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('outstanding_amount');
            $table->string('payment_request_id',256)->after('payment_id')->nullable();
            $table->bigInteger('customer_address_id')->after('vendor_id')->nullable();
            $table->char('order_status',1)->after('payment_status')->default('1')->comment('1 = New, 2 = Accepted,3 = Rescheduled , 4=Completed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
