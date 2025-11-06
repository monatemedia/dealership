<?php
// app/Services/VinValidatorService.php

namespace App\Services;

/**
 * VIN Validator Service
 *
 * Validates Vehicle Identification Numbers (VINs) according to ISO 3779 standards.
 * Uses check digit validation for North American VINs (starting with 1-5).
 *
 * @package App\Services
 */
class VinValidatorService
{
    /**
     * VIN must be exactly 17 characters
     */
    private const VIN_LENGTH = 17;

    /**
     * Characters not allowed in VINs (look too similar to numbers)
     */
    private const INVALID_CHARS = ['I', 'O', 'Q'];

    /**
     * Known invalid test/fake VINs
     */
    private const BLACKLISTED_VINS = [
        '11111111111111111',
        '00000000000000000',
        'AAAAAAAAAAAAAAAAA',
        '12345678901234567',
    ];

    /**
     * Transliteration table for check digit calculation
     * Converts each VIN character to its numeric value
     */
    private const TRANSLITERATION = [
        'A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'F' => 6, 'G' => 7, 'H' => 8,
        'J' => 1, 'K' => 2, 'L' => 3, 'M' => 4, 'N' => 5, 'P' => 7, 'R' => 9,
        'S' => 2, 'T' => 3, 'U' => 4, 'V' => 5, 'W' => 6, 'X' => 7, 'Y' => 8, 'Z' => 9,
        '0' => 0, '1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5,
        '6' => 6, '7' => 7, '8' => 8, '9' => 9
    ];

    /**
     * Weight factors for each position in the VIN
     * Position 9 (index 8) has weight 0 as it's the check digit itself
     */
    private const WEIGHTS = [8, 7, 6, 5, 4, 3, 2, 10, 0, 9, 8, 7, 6, 5, 4, 3, 2];

    /**
     * North American region codes (1-5)
     */
    private const NORTH_AMERICAN_CODES = ['1', '2', '3', '4', '5'];

    /**
     * Validate a VIN
     *
     * @param string $vin The VIN to validate
     * @return bool True if valid, false otherwise
     */
    public function validate(string $vin): bool
    {
        $vin = strtoupper(trim($vin));

        // Step 1: Check length
        if (strlen($vin) !== self::VIN_LENGTH) {
            return false;
        }

        // Step 2: Check for blacklisted VINs
        if (in_array($vin, self::BLACKLISTED_VINS)) {
            return false;
        }

        // Step 3: Check for repeated characters (e.g., all same character)
        if ($this->hasRepeatedPattern($vin)) {
            return false;
        }

        // Step 4: Check for invalid characters (I, O, Q)
        foreach (self::INVALID_CHARS as $char) {
            if (str_contains($vin, $char)) {
                return false;
            }
        }

        // Step 5: Check if all characters are valid (A-Z except I,O,Q and 0-9)
        if (!preg_match('/^[A-HJ-NPR-Z0-9]+$/', $vin)) {
            return false;
        }

        // Step 6: Validate check digit (required for North American VINs)
        if ($this->isNorthAmerican($vin)) {
            return $this->validateCheckDigit($vin);
        }

        // Non-North American VINs pass if they meet basic requirements
        return true;
    }

    /**
     * Check if VIN has suspicious repeated patterns
     * Rejects VINs where the same character repeats more than 10 times
     * or where all characters are the same
     *
     * @param string $vin The VIN to check
     * @return bool True if suspicious pattern found, false otherwise
     */
    private function hasRepeatedPattern(string $vin): bool
    {
        // Check if all characters are the same
        if (count(array_unique(str_split($vin))) === 1) {
            return true;
        }

        // Check for any single character repeating more than 10 times
        $charCounts = array_count_values(str_split($vin));
        foreach ($charCounts as $count) {
            if ($count > 10) {
                return true;
            }
        }

        // Check for sequential patterns (e.g., 12345678901234567)
        if (preg_match('/0123456789|123456789/', $vin)) {
            return true;
        }

        return false;
    }

