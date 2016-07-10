<?php

/**
 *	custom functions to add rows to CV table
 *	WARN! - function could be defined in other controlers too
 */
function addCVrow($key = false, $val = false)
{
	// early return if empty field
	if($key && empty($val) && false !== $val)
		return '';

	if(!$key)
		$key = '&nbsp;';

	$row = '<tr>';

	$row .= $val ? '<th align="right">'.$key.'</th><td>'.$val.'</td>'
				 : '<th colspan="2">'.$key.'</th>';

	$row .= '</tr>';

	return $row;
}


function CVdate($date)
{
	if(empty($date))
		return 'present';

	$date = (object)date_parse($date);

	$year	= $date->year;
	$day	= (1 == $date->day) ? '' : '-'.str_pad($date->day, 2, '0', STR_PAD_LEFT);
	$month	= (1 == $date->month  && 1 == $date->day) ? '' : '-'.str_pad($date->month, 2, '0', STR_PAD_LEFT);

	return $year.$month.$day;
}


// End of custom functions




require_once getcwd().'/include/common.php';

$id = Util::requestVar('id');

if(!$id)
	throw new Exception('Error Processing Request', 1);

$cv = new CV($id);


$rows = '';

$rows .= addCVrow('PERSONAL INFORMATION').
		 addCVrow().
		 addCVrow('Name', $cv->user->name) .
		 addCVrow('Address', $cv->user->address) .
		 addCVrow('Phone', $cv->user->phone) .
		 addCVrow('E-mail', $cv->user->email) .
		 addCVrow('Skype', $cv->user->skype) .
		 addCVrow('Nationality', $cv->user->nationality) .
		 addCVrow('Date of birth', CVdate($cv->user->birthday)) .
		 addCVrow();

$rows .= addCVrow('EMPLOYMENT HISTORY').
		 addCVrow();

foreach($cv->employments as $e)
	$rows .=
		addCVrow('Dates (from-tо)', CVdate($e->date_from) . ' - ' . CVdate($e->date_to)).
		addCVrow('Employer', $e->employer).
		addCVrow('Position', $e->position).
		addCVrow('Types of activity', $e->activity).
		addCVrow();

$rows .= addCVrow('EDUCATION').
		 addCVrow();

foreach($cv->educations as $e)
	$rows .=
		addCVrow('Dates (from-tо)', CVdate($e->date_from) . ' - ' . CVdate($e->date_to)).
		addCVrow('Educational facility', $e->facility).
		addCVrow('Main professional skills', $e->skills).
		addCVrow('Qualification title', $e->qualification).
		addCVrow();


$rows .= addCVrow('LinkedIn', $cv->user->linkedin).
		 addCVrow('Driving license', $cv->user->driving_license ? 'Yes' : 'No')
		 ;




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
Util::p($cv);
*/


