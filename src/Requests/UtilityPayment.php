<?php

namespace EdLugz\SasaPay\Requests;

use EdLugz\SasaPay\Exceptions\SasaPayRequestException;
use EdLugz\SasaPay\Models\SasaPayTransaction;
use EdLugz\SasaPay\SasaPayClient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UtilityPayment extends SasaPayClient
{
    /**
     * pay utilities end point on Sasapay API.
     *
     * @var string
     */
    protected string $payEndPoint = 'utilities/';

    /**
     * query bill end point on Sasapay API.
     *
     * @var string
     */
    protected string $queryEndPoint = 'utilities/bill-query';

    /**
     * The URL where Sasapay Transaction Status API will send result of the
     * transaction.
     *
     * @var string
     */
    protected string $resultURL;

    /**
     * UtilityPayment constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->resultURL = $this->setUrl(config('sasapay.result_url.business_payment'));
    }

    /**
     * pay utilities including airtime purchase, electricity payments, water payment and tv payments.
     *
     * @param       $amount
     * @param       $payerAccountNumber
     * @param       $accountNumber
     * @param       $accountReference
     * @param int   $transactionFee
     * @param array $customFieldsKeyValue
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     *
     * @return mixed
     */
    public function payUtility(
        $amount,
        $payerAccountNumber,
        $accountNumber,
        $accountReference,
        $transactionFee = 0,
        array $customFieldsKeyValue = [],
    ): SasaPayTransaction {
        $transactionRef = (string) Str::uuid();

        $payment = SasaPayTransaction::create(array_merge([
            'transaction_reference' => $transactionRef,
            'currency_code'         => 'KES',
            'amount'                => $amount,
            'payer_account_number'  => $payerAccountNumber,
            'account_number'        => $accountNumber,
            'account_reference'     => $accountReference,
            'transaction_fee'       => $transactionFee,
        ], $customFieldsKeyValue));

        $parameters = [
            'merchantCode'         => $this->merchantCode,
            'transactionReference' => $transactionRef,
            'currencyCode'         => 'KES',
            'amount'               => $amount,
            'payerAccountNumber'   => $payerAccountNumber,
            'accountNumber'        => $accountNumber,
            'accountReference'     => $accountReference,
            'transactionFee'       => $transactionFee,
            'callbackUrl'          => $this->resultURL,
        ];

        try {
            $response = $this->call($this->payEndPoint, ['json' => $parameters]);
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
     * Query Bill Payments before paying - DSTV, GOTV, NAIROBI WATER.
     *
     * @param $serviceCode
     * @param $customerMobile
     * @param $accountNumber
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     *
     * @return mixed
     */
    public function billQuery($serviceCode, $customerMobile, $accountNumber): mixed
    {
        $parameters = [
            'merchantCode'   => $this->merchantCode,
            'serviceCode'    => $serviceCode,
            'customerMobile' => $customerMobile,
            'accountNumber'  => $accountNumber,
        ];

        return $this->call($this->queryEndPoint, ['json' => $parameters]);
    }

    /**
     * Process results for pay utilities function.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \EdLugz\SasaPay\Models\SasaPayTransaction
     */
    public function utilityResult(Request $request): SasaPayTransaction
    {
        $transaction = SasaPayTransaction::where('checkout_request_id', $request->input('CheckoutRequestID'))->first();

        $transaction->update([
            'result_code'           => $request->input('ResultCode'),
            'result_desc'           => $request->input('ResultDesc'),
            'merchant_reference'    => $request->input('MerchantTransactionReference'),
            'contact_number'        => $request->input('ContactNumber'),
            'sender_account_number' => $request->input('SenderAccountNumber'),
            'account_number'        => $request->input('AccountNumber'),
            'service_code'          => $request->input('ServiceCode'),
            'pin'                   => $request->input('Pin'),
            'network_code'          => $request->input('NetworkCode'),
            'transaction_date'      => $request->input('TransTime'),
            'units'                 => $request->input('Units'),
        ]);

        return $transaction;
    }
}
