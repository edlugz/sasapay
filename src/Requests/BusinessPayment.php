<?php

namespace EdLugz\SasaPay\Requests;

use Edlugz\SasaPay\Models\SasaPayTransaction;
use EdLugz\SasaPay\SasaPayClient;
use Illuminate\Support\Str;

class BusinessPayment extends SasaPayClient
{
    /**
     * customers end point on Sasapay API.
     *
     * @var string
     */
    protected $endPoint = 'payments/pay-bills/';

    /**
     * The merchant code assigned for the application on Sasapay API.
     *
     * @var string
     */
    protected $merchantCode;

    /**
     * The URL where Sasapay Transaction Status API will send result of the
     * transaction.
     *
     * @var string
     */
    protected $resultURL;

    /**
     * BusinessPayment constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->merchantCode = config('sasapay.merchant_code');

        $this->resultURL = $this->setUrl(config('sasapay.result_url.business_payment'));
    }

    /**
     * Transfer funds to mobile wallets or bank accounts.
     *
     * @param string merchantCode
     * @param string transactionReference
     * @param string currencyCode
     * @param string amount
     * @param string senderAccountNumber
     * @param string receiverMerchantCode
     * @param string accountReference
     * @param string transactionFee
     * @param string billerType
     * @param string networkCode
     * @param string callbackUrl
     * @param string reason
     */
    protected function lipa($amount, $senderAccountNumber, $receiverMerchantCode, $accountReference, $transactionFee = 0, $billerType, $networkCode, $reason)
    {
        $transactionRef = (string) Str::uuid();

        $payment = SasaPayTransaction::create([
            'transaction_reference'  => $transactionRef,
            'currency_code'          => 'KES',
            'amount'                 => $amount,
            'sender_account_number'  => $senderNumber,
            'receiver_merchant_code' => $receiverMerchantCode,
            'account_reference'      => $accountReference,
            'transaction_fee'        => $transactionFee,
            'biller_type'            => $billerType,
            'network_code'           => $networkCode,
            'reason'                 => $reason,
        ]);

        $id = $payment->id;

        $parameters = [
            'merchantCode'         => $this->merchantCode,
            'transactionReference' => $transactionRef,
            'currencyCode'         => 'KES',
            'amount'               => $amount,
            'senderAccountNumber'  => $senderAccountNumber,
            'receiverMerchantCode' => $receiverMerchantCode,
            'accountReference'     => $accountReference,
            'transactionFee'       => $transactionFee,
            'billerType'           => $billerType,
            'networkCode'          => $networkCode,
            'callbackUrl'          => $this->resultURL,
            'reason'               => $reason,
        ];

        $response = $this->call($this->endPoint, ['json' => $parameters]);

        if ($response->status == true) {
            $update = SasaPayTransaction::where('id', $id)
                    ->update([
                        'request_status'      => $response->status,
                        'response_code'       => $response->responseCode,
                        'message'             => $response->message,
                        'checkout_request_id' => $response->checkoutRequestID,
                        'merchant_reference'  => $response->transactionCharges,
                    ]);
        } else {
            $update = SasaPayTransaction::where('id', $id)
                    ->update([
                        'request_status' => $response->status,
                        'response_code'  => $response->responseCode,
                        'message'        => $response->message,
                    ]);
        }

        return $response;
    }

    /**
     * Process results for send money function.
     *
     * @param jsonObject data
     */
    protected function businessPaymentResult($data)
    {
        $data = json_decode($data);

        SasaPayTransaction::where('checkout_request_id', $data->CheckoutRequestID)
        ->update([
            'result_code'                    => $data->ResultCode,
            'result_desc'                    => $data->ResultDesc,
            'merchant_account_balance'       => $data->MerchantAccountBalance,
            'merchant_transaction_reference' => $data->MerchantTransactionReference,
            'transaction_date'               => $data->TransactionDate,
            'recipient_account_number'       => $data->RecipientAccountNumber,
            'destination_channel'            => $data->DestinationChannel,
            'source_channel'                 => $data->SourceChannel,
            'sasapay_transaction_id'         => $data->SasaPayTransactionID,
            'recipient_name'                 => $data->RecipientName,
            'sender_account_number'          => $data->SenderAccountNumber,
        ]);
    }
}
