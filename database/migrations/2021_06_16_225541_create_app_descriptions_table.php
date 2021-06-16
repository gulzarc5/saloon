<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_descriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('type',1)->nullable()->comment('1 =userapp ,2=vendorapp');
            $table->longText('about_us')->nullable();
            $table->longText('refund_cancellation')->nullable();
            $table->longText('disclaimers')->nullable();
            $table->longText('privacy_policy')->nullable(); 
            $table->longText('tc')->nullable(); 
            $table->longText('faq')->nullable(); 
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
        Schema::dropIfExists('app_descriptions');
    }
}
