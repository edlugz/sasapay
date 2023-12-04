<?php

// File: packages/Edlugz/SasPay/src/Models/SasaPayTransaction.php

namespace Edlugz\SasaPay\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SasaPayTransaction extends Model
{
	use SoftDeletes;
	
    protected $fillable = [
        'transaction_reference',
        'service_code',
        'payer_account_number',
        'account_number',
        'currency_code',
        'transaction_desc',
        'sender_number',
        'receiver_merchant_code',
        'account_reference',
        'biller_type',
        'network_code',
        'reason',
        'amount',
        'transaction_fee',
        'channel',
        'receiver_number',
        'request_status',
        'response_code',
        'checkout_request_id',
        'transaction_charges',
        'merchant_reference',
        'message',
        'result_code',
        'result_desc',
        'contact_number',
        'pin',
        'units',
        'merchant_account_balance',
        'merchant_transaction_reference',
        'transaction_date',
        'recipient_account_number',
        'destination_channel',
        'source_channel',
        'sasapay_transaction_id',
        'recipient_name',
        'sender_account_number',
    ];
}
