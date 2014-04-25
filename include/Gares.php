<?php
	if (!class_exists('Gares'))
	{
		require_once 'include/Mysql.php';
		require_once 'Configuration.php';
	
		class Gares
		{
			public $IdGare;
			public $Nom;
			public $geoLat;
			public $geoLgt;
		
			public function	__construct()
			{
				$this->m_class = 'Bus';
				$this->location = Configuration::$Images['location'];
				$this->cache = Configuration::$Images['cache'];
			}

			public function GetGares($id = null)
			{	
				$lignes=array();
				if ($id !== null)
				{
					
					$base = Mysql::Get();
					$query =
					'SELECT IdGare,Nom,geoLat,geoLgt FROM Gares WHERE IdGare ='.intval($id);
					$res = $base->Query($query);
					$lignes = mysql_fetch_object($res,'Gares');
					if ($lignes === false)
						$lignes = null;
				}
				else
				{
					$base = Mysql::Get();
					$query =
					'SELECT IdGare,Nom,geoLat,geoLgt FROM Gares';
					$res = $base->UnbufferedQuery($query);
					if ($res === null)
						return false;
					while ($record = mysql_fetch_object($res, 'Gares'))
						$lignes[intval($record->IdGare)] = $record;
					
				}
				mysql_free_result($res);
				return $lignes;
			}
			
			


		
		}	
		
	}
?>
