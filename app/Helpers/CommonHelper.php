<?php

namespace App\Helpers;

use Exception;
use App\Models\City;
use Illuminate\Support\Facades\DB;

class CommonHelper
{
    public static function get_NumberInWord($number)
    {
        if (($number < 0) || ($number > 9999999999)) {
            throw new Exception("Number is out of range");
        }

        $Kt = floor($number / 10000000); // crore
        $number -= $Kt * 10000000;

        $Gn = floor($number / 100000); // lac
        $number -= $Gn * 100000;

        $kn = floor($number / 1000); // thousands
        $number -= $kn * 1000;

        $Hn = floor($number / 100); // hundreds
        $number -= $Hn * 100;

        $Dn = floor($number / 10); // tens
        $n = $number % 10; // ones

        $res = "";

        if ($Kt) {
            $res .= self::get_NumberInWord($Kt) . " Crore ";
        }
        if ($Gn) {
            $res .= self::get_NumberInWord($Gn) . " Lac ";
        }
        if ($kn) {
            $res .= self::get_NumberInWord($kn) . " Thousand ";
        }
        if ($Hn) {
            $res .= self::get_NumberInWord($Hn) . " Hundred ";
        }

        $ones = [
            "",
            "One",
            "Two",
            "Three",
            "Four",
            "Five",
            "Six",
            "Seven",
            "Eight",
            "Nine",
            "Ten",
            "Eleven",
            "Twelve",
            "Thirteen",
            "Fourteen",
            "Fifteen",
            "Sixteen",
            "Seventeen",
            "Eighteen",
            "Nineteen"
        ];

        $tens = [
            "",
            "",
            "Twenty",
            "Thirty",
            "Forty",
            "Fifty",
            "Sixty",
            "Seventy",
            "Eighty",
            "Ninety"
        ];

        if ($Dn || $n) {
            if (!empty($res)) {
                $res .= " and ";
            }
            if ($Dn < 2) {
                $res .= $ones[$Dn * 10 + $n];
            } else {
                $res .= $tens[$Dn];
                if ($n) {
                    $res .= " " . $ones[$n];
                }
            }
        }

        if (empty($res)) {
            $res = "Zero";
        }

        return trim($res);
    }
}
