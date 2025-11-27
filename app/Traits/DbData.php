<?php 

namespace App\Traits;

use Illuminate\Support\Facades\DB;


trait DbData {
	public function getBooths($transport_id, $connection='greenline_main')
	{
		$transport_id = intval($transport_id);

		return DB::connection($connection)
			->table('booth')->select('booth_id', 'booth_name')
            ->where('transport_id', $transport_id)
            ->orderBy('booth_name', 'ASC')
            ->get();
	}

	public function getBoothByID($transport_id, $booth_id=0, $connection='greenline_main')
	{
		$transport_id = intval($transport_id);
		$booth_id = intval($booth_id);

        if ($booth_id>0) {
            $sql = "SELECT booth_name, booth_id from booth 
                    where booth.transport_id='{$transport_id}' AND booth_id='{$booth_id}'";      
        }else {
            $sql = "SELECT booth_name, booth_id from booth 
                    where booth.transport_id='{$transport_id}' AND booth_block='0'";  
        } 
        return DB::connection($connection)->select($sql);
    }	
}