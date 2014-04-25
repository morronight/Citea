<?php
	if (!class_exists('Lignes'))
	{
		require_once 'include/Mysql.php';
		require_once 'Configuration.php';
	
		class Lignes
		{
			public $Id;
			public $Nom;
			
			private function GetGareDepart_Arrivee ($direct)
			{
				$base = Mysql::Get();
				$query = 
				'SELECT g.* 
					FROM Gares g 
					JOIN GaresLignes gl ON gl.id_Gare = g.IdGare 
					WHERE gl.id_Ligne = '.$this->Id;
				if ($direct == TRUE)			
				{
					$query .= '
					ORDER BY gl.Ordre ASC'; 
				}
				if ($direct == FALSE)			
				{
					$query .= '
					ORDER BY gl.Ordre DESC'; 
				}
				$query .= '
					LIMIT 1 ';
					$res = $base->Query($query);
					$lignes = mysql_fetch_object($res, 'Gares');
					if ($lignes === false)
						$lignes = null;
				mysql_free_result($res);
				return $lignes;	
			}
							
			public function Get($id = null,$tri = null)
			{	
				$lignes=array();
				if ($id !== null)
				{
					
					$base = Mysql::Get();
					$query =
					'SELECT * FROM Lignes WHERE Id ='.intval($id);
					$res = $base->Query($query);
					$lignes = mysql_fetch_object($res,'Lignes');
					if ($lignes === false)
						$lignes = null;
				}
				else
				{
					$base = Mysql::Get();
					$query =
					'SELECT * FROM Lignes';
					if ($tri == TRUE)			
					{
						$query .= '
						ORDER BY Nom ASC'; 
					}
					if ($tri == FALSE)			
					{
						$query .= '
						ORDER BY Nom DESC'; 
					}
					$res = $base->UnbufferedQuery($query);
					if ($res === null)
						return false;
					while ($record = mysql_fetch_object($res, 'Lignes'))
						$lignes[intval($record->Id)] = $record;
					
				}
				mysql_free_result($res);
				return $lignes;
			}
			
			public function GetGareDepart($direct)
			{
		
				return $this->GetGareDepart_Arrivee($direct);
			}
			
			public function GetGareArrivee($direct)
			{
				return $this->GetGareDepart_Arrivee(!($direct));
			}
			
		}
	}