<?php

class Store extends Model {

	function search($lat, $lon, $limit, $offset) {
		$round = 2; // Does this need to be a param?
		if($limit < 0) $limit = 0; // validation
		if($offset < 0) $offset = 0; // validation

		$sql = "SELECT *, ROUND(((ACOS( SIN($lat * PI()/180 ) * SIN(`latitude` * PI()/180 ) + COS($lat * PI()/180 ) * COS(`latitude` * PI()/180 ) * COS(($lon - `longitude`) * PI()/180))*180/PI())*60*1.1515),$round) AS distance
				FROM `stores`
				ORDER BY distance" . ($limit ? "
				LIMIT $offset, $limit" : "");
		//echo '<pre>'.print_r($sql, true).'</pre>';
		if($request = mysql_query($sql)) {
			if(mysql_num_rows($request)) {
				while($row = mysql_fetch_assoc($request)) {
					$data []= $row;
				}
				return $data;
			}
		}
		return array();
	}
}

?>