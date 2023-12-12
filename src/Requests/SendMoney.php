<?php

namespace EdLugz\SasaPay\Requests;

use EdLugz\SasaPay\Exceptions\SasaPayRequestException;
use EdLugz\SasaPay\Models\SasaPayTransaction;
use EdLugz\SasaPay\SasaPayClient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SendMoney extends SasaPayClient
{
    /**
     * customers end point on Sasapay API.
     *
     * @var string
     */
    protected string $endPoint = 'payments/send-money/';

    /**
     * The URL where Sasapay Transaction Status API will send result of the
     * transaction.
     *
     * @var string
     */
    protected string $resultURL;

    /**
     * SendMoney constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->resultURL = $this->setUrl(config('sasapay.result_url.send_money'));
    }

    /**
     * Transfer funds to mobile wallets or bank accounts.
     *
     * @param string $transactionDesc
     * @param string $senderNumber
     * @param int    $amount
     * @param string $reason
     * @param string $channel
     * @param string $receiverNumber
     * @param int    $transactionFee
     * @param array  $customFieldsKeyValue
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     *
     * @return mixed
     */
    private function transfer(
        string $transactionDesc,
        string $senderNumber,
        int $amount,
        string $reason,
        string $channel,
        string $receiverNumber,
        int $transactionFee = 0,
        array $customFieldsKeyValue = [],
    ): SasaPayTransaction {
        $transactionRef = (string) Str::uuid();

        /** @var SasaPayTransaction $payment */
        $payment = SasaPayTransaction::create(array_merge([
            'transaction_reference' => $transactionRef,
            'currency_code'         => 'KES',
            'transaction_desc'      => $transactionDesc,
            'sender_number'         => $senderNumber,
            'amount'                => $amount,
            'reason'                => $reason,
            'transaction_fee'       => $transactionFee,
            'channel'               => $channel,
            'receiver_number'       => $receiverNumber,
        ], $customFieldsKeyValue));

        $parameters = [
            'merchantCode'         => $this->merchantCode,
            'transactionReference' => $transactionRef,
            'currencyCode'         => 'KES',
            'TransactionDesc'      => $transactionDesc,
            'senderNumber'         => $senderNumber,
            'amount'               => $amount,
            'reason'               => $reason,
            'transactionFee'       => $transactionFee,
            'channel'              => $channel,
            'receiverNumber'       => $receiverNumber,
            'callbackUrl'          => $this->resultURL,
        ];

        try {
            $response = $this->call($this->endPoint, ['json' => $parameters]);
        } catch(SasaPayRequestException $e) {
            $response = [
                'status'         => false,
                'responseCode'   => $e->getCode(),
                'message'        => $e->getMessage(),
            ];
			
			$response = (object) $response;
        }

        $data = [
            'request_status'      => $response->status,
            'response_code'       => $response->responseCode,
            'message'             => $response->message,
        ];

        if ($response->status) {
            $data = array_merge($data, [
                'checkout_request_id'  => $response->checkoutRequestId,
                'merchant_reference'   => $response->merchantReference,
                'transaction_charges'  => $response->transactionCharges,
            ]);
        }

        $payment->update($data);

        return $payment;
    }

    /**
     * Transfer funds to mobile wallets.
     *
     * @param string $transactionDesc
     * @param string $senderNumber
     * @param int    $amount
     * @param string $reason
     * @param string $networkCode
     * @param string $receiverNumber
     * @param array  $customFieldsKeyValue
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     *
     * @return SasaPayTransaction
     */
    public function sendToMobile(
        string $transactionDesc,
        string $senderNumber,
        int $amount,
        string $reason,
        string $networkCode,
        string $receiverNumber,
        array $customFieldsKeyValue = [],
    ): SasaPayTransaction {
        return SendMoney::transfer(
            transactionDesc: $transactionDesc,
            senderNumber: $senderNumber,
            amount: $amount,
            reason: $reason,
            channel: $networkCode,
            receiverNumber: $receiverNumber,
            customFieldsKeyValue: $customFieldsKeyValue,
        );
    }

    /**
     * Transfer funds to bank accounts.
     *
     * @param string $transactionDesc
     * @param string $senderNumber
     * @param int    $amount
     * @param string $reason
     * @param string $bankCode
     * @param string $accountNumber
     * @param array  $customFieldsKeyValue
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     *
     * @return mixed
     */
    public function sendToBank(
        string $transactionDesc,
        string $senderNumber,
        int $amount,
        string $reason,
        string $bankCode,
        string $accountNumber,
        array $customFieldsKeyValue = [],
    ): SasaPayTransaction {
        return SendMoney::transfer(
            transactionDesc: $transactionDesc,
            senderNumber: $senderNumber,
            amount: $amount,
            reason: $reason,
            channel: $bankCode,
            receiverNumber: $accountNumber,
            customFieldsKeyValue: $customFieldsKeyValue
        );
    }

    /**
     * Process results for send money function.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \EdLugz\SasaPay\Models\SasaPayTransaction
     */
    public function sendMoneyResult(Request $request): SasaPayTransaction
    {
        $transaction = SasaPayTransaction::where('checkout_request_id', $request->input('CheckoutRequestID'))->first();
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
