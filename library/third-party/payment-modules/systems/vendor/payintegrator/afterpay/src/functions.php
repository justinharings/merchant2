<?php

namespace Afterpay;

 /**
 * Copyright (c) 2017  arvato Finance B.V.
 *
 * AfterPay reserves all rights in the Program as delivered. The Program
 * or any portion thereof may not be reproduced in any form whatsoever without
 * the written consent of AfterPay.
 *
 * Disclaimer:
 * THIS NOTICE MAY NOT BE REMOVED FROM THE PROGRAM BY ANY USER THEREOF.
 * THE PROGRAM IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE PROGRAM OR THE USE OR OTHER DEALINGS
 * IN THE PROGRAM.
 * 
 * @name        AfterPay Class
 * @author      AfterPay (support@afterpay.nl)
 * @description PHP Library to connect with AfterPay Post Payment services
 * @copyright   Copyright (c) 2017 arvato Finance B.V.
 */

use Afterpay;

/**
 * Function for cleaning phone numbers to correct data depending on country
 *
 * @param string $phoneNumber
 * @param string $country
 *
 * @return string $phoneNumber
 */

function cleanphone($phoneNumber, $country = 'NL')
{
    // Replace + with 00
    $phoneNumber = str_replace('+', '00', $phoneNumber);
    // Remove (0) because output is international format
    $phoneNumber = str_replace('(0)', '', $phoneNumber);
    // Only numbers
    $phoneNumber = preg_replace("/[^0-9]/", "", $phoneNumber);
    // Country specific checks
    if ($country == 'NL') {
        if (
            strlen($phoneNumber) == '10'
            && substr($phoneNumber, 0, 3) != '0031'
            && substr($phoneNumber, 0, 1) == '0'
        ) {
            $phoneNumber = '0031' . substr($phoneNumber, -9);
        } elseif (strlen($phoneNumber) == '13' && substr($phoneNumber, 0, 3) == '0031') {
            $phoneNumber = '0031' . substr($phoneNumber, -9);
        }
    } elseif ($country == 'BE') {
        // Land lines
        if (
            strlen($phoneNumber) == '9'
            && substr($phoneNumber, 0, 3) != '0032'
            && substr($phoneNumber, 0, 1) == '0'
        ) {
            $phoneNumber = '0032' . substr($phoneNumber, -8);
        } elseif (strlen($phoneNumber) == '12' && substr($phoneNumber, 0, 3) == '0032') {
            $phoneNumber = '0032' . substr($phoneNumber, -8);
        }
        // Mobile lines
        if (
            strlen($phoneNumber) == '10'
            && substr($phoneNumber, 0, 3) != '0032'
            && substr($phoneNumber, 0, 1) == '0'
        ) {
            $phoneNumber = '0032' . substr($phoneNumber, -9);
        } elseif (strlen($phoneNumber) == '13' && substr($phoneNumber, 0, 3) == '0032') {
            $phoneNumber = '0032' . substr($phoneNumber, -9);
        }
    }

    return $phoneNumber;
}

/**
 * Check validation error and give back readable error message
 *
 * @param string $failure
 * @param string $fieldName
 * @param string $language
 *
 * @return array|string
 */
function check_validation_error($failure, $fieldName = '', $language = 'nl')
{
    // Belgium has a different buildup of the failure message
    if (in_array($failure, array('field.invalid', 'field.missing'))) {
        $oldFailure = explode('.', $failure);
        // In Belgium person is ReferencePerson, so replace
        $fieldName = str_replace('referencePerson', 'person', $fieldName);
        // In Belgium phonenumber1 is onder person, so replace
        $fieldName = str_replace('person.phonenumber1', 'phonenumber1', $fieldName);
        $fieldName = str_replace('person.phonenumber2', 'phonenumber2', $fieldName);

        $field_failure = $oldFailure[0] . '.' . $fieldName . '.' . $oldFailure[1];
    } else {
        $field_failure = $failure;
    }

    $translationFile = 'ValidationError';
    return Afterpay\lang($field_failure, $translationFile, $language);
}

/**
 * Check rejection error and give back readable error message
 *
 * @param string $rejection_code
 * @param string $language
 *
 * @return array|string
 */
function check_rejection_error($rejection_code, $language = 'nl')
{
    $translationFile = 'RejectionError';
    return Afterpay\lang($rejection_code, $translationFile, $language);
}

/**
 * Check technical error and give back readable error message
 *
 * @param string $field_failure
 *
 * @return array
 */
function check_technical_error($field_failure, $language = 'nl')
{
    $translationFile = 'TechnicalError';
    return Afterpay\lang($field_failure, $translationFile, $language);
}

/**
 * @param string $fieldKey
 * @param string $translationFile
 * @param string $language
 *
 * @return array|string
 */
function lang($fieldKey, $translationFile, $language = 'nl')
{
    $translationFilePath = __DIR__ . '/lang/' . $language . '/' . $translationFile . '.php';
    if (file_exists($translationFilePath)) {
        $langArray = include($translationFilePath);
        if (array_key_exists($fieldKey, $langArray)) {
            return $langArray[$fieldKey];
        } else {
            try {
                return $langArray['fallback'];
            } catch (\Exception $e) {
                // todo: log it some where
            }
        }
    }
}

/**
 * @param array $arrayOne
 * @param array $arrayTwo
 *
 * @return array
 */
function arrayRecursiveDiff($arrayOne, $arrayTwo)
{
    $diffedArray = array();
    foreach ($arrayOne as $key => $value) {
        if (array_key_exists($key, $arrayTwo)) {
            if (is_array($value)) {
                $recursiveDiff = Afterpay\arrayRecursiveDiff($value, $arrayTwo[$key]);
                if (count($recursiveDiff)) {
                    $diffedArray[$key] = $recursiveDiff;
                }
            } else {
                if ($value != $arrayTwo[$key]) {
                    $diffedArray[$key] = $value;
                }
            }
        } else {
            $diffedArray[$key] = $value;
        }
    }
    return $diffedArray;
}

/**
 * @param $price
 *
 * @return number|string
 */
function convertPrice($price) {
    // Check if price is negative
    $priceIsNegative = false;
    if( $price < 0 ) {
        $priceIsNegative = true;
    }
    $price = abs($price);
    if( $priceIsNegative ) {
        $price = $price * -1;
    }
    $price = number_format(
        $price,
        RestClient::DECIMALS,
        RestClient::DEC_POINT,
        RestClient::THOUSANDS_SEP
    );
    return $price;
}

/**
 * Calculate vat percentage based on totalamount and vatamount
 *
 * @param int $priceInclVat
 * @param int $vatAmount
 *
 * @return int $vatPercentage
 */
function calculateVatPercentage($priceInclVat, $vatAmount) {
    $vatPercentage = 0;
    $priceExclVat = $priceInclVat - $vatAmount;
    $onePercentage = $priceExclVat / 100;
    $vatPercentage = $vatAmount / $onePercentage;
    return round($vatPercentage);
}

/**
 * Calculate vat amount based on totalamount and vat percentage
 *
 * @param int $priceInclVat
 * @param int $vatPercentage
 *
 * @return float $vatAmount
 */
function calculateVatAmount($priceInclVat, $vatPercentage) {
    $vatAmount = 0;
    $priceExclVat = ( $priceInclVat / ( $vatPercentage + 100 ) ) * 100;
    $vatAmount = $priceInclVat - $priceExclVat;
    return round( $vatAmount, 2 );
}