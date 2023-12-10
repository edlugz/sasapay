<?php

namespace EdLugz\SasaPay\Requests;

use EdLugz\SasaPay\Exceptions\SasaPayRequestException;
use EdLugz\SasaPay\Models\SasaPayTransaction;
use EdLugz\SasaPay\SasaPayClient;
use Illuminate\Http\JsonResponse;

class Transaction extends SasaPayClient
{
    /**
     * check transaction status end point on Sasapay API.
     *
     * @var string
     */
    protected string $checkEndPoint = 'transactions/status/';

    /**
     * verify transaction end point on Sasapay API.
     *
     * @var string
     */
    protected string $verifyEndPoint = 'transactions/verify/';

    /**
     * Check Transaction Status.
     *
     * @param $checkoutRequestId
     * @param $merchantTransactionReference
     * @param $transactionCode
     *
     * @return \Illuminate\Http\JsonResponse
     *@throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     *
     */
    public function check($checkoutRequestId, $merchantTransactionReference, $transactionCode): JsonResponse
    {
        $payment = SasaPayTransaction::where('checkout_request_id', $checkoutRequestId)->first();

        if ($payment) {
            $parameters = [
                'merchantCode'                 => $this->merchantCode,
                'checkoutRequestId'            => $checkoutRequestId,
                'merchantTransactionReference' => $merchantTransactionReference,
                'transactionCode'              => $transactionCode,
            ];

            try {
                $response = $this->call($this->checkEndPoint, ['json' => $parameters]);
            } catch(SasaPayRequestException $e) {
                $response = json_encode([
                    'status'         => false,
                    'responseCode'   => $e->getCode(),
                    'message'        => $e->getMessage(),
                ]);
            }

            if ($response->status) {
                $data = [
                    'result_code' => $response->data->ResultCode,
                    'result_desc' => $response->data->ResultDescription,
                    'merchant_transaction_reference',
                    'third_party_transaction_code' => $response->data->ThirdPartyTransactionCode,
                    'transaction_date'             => $response->data->TransactionDate,
                    'destination_channel'          => $response->data->DestinationChannel,
                    'source_channel'               => $response->data->SourceChannel,
                    'sasapay_transaction_id'       => $response->data->TransactionCode,
                ];

                $payment->update($data);

                return response()->json(['status' => true, 'message' => 'Payment details updated successfully.']);
            } else {
                return response()->json(['status' => false, 'message' => 'Transaction details not found.']);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'Transaction does not exist.']);
        }
    }

    /**
     * verify transaction details directly from our API.
     *
     * @param $transactionCode
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     *
     * @return mixed
     */
    public function verify($transactionCode): mixed
    {
        $parameters = [
            'merchantCode'    => $this->merchantCode,
            'transactionCode' => $transactionCode,
        ];

        return $this->call($this->verifyEndPoint, ['json' => $parameters]);
    }
}
