<?php

class Market extends Model {

	function search($lat, $lon) {
		$limit = 1;
		$round = 2; // Does this need to be a param?
		$sql = "SELECT *, ROUND(((ACOS( SIN($lat * PI()/180 ) * SIN(`latitude` * PI()/180 ) + COS($lat * PI()/180 ) * COS(`latitude` * PI()/180 ) * COS(($lon - `longitude`) * PI()/180))*180/PI())*60*1.1515),$round) AS distance
				FROM `markets`
				ORDER BY distance" . ($limit ? "
				LIMIT $limit" : "");
		if($request = mysql_query($sql)) {
			if(mysql_num_rows($request)) {
				$row = mysql_fetch_assoc($request);
				return $row;
			}
		}
		return array();
	}
}

?>