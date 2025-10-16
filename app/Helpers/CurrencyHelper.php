<?php

declare(strict_types=1);

namespace App\Helpers;

final class CurrencyHelper
{
    /**
     * Get currency code for a given country name
     */
    public static function getCurrencyForCountry(string $countryName): string
    {
        $currencyMap = [
            // Major study destinations
            'United States' => 'USD',
            'United Kingdom' => 'GBP',
            'Canada' => 'CAD',
            'Australia' => 'AUD',
            'New Zealand' => 'NZD',
            'Germany' => 'EUR',
            'France' => 'EUR',
            'Italy' => 'EUR',
            'Spain' => 'EUR',
            'Netherlands' => 'EUR',
            'Ireland' => 'EUR',
            'Belgium' => 'EUR',
            'Austria' => 'EUR',
            'Portugal' => 'EUR',
            'Greece' => 'EUR',
            'Finland' => 'EUR',
            'Sweden' => 'SEK',
            'Norway' => 'NOK',
            'Denmark' => 'DKK',
            'Switzerland' => 'CHF',
            'Japan' => 'JPY',
            'South Korea' => 'KRW',
            'China' => 'CNY',
            'Singapore' => 'SGD',
            'Malaysia' => 'MYR',
            'India' => 'INR',
            'Pakistan' => 'PKR',
            'Bangladesh' => 'BDT',
            'Sri Lanka' => 'LKR',
            'United Arab Emirates' => 'AED',
            'Saudi Arabia' => 'SAR',
            'Qatar' => 'QAR',
            'Kuwait' => 'KWD',
            'Bahrain' => 'BHD',
            'Oman' => 'OMR',
            'Turkey' => 'TRY',
            'Russia' => 'RUB',
            'Poland' => 'PLN',
            'Czech Republic' => 'CZK',
            'Hungary' => 'HUF',
            'Romania' => 'RON',
            'Bulgaria' => 'BGN',
            'Croatia' => 'EUR',
            'Serbia' => 'RSD',
            'Ukraine' => 'UAH',
            'South Africa' => 'ZAR',
            'Egypt' => 'EGP',
            'Nigeria' => 'NGN',
            'Kenya' => 'KES',
            'Ghana' => 'GHS',
            'Brazil' => 'BRL',
            'Argentina' => 'ARS',
            'Chile' => 'CLP',
            'Mexico' => 'MXN',
            'Colombia' => 'COP',
            'Peru' => 'PEN',
            'Thailand' => 'THB',
            'Vietnam' => 'VND',
            'Indonesia' => 'IDR',
            'Philippines' => 'PHP',
            'Hong Kong' => 'HKD',
            'Taiwan' => 'TWD',
            'Israel' => 'ILS',
        ];

        return $currencyMap[$countryName] ?? 'USD';
    }

    /**
     * Get currency symbol for a given currency code
     */
    public static function getCurrencySymbol(string $currencyCode): string
    {
        $symbolMap = [
            'USD' => '$',
            'GBP' => '£',
            'EUR' => '€',
            'CAD' => 'C$',
            'AUD' => 'A$',
            'NZD' => 'NZ$',
            'JPY' => '¥',
            'CNY' => '¥',
            'INR' => '₹',
            'PKR' => '₨',
            'BDT' => '৳',
            'LKR' => '₨',
            'SGD' => 'S$',
            'MYR' => 'RM',
            'AED' => 'د.إ',
            'SAR' => 'ر.س',
            'QAR' => 'ر.ق',
            'KWD' => 'د.ك',
            'BHD' => 'د.ب',
            'OMR' => 'ر.ع.',
            'CHF' => 'CHF',
            'SEK' => 'kr',
            'NOK' => 'kr',
            'DKK' => 'kr',
            'KRW' => '₩',
            'THB' => '฿',
            'VND' => '₫',
            'IDR' => 'Rp',
            'PHP' => '₱',
            'HKD' => 'HK$',
            'TWD' => 'NT$',
            'TRY' => '₺',
            'RUB' => '₽',
            'PLN' => 'zł',
            'CZK' => 'Kč',
            'HUF' => 'Ft',
            'RON' => 'lei',
            'BGN' => 'лв',
            'RSD' => 'дин',
            'UAH' => '₴',
            'ZAR' => 'R',
            'EGP' => 'ج.م',
            'NGN' => '₦',
            'KES' => 'KSh',
            'GHS' => '₵',
            'BRL' => 'R$',
            'ARS' => '$',
            'CLP' => '$',
            'MXN' => '$',
            'COP' => '$',
            'PEN' => 'S/',
            'ILS' => '₪',
        ];

        return $symbolMap[$currencyCode] ?? $currencyCode;
    }
}
