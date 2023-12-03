<?php

namespace EdLugz\SasaPay\Requests;

use Edlugz\SasaPay\Models\SasaPayFunding;
use EdLugz\SasaPay\SasaPayClient;
use Illuminate\Support\Str;

class Fund extends SasaPayClient
{
    /**
     * customers end point on Sasapay API.
     *
     * @var string
     */
    protected $requestEndPoint = 'payments/request-payment/';

    /**
     * Individual customer details end point on Sasapay API.
     *
     * @var string
     */
    protected $processEndPoint = 'payments/process-payment/';

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
     * Fund constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->merchantCode = config('sasapay.merchant_code');

        $this->resultURL = config('sasapay.result_url.funding');
    }

    /**
     * Send payment request to top up wallet (STK or via Sasapay App).
     *
     * @param string merchantCode
     * @param string merchantReference
     * @param string networkCode
     * @param string mobileNumber
     * @param string receiverAccountNumber
     * @param string amount
     * @param string transactionFee
     * @param string currencyCode
     * @param string transactionDesc
     * @param string amount
     * @param string callbackUrl
     */
    protected function fundRequest($networkCode, $mobileNumber, $receiverAccountNumber, $amount, $transactionDesc)
    {
        $fundRef = (string) Str::uuid();

        $funding = SasaPayFunding::create([
            'fund_reference'          => $fundRef,
            'network_code'            => $networkCode,
            'mobile_number'           => $mobileNumber,
            'receiver_account_number' => $receiverAccountNumber,
            'amount'                  => $amount,
            'currency_code'           => 'KES',
        ]);

        $id = $funding->id;

        $parameters = [
            'merchantCode'          => $this->merchantCode,
            'merchantReference'     => $fundRef,
            'networkCode'           => $networkCode,
            'mobileNumber'          => $mobileNumber,
            'receiverAccountNumber' => $receiverAccountNumber,
            'amount'                => $amount,
            'transactionFee'        => '0',
            'currencyCode'          => 'KES',
            'transactionDesc'       => $transactionDesc,
            'callbackUrl'           => $this->resultURL,
        ];

        $response = $this->call($this->requestEndPoint, ['json' => $parameters]);

        if ($response->status == true) {
            $update = SasaPayFunding::where('id', $id)
                    ->update([
                        'request_status'      => $response->status,
                        'response_code'       => $response->responseCode,
                        'message'             => $response->message,
                        'payment_gateway'     => $response->paymentGateway,
                        'checkout_request_id' => $response->checkoutRequestID,
                        'merchant_reference'  => $response->merchantReference,
                        'customer_message'    => $response->customerMessage,
                    ]);
        } else {
            $update = SasaPayFunding::where('id', $id)
                    ->update([
                        'request_status' => $response->status,
                        'response_code'  => $response->responseCode,
                        'message'        => $response->message,
                    ]);
        }

        return $response;
    }

    /**
     * Process a fund request via Sasapay channel.
     *
     * @param string merchantCode
     * @param string receiverAccountNumber
     * @param string checkoutRequestId
     * @param string verificationCode
     */
    protected function processRequest($receiverAccountNumber, $checkoutRequestId, $verificationCode)
    {
        $parameters = [
            'merchantCode'          => $this->merchantCode,
            'receiverAccountNumber' => $receiverAccountNumber,
            'checkoutRequestId'     => $checkoutRequestId,
            'verificationCode'      => $verificationCode,
        ];

        return $this->call($this->processEndPoint, ['json' => $parameters]);
    }

    /**
     * Process a fund results.
     *
     * @param jsonObject data
     */
    protected function fundingResult($data)
    {
        $data = json_decode($data);

        SasaPayFunding::where('checkout_request_id', $data->CheckoutRequestID)
        ->update([
            'payment_request_id'   => $data->PaymentRequestID,
            'result_code'          => $data->ResultCode,
            'result_description'   => $data->ResultDesc,
            'source_channel'       => $data->SourceChannel,
            'bill_ref_number'      => $data->BillRefNumber,
            'transaction_date'     => $data->TransactionDate,
            'transaction_code'     => $data->TransactionCode,
            'third_party_trans_id' => $data->ThirdPartyTransID,
        ]);
    }
}
