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
        Schema::create('sasapay_transactions', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('transaction_reference')->nullable();
            $table->string('service_code')->nullable();
            $table->string('payer_account_number')->nullable();
            $table->string('account_number')->nullable();
            $table->string('currency_code')->nullable();
            $table->string('transaction_desc')->nullable();
            $table->string('sender_number')->nullable();
            $table->string('receiver_merchant_code')->nullable();
            $table->string('account_reference')->nullable();
            $table->string('biller_type')->nullable();
            $table->string('network_code')->nullable();
            $table->string('reason')->nullable();
            $table->string('amount')->nullable();
            $table->string('transaction_fee')->nullable();
            $table->string('channel')->nullable();
            $table->string('receiver_number')->nullable();
            $table->string('request_status')->nullable();
            $table->string('response_code')->nullable();
            $table->string('checkout_request_id')->nullable();
            $table->string('transaction_charges')->nullable();
            $table->string('merchant_reference')->nullable();
            $table->string('message')->nullable();
            $table->string('result_code')->nullable();
            $table->string('result_desc')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('pin')->nullable();
            $table->string('units')->nullable();
            $table->string('merchant_account_balance')->nullable();
            $table->string('merchant_transaction_reference')->nullable();
            $table->string('transaction_date')->nullable();
            $table->string('recipient_account_number')->nullable();
            $table->string('destination_channel')->nullable();
            $table->string('source_channel')->nullable();
            $table->string('sasapay_transaction_id')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('sender_account_number')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sasapay_transactions');
    }
};
