<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_setting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('address');
            $table->string('phone',256);
            $table->string('gst',256);
            $table->string('email',256);
            $table->text('note1');
            $table->text('note2');
            $table->text('note3');
            $table->string('image',256);
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
        Schema::dropIfExists('invoice_settings');
    }
}
