<?php

class StoresController extends Controller {
	var $models = array('Store', 'Market');
	var $helpers = array('Html');

	function index() {
	}

	function search($address = '', $limit = 5, $offset = 0) {
		if($address) {
			$address = sanitize($address);

			// Make sure address includes 'philadelphia' or zip code
			if(strpos(strtolower($address), 'philadelphia') === FALSE && !preg_match('/\d{5}$/', trim($address)))
			{
				// If it contains 'phila', just replace that with 'philadelphia'
				if(strpos(strtolower($address), 'phila') !== FALSE)
					$address = str_replace('phila', 'philadelphia');
				// Otherwise append the whole word 'philadelphia'
				else
					$address .= ' philadelphia';
			}

			if($latlon = geocode($address)) {
				if($stores = $this->Store->search($latlon['Latitude'], $latlon['Longitude'], $limit, $offset))
					$this->set('stores', $stores);
				
				if($market = $this->Market->search($latlon['Latitude'], $latlon['Longitude']))
					$this->set('market', $market);
			}
			else
				$this->set('error', 'Error locating that address. Please check the address format and try again.');

			$this->set('address', $address);
		}
	}
}

?>