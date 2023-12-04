<?php

namespace EdLugz\SasaPay\Requests;

use Edlugz\SasaPay\Models\SasaPayBeneficiary;
use EdLugz\SasaPay\SasaPayClient;

class PersonalOnboarding extends SasaPayClient
{
    /**
     * Personal onboarding end point on Sasapay API.
     *
     * @var string
     */
    protected string $signupEndPoint = 'personal-onboarding/';

    /**
     * Personal onboarding confirmation end point on Sasapay API.
     *
     * @var string
     */
    protected string $confirmationEndPoint = 'personal-onboarding/confirmation/';

    /**
     * Personal onboarding kyc end point on Sasapay API.
     *
     * @var string
     */
    protected string $kycEndPoint = 'personal-onboarding/kyc/';

    /**
     * The URL where Sasapay Transaction Status API will send result of the
     * transaction.
     *
     * @var string
     */
    protected string $resultURL;

    /**
     * Personal onboarding constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->merchantCode = config('sasapay.merchant_code');

        $this->resultURL = $this->setUrl(config('sasapay.onboarding_result_url.personal'));
    }

    /**
     * Onboard personal accounts.
     *
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param $countryCode
     * @param $mobileNumber
     * @param $documentNumber
     * @param $documentType
     * @param string $middleName
     * @return mixed
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     */
    protected function signUp(
        $firstName, $lastName, $email,
      $countryCode, $mobileNumber,
      $documentNumber, $documentType, string $middleName = ''
    ): mixed
    {
        $beneficiary = SasaPayBeneficiary::create([
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'email' => $email,
            'mobile_number' => $mobileNumber,
            'country_code' => $countryCode,
            'document_type' => $documentType,
            'document_number' => $documentNumber,
        ]);


        $parameters = [
            'merchantCode' => $this->merchantCode,
            'firstName' => $firstName,
            'middleName' => $middleName,
            'lastName' => $lastName,
            'countryCode' => $countryCode,
            'mobileNumber' => $mobileNumber,
            'documentNumber' => $documentNumber,
            'documentType' => $documentType,
            'email' => $email,
            'callbackUrl' => $this->resultURL,
        ];

        $response = $this->call($this->signupEndPoint, ['json' => $parameters]);

        $data = [
            'request_status' => $response->status,
            'response_code' => $response->responseCode,
            'message' => $response->message,
        ];

        if ($response->status) {
            $data = array_merge($data, [
                'request_id' => $response->requestId,
            ]);
        }

        $beneficiary->update($data);

        return $response;
    }

    /**
     * Confirm personal onboarded accounts.
     *
     * @param $id
     * @param $otp
     * @return mixed
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     */
    protected function confirm($id, $otp): mixed
    {
        $beneficiary = SasaPayBeneficiary::find($id);

        $parameters = [
            'merchantCode' => $this->merchantCode,
            'requestId' => $beneficiary->request_id,
            'otp' => $otp,
        ];

        $response = $this->call($this->confirmationEndPoint, ['json' => $parameters]);

        $data = [
            'confirmation_status' => $response->status,
            'confirmation_message' => $response->message,
            'confirmation_response_code' => $response->responseCode,
        ];

        if ($response->status) {
            $data = array_merge($data, [
                'account_number' => $response->data['accountNumber'],
                'account_status' => $response->data['accountStatus'],
            ]);
        }

        $beneficiary->update($data);

        return $response;
    }

    /**
     * Upload personal kyc documents.
     *
     * @param string $customerMobileNumber
     * @param $passportSizePhoto
     * @param $documentImageFront
     * @param $documentImageBack
     * @return mixed
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     */
    protected function kyc(string $customerMobileNumber, $passportSizePhoto, $documentImageFront, $documentImageBack): mixed
    {
        $parameters = [
            'merchantCode' => $this->merchantCode,
            'customerMobileNumber' => $customerMobileNumber,
            'passportSizePhoto' => $passportSizePhoto,
            'documentImageFront' => $documentImageFront,
            'documentImageBack' => $documentImageBack,
        ];

        return $this->call($this->kycEndPoint, ['json' => $parameters]);
    }
}
