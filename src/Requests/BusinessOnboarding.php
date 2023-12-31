<?php

namespace EdLugz\SasaPay\Requests;

use EdLugz\SasaPay\Models\SasaPayBeneficiary;
use EdLugz\SasaPay\SasaPayClient;

class BusinessOnboarding extends SasaPayClient
{
    /**
     * business onboarding end point on Sasapay API.
     *
     * @var string
     */
    protected string $signupEndPoint = 'business-onboarding/';

    /**
     * business onboarding confirmation end point on Sasapay API.
     *
     * @var string
     */
    protected string $confirmationEndPoint = 'business-onboarding/confirmation/';

    /**
     * business onboarding kyc end point on Sasapay API.
     *
     * @var string
     */
    protected string $kycEndPoint = 'business-onboarding/kyc/';

    /**
     * The URL where Sasapay Transaction Status API will send result of the
     * transaction.
     *
     * @var string
     */
    protected string $resultURL;

    /**
     * Business onboardings constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->resultURL = $this->setUrl(config('sasapay.onboarding_result_url.business'));
    }

    /**
     * Onboard business accounts.
     *
     * @param int    $accountId            -  this links a beneficiary to your system table
     * @param string $businessName
     * @param string $billNumber
     * @param string $description
     * @param string $productType
     * @param string $countryId
     * @param string $subregionId
     * @param string $industryId
     * @param string $subIndustryId
     * @param string $bankId
     * @param string $bankAccountNumber
     * @param string $mobileNumber
     * @param string $businessTypeId
     * @param string $email
     * @param string $registrationNumber
     * @param string $kraPin
     * @param string $directorName
     * @param string $directorIdNumber
     * @param string $directorMobileNumber
     * @param string $directorKraPin
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     *
     * @return mixed
     */
    public function signUp(
        string $accountId,
        string $businessName,
        string $billNumber,
        string $description,
        string $productType,
        string $countryId,
        string $subregionId,
        string $industryId,
        string $subIndustryId,
        string $bankId,
        string $bankAccountNumber,
        string $mobileNumber,
        string $businessTypeId,
        string $email,
        string $registrationNumber,
        string $kraPin,
        string $directorName,
        string $directorIdNumber,
        string $directorMobileNumber,
        string $directorKraPin
    ): mixed {
        $beneficiary = SasaPayBeneficiary::create([
            'business_name'          => $businessName,
            'account_id'             => $accountId,
            'bill_number'            => $billNumber,
            'description'            => $description,
            'product_type'           => $productType,
            'country_code'           => $countryId,
            'sub_region'             => $subregionId,
            'industry'               => $industryId,
            'sub_industry'           => $subIndustryId,
            'bank_code'              => $bankId,
            'bank_account_number'    => $bankAccountNumber,
            'mobile_number'          => $mobileNumber,
            'business_type'          => $businessTypeId,
            'email'                  => $email,
            'registration_number'    => $registrationNumber,
            'kra_pin'                => $kraPin,
            'director_name'          => $directorName,
            'director_id_number'     => $directorIdNumber,
            'director_mobile_number' => $directorMobileNumber,
            'director_kra_pin'       => $directorKraPin,
        ]);

        $parameters = [
            'merchantCode'       => $this->merchantCode,
            'businessName'       => $businessName,
            'billNumber'         => $billNumber,
            'description'        => $description,
            'productType'        => $productType,
            'countryId'          => $countryId,
            'subregionId'        => $subregionId,
            'industryId'         => $industryId,
            'subIndustryId'      => $subIndustryId,
            'bankId'             => $bankId,
            'bankAccountNumber'  => $bankAccountNumber,
            'mobileNumber'       => $mobileNumber,
            'businessTypeId'     => $businessTypeId,
            'email'              => $email,
            'registrationNumber' => $registrationNumber,
            'kraPin'             => $kraPin,
            'callbackUrl'        => $this->resultURL,
            'directors'          => [
                'directorName'         => $directorName,
                'directorIdnumber'     => $directorIdNumber,
                'directorMobileNumber' => $directorMobileNumber,
                'directorKraPin'       => $directorKraPin,
            ],
        ];

        $response = $this->call($this->signupEndPoint, ['json' => $parameters]);

        $data = [
            'request_status' => $response->status,
            'response_code'  => $response->responseCode,
            'message'        => $response->message,
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
     * Confirm business onboarded accounts.
     *
     * @param $id
     * @param $otp
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     *
     * @return mixed
     */
    public function confirm($id, $otp): mixed
    {
        $beneficiary = SasaPayBeneficiary::find($id);

        $parameters = [
            'merchantCode' => $this->merchantCode,
            'requestId'    => $beneficiary->request_id,
            'otp'          => $otp,
        ];

        $response = $this->call($this->confirmationEndPoint, ['json' => $parameters]);

        $data = [
            'confirmation_status'        => $response->status,
            'confirmation_message'       => $response->message,
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
     * Upload business kyc documents.
     *
     * @param $requestId
     * @param $businessKraPin
     * @param $businessRegistrationCertificate
     * @param $directorIdCardFront
     * @param $directorIdCardBack
     * @param $directorKraPin
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     *
     * @return mixed
     */
    public function kyc($requestId, $businessKraPin, $businessRegistrationCertificate, $directorIdCardFront, $directorIdCardBack, $directorKraPin): mixed
    {
        $parameters = [
            'merchantCode'                    => $this->merchantCode,
            'requestId'                       => $requestId,
            'businessKraPin'                  => $businessKraPin,
            'businessRegistrationCertificate' => $businessRegistrationCertificate,
            'directorIdCardFront'             => $directorIdCardFront,
            'directorIdCardBack'              => $directorIdCardBack,
            'directorKraPin'                  => $directorKraPin,
        ];

        return $this->call($this->kycEndPoint, ['json' => $parameters]);
    }
}
