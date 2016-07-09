<?php

require_once getcwd().'/include/common.php';

$id = Util::requestVar('id');

if(!$id)
	throw new Exception('Error Processing Request', 1);

$cv = new CV($id);



function addCVrow($key = false, $val = false)
{
	if(!$key)
		$key = '&nbsp;';

	$row = '<tr>';

	$row .= $val ? '<th align="right">'.$key.'</th><td>'.$val.'</td>'
				 : '<th colspan="2">'.$key.'</th>';

	$row .= '</tr>';

	return $row;
}

$rows = '';

$rows .= addCVrow('PERSONAL INFORMATION').
		 addCVrow().
		 addCVrow('Name', $cv->user->name) .
		 addCVrow('Address', $cv->user->address) .
		 addCVrow('Phone', $cv->user->phone) .
		 addCVrow('E-mail', $cv->user->email) .
		 addCVrow('Skype', $cv->user->skype) .
		 addCVrow('E-mail', $cv->user->email) .
		 addCVrow('Nationality', $cv->user->nationality) .
		 addCVrow('Date of birth', $cv->user->birthday) .
		 addCVrow();

$rows .= addCVrow('EMPLOYMENT HISTORY').
		 addCVrow();

foreach($cv->employments as $e)
	$rows .=
		addCVrow('Dates (from-tо)', $e->date_from . ' - ' . $e->date_to).
		addCVrow('Employer', $e->employer).
		addCVrow('Position', $e->position).
		addCVrow('Types of activity', $e->activity).
		addCVrow();

$rows .= addCVrow('EDUCATION').
		 addCVrow();

foreach($cv->educations as $e)
	$rows .=
		addCVrow('Dates (from-tо)', $e->date_from . ' - ' . $e->date_to).
		addCVrow('Educational facility', $e->facility).
		addCVrow('Main professional skills', $e->skills).
		addCVrow('Qualification title', $e->qualification).
		addCVrow();




//[linkedin] => https://bg.linkedin.com/in/danieldenev
//[driving_license] => 1

$content = (new Template('cv'))
			->set('name', $cv->user->name)
			->set('rows', $rows)
			->parse()
			->content;


$tpl = (new Template('main'))
	 ->set('title','CV - ' . $cv->user->name)
	 ->set('content', $content)
	 ->parse();

header('Content-Type: text/html; charset=UTF-8');
$tpl->flush();

/*
*/

Util::p($cv);

