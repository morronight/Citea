<?php
	if (!class_exists('Utilisateurs'))
	{
		require_once 'include/Mysql.php';
		require_once 'Configuration.php';
	
		class Utilisateurs
		{
			public $Id;
			public $GooglePlus_id;
			public $Facebook_id;

			public static function Get($Id = null,$IdGooglePlus = null, $IdFB = null)
			{
				$base = Mysql::Get();
				$conditions = array();
				if ($Id !== null || $IdFB !== null || $IdGooglePlus !== null)
				{
					if ($Id !== null)
					{$conditions[] = ' Id = \''.intval($Id).'\'';}
					
					if ($IdGooglePlus !== null)
					{$conditions[] = ' Google_id = \''.htmlentities($IdGooglePlus).'\'';}
					
					if ($IdFB !== null)
					{$conditions[] = ' Facebook_id = \''.htmlentities($IdFB).'\'';} 
					
					$query = 'SELECT * FROM Utilisateurs ';
					
					if (count($conditions) > 0)	
					$query .= ' WHERE '.implode('',$conditions);
					
					
					error_log($query);
					$res = $base->Query($query);
					$lignes = mysql_fetch_object($res, 'Utilisateurs');
					if ($lignes === false)
						$lignes = null;
				}
				
				
				mysql_free_result($res);
				return $lignes;
			}
		}
			
	}	
?>
