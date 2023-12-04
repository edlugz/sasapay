<?php

namespace EdLugz\SasaPay\Requests;

use Edlugz\SasaPay\Models\SasaPayTransaction;
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
    protected function lipa(
        $amount,
        $senderAccountNumber,
        $receiverMerchantCode,
        $accountReference,
        $billerType,
        $networkCode,
        $reason,
        $transactionFee = 0
    ) {
        $transactionRef = (string) Str::uuid();

        $payment = SasaPayTransaction::create([
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

        $data = [
            'request_status'      => $response->status,
            'response_code'       => $response->responseCode,
            'message'             => $response->message,
        ];

        if ($response->status) {
            $data = array_merge($data, [
                'checkout_request_id' => $response->checkoutRequestID,
                'transaction_charge'  => $response->transactionCharges,
            ]);
        }

        $payment->update($data);

        return $response;
    }

    /**
     * Process results for send money function.
     *
     * @param \Illuminate\Http\Request $request
     */
    protected function businessPaymentResult(Request $request): void
    {
        SasaPayTransaction::where('checkout_request_id', '=', $request->input('CheckoutRequestID'))
        ->update([
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
    }
}
