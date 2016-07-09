<?php

class DB {

	public $dbh = false;
	public $type;
	public $raise = false;
	public $params = false;

	/**
	 * params: type, host, base, user, pass ... etc. ;)
	 */
	function __construct($params = array())
	{
		if(is_array($params) && count($params))
			$this->params = $params;
	}


	public function connect()
	{
		if(!$this->params || !is_array($this->params))
			throw new Exception('No database information');

		extract($this->params);

		$this->type = $type;

		if('sqlite' == $type)
			$dsn = 'sqlite:' . $base;
		else
			$dsn = $type.':host='.$host.';dbname='.$base;


		if(!isset($user)) $user = null;
		if(!isset($pass)) $pass = null;

		if(isset($raise)) $this->raise = $raise;

		$options = array(
			PDO::ATTR_TIMEOUT	=> 5,
			PDO::ATTR_ERRMODE	=> PDO::ERRMODE_EXCEPTION,
		);

		if(isset($opts) && is_array($opts))
			foreach($opts as $k => $v)
				$options[$k] = $v;

		if('mysql' == $type)
			$options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES utf8';

		try {
			$this->dbh = new PDO($dsn, $user, $pass, $options);
		} catch (Exception $e) {

			if($this->raise)
				throw $e;
			else
			{
				if(defined('DUSTY') && DUSTY)
					$err = $e->__toString();
				else
					$err = 'Server overfow!!!';
				die($err);
			}
		}

		return $this->dbh;
	}


	public function query($query, $data = array())
	{
		if(!$this->dbh) $this->connect();

		$params = array();

		if(count($data))
		{
			$query = $query . ' ';	// UGLY HACK
			preg_match_all("/\:\w+/", $query, $matches);

			foreach($matches[0] as $m)
			{
				$params[] = $data[substr($m,1)];
				$query = preg_replace("/(\W){$m}(\W)/", '$1?$2', $query);
			}
		}

		$result = $this->dbh->prepare($query);

		foreach($params as $k => $v)
			if( is_null($v) || 'NULL' == strtoupper($v) )
				$result->bindValue($k+1, null, PDO::PARAM_NULL);
			elseif( is_int($v) )
				$result->bindValue($k+1,   $v, PDO::PARAM_INT);
			elseif( is_bool($v) )
				$result->bindValue($k+1,   $v, PDO::PARAM_BOOL);
			else
				$result->bindValue($k+1,   $v, PDO::PARAM_STR);

		if (! $result->execute())
			return false;

		return $result;

	}


	/**
	 *	FETCH SINGLE VALUE
	 */
	public function fetch_value($query, $data = array())
	{
		$stmt = $this->query($query, $data);
		$v = $stmt->fetchColumn(0);
		$stmt->closeCursor();
		$stmt = null;
		return $v;
	}

	/**
	 *	FETCH SINGLE ROW AS OBJECT
	 */
	public function fetch_object($query, $data = array())
	{
		$stmt = $this->query($query, $data);
		$v = $stmt->fetch(PDO::FETCH_OBJ);
		$stmt->closeCursor();
		$stmt = null;
		return $v;
	}

	/**
	 *	FETCH SINGLE ROW AS ASSOC ARRAY
	 */
	public function fetch_assoc($query, $data = array())
	{
		$stmt = $this->query($query, $data);
		$v = $stmt->fetch(PDO::FETCH_ASSOC);
		$stmt->closeCursor();
		$stmt = null;
		return $v;
	}


	/**
	 *	FETCH ARRAY OF ROWS AS OBJECTS
	 */
	public function fetch_array($query, $data = array())
	{
		$stmt = $this->query($query, $data);
		$v = array();
		foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $row)
			$v[] = $row;
		$stmt->closeCursor();
		$stmt = null;
		return $v;
	}



	/**
	 *	FETCH VALUES (COLUMN)
	 */
	public function fetch_values($query, $data = array())
	{
		$stmt = $this->query($query, $data);
		$v = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
		$stmt->closeCursor();
		$stmt = null;
		return $v;
	}

	/**
	 *	FETCH TWO COLUMNS AS ARRAY col[0] => col[1]
	 */
	public function fetch_key_val($query, $data = array())
	{
		$stmt = $this->query($query, $data);
		$v = array();
		foreach($stmt->fetchAll(PDO::FETCH_NUM) as $row)
			$v[$row[0]] = $row[1];
		$stmt->closeCursor();
		$stmt = null;
		return $v;
	}



	/**
	 *	GET LAST INSERTED ID
	 */
	public function last_insert_id()
	{
		if(!$this->dbh) $this->connect();
		return $this->dbh->lastInsertId();
	}





	/**
	 *	TRANSACTIONS
	 *
	 *	- begin
	 *	- commit
	 *	- rollback
	 */


	/**
	 *	BEGIN TRANSACTION
	 */
	public function begin()
	{
		if(!$this->dbh) $this->connect();
		return $this->dbh->beginTransaction();
	}

	/**
	 *	COMMIT TRANSACTION
	 */
	public function commit()
	{
		if(!$this->dbh) $this->connect();
		return $this->dbh->commit();
	}

	/**
	 *	ROLLBACK TRANSACTION
	 */
	public function rollback()
	{
		if(!$this->dbh) $this->connect();
		return $this->dbh->rollBack();
	}




	/**
	 *	HELPERS
	 *
	 *	- insert
	 *	- update
	 *	- replace
	 */


	/**
	 *	INSERT
	 */
	public function insert($table, $data = array())
	{
		$keys = array_keys($data);

		$q = 'INSERT INTO '.$table.' ('.implode(',',$keys).') VALUES (:'.implode(',:',$keys).')';

		$v = $this->query($q, $data);

		if('firebird' == $this->type)
			return $v;

		if($v)
		{
			$v = null;
			return $this->last_insert_id();
		}

		return false;
	}


	/**
	 *	REPLACE
	 */
	public function replace($table, $data = array())
	{
		$keys = array_keys($data);

		$q = 'REPLACE INTO '.$table.' ('.implode(',',$keys).') VALUES (:'.implode(',:',$keys).')';

		return $this->query($q);
	}


	/**
	 *	UPDATE
	 */
	public function update($table, $data = array(), $pKey = array())
	{
		$errors	= array();
		$fields	= array();
		$keys	= array();

		foreach($data as $key => $val)
			$fields[] = $key.'=:'.$key;

		foreach($pKey as $key => $val)
			$keys[] = $key.'=:'.$key;

		$query = 'UPDATE '.$table.' SET '.implode(',',$fields).' WHERE '.implode(' AND ',$keys);
		return $this->query($query, $data + $pKey);
	}



	/**
	 *	CHECK TABLE EXISTS
	 */
	public function is_table($table)
	{

		if('mysql' != $this->type)
			throw new Exception('Unsupported database type');

		return $this->fetch_value("
					select `table_name`
					from `information_schema`.`tables`
					where `table_type` = 'base table'
					and `table_name` = :table
				", array('table' => $table));
	}


}	//	END OF CLASS

