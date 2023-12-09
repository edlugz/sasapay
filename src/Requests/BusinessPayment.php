<?php

namespace EdLugz\SasaPay\Requests;

use EdLugz\SasaPay\Exceptions\SasaPayRequestException;
use EdLugz\SasaPay\Models\SasaPayTransaction;
use EdLugz\SasaPay\SasaPayClient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BusinessPayment extends SasaPayClient
{
    /**
     * customers end point on Sasapay API.
     *
     * @var string
     */
    protected string $endPoint = 'payments/pay-bills/';

    /**
     * The URL where Sasapay Transaction Status API will send result of the
     * transaction.
     *
     * @var string
     */
    protected string $resultURL;

    /**
     * BusinessPayment constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->resultURL = $this->setUrl(config('sasapay.result_url.business_payment'));
    }

    /**
     * Transfer funds to mobile wallets or bank accounts.
     *
     *
     * @param int    $amount
     * @param string $senderAccountNumber
     * @param string $receiverMerchantCode
     * @param string $accountReference
     * @param string $billerType
     * @param string $networkCode
     * @param string $reason
     * @param int    $transactionFee
     * @param array  $customFieldsKeyValue Custom Database Columns added to your sasa_pay_transaction published migrations
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     *
     * @return mixed
     */
    public function lipa(
        int $amount,
        string $senderAccountNumber,
        string $receiverMerchantCode,
        string $accountReference,
        string $billerType,
        string $networkCode,
        string $reason,
        int $transactionFee = 0,
        array $customFieldsKeyValue = [],
    ): SasaPayTransaction {
        $transactionRef = (string) Str::uuid();

        $payment = SasaPayTransaction::create(array_merge([
            'transaction_reference'  => $transactionRef,
            'currency_code'          => 'KES',
            'amount'                 => $amount,
            'sender_account_number'  => $senderAccountNumber,
            'receiver_merchant_code' => $receiverMerchantCode,
            'account_reference'      => $accountReference,
            'transaction_fee'        => $transactionFee,
            'biller_type'            => $billerType,
            'network_code'           => $networkCode,
            'reason'                 => $reason,
        ], $customFieldsKeyValue));

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

        try {
            $response = $this->call($this->endPoint, ['json' => $parameters]);
        } catch(SasaPayRequestException $e) {
            $response = json_encode([
                'status'         => false,
                'responseCode'   => $e->getCode(),
                'message'        => $e->getMessage(),
            ]);
        }

        $data = [
            'request_status'      => $response->status,
            'response_code'       => $response->responseCode,
            'message'             => $response->message,
        ];

        if ($response->status) {
            $data = array_merge($data, [
                'checkout_request_id' => $response->checkoutRequestId,
                'merchant_reference'  => $response->merchantReference,
            ]);
        }

        $payment->update($data);

        return $payment;
    }

    /**
     * Process results for send money function.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \EdLugz\SasaPay\Models\SasaPayTransaction
     */
    public function businessPaymentResult(Request $request): SasaPayTransaction
    {
        $transaction = SasaPayTransaction::where('checkout_request_id', '=', $request->input('CheckoutRequestID'))->first();
        $transaction->update([
            'result_code'                    => $request->input('ResultCode'),
            'result_desc'                    => $request->input('ResultDesc'),
            'merchant_account_balance'       => $request->input('MerchantAccountBalance'),
            'merchant_transaction_reference' => $request->input('MerchantTransactionReference'),
            'transaction_date'               => $request->input('TransactionDate'),
            'recipient_account_number'       => $request->input('RecipientAccountNumber'),
            'destination_channel'            => $request->input('DestinationChannel'),
            'source_channel'                 => $request->input('SourceChannel'),
            'sasapay_transaction_id'         => $request->input('SasaPayTransactionID'),
            'recipient_name'                 => $request->input('RecipientName'),
            'sender_account_number'          => $request->input('SenderAccountNumber'),
        ]);

        return $transaction;
    }
}
