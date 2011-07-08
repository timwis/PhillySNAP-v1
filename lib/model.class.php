<?php

class Model {
	var $models;
		
	function __construct() {
		// Load Models
		if(isset($this->models)) {
			foreach($this->models as $model) {
				if(file_exists('models/'.strtolower($model).'.php')) {
					require_once('models/'.strtolower($model).'.php');
					$this->$model = new $model;
				}
				else
					trigger_error('Model \''.$model.'\' not found');
			}
		}
	}

	function join_top_record($table, $foreignAlias, $foreignKey, $localAlias, $localKey, $agFunc, $agField) {
		$sql = "LEFT JOIN `$table` AS $foreignAlias
					ON $foreignAlias.`$foreignKey` = $localAlias.`$localKey`
				JOIN (
					SELECT {$foreignAlias}3.`$foreignKey`, $agFunc({$foreignAlias}3.`$agField`) AS {$foreignAlias}Ag
					FROM `$table` AS {$foreignAlias}3
					GROUP BY {$foreignAlias}3.`$foreignKey`
				) AS {$foreignAlias}2
					ON {$foreignAlias}2.`$foreignKey` = $foreignAlias.`$foreignKey`
					AND {$foreignAlias}2.`{$foreignAlias}Ag` = $foreignAlias.`$agField`";
		return $sql;
	}
}

?>