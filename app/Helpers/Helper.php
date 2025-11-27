<?php

namespace App\Helpers;
use Illuminate\Support\Facades\DB;

class Helper
{
	public static function genSeatNumbers($total_col, $total_row, $useHyphen = true)
	{
		$ch = 65;
		$j = 1;
		$data = [];
		for ($i = 1; $i <= ($total_col * $total_row); $i++, $j++) {
			if ($useHyphen) {
				$data[$i] = chr($ch) . "-{$j}";
			} else {
				$data[$i] = chr($ch) . "{$j}";
			}

			if ($i % $total_col == 0) {
				$ch++;
				$j = 0;
			}
		}
		return $data;
	}

}