<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJobIdToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('service_time')->nullable()->after('order_status');
            $table->char('refund_request',1)->default(1)->after('service_time')->comment('1 = no, 2 = Yes, 3 = Done');
            $table->char('vendor_cancel_status',1)->default(1)->after('service_time')->comment('1 = no, 2 = Yes');
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
