<?php

namespace EdLugz\SasaPay\Requests;

use EdLugz\SasaPay\Models\SasaPayFunding;
use EdLugz\SasaPay\SasaPayClient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Fund extends SasaPayClient
{
    /**
     * customers end point on Sasapay API.
     *
     * @var string
     */
    protected string $requestEndPoint = 'payments/request-payment/';

    /**
     * Individual customer details end point on Sasapay API.
     *
     * @var string
     */
    protected string $processEndPoint = 'payments/process-payment/';

    /**
     * The URL where Sasapay Transaction Status API will send result of the
     * transaction.
     *
     * @var string
     */
    protected string $resultURL;

    /**
     * Fund constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->resultURL = config('sasapay.result_url.funding');
    }

    /**
     * Send payment request to top up wallet (STK or via Sasapay App).
     *
     * @param string      $mobileNumber
     * @param string      $receiverAccountNumber
     * @param string      $amount
     * @param string      $transactionDesc
     * @param string|null $fundReference
     * @param string      $networkCode
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     *
     * @return mixed
     */
    public function fundRequest(
        string $mobileNumber,
        string $receiverAccountNumber,
        string $amount,
        string $transactionDesc,
        string $fundReference = null,
        string $networkCode = '63902'
    ): mixed {
        $fundRef = empty($fundReference) ? (string) Str::uuid() : $fundReference;

        $funding = SasaPayFunding::create([
            'fund_reference'          => $fundRef,
            'network_code'            => $networkCode,
            'mobile_number'           => $mobileNumber,
            'receiver_account_number' => $receiverAccountNumber,
            'amount'                  => $amount,
            'currency_code'           => 'KES',
        ]);

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

        $data = [
            'request_status'      => $response->status,
            'response_code'       => $response->responseCode,
            'message'             => $response->message,
        ];

        if ($response->status) {
            $data = array_merge($data, [
                'payment_gateway'     => $response->paymentGateway,
                'checkout_request_id' => $response->checkoutRequestID,
                'merchant_reference'  => $response->merchantRequestID,
                'customer_message'    => $response->customerMessage,
            ]);
        }

        $funding->update($data);

        return $response;
    }

    /**
     * Process a fund request via Sasapay channel.
     *
     * @param $receiverAccountNumber
     * @param $checkoutRequestId
     * @param $verificationCode
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     *
     * @return mixed
     */
    public function processRequest($receiverAccountNumber, $checkoutRequestId, $verificationCode): mixed
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
     * @param \Illuminate\Http\Request $request
     * @return \EdLugz\SasaPay\Models\SasaPayFunding
     */
    public function fundingResult(Request $request) : SasaPayFunding
    {

        $funding = SasaPayFunding::where('checkout_request_id', $request->input('CheckoutRequestID'))->first();

        $funding->update([
            'payment_request_id'   => $request->input('PaymentRequestID'),
            'result_code'          => $request->input('ResultCode'),
            'result_description'   => $request->input('ResultDesc'),
            'source_channel'       => $request->input('SourceChannel'),
            'bill_ref_number'      => $request->input('BillRefNumber'),
            'transaction_date'     => $request->input('TransactionDate'),
            'transaction_code'     => $request->input('TransactionCode'),
            'third_party_trans_id' => $request->input('ThirdPartyTransID'),
        ]);

        return $funding;
    }
}
