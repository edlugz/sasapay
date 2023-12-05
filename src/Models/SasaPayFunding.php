<?php

// File: packages/Edlugz/SasPay/src/Models/SasaPayFunding.php

namespace EdLugz\SasaPay\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SasaPayFunding extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'fund_reference',
        'network_code',
        'mobile_number',
        'receiver_account_number',
        'amount',
        'currency_code',
        'request_status',
        'response_code',
        'message',
        'payment_gateway',
        'checkout_request_id',
        'merchant_reference',
        'customer_message',
        'process_checkout_request_id',
        'verification_code',
        'process_status',
        'process_response_code',
        'process_message',
        'process_result_checkout_request_id',
        'payment_request_id',
        'result_code',
        'result_description',
        'source_channel',
        'bill_ref_number',
        'transaction_date',
        'transaction_code',
        'third_party_trans_id',
    ];
}
