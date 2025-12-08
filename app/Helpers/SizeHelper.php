<?php
// app/Helpers/SizeHelper.php

namespace App\Helpers;

class SizeHelper
{
    const SIZE_ORDER = [
        // Standard sizes
        'XXS' => 1,
        '2XS' => 2,
        'XS' => 3,
        'S' => 4,
        'M' => 5,
        'L' => 6,
        'XL' => 7,
        'XXL' => 8,
        '2XL' => 8,
        '3XL' => 9,
        '4XL' => 10,
        '5XL' => 11,
        '6XL' => 12,

        // Numeric sizes
        '0' => 101,
        '00' => 102,
        '1' => 103,
        '2' => 104,
        '3' => 105,
        '4' => 106,
        '5' => 107,
        '6' => 108,
        '7' => 109,
        '8' => 110,
        '9' => 111,

        // Petite sizes
        'XS PETITE' => 201,
        'S PETITE' => 202,
        'M PETITE' => 203,
        'L PETITE' => 204,
        'XL PETITE' => 205,
        'XXL PETITE' => 206,

        // Plus sizes dengan "P"
        '0P' => 301,
        '2P' => 302,
        '4P' => 303,
        '6P' => 304,
        '8P' => 305,
        '10P' => 306,
        '12P' => 307,

        // Regular sizes dengan "R"
        '0R' => 401,
        '1R' => 402,
        '2R' => 403,
        '3R' => 404,
        '4R' => 405,
        '6R' => 406,
        '8R' => 407,
        '10R' => 408,
        '12R' => 409,
        '14R' => 410,

        // Women sizes dengan "W"
        '14W' => 501,
        '16W' => 502,
        '18W' => 503,
        '20W' => 504,
        '22W' => 505,

        // Toddler sizes dengan "T"
        '2T' => 601,
        '3T' => 602,
        '4T' => 603,

        // Special pattern sizes
        'ALL SIZE' => 999,
    ];

    /**
     * Get order number for a given size
     */
    public static function getSizeOrder($size)
    {
        $size = strtoupper(trim($size));

        // Cek exact match pertama
        if (isset(self::SIZE_ORDER[$size])) {
            return self::SIZE_ORDER[$size];
        }

        // Handle special patterns
        return self::handleSpecialPatterns($size);
    }

    /**
     * Handle special size patterns
     */
    private static function handleSpecialPatterns($size)
    {
        // Pattern untuk size dengan suffix (m, f, lp, lpj, etc)
        if (preg_match('/^(\d?X*S*XL?)([A-Z]*)$/i', $size, $matches)) {
            $baseSize = $matches[1];
            $suffix = $matches[2] ?? '';

            $baseOrder = self::getBaseSizeOrder($baseSize);

            if ($baseOrder < 999) {
                // Tambahkan nilai berdasarkan suffix untuk ordering dalam group yang sama
                $suffixValue = self::getSuffixValue($suffix);
                return $baseOrder + ($suffixValue * 0.01);
            }
        }

        // Pattern untuk size dengan dash (S-02, XL-04, etc)
        if (preg_match('/^([A-Z]+)-(\d+)$/i', $size, $matches)) {
            $baseSize = $matches[1];
            $variant = $matches[2];

            $baseOrder = self::getBaseSizeOrder($baseSize);
            if ($baseOrder < 999) {
                return $baseOrder + ($variant * 0.001);
            }
        }

        // Pattern untuk kombinasi (W-S, M-L, etc)
        if (preg_match('/^([A-Z])-([A-Z])$/i', $size, $matches)) {
            $first = $matches[1];
            $second = $matches[2];

            $firstOrder = self::getSingleSizeOrder($first);
            $secondOrder = self::getSingleSizeOrder($second);

            if ($firstOrder < 999 && $secondOrder < 999) {
                return 700 + (($firstOrder + $secondOrder) * 0.01);
            }
        }

        // Default untuk size yang tidak dikenal
        return 1000;
    }

    /**
     * Get base size order without suffix
     */
    private static function getBaseSizeOrder($baseSize)
    {
        $baseSize = strtoupper($baseSize);

        // Mapping untuk base sizes
        $baseSizes = [
            'XS' => 3,
            'S' => 4,
            'M' => 5,
            'L' => 6,
            'XL' => 7,
            'XXL' => 8,
            '2XL' => 8,
            '3XL' => 9,
            '4XL' => 10,
            '5XL' => 11,
            '6XL' => 12
        ];

        return $baseSizes[$baseSize] ?? 1000;
    }

    /**
     * Get order for single letter size
     */
    private static function getSingleSizeOrder($letter)
    {
        $singleSizes = [
            'X' => 1,
            'S' => 2,
            'M' => 3,
            'L' => 4
        ];

        return $singleSizes[strtoupper($letter)] ?? 1000;
    }

    /**
     * Get value for suffix
     */
    private static function getSuffixValue($suffix)
    {
        $suffixValues = [
            '' => 0,
            'M' => 1,
            'F' => 2,
            'LP' => 3,
            'LPJ' => 4,
            'M' => 1,
            'F' => 2,
            'LP' => 3,
            'PJ' => 4
        ];

        return $suffixValues[strtoupper($suffix)] ?? 10;
    }

    /**
     * Get all sizes ordered
     */
    public static function getOrderedSizes()
    {
        return array_keys(self::SIZE_ORDER);
    }

    /**
     * Scope untuk query builder
     */
    public static function getSizeOrderSql()
    {
        $sql = "CASE ";

        foreach (self::SIZE_ORDER as $size => $order) {
            $sql .= "WHEN UPPER(TRIM(size)) = '{$size}' THEN {$order} ";
        }

        $sql .= "ELSE 1000 END";

        return $sql;
    }
}
