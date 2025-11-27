<?php 

namespace App\Helpers;
use Illuminate\Support\Facades\DB;

class QueryHelper 
{
	public static function ServerTimeToBDTime ($timeFormat = 'Y-m-d')
	{
		return (gmdate($timeFormat, strtotime('+6 hours')));
	}
	
	public static function isCheckedBoothRoute($booth_id, $route_id)
	{
		$booth_id = intval($booth_id);
		$route_id = intval($route_id);
        
        $checked_row = DB::connection('paribahan')->select("SELECT count(*) AS total FROM booth_route WHERE booth_id = ? AND route_id = ?", [$booth_id, $route_id]);
        $is_checked  = ($checked_row[0]->total > 0 ? 'checked': '');
        return $is_checked;
	}
	
	public static function isCheckedBoothSubroute($booth_iid, $route_id, $subroute_id)
	{

	    $booth_iid   = intval($booth_iid);
	    $route_id    = intval($route_id);
	    $subroute_id = intval($subroute_id);

	    $result = DB::connection('paribahan')->select("SELECT COUNT(*) AS total FROM booth_subroute 
														WHERE booth_id = ? 
      													AND route_id = ? 
      													AND subroute_id = ?", [$booth_iid, $route_id,$subroute_id]);

	    return ($result[0]->total > 0) ? 'checked' : '';
	}

	public static function getMemberLoggedInTicketSeatCount($connection, $txtFdate, $txtTdate, $city_dep_ids, $saved_by)
	{
		$txtFdate = date('Y-m-d', strtotime($txtFdate));
		$txtTdate = date('Y-m-d', strtotime($txtTdate));
		$saved_by = intval($saved_by);

		$cityDepIds = array_filter(array_map('intval', explode(',', $city_dep_ids)));

		$results = DB::connection($connection)->table('booking_info')
		    ->join('booking_seat', 'booking_info.booking_info_id', '=', 'booking_seat.booking_info_id')
		    ->selectRaw('COUNT(*) AS seat, ticket_price')
		    ->where('seat_status_id', 4)
		    ->whereBetween('booking_date', [$txtFdate, $txtTdate])
		    ->whereIn('city_dep_id', $cityDepIds)
		    ->where('booking_info.booking_info_saved_by', $saved_by)
		    ->groupBy('ticket_price')
		    ->orderBy('ticket_price')
		    ->get(); 

		return $results;
	}
}
