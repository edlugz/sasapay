<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sasapay_funding', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('fund_reference')->nullable();
            $table->string('network_code')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('receiver_account_number')->nullable();
            $table->string('amount')->nullable();
            $table->string('currency_code')->nullable();
            $table->string('request_status')->nullable();
            $table->string('response_code')->nullable();
            $table->string('message')->nullable();
            $table->string('payment_gateway')->nullable();
            $table->string('checkout_request_id')->nullable();
            $table->string('merchant_reference')->nullable();
            $table->string('customer_message')->nullable();
            $table->string('process_checkout_request_id')->nullable();
            $table->string('verification_code')->nullable();
            $table->string('process_status')->nullable();
            $table->string('process_response_code')->nullable();
            $table->string('process_message')->nullable();
            $table->string('process_result_checkout_request_id')->nullable();
            $table->string('payment_request_id')->nullable();
            $table->string('result_code')->nullable();
            $table->string('result_description')->nullable();
            $table->string('source_channel')->nullable();
            $table->string('bill_ref_number')->nullable();
            $table->string('transaction_date')->nullable();
            $table->string('transaction_code')->nullable();
            $table->string('third_party_trans_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sasapay_funding');
    }
};
