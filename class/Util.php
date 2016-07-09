<?php

class Util {

	public static function p($item, $die = false)
	{
		echo '<pre>';
		print_r($item);
		echo '</pre>';

		if($die)
			die();
	}


	public static function requestVar($str, $default = false)
	{
		return isset($_GET[$str]) ? $_GET[$str] : ( isset($_POST[$str]) ? $_POST[$str] : $default );
	}


	public static function xml2date($xml_element)
	{
		$ts = (string)$xml_element;
		$ts = str_pad($ts, 8, '01', STR_PAD_RIGHT);
		$ts = strtotime($ts);
		return $ts ? strftime('%F', $ts) : null;
	}


}
