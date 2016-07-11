<?php

require_once getcwd().'/include/common.php';

try {
	require_once CWD.'include/parseXML.php';
} catch (Exception $e) {
	$session['errors'][] = $e->getMessage();
}



$cvs = CV::all();

$cvrows = '';

foreach ($cvs as $cv)
	$cvrows .= (new Template('cvlistrow'))
				->set('id',		 $cv->id)
				->set('name',	 $cv->user->name)
				->set('created', strftime('%F',strtotime($cv->uploaded)))
				->set('updated', strftime('%F',strtotime($cv->last_edited)))
				->parse()
				->content;


$content = (new Template('home'))
			->set('title','CV Processor')
			->set('cvrows',$cvrows)
			->parse()
			->content;




$tpl = (new Template('main'))
		->set('title','CV Processor')
		->set('content', $content)
		->parse();

header('Content-Type: text/html; charset=UTF-8');
$tpl->flush();

