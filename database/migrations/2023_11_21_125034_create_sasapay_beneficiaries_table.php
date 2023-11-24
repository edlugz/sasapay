<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sasapay_beneficiaries', function (Blueprint $table) {
            $table->engine = 'InnoDB';

			$table->increments('id');
			$table->string('first_name')->nullable();
			$table->string('middle_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('email')->nullable();
			$table->string('director_name')->nullable();
			$table->string('director_id_number')->nullable();
			$table->string('director_mobile_number')->nullable();
			$table->string('director_kra_pin')->nullable();
			$table->string('business_name')->nullable();
			$table->string('bill_number')->nullable();
			$table->string('mobile_number')->nullable();
			$table->string('country_code')->nullable();
			$table->string('sub_region')->nullable();
			$table->string('industry')->nullable();
			$table->string('sub_industry')->nullable();
			$table->string('bank_code')->nullable();
			$table->string('bank_account_number')->nullable();
			$table->string('business_type')->nullable();
			$table->string('registration_number')->nullable();
			$table->string('kra_pin')->nullable();
			$table->string('document_type')->nullable();
			$table->string('document_number')->nullable();
			$table->string('description')->nullable();
			$table->string('product_type')->nullable();
			$table->string('request_status')->nullable();
			$table->string('response_code')->nullable();
			$table->string('message')->nullable();
			$table->string('otp')->nullable();
			$table->string('request_id')->nullable();
			$table->string('confirmation_request_id')->nullable();
			$table->string('confirmation_status')->nullable();
			$table->string('confirmation_message')->nullable();
			$table->string('confirmation_response_code')->nullable();
			$table->string('account_number')->nullable();
			$table->string('account_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sasapay_beneficiaries');
    }
};
