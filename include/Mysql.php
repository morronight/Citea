<?php
	if (!class_exists('Mysql'))
	{
		require_once 'include/Configuration.php';
		
		class Mysql
		{
			private $_link;
			private static $_instance = null;
			private $_savepoints;
		
			private function Mysql()
			{
				$this->_link = mysql_connect(Configuration::$MySql['HOST'], Configuration::$MySql['USER'], Configuration::$MySql['PASS']);
				if ($this->_link)
				{
					mysql_select_db(Configuration::$MySql['BD'], $this->_link);
					mysql_set_charset('utf8', $this->_link);
					mysql_query('SET AUTOCOMMIT=1', $this->_link);
					$this->_savepoints = null;
				}
			}
		
			public static function Get()
			{
				if (Mysql::$_instance === null)
					Mysql::$_instance = new Mysql();
				return Mysql::$_instance;
			}
			
			public function Query($sql)
			{
				if ($res = mysql_query($sql, $this->_link))
					return $res;
				return false;
			}
			
			public function UnbufferedQuery($sql)
			{
				if ($res = mysql_unbuffered_query($sql, $this->_link))
					return $res;
				return false;
			}
			
			public function StartTransaction()
			{
				if ($this->_savepoints === null)
				{
					mysql_query('SET AUTOCOMMIT=0', $this->_link);
					mysql_query('START TRANSACTION', $this->_link);
					$this->_savepoints = array();
				}
				else
				{
					$savepoint = uniqid();
					array_push($this->_savepoints, $savepoint);
					mysql_query('SAVEPOINT `'.$savepoint.'`', $this->_link);
				}
			}
			
			public function Commit()
			{
				if (($this->_savepoints === null) || (count($this->_savepoints) == 0))
				{
					mysql_query('COMMIT', $this->_link);
					mysql_query('SET AUTOCOMMIT=1', $this->_link);
					$this->_savepoints = null;
				}
				else
				{
					$savepoint = array_pop($this->_savepoints);
					mysql_query('RELEASE SAVEPOINT `'.$savepoint.'`', $this->_link);
				}
			}
			
			public function Rollback()
			{
				if (($this->_savepoints === null) || (count($this->_savepoints) == 0))
				{
					mysql_query('ROLLBACK', $this->_link);
					mysql_query('SET AUTOCOMMIT=1', $this->_link);
					$this->_savepoints = null;
				}
				else
				{
					$savepoint = array_pop($this->_savepoints);
					mysql_query('ROLLBACK TO SAVEPOINT `'.$savepoint.'`', $this->_link);
				}
			}
			
			public function GetInsertId()
			{
				return mysql_insert_id($this->_link);
			}
			
			public function Escape($texte)
			{
				return mysql_real_escape_string($texte, $this->_link);
			}
			
			public function IsAutocommit()
			{
				$res = $this->Query('SELECT @@AUTOCOMMIT');
				if ($res !== false)
				{
					$row = mysql_fetch_row($res);
					mysql_free_result($res);
					if (($row !== false) && (count($row) > 0))
					{
						if (intval($row) > 0)
							return true;
						return false;
					}
				}
				return null;
			}
			
			public function GetAffectedRows()
			{
				return mysql_affected_rows($this->_link);
			}
		}
	}
?>