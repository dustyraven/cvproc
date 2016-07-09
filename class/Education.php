<?php

class Education extends Model {

	public $id;
	public $cv_id;

	public static function fromXML($xml)
	{

		$e = new self;

		$e->date_from 		= xml2date($xml->date_from);
		$e->date_to			= xml2date($xml->date_to);
		$e->facility 		= (string)$xml->facility;
		$e->skills			= (string)$xml->skills;
		$e->qualification	= (string)$xml->qualification;

		return $e;

	}


}
