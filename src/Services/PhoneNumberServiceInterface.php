<?php

namespace App\Services;

interface PhoneNumberServiceInterface
{
    /**
     * Normalizes a phone number to E.164 international format
     *
     * @param string $phoneNumber The phone number to normalize
     * @param string $regionCode The region/country code (default: 'AU')
     * @return string|null Normalized number in E.164 format or null if invalid
     */
    public function normalizePhoneNumber(string $phoneNumber, string $regionCode = 'AU'): ?string;

    /**
     * Validates whether a phone number is correctly formatted
     *
     * @param string $phoneNumber The phone number to validate
     * @param string $regionCode The region/country code (default: 'US')
     * @return bool True if valid, false otherwise
     */
    public function validatePhoneNumber(string $phoneNumber, string $regionCode = 'US'): bool;
}