    /**
     * Check if VIN is from North America (starts with 1-5)
     *
     * @param string $vin The VIN to check
     * @return bool True if North American, false otherwise
     */
    private function isNorthAmerican(string $vin): bool
    {
        return in_array($vin[0], self::NORTH_AMERICAN_CODES);
    }

    /**
     * Validate the check digit (9th character, index 8)
     *
     * Algorithm:
     * 1. Convert each character to numeric value using TRANSLITERATION
     * 2. Multiply by position weight from WEIGHTS
     * 3. Sum all products
     * 4. Divide sum by 11 and get remainder
     * 5. If remainder is 10, check digit should be 'X', otherwise it should be the remainder as a string
     *
     * @param string $vin The VIN to validate
     * @return bool True if check digit is valid, false otherwise
     */
    private function validateCheckDigit(string $vin): bool
    {
        $sum = 0;

        // Calculate weighted sum
        for ($i = 0; $i < self::VIN_LENGTH; $i++) {
            $char = $vin[$i];

            // Get transliteration value
            if (!isset(self::TRANSLITERATION[$char])) {
                return false;
            }

            $value = self::TRANSLITERATION[$char];
            $sum += $value * self::WEIGHTS[$i];
        }

        // Calculate check digit
        $checkDigit = $sum % 11;
        $actualCheckDigit = $vin[8];

        // Check digit 10 is represented as 'X'
        if ($checkDigit === 10) {
            return $actualCheckDigit === 'X';
        }

        return $actualCheckDigit === (string)$checkDigit;
    }

    /**
     * Compute the check digit for a VIN
     * Useful when generating VINs
     *
     * @param string $vin VIN with any character at position 9 (will be replaced)
     * @return string The computed check digit ('0'-'9' or 'X')
     */
    public function computeCheckDigit(string $vin): string
    {
        $sum = 0;

        for ($i = 0; $i < self::VIN_LENGTH; $i++) {
            $char = $vin[$i];
            $value = self::TRANSLITERATION[$char] ?? 0;
            $sum += $value * self::WEIGHTS[$i];
        }

        $checkDigit = $sum % 11;
        return $checkDigit === 10 ? 'X' : (string)$checkDigit;
    }

    /**
     * Validate and get detailed error message
     * Useful for form validation
     *
     * @param string $vin The VIN to validate
     * @return array ['valid' => bool, 'message' => string]
     */
    public function validateWithMessage(string $vin): array
    {
        $vin = strtoupper(trim($vin));

        if (strlen($vin) !== self::VIN_LENGTH) {
            return [
                'valid' => false,
                'message' => sprintf('VIN must be exactly %d characters. Got %d.', self::VIN_LENGTH, strlen($vin))
            ];
        }

        if (in_array($vin, self::BLACKLISTED_VINS)) {
            return [
                'valid' => false,
                'message' => 'This VIN is not valid. Please enter a real vehicle identification number.'
            ];
        }

        if ($this->hasRepeatedPattern($vin)) {
            return [
                'valid' => false,
                'message' => 'This VIN appears to be invalid. VINs cannot have all identical characters or suspicious patterns.'
            ];
        }

        foreach (self::INVALID_CHARS as $char) {
            if (str_contains($vin, $char)) {
                return [
                    'valid' => false,
                    'message' => sprintf('Invalid character "%s" found. VINs cannot contain I, O, or Q.', $char)
                ];
            }
        }

        if (!preg_match('/^[A-HJ-NPR-Z0-9]+$/', $vin)) {
            return [
                'valid' => false,
                'message' => 'VIN contains invalid characters. Only A-Z (except I, O, Q) and 0-9 are allowed.'
            ];
        }

        if ($this->isNorthAmerican($vin) && !$this->validateCheckDigit($vin)) {
            return [
                'valid' => false,
                'message' => 'Check digit validation failed. This VIN is not valid.'
            ];
        }

        return [
            'valid' => true,
            'message' => 'VIN is valid.'
        ];
    }
}
