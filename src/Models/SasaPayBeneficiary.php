<?php

// File: packages/Edlugz/SasPay/src/Models/SasaPayBeneficiary.php

namespace Edlugz\SasaPay\Models;

use Illuminate\Database\Eloquent\Model;

class SasaPayBeneficiary extends Model
{
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'director_name',
        'director_id_number',
        'director_mobile_number',
        'director_kra_pin',
        'business_name',
        'bill_number',
        'mobile_number',
        'country_code',
        'sub_region',
        'industry',
        'sub_industry',
        'bank_code',
        'bank_account_number',
        'business_type',
        'registration_number',
        'kra_pin',
        'document_type',
        'document_number',
        'description',
        'product_type',
        'request_status',
        'response_code',
        'message',
        'otp',
        'request_id',
        'confirmation_status',
        'confirmation_message',
        'confirmation_response_code',
        'account_number',
        'account_status',
    ];
}
