<?php
	if (!class_exists('GaresLignes'))
	{
		require_once 'include/Mysql.php';
		require_once 'Configuration.php';
	
		class GaresLignes
		{
			public $Id;
			public $id_Ligne;
			public $id_Gare;
			public $Ordre;
			
			public static function Get($BusId = null)
			{
				
			
			}	
		}
	}
?>
