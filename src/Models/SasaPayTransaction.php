<?php

// File: packages/Edlugz/SasPay/src/Models/SasaPayTransaction.php

namespace EdLugz\SasaPay\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SasaPayTransaction.
 *
 * Represents a SasaPay transaction.
 *
 *
 * @property int    $id                             The unique identifier of the transaction.
 * @property string $transaction_reference          The reference number of the transaction.
 * @property string $service_code                   The code of the service associated with the transaction.
 * @property string $payer_account_number           The account number of the payer.
 * @property string $account_number                 The account number involved in the transaction.
 * @property string $currency_code                  The code of the currency used in the transaction.
 * @property string $transaction_desc               The description of the transaction.
 * @property string $sender_number                  The number of the sender.
 * @property string $receiver_merchant_code         The merchant code of the receiver, if applicable.
 * @property string $account_reference              The reference number of the account involved in the transaction.
 * @property string $biller_type                    The type of the biller associated with the transaction.
 * @property string $network_code                   The code of the network used in the transaction.
 * @property string $reason                         The reason for the transaction.
 * @property string $amount                         The amount of the transaction.
 * @property string $transaction_fee                The fee charged for the transaction.
 * @property string $channel                        The channel used for the transaction.
 * @property string $receiver_number                The number of the receiver.
 * @property string $request_status                 The status of the transaction request.
 * @property string $response_code                  The code of the response received for the transaction.
 * @property string $checkout_request_id            The ID of the checkout request associated with the transaction.
 * @property string $transaction_charges            The charges applied to the transaction.
 * @property string $merchant_reference             The reference number of the merchant associated with the transaction.
 * @property string $message                        The message associated with the transaction.
 * @property string $result_code                    The code of the result of the transaction.
 * @property string $result_desc                    The description of the result of the transaction.
 * @property string $contact_number                 The contact number associated with the transaction.
 * @property string $pin                            The PIN used for the transaction.
 * @property string $units                          The number of units involved in the transaction.
 * @property string $merchant_account_balance       The balance of the merchant's account associated with the transaction.
 * @property string $merchant_transaction_reference The reference number of the merchant transaction associated with the transaction.
 * @property string $transaction_date               The date of the transaction.
 * @property string $recipient_account_number       The account number of the recipient.
 * @property string $destination_channel            The destination channel of the transaction.
 * @property string $source_channel                 The source channel of the transaction.
 * @property string $sasapay_transaction_id         The ID of the SasaPay transaction.
 * @property string $recipient_name                 The name of the recipient.
 * @property string $sender_account_number          The account number of the sender.
 * @property string $third_party_transaction_code   The third party transaction code.
 */
class SasaPayTransaction extends Model
{
    use SoftDeletes;

    protected $guarded = [];
}
