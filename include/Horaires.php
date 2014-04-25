<?php
	if (!class_exists('Horaires'))
	{
		require_once 'include/Mysql.php';
		require_once 'Configuration.php';
	
		class Horaires
		{
			public $IdHoraire;
			public $Gare;
			public $Bus;
			public $Heure;
			
			public function getListeHorairesDepart($ligne,$sens,$heureMin,$heureMax)
			{
				$ligne=array();
				$base = Mysql::Get();
				$conditions =array();
				
				$query = 'SELECT Heure,Gare,IdHoraire FROM Horaires';
			
			}
			
			public function getHoraireByBus($BusId)
			{
				$ligne=array();
				$base = Mysql::Get();
				$conditions =array();
				
				$query = 'SELECT * FROM Horaires';

				if ($BusId !== null)
					$conditions[]=' Bus ='.intval($BusId);
				
				if (count($conditions) > 0)	
					$query .= ' Where '.implode('',$conditions);
					$query .= ' Order by IdHoraire ASC';
				error_log($query);
					
					$res = $base->Query($query);
					while ($row = mysql_fetch_object($res,'Horaires'))
						$ligne[] = $row;
					
					
						if ($res === false)
						{
						error_log(mysql_error());
						error_log($query);
						return null;
						}
					mysql_free_result($res);
					if ($ligne === false)
						return false;
					return $ligne;
			}	
			
			public function Save()
			{
			
				$id = null;
				{
					$base = Mysql::Get();
					
					if ($this->IdHoraire === null)
					{
						$query = 'INSERT INTO Horaires (Heure,Bus,Gare)';
						$query .= ' VALUES (';
						$query .= '\''.$base->Escape($this->Heure).'\'';
						$query .= ', \''.intval($this->Bus).'\'';
						$query .= ', \''.intval($this->Gare).'\')';
						error_log($query);			
						$res = $base->Query($query);
						if ($res !== true)
						{
							error_log(mysql_error());
							error_log($query);
							return null;
						}
						$id = $base->GetInsertId();
						$this->IdHoraire = $id;
					}	
					else
					{
						
						if (($this->Heure) !== null && ($this->Heure != '00:00:00') && ($this->Heure != ''))
								{$query = 'UPDATE Horaires SET';
								$query .= ' Heure = \''.$base->Escape($this->Heure).'\'' ;
								$query .= ' WHERE IdHoraire = '.intval($this->IdHoraire);
								
						
								error_log($query);
								$res = $base->Query($query);
								if ($res !== true)
								{
									error_log(mysql_error());
									error_log($query);
									return null;
								}
								$id = $this->IdHoraire;
							}
						}
				}
				return $id;
			
			}
			
			public static function Get($idHoraire = null)
			{
				$base = Mysql::Get();
				if ($idHoraire !== null)
				{
					$query = 'SELECT * FROM Horaires WHERE IdHoraire = '.intval($idHoraire);
					$res = $base->Query($query);
					$lignes = mysql_fetch_object($res, 'Horaires');
					if ($lignes === false)
						$lignes = null;
				}
				else
				{
					$query = 'SELECT * FROM Horaires';
					$res = $base->UnbufferedQuery($query);
					while ($record = mysql_fetch_object($res, 'Horaires'))
						$lignes[intval($record->IdHoraire)] = $record;
				}
				error_log($query);
				mysql_free_result($res);
				return $lignes;
			}
			
			public function Remove()
			{
				if (($this->IdHoraire !== null))
				{
					$base = Mysql::Get();						
					$query = 'DELETE FROM Horaires WHERE IdHoraire = '.intval($this->IdHoraire);
					error_log($query);
					$res = $base->query($query);
					if ($res !== true)
					{
						error_log(mysql_error());
						error_log($query);
						return false;
					}
					return true;
				}
				return false;
			}
		
		}	
		
	}
?>
