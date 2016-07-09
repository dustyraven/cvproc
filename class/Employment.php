<?php

class Employment extends Model {

	public $id;
	public $cv_id;

	public static function fromXML($xml)
	{

		$e = new self;

		$e->date_from 	= xml2date($xml->date_from);
		$e->date_to		= xml2date($xml->date_to);
		$e->employer 	= (string)$xml->employer;
		$e->position 	= (string)$xml->position;
		$e->activity 	= (string)$xml->activity;

		return $e;

	}


}
