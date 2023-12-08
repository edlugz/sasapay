<?php
/**
 * Created by PhpStorm
 * Filename: SasaPayHelper.php
 * User: henriquedn
 * Email: henrydkm@gmail.com
 * Datetime: 08/12/2023 Dec 2023 at 21:53.
 */

namespace EdLugz\SasaPay\Helpers;

class SasaPayHelper
{
    /**
     * get channel code from mobile number.
     *
     * @param string $mobileNumber - 0XXXXXXXXX
     *
     * @return string
     */
    public static function getChannel(string $mobileNumber): string
    {
        $safaricom = '/(?:0)?((?:(?:7(?:(?:[01249][0-9])|(?:5[789])|(?:6[89])))|(?:1(?:[1][0-5])))[0-9]{6})$/';
        $airtel = '/(?:0)?((?:(?:7(?:(?:3[0-9])|(?:5[0-6])|(8[5-9])))|(?:1(?:[0][0-2])))[0-9]{6})$/';
        $telkom = '/(?:0)?(77[0-9][0-9]{6})/';
        if (preg_match($safaricom, $mobileNumber)) {
            return '63902';
        } elseif (preg_match($airtel, $mobileNumber)) {
            return '63903';
        } elseif (preg_match($telkom, $mobileNumber)) {
            return '63907';
        } else {
            return '0';
        }
    }
}
