<?php

namespace EdLugz\SasaPay\Requests;

use Edlugz\SasaPay\Models\SasaPayBeneficiary;
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
     * @param string merchantCode
     * @param string businessName
     * @param string billNumber
     * @param string description
     * @param int productType
     * @param string countryId
     * @param int subregionId
     * @param int industryId
     * @param int subIndustryId
     * @param int bankId
     * @param string bankAccountNumber
     * @param string mobileNumber
     * @param int businessTypeId
     * @param string email
     * @param string registrationNumber
     * @param string kraPin
     * @param string callbackUrl
     * @param string directorName
     * @param string directorIdnumber
     * @param string directorMobileNumber
     * @param string directorKraPin
     */
    protected function signUp(
		$businessName,
        $billNumber,
        $description,
        $productType,
        $countryId,
        $subregionId,
        $industryId,
        $subIndustryId,
        $bankId,
        $bankAccountNumber,
        $mobileNumber,
        $businessTypeId,
        $email,
        $registrationNumber,
        $kraPin,
        $directorName,
        $directorIdnumber,
        $directorMobileNumber,
        $directorKraPin )
	{
        $beneficiary = SasaPayBeneficiary::create([
            'business_name'          => $businessName,
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
            'director_id_number'     => $directorIdnumber,
            'director_mobile_number' => $directorMobileNumber,
            'director_kra_pin'       => $directorKraPin,
        ]);

        $id = $beneficiary->id;

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
                'directorIdnumber'     => $directorIdnumber,
                'directorMobileNumber' => $directorMobileNumber,
                'directorKraPin'       => $directorKraPin,
            ],
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
     * Confirm business onboarded accounts.
     *
     * @param $id
     * @param $otp
     * @return mixed
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
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
     * Upload business kyc documents.
     *
     * @param $requestId
     * @param $businessKraPin
     * @param $businessRegistrationCertificate
     * @param $directorIdCardFront
     * @param $directorIdCardBack
     * @param $directorKraPin
     * @return mixed
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     */
    protected function kyc($requestId, $businessKraPin, $businessRegistrationCertificate, $directorIdCardFront, $directorIdCardBack, $directorKraPin)
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
