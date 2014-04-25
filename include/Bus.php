<?php
	if (!class_exists('Bus'))
	{
		require_once 'include/Mysql.php';
		require_once 'Configuration.php';
	
		class Bus
		{
			public $Numero;
			public $Remarque;
			public $Ligne;
			public $Sens;
			
			public static function Get($BusId = null)
			{
				$base = Mysql::Get();
				if ($BusId !== null)
				{
					$query = 'SELECT * FROM Bus WHERE Numero = '.intval($BusId);
					error_log($query);
					$res = $base->Query($query);
					$lignes = mysql_fetch_object($res, 'Bus');
					if ($lignes === false)
						$lignes = null;
				}
				else
				{
					$query = 'SELECT * FROM Bus';
					error_log($query);
					$res = $base->UnbufferedQuery($query);
					while ($record = mysql_fetch_object($res, 'Bus'))
					{
						$lignes[intval($record->Numero)] = $record;}
				}
				mysql_free_result($res);
				return $lignes;
			}
			
			public function	__construct()
			{
				$this->m_class = 'Bus';
				$this->location = Configuration::$Images['location'];
				$this->cache = Configuration::$Images['cache'];
			}
			
			public function Save()
			{
				$id = null;
				if ($this->Numero == null)
					{	$base = Mysql::Get();
						$query = 'INSERT INTO Bus (Numero,Remarque,Ligne,Sens) VALUES (NULL';
						if ($this->Remarque !== NULL)
							$query .= ',\''.$this->Remarque.'\'';
						else
							$query .= ', NULL ';
						if ($this->Ligne !== NULL)
							$query .= ',\''.$this->Ligne.'\'';
						else
							$query .= ', NULL ';
						if ($this->Sens !== NULL)
							$query .= ',\''.$this->Sens.'\')';
						else
							$query .= ', NULL )';			
						error_log($query);
						$res = $base->Query($query);
						if ($res !== true)
							{
								error_log(mysql_error());
								error_log($query);
								return null;
							}
							$id = $base->GetInsertId();
							$this->Numero = $id;
					}
				else
					{
						$base = Mysql::Get();
						$query = 'UPDATE Bus SET Remarque = \''.$this->Remarque.'\' Where Numero = '.$this->Numero.'';
						error_log($query);
						$res = $base->Query($query);
						if ($res !== true)
							{
								error_log(mysql_error());
								error_log($query);
								return null;
							}
							$id = $base->GetInsertId();
							$this->Numero = $id;
					}
				return $id;
			}
			
			public function GetBus($ligne = null,$sens = null,
							$gare = null,$heureMin = null,$heureMax = null)
			{	$base = Mysql::Get();
				$conditions =array();
				$lignes=array();
				$joins =array(); 
				$having = array();
				
					$query = 
					'SELECT b.* FROM Bus b ';
					
				
				if ($ligne !== null || $sens !== null || $gare !== null || $heureMin !== null || $heureMax !== null)
					{
						if ($ligne !== null)
						{$conditions[]= ' Ligne =  '.intval($ligne).' ';
							if ($sens !== null)
							{
							$conditions[]= ' Sens =  '.intval($sens).' ';
							}
						}
						if ($gare !== null || $heureMin !== null || $heureMax !== null)
							{	$joins[] = ' JOIN Horaires h ON h.Bus = b.Numero ';
								if ($gare !== null)
								{
								$conditions[]= ' h.Gare = '.intval($gare).' ';
								}
								if ($heureMin !== null)
								{
								$having[]= ' MIN(h.Heure) >= \''.intval($heureMin).':00\'';
								}
								if ($heureMax !== null)
								{
								$having[]= ' MIN(h.Heure) < \''.intval($heureMax).':00\'';
								}
							}
						if (count($joins) > 0)
						{
						$query .= ' '.implode(' ', $joins);
						}
						if (count($conditions) > 0)
						{
						$query .= ' WHERE '.implode(' AND ',$conditions);
						}
						$query .= ' GROUP BY b.Numero ';
						if (count($having) > 0)
						{
						$query .= ' HAVING '.implode(' AND ',$having);
						}
						$query .= ' ORDER BY MIN(h.Heure) ASC';
					}
					
				else
					{
						$query = 'SELECT * FROM Bus';
					}
					error_log($query);
						$res = $base->Query($query);
						while ($record = mysql_fetch_object($res, 'Bus'))
						$lignes[] = $record;
						
						if ($lignes === false)
							$lignes = null;
					mysql_free_result($res);
					return $lignes;
			}
			
			public function getHoraireBus($lieuD,$lieuA,$heureD,$minuteD,$ligne_bus,$nbres=1)
			{
				$ligne=array();
				$base = Mysql::Get();
				if ($nbres == 1)
				{
					$query =
					'SELECT hd.Bus, hd.Heure heureD, ha.Heure heureA
					FROM Horaires hd
					JOIN Horaires ha ON hd.Bus = ha.Bus
					WHERE hd.Heure < ha.Heure
					AND hd.Gare ='.$lieuD.'
					AND ha.Gare ='.$lieuA.'
					AND hd.Heure >= TIME( "'.$heureD.':'.$minuteD.':00" ) 
					AND hd.Bus IN (SELECT Numero FROM Bus WHERE Ligne = '.intval($ligne_bus).')
					ORDER BY hd.Heure ASC 
					LIMIT 1';
					error_log("getHoraireBus Cas 1");
					error_log($query);
					$res = $base->Query($query);
					$ligne = mysql_fetch_array($res);
					mysql_free_result($res);
				}
				else
				{
					$query =
					'SELECT hd.Bus, hd.Heure heureD, ha.Heure heureA
					FROM Horaires hd
					JOIN Horaires ha ON hd.Bus = ha.Bus
					WHERE hd.Heure < ha.Heure
					AND hd.Gare ='.$lieuD.'
					AND ha.Gare ='.$lieuA.'
					AND hd.Heure >= TIME( "'.$heureD.':'.$minuteD.':00" ) 
					AND hd.Bus IN (SELECT Numero FROM Bus WHERE Ligne = '.intval($ligne_bus).')
					ORDER BY hd.Heure ASC 
					LIMIT '.intval($nbres);
					$res = $base->Query($query);
					error_log("getHoraireBus Cas 2");
					error_log($query); 
					if($res === false)
						return false;
					while ($row = mysql_fetch_array($res,MYSQL_ASSOC))
						$ligne[] = $row;
					mysql_free_result($res);
				}
				if ($ligne === false)
					return false;
				return $ligne;
			}

			public function getHoraireBusPrecedent($lieuD,$lieuA,$heureD,$minuteD,$ligne_bus)
			{
				$ligne=array();
				$base = Mysql::Get();
				$query =
				'SELECT hd.Bus, hd.Heure heureD, ha.Heure heureA
				FROM Horaires hd
				JOIN Horaires ha ON hd.Bus = ha.Bus
				WHERE hd.Heure < ha.Heure
				AND hd.Gare ='.$lieuD.'
				AND ha.Gare ='.$lieuA.'
				AND hd.Heure >= (TIME( "'.$heureD.':'.$minuteD.':00" ) - TIME( "00:05:00" ))
				AND hd.Heure < TIME( "'.$heureD.':'.$minuteD.':00" )
				AND hd.Bus IN (SELECT Numero FROM Bus WHERE Ligne = '.intval($ligne_bus).')
				ORDER BY hd.Heure ASC 
				LIMIT 1';
				error_log("getHoraireBusPrecedent");
				error_log($query);
				$res = $base->Query($query);
				if($res === false)
					return false;
				$ligne = mysql_fetch_array($res);
				mysql_free_result($res);
				if ($ligne === false)
					return false;
				return $ligne;
			}
			
			public function getListeHoraireBusPrecedent($lieuD,$lieuA,$heureD,$minuteD,$ligne_bus)
			{
				$ligne=array();
				$base = Mysql::Get();
				$query =
				'SELECT hd.Bus, hd.Heure heureD, ha.Heure heureA
				FROM Horaires hd
				JOIN Horaires ha ON hd.Bus = ha.Bus
				WHERE hd.Heure < ha.Heure
				AND hd.Gare ='.$lieuD.'
				AND ha.Gare ='.$lieuA.'
				AND hd.Heure >= (TIME( "'.$heureD.':'.$minuteD.':00" ) - TIME( "1:00:00" ))
				AND hd.Heure < (TIME( "'.$heureD.':'.$minuteD.':00" ) - TIME( "00:05:00" ))
				AND hd.Bus IN (SELECT Numero FROM Bus WHERE Ligne = '.intval($ligne_bus).')
				ORDER BY hd.Heure ASC';
				$res = $base->Query($query);
				error_log("getListeHoraireBusPrecedent");
				error_log($query);
				if ($res === false)
					return false;
				while ($row = mysql_fetch_array($res,MYSQL_ASSOC))
					$ligne[] = $row;
				mysql_free_result($res);
				if ($ligne === false)
					return false;
				return $ligne;
			}
			
			
			public function getListeHoraire()
			{
				$ligne=array();
				$base = Mysql::Get();
				$query =
				'SELECT * FROM Horaires';
				$res = $base->Query($query);
				while ($row = mysql_fetch_array($res,MYSQL_ASSOC))
					$ligne[] = $row;
				mysql_free_result($res);
				if ($ligne === false)
					return false;
				return $ligne;
			}
			
			public function getListeHorairesByBus($busId)
			{
				$ligne=array();
				$base = Mysql::Get();
				$query =
				'SELECT * FROM Horaires WHERE Bus ='.intval($busId);
				$res = $base->Query($query);
				while ($row = mysql_fetch_array($res,MYSQL_ASSOC))
					$ligne[] = $row;
				mysql_free_result($res);
				if ($ligne === false)
					return false;
				return $ligne;
			}
			
			public function setPassage($busId,$lieuD,$mail=null,$telephone=null)
			{
				$base = Mysql::Get();
				$curTime = date('Y-m-d H:i:s');
				$query = 'INSERT INTO Incident (Bus, Nature, DateTime';						
						if ($mail !== null && preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", $mail))
							$query .= ', Email';
						if ($telephone !== null && $telephone !== "")
							$query .= ', Telephone';
						if ($lieuD !== null && $lieuD !=="")
						{
							$query .= ', GareId';
							$query .= ', Retard';

						}
					$query .= ') SELECT \''.$base->Escape($busId).'\', \' Retard \', \''.$base->Escape($curTime).'\'';
					if ($mail !== null && preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", $mail))
						$query .= ', '.$base->Escape($mail).'\'';
					if ($telephone !== null && $telephone !== "")
						$query .= ', '.$base->Escape($telephone).'\'';
					if ($lieuD !== null && $lieuD !=="")
					{
						$query .= ', '.$base->Escape($lieuD).'';
						$query .= ', TIME_TO_SEC(TIMEDIFF(CURTIME(), Heure))';
						$query .=' FROM Horaires WHERE Bus = '.$busId.' AND Gare='.$lieuD.' LIMIT 1;';
					}

				$res = $base->Query($query);
				if ($res !== true)
				{
					error_log(mysql_error());
					error_log($query);
					return null;
				}
			}

			public function getRetard($busId)
			{
				$base = Mysql::Get();
				$query = 'SELECT Retard
				FROM Incidents
				WHERE Bus = '.intval($busId).' 
				AND Retard != 0 
				AND DateTime > CONCAT(CURDATE()," 00:00:00") ORDER BY Retard DESC LIMIT 1';
				$res = $base->Query($query);
				if($res === false)
					return $res;
				if (mysql_num_rows($res)==0)
					return 0;
				$retard = mysql_result($res,0);
				mysql_free_result($res);
				if ($retard <= 0)
					return 0;
				$retardMin=$retard/60;
				return $retardMin;
			}

			public function getAlerte($busId)
			{
				$base = Mysql::Get();
				$query = 'SELECT Nature
				FROM Incidents
				WHERE Bus = '.intval($busId).' 
				AND Retard = 0 
				AND DateTime > CONCAT(CURDATE()," 00:00:00") 
				ORDER BY Retard DESC LIMIT 1';
				$res = $base->Query($query);
				if($res === false)
					return $res;
				if (mysql_num_rows($res)==0)
					return false;
				$alerte = mysql_result($res,0);
				mysql_free_result($res);
				return $alerte;
			}

			public function setAlerte($busId,$lieuD,$mail=null,$telephone=null,$nature)
			{
				$base = Mysql::Get();
				$curTime = date('Y-m-d H:i:s');
				$query = 'INSERT INTO Incident (Bus';						
						if ($mail !== null && preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", $mail))
							$query .= ', Email';
						if ($telephone !== null && $telephone !== "")
							$query .= ', Telephone';
						if ($nature !== null && $nature !== "")
							$query .= ', Nature';
							$query .= ', Retard';
							$query .= ', GareId';
							$query .= ', DateTime';
					$query .= ') VALUES (\''.$base->Escape($busId).'\'';
					if ($mail !== null && preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", $mail))
							$query .= ', '.$base->Escape($mail).'\'';
					if ($telephone !== null && $telephone !== "")
							$query .= ', '.$base->Escape($telephone).'\'';
					if ($nature !== null && $nature !== "")
						$query .= ',\''.$base->Escape($nature).'\'';
						$query .= ', 0';
						$query .= ',\''.$base->Escape($lieuD).'\'';
						$query .= ',\''.$base->Escape($curTime).'\'';
					$query .= ')';
				
				$res = $base->Query($query);
				if ($res !== true)
				{
					error_log(mysql_error());
					error_log($query);
					return null;
				}
			}

			public function contact($text,$mail=null)
			{
				$TO = "webmaster@monrovaltain.com"; 
				$subject = "Alerte InterCitea"; 
				if($mail !== null && preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", $mail))
					$h = "From: " . $mail; 
				else
					$h = "From: " . $TO; 
				$message = ""; 
				$message .= "$text\n"; 
				mail($TO, $subject, $message, $h);  
			}
		

			public function remerciement($mail)

			{
				if($mail !== null && preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", $mail))
				{
					$headers = 'From: "MonRovaltain"<webmaster@monrovaltain.com>'.PHP_EOL;
					$headers .= 'Content-Type: text/plain; charset="utf-8"'.PHP_EOL;
					$msg = 'Votre demande a bien été prise en compte, nous vous en remercions.'.PHP_EOL;
					mail($mail, 'L\équipe Cansii vous remercie', $msg, $headers);				
				}
				return false;
			}
			
			public function GetRemarque($BusId)
			{
			if ($BusId !== NULL)
				{
				$ligne=array();
				$base = Mysql::Get();
				
				$query = 'SELECT  Remarque
							FROM Bus
							WHERE Numero ='.$BusId;
			
				error_log($query);
				
					$res = $base->Query($query);
					while ($record = mysql_fetch_object($res,'Bus'))
						$ligne[] = $record;
					
					
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
			}
			public function getHeureDepart($idBus)
			{
				if ($idBus !== null)
					$ligne=array();
					$base = Mysql::Get();
					$query = 'SELECT * FROM Horaires where Bus ='.$idBus.''; 
					$query .= '	ORDER BY Heure ASC';
					$query .= '	LIMIT 1';
					error_log($query);
				
					$res = $base->Query($query);
					$ligne = mysql_fetch_object($res,'Horaires');
					
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
			
			public function getHeureArrivee($idBus)
			{
				if ($idBus !== null)
					$ligne=array();
					$base = Mysql::Get();
					$query = 'SELECT * FROM Horaires where Bus ='.$idBus.''; 
					$query .= '	ORDER BY Heure DESC';
					$query .= '	LIMIT 1';
					error_log($query);
				
					$res = $base->Query($query);
					$ligne = mysql_fetch_object($res,'Horaires');
					
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
			
			public function getGareDepart()
			{
				if ($this->Numero !== null)
				{
					$ligne=array();
					$base = Mysql::Get();
					$query = 'SELECT g.* FROM Gares g'; 
					$query .= '	JOIN Horaires h ON h.Gare = g.IdGare';
					$query .= '	WHERE g.IdGare = ';
					$query .= '(SELECT Gare from Horaires WHERE Bus ='.$this->Numero.'';
					$query .= ' ORDER BY Heure ASC';
					$query .= ' LIMIT 1)';
					error_log($query);
				
					$res = $base->Query($query);
					$ligne = mysql_fetch_object($res,'Gares');
					
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
			
			}
			
			public function getGareArrivee()
			{
				if ($this->Numero !== null)
				{
					$ligne=array();
					$base = Mysql::Get();
					$query = 'SELECT g.* FROM Gares g'; 
					$query .= '	JOIN Horaires h ON h.Gare = g.IdGare';
					$query .= '	WHERE g.IdGare = ';
					$query .= '(SELECT Gare from Horaires WHERE Bus ='.$this->Numero.'';
					$query .= ' ORDER BY Heure DESC';
					$query .= ' LIMIT 1)';
					error_log($query);
				
					$res = $base->Query($query);
					$ligne = mysql_fetch_object($res,'Gares');
					
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
			
			}
			
			
			public function getHoraire($IdDepart,$heureMin,$heureMax)
			{
				$ligne=array();
				$base = Mysql::Get();
				$conditions =array();
				
			
				if ($IdDepart !== null)
					$conditions[]=' hd.Gare ='.intval($IdDepart);
					
				if ($heureMin !== null)
					$conditions[]=' hd.Heure >= \''.intval($heureMin).':00\'';
				if ($heureMax !== null)
					$conditions[]=' hd.Heure < \''.intval($heureMax).':00\'';
				
					
				$query = 'SELECT  hd.Heure,hd.Bus,hd.IdHoraire
							FROM Horaires hd
							JOIN Horaires ha ON hd.Bus = ha.Bus';
				$conditions[]='hd.Heure < ha.Heure';
				if (count($conditions) > 0)	
					$query .= ' Where '.implode(' AND ',$conditions);
					
				$query.= ' Group by hd.Heure
							Order by hd.Heure ASC';	
				error_log($query);
				
					$res = $base->Query($query);
					while ($row = mysql_fetch_array($res,MYSQL_ASSOC))
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

			public function ListeArrets()
			{
			
				$base = Mysql::Get();
				
				if ($this->Ligne !== null && $this->Sens !== null) 
				{
					
					$query = 'Select g.* from Gares g
							JOIN GaresLignes gl ON gl.id_Gare = g.IdGare
							WHERE gl.id_Ligne = '.$this->Ligne;
					if ($this->Sens == '0')
						$query .= ' ORDER BY gl.Ordre ASC';
					if ($this->Sens == '1')
						$query .= ' ORDER BY gl.Ordre DESC';
				}
				
					error_log($query);
						
						$res = $base->Query($query);
						while ($row = mysql_fetch_object($res,'Gares'))
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
			public function ListeArretsLigne($IdLigne, $Sens)
			{
			$base = Mysql::Get();
				if ($IdLigne !== null && $Sens !== null)
				{
				$query = 'Select g.* from Gares g
							JOIN GaresLignes gl ON gl.id_Gare = g.IdGare
							WHERE gl.id_Ligne = '.$IdLigne.'';
					if ($Sens == '0')
						$query .= ' ORDER BY gl.Ordre ASC';
					if ($Sens == '1')
						$query .= ' ORDER BY gl.Ordre DESC';
				
				error_log($query);
						$ligne;
						$res = $base->Query($query);
						if ($res)
						{
						
						while ($row = mysql_fetch_object($res,'Gares'))
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
				}
			}
		}
		
	}	
		
?>
