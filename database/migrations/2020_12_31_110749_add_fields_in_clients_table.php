<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('address_proof',255)->nullable()->after('profile_status');
            $table->string('address_proof_file',255)->nullable()->after('address_proof');
            $table->string('photo_proof',255)->nullable()->after('address_proof_file');
            $table->string('photo_proof_file',255)->nullable()->after('photo_proof');
            $table->string('business_proof',255)->nullable()->after('photo_proof_file');
            $table->string('business_proof_file',255)->nullable()->after('business_proof');
            $table->char('verify_status',1)->default(1)->comment('1 = pending, 2 = verified, 3 = rejected')->after('business_proof_file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            //
        });
    }
}
