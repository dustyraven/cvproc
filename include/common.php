<?php

define('CWD', getcwd() . DIRECTORY_SEPARATOR);

spl_autoload_register(function($class)
{
	$file = str_replace('_', '/', $class).'.php';

	foreach( [ CWD.'class/', '/var/www/lib/' ] as $path)
		if(file_exists($path.$file))
		{
		 	require $path.$file;
		 	break;
		}
});

dError::init(true);

Model::$db = new DB(parse_ini_file(CWD.'db.ini'));


function xml2date($xml_element)
{
	$ts = (string)$xml_element;
	$ts = str_pad($ts, 8, '01', STR_PAD_RIGHT);
	$ts = strtotime($ts);
	return $ts ? strftime('%F', $ts) : null;
}
