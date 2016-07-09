<?php

abstract class Model {

	static $db;

	private $_table;
	private $_primary;
	private $_columns;


	public function __construct($id = false)
	{
		$this->_table = strtolower(get_class($this));

		$this->_columns = self::$db->fetch_array('show columns from `'.$this->_table.'`');

		foreach($this->_columns as $col)
			if('PRI' == $col->Key)
			{
				$this->_primary = $col->Field;
				break;
			}


		if($id)
			$this->load($id);

		return $this;
	}



	public function load($id)
	{

		$query = 'select * from `'.$this->_table.'` where `id` = :id';

		$data = self::$db->fetch_object($query, ['id' => $id]);

		foreach($data as $k => $v)
			$this->$k = $v;

		return $this;
	}




	public function save()
	{

		$params = [];

		foreach($this->_columns as $col)
			if($col->Field != $this->_primary)
				$params[$col->Field] = $this->{$col->Field};

		if($this->{$this->_primary})
			self::$db->update($this->_table, $params, [$this->_primary => $this->{$this->_primary}]);
		else
			$this->{$this->_primary} = self::$db->insert($this->_table, $params);

		return $this;
	}




	/**
	 * custom method for Employment and Education
	 */
	public static function getByCV($cv_id)
	{
		$table = strtolower(get_called_class());

		$query = 'select `id` from `'.$table.'` where `cv_id` = :cv_id order by `date_from` desc';

		$ids = self::$db->fetch_values($query, ['cv_id' => $cv_id]);

		$data = [];
		foreach ($ids as $id)
			$data[$id] = new static($id);

		return $data;
	}

}
