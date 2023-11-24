<?php

namespace EdLugz\SasaPay\Requests;

use EdLugz\SasaPay\SasaPayClient;
use Edlugz\SasaPay\Models\SasaPayTransaction;
use Illuminate\Support\Str;

class UtilityPayment extends SasaPayClient
{
    /**
     * pay utilities end point on Sasapay API.
     *
     * @var string
     */
    protected $payEndPoint = 'utilities/';
	
	/**
     * query bill end point on Sasapay API.
     *
     * @var string
     */
    protected $queryEndPoint = 'utilities/bill-query';
	
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
     * UtilityPayment constructor.
     */
    public function __construct()
    {
        parent::__construct();
		
        $this->merchantCode = config('sasapay.merchant_code');     
		
		$this->resultURL = $this->setUrl(config('sasapay.result_url.business_payment'));
    }

    /**
     * pay utilities including airtime purchase, electricity payments, water payment and tv payments.
     
      	@param string merchantCode
      	@param string transactionReference
      	@param string currencyCode
      	@param string amount
      	@param string payerAccountNumber
      	@param string accountNumber
      	@param string transactionFee
      	@param string callbackUrl
		
     */
    protected function payUtility($amount, $payerAccountNumber, $accountNumber, $transactionFee = 0)
    {
		$transactionRef = (string) Str::uuid();
		
		$payment = SasaPayTransaction::create([
			'transaction_reference' => $transactionRef,
            'currency_code' => 'KES',
            'amount' => $amount,
            'payer_account_number' => $payerAccountNumber,
            'account_number' => $accountNumber,
            'account_reference' => $accountReference,
            'transaction_fee' => $transactionFee
		]);
		
		$id = $payment->id;
		
        $parameters = [
            "merchantCode" => $this->merchantCode,
            "transactionReference" => $transactionRef,
            "currencyCode" => "KES",
            "amount" => $amount,
            "payerAccountNumber" => $payerAccountNumber,
            "accountNumber" => $accountNumber,
            "accountReference" => $accountReference,
            "transactionFee" => $transactionFee,
            "callbackUrl" => $this->resultURL,
        ];

        $response = $this->call($this->payEndPoint, ['json' => $parameters]);
		
		if($response->status == true){
			$update = SasaPayTransaction::where('id', $id)
					->update([
						'request_status' => $response->status,
						'response_code' => $response->responseCode,
						'message' => $response->message,
						'checkout_request_id' => $response->checkoutRequestID,
						'merchant_reference' => $response->transactionCharges,
					]);
		} else {
			$update = SasaPayTransaction::where('id', $id)
					->update([
						'request_status' => $response->status,
						'response_code' => $response->responseCode,
						'message' => $response->message
					]);
		}
		
		return $response;
	}	
	
    /**
     * Query Bill Payments before paying - DSTV, GOTV, NAIROBI WATER
     
      	@param string merchantCode
      	@param string serviceCode
      	@param string customerMobile
      	@param string accountNumber
		
     */
    protected function billQuery($serviceCode, $customerMobile, $accountNumber)
    {
        $parameters = [
            "merchantCode" => $this->merchantCode,
            "serviceCode" => $serviceCode,
            "customerMobile" => $customerMobile,
            "accountNumber" => $accountNumber
        ];

        return $this->call($this->queryEndPoint, ['json' => $parameters]);
    }	
	
    /**
     * Process results for pay utilites function
     
      	@param jsonObject data
		
     */
    protected function utilityResult($data)
    {
		$data = json_decode($data);
		
		SasaPayTransaction::where('checkout_request_id', $data->CheckoutRequestID)
		->update([
			"result_code" => $data->ResultCode,
			"result_desc" => $data->ResultDesc,
			"merchant_reference" => $data->MerchantTransactionReference,
			"contact_number" => $data->ContactNumber,
			"sender_account_number" => $data->SenderAccountNumber,
			"account_number" => $data->AccountNumber,
			"service_code" => $data->ServiceCode,
			"pin" => $data->Pin,
			"network_code" => $data->NetworkCode,
			"transaction_date" => $data->TransTime,
			"units" => $data->Units,
		]);
		
    }	
	
}