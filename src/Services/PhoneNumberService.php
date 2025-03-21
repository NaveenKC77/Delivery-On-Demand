<?php

namespace App\Services;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;

class PhoneNumberService
{
    private PhoneNumberUtil $phoneUtil;

    public function __construct()
    {
        $this->phoneUtil = PhoneNumberUtil::getInstance();
    }

    /**
     * Normalize a phone number to E.164 format
     *
     * @param string $phoneNumber
     * @param string $regionCode (e.g., 'US')
     * @return string|null
     */
    public function normalizePhoneNumber(string $phoneNumber, string $regionCode = 'AU'): ?string
    {
        try {
            $number = $this->phoneUtil->parse($phoneNumber, $regionCode);
            if ($this->phoneUtil->isValidNumber($number)) {
                return $this->phoneUtil->format($number, PhoneNumberFormat::E164);
            }
        } catch (\libphonenumber\NumberParseException $e) {
            return null;
        }
        
        return null;
    }

    /**
     * Validate a phone number format
     *
     * @param string $phoneNumber
     * @param string $regionCode (e.g., 'US')
     * @return bool
     */
    public function validatePhoneNumber(string $phoneNumber, string $regionCode = 'US'): bool
    {
        try {
            $number = $this->phoneUtil->parse($phoneNumber, $regionCode);
            return $this->phoneUtil->isValidNumber($number);
        } catch (\libphonenumber\NumberParseException $e) {
            return false;
        }
    }
}
