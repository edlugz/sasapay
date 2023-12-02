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
    protected $signupEndPoint = 'personal-onboarding/';

    /**
     * Personal onboarding confirmation end point on Sasapay API.
     *
     * @var string
     */
    protected $confirmationEndPoint = 'personal-onboarding/confirmation/';

    /**
     * Personal onboarding kyc end point on Sasapay API.
     *
     * @var string
     */
    protected $kycEndPoint = 'personal-onboarding/kyc/';

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
     * @param string merchantCode
     * @param string firstName
     * @param string middleName
     * @param string lastName
     * @param string countryCode
     * @param string mobileNumber
     * @param string documentNumber
     * @param string documentType
     * @param string email
     * @param string callbackUrl
     *
     * @return mixed
     */
    protected function signUp($firstName, $middleName = '', $lastName, $email, $countryCode, $mobileNumber, $documentNumber, $documentType)
    {
        $beneficiary = SasaPayBeneficiary::create([
            'first_name'      => $firstName,
            'middle_name'     => $middleName,
            'last_name'       => $lastName,
            'email'           => $email,
            'mobile_number'   => $mobileNumber,
            'country_code'    => $countryCode,
            'document_type'   => $documentType,
            'document_number' => $documentNumber,
        ]);

        $id = $beneficiary->id;

        $parameters = [
            'merchantCode'   => $this->merchantCode,
            'firstName'      => $firstName,
            'middleName'     => $middleName,
            'lastName'       => $lastName,
            'countryCode'    => $countryCode,
            'mobileNumber'   => $mobileNumber,
            'documentNumber' => $documentNumber,
            'documentType'   => $documentType,
            'email'          => $email,
            'callbackUrl'    => $this->resultURL,
        ];

        $response = $this->call($this->signupEndPoint, ['json' => $parameters]);

        if ($response->status == true) {
            $update = SasaPayBeneficiary::where('id', $id)
                    ->update([
                        'request_status' => $response->status,
                        'response_code'  => $response->responseCode,
                        'message'        => $response->message,
                        'request_id'     => $response->requestId,
                    ]);
        } else {
            $update = SasaPayBeneficiary::where('id', $id)
                    ->update([
                        'request_status' => $response->status,
                        'response_code'  => $response->responseCode,
                        'message'        => $response->message,
                    ]);
        }

        return $response;
    }

    /**
     * Confirm personal onboarded accounts.
     *
     * @param string merchantCode
     * @param string requestId
     * @param string otp
     *
     * @return mixed
     */
    protected function confirm($id, $otp)
    {
        $beneficiary = SasaPayBeneficiary::find($id);

        $parameters = [
            'merchantCode' => $this->merchantCode,
            'requestId'    => $beneficiary->request_id,
            'otp'          => $otp,
        ];

        $response = $this->call($this->confirmationEndPoint, ['json' => $parameters]);

        if ($response->status == true) {
            $update = SasaPayBeneficiary::where('id', $id)
                    ->update([
                        'confirmation_status'        => $response->status,
                        'confirmation_message'       => $response->message,
                        'confirmation_response_code' => $response->responseCode,
                        'account_number'             => $response->data['accountNumber'],
                        'account_status'             => $response->data['accountStatus'],
                    ]);
        } else {
            $update = SasaPayBeneficiary::where('id', $id)
                    ->update([
                        'confirmation_status'        => $response->status,
                        'confirmation_message'       => $response->message,
                        'confirmation_response_code' => $response->responseCode,
                    ]);
        }

        return $response;
    }

    /**
     * Upload personal kyc documents.
     *
     * @param string merchantCode
     * @param string customerMobileNumber
     * @param string passportSizePhoto
     * @param string documentImageFront
     * @param string documentImageBack
     *
     * @return mixed
     */
    protected function kyc($customerMobileNumber, $passportSizePhoto, $documentImageFront, $documentImageBackdocumentImageBack)
    {
        $parameters = [
            'merchantCode'         => $this->merchantCode,
            'customerMobileNumber' => $customerMobileNumber,
            'passportSizePhoto'    => $passportSizePhoto,
            'documentImageFront'   => $documentImageFront,
            'documentImageBack'    => $documentImageBack,
        ];

        return $this->call($this->kycEndPoint, ['json' => $parameters]);
    }
}
