<?php

class Template {

	public $title;
	public $content;
	public $vars;


	public function __construct($tpl = false)
	{
		$this->vars = (object)[];

		if($tpl)
			$this->load($tpl);

		return $this;
	}


	public function load($tpl)
	{
		ob_start();

		include 'tpl/'.$tpl.'.html';

		$this->content = ob_get_clean();

		return $this;
	}


	public function parse($data = false)
	{
		// early return
		if(empty($this->content))
			return false;

		if(!$data)
			$data = $this->vars;

		foreach ($data as $key => $val)
			if(is_array($val) || is_object($val))
				$this->parse($val);
			else
				$this->content = str_replace('{{'.$key.'}}', $val, $this->content);

		return $this;
	}


	public function set($key, $val = false)
	{
		if( !$val && (is_array($key) || is_object($key)) )
			foreach($key as $k => $v)
				$this->set($k, $v);
		else
			$this->vars->$key = $val;

		return $this;
	}


	public function flush()
	{
		//ob_start('ob_gzhandler');
		echo $this->content;

		return $this;
	}


}
