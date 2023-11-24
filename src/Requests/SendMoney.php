<?php

namespace EdLugz\SasaPay\Requests;

use EdLugz\SasaPay\SasaPayClient;
use Edlugz\SasaPay\Models\SasaPayTransaction;
use Illuminate\Support\Str;

class SendMoney extends SasaPayClient
{
    /**
     * customers end point on Sasapay API.
     *
     * @var string
     */
    protected $endPoint = 'payments/send-money/';
	
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
     * SendMoney constructor.
     */
    public function __construct()
    {
        parent::__construct();
		
        $this->merchantCode = config('sasapay.merchant_code');     
		
		$this->resultURL = $this->setUrl(config('sasapay.result_url.send_money'));
    }

    /**
     * Transfer funds to mobile wallets or bank accounts
     
      	@param string merchantCode
      	@param string transactionReference
      	@param string currencyCode
      	@param string TransactionDesc
      	@param string senderNumber
      	@param string amount
      	@param string reason
      	@param string transactionFee
      	@param string channel
      	@param string receiverNumber
      	@param string callbackUrl
		
     */
    protected function transfer($transactionDesc, $senderNumber, $amount, $reason, $transactionFee = 0, $channel, $receiverNumber)
    {
		$transactionRef = (string) Str::uuid();
		
		$payment = SasaPayTransaction::create([
			'transaction_reference' => $transactionRef,
            'currency_code' => 'KES',
            'transaction_desc' => $transactionDesc,
            'sender_number' => $senderNumber,
            'amount' => $amount,
            'reason' => $reason,
            'transaction_fee' => $transactionFee,
            'channel' => $channel,
            'receiver_number' => $receiverNumber,
		]);
		
		$id = $payment->id;
		
        $parameters = [
            'merchantCode' => $this->merchantCode,
            'transactionReference' => $transactionRef,
            'currencyCode' => $currencyCode,
            'TransactionDesc' => $transactionDesc,
            'senderNumber' => $senderNumber,
            'amount' => $amount,
            'reason' => $reason,
            'transactionFee' => $transactionFee,
            'channel' => $channel,
            'receiverNumber' => $receiverNumber,
            'callbackUrl' => $this->resultURL,
        ];

		$response = $this->call($this->endPoint, ['json' => $parameters]);
		
		if($response->status == true){
			$update = SasaPayTransaction::where('id', $id)
					->update([
						'request_status' => $response->status,
						'response_code' => $response->responseCode,
						'message' => $response->message,
						'checkout_request_id' => $response->checkoutRequestID,
						'transaction_charges' => $response->merchantReference,
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
     * Process results for send money function
     
      	@param jsonObject data
		
     */
    protected function sendMoneyResult($data)
    {
		$data = json_decode($data);
		
		SasaPayTransaction::where('checkout_request_id', $data->CheckoutRequestID)
		->update([
			"result_code" => $data->ResultCode,
			"result_desc" => $data->ResultDesc,
			"merchant_account_balance" => $data->MerchantAccountBalance,
			"merchant_transaction_reference" => $data->MerchantTransactionReference,
			"transaction_date" => $data->TransactionDate,
			"recipient_account_number" => $data->RecipientAccountNumber,
			"destination_channel" => $data->DestinationChannel,
			"source_channel" => $data->SourceChannel,
			"sasapay_transaction_id" => $data->SasaPayTransactionID,
			"recipient_name" => $data->RecipientName,
			"sender_account_number" => $data->SenderAccountNumber,
		]);
		
    }	
	
}