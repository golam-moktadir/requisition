<?php 
use Illuminate\Support\Facades\DB;

function numberToWords($number) {
    $words = [
        0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
        5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen',
        15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen',
        20 => 'Twenty', 30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
        70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety'
    ];

    if ($number < 0) {
        return 'Minus ' . numberToWords(-$number);
    }

    if ($number < 21) {
        return $words[$number];
    }

    if ($number < 100) {
        $tens = intval($number / 10) * 10;
        $units = $number % 10;
        return $words[$tens] . ($units ? ' ' . $words[$units] : '');
    }

    if ($number < 1000) {
        $hundreds = intval($number / 100);
        $remainder = $number % 100;
        return $words[$hundreds] . ' Hundred' . ($remainder ? ' and ' . numberToWords($remainder) : '');
    }

    if ($number < 1000000) {
        $thousands = intval($number / 1000);
        $remainder = $number % 1000;
        return numberToWords($thousands) . ' Thousand' . ($remainder ? ' ' . numberToWords($remainder) : '');
    }

    // Handling larger numbers, e.g., millions, billions, etc.
    // You can extend this further based on your needs

    return 'Number too large to convert';
}

function get_journal_description_total_amount($json_data_id) { 
    foreach ($json_data_id as $key => $item) {
        $json_data_id[$key] = intval($item);
    }
    $amount = DB::table('journal_description')
            ->whereIn('jrnl_desc_id', $json_data_id)
            ->sum('amount');
    return $amount;   
}

if( ! function_exists('in_array_any')){
    function in_array_any(array $needles, array $haystack): bool {
        return array_intersect($needles, $haystack) !== [];
    }
}

// Modified date:: 2024-10-17 (Thu)
if( ! function_exists('datetime_diff_in_minute')){
    function datetime_diff_in_minute(string $date1, string $date2): int {

        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);

        $interval = $date1->diff($date2);

        $minutesDiff = ($interval->h * 60) + $interval->i;

        return intval($minutesDiff);
    }
}
