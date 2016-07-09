<?php

$iname = 'xmlcv';
$xml_file = '/tmp/'.$iname.'.xml';

if( !count($_FILES) || !isset($_FILES[$iname]) || 'text/xml' != $_FILES[$iname]['type'] )
	return;

if(!move_uploaded_file($_FILES[$iname]["tmp_name"], $xml_file))
	throw new Exception('Could not move uploaded file', 1);


$xml = simplexml_load_file($xml_file);

if(false === $xml)
	throw new Exception('Could not load XML file', 1);


$cv = CV::fromXML($xml);

header('Location: cv.php?id='.$cv->id);
exit;

