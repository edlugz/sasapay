# SasaPay

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

```bash
composer require edlugz/sasapay
```

## Publish Configuration File

```bash
php artisan vendor:publish --provider="EdLugz\SasaPay\SasaPayServiceProvider" --tag="migrations"
```

Fill in all the details you will be requiring for your application. Here are the env variables for quick copy paste.

```bash
SASAPAY_PERSONAL_ONBOARDING_RESULT_URL
SASAPAY_BUSINESS_ONBOARDING_RESULT_URL
SASAPAY_FUNDING_RESULT_URL
SASAPAY_SEND_MONEY_RESULT_URL
SASAPAY_BUSINESS_PAYMENT_RESULT_URL
SASAPAY_UTILITY_PAYMENT_RESULT_URL
SASAPAY_CLIENT_ID
SASAPAY_CLIENT_SECRET
SASAPAY_MERCHANT_CODE
SASAPAY_BASE_URL=
```

## Usage

Using the facade

Onboarding - Personal
```bash
SasaPay::personalOnboarding()->signUp($firstName, $middleName = '', $lastName, $email, $countryCode, $mobileNumber, $documentNumber, $documentType);
SasaPay::personalOnboarding()->confirm($id, $otp);
SasaPay::personalOnboarding()->kyc($customerMobileNumber, $passportSizePhoto, $documentImageFront, $documentImageBackdocumentImageBack);
```
Onboarding - Business
```bash
SasaPay::businessOnboarding()->signUp($firstName, $middleName, $lastName, $countryCode, $mobileNumber, $documentNumber, $documentType, $documentType);
SasaPay::businessOnboarding()->confirm($id, $otp);
SasaPay::businessOnboarding()->kyc($requestId, $businessKraPin, $businessRegistrationCertificate, $directorIdCardFront, $directorIdCardBack, $directorKraPin);
```
Customers 
```bash
SasaPay::customer()->getCustomers();
SasaPay::customer()->customerDetails($accountNumber);
```
Fund Account - send stk push to mobile number 
```bash
SasaPay::fund()->fundRequest($networkCode, $mobileNumber, $receiverAccountNumber, $amount, $transactionDesc);
SasaPay::fund()->processRequest($receiverAccountNumber, $checkoutRequestId, $verificationCode);
SasaPay::fund()->fundingResult($data);
```
Send Money -  to mobile wallets
```bash
SasaPay::sendMoney()->sendToMObile($transactionDesc, $senderNumber, $amount, $reason, $networkCode, $receiverNumber, $transactionReference);
SasaPay::sendMoney()->sendMoneyResult($data);
```
Send Money -  to bank accounts
```bash
SasaPay::sendMoney()->sendToBank($transactionDesc, $senderNumber, $amount, $reason, $bankCode, $accountNumber, $transactionReference);
SasaPay::sendMoney()->sendMoneyResult($data);
```
Lipa - to paybills and till numbers
```bash
SasaPay::businessPayment()->lipa($amount, $senderAccountNumber, $receiverMerchantCode, $accountReference, $transactionFee = 0, $billerType, $networkCode, $reason);
SasaPay::businessPayment()->businessPaymentResult($data);
```
Utility - for airtime, nairobi water, dstv, gotv
```bash
SasaPay::utility()->payUtility($amount, $payerAccountNumber, $accountNumber, $transactionFee = 0);
SasaPay::utility()->billQuery($serviceCode, $customerMobile, $accountNumber);
SasaPay::utility()->utilityResult($data);
```
Statement - fetch transaction statement
```bash
SasaPay::statement()->fetch($accountNumber);
```
Transaction - verify and check status
```bash
SasaPay::transaction()->check($checkoutRequestId, $merchantTransactionReference, $transactionCode);
SasaPay::transaction()->verify($transactionCode);
```
Balance - check merchant balance
```bash
SasaPay::balance()->check($accountNumber);
```
Supplementary functions - channel codes, countries, sub-regions, industries, sub-industries, business types, account product types, agent locations
```bash
SasaPay::supplementary()->channelCodes();
SasaPay::supplementary()->countries();
SasaPay::supplementary()->subRegions();
SasaPay::supplementary()->industries();
SasaPay::supplementary()->subIndustries();
SasaPay::supplementary()->businessTypes();
SasaPay::supplementary()->accountProductTypes();
SasaPay::supplementary()->agentLocations();
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email eddy.lugaye@gmail.com instead of using the issue tracker.

## Credits

- [Eddy Lugaye][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/edlugz/sasapay.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/edlugz/sasapay.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/edlugz/sasapay/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/edlugz/sasapay
[link-downloads]: https://packagist.org/packages/edlugz/sasapay
[link-travis]: https://travis-ci.org/edlugz/sasapay
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/edlugz
[link-contributors]: ../../contributors
