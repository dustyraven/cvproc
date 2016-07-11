<?php

class CV extends Model {

	public $id;
	public $user_id;

	public function __construct($id = false)
	{
		if($id)
			$this->load($id);
		else
		  $this->user = new User;

		return $this;
	}


	public static function all($params = [])
	{
		$query = 'select `id` from `cv`'; // TODO - order, limit, etc.
		$ids = self::$db->fetch_values($query);

		$data = [];
		foreach ($ids as $id)
			$data[$id] = new CV($id);

		return $data;

	}


	public static function fromXML($xml)
	{
		$cv = new self;


		// here we must do some checks. for now we assume that all fields are filled corectly

		if($cv->user->getByName((string)$xml->name))
			throw new Exception("CV for this name already exists", 1);


		foreach(['name', 'address', 'phone', 'email', 'skype', 'linkedin', 'nationality'] as $param)
			$cv->user->$param = (string)$xml->$param;

		$cv->user->driving_license = (int)$xml->driving_license;

		$cv->user->birthday = xml2date($xml->birthday);

		// skills

		$skills = [];

		foreach($xml->skills->skill as $skill)
			$skills[] = (string)$skill;

		$cv->user->skills = implode(', ', $skills);


		// langs

		$langs = [];

		foreach($xml->languages->language as $lang)
			$langs[] = (string)$lang;

		$cv->user->languages = implode(', ', $langs);



		// employments

		$cv->employments = [];

		foreach($xml->employments->employment as $empl)
			$cv->employments[] = Employment::fromXML($empl);



		// educations

		$cv->educations = [];

		foreach($xml->educations->education as $edu)
			$cv->educations[] = Education::fromXML($edu);


		// save

		$cv->save();

		return $cv;

	}



	public function load($id)
	{
		$query = 'select * from `cv` where `id` = :id';

		$data = self::$db->fetch_object($query, ['id' => $id]);

		if(!$data)
			throw new Exception('Invalid CV', 1);

		foreach($data as $k => $v)
			$this->$k = $v;

		$this->user = new User($this->user_id);

		$this->employments = Employment::getByCV($this->id);

		$this->educations = Education::getByCV($this->id);

		return $this;
	}



	public function save()
	{
		$this->user->save();

		if(empty($this->user_id))
			$this->user_id = $this->user->id;

		$params = [
			'user_id'	=> $this->user_id,
			'last_edited'	=> strftime('%F %T'),
		];

		if($this->id)
		{
			self::$db->update('cv', $params, ['id' => $this->id]);
		}
		else
		{
			$params['uploaded'] = strftime('%F %T');
			$this->id = self::$db->insert('cv', $params);
		}


		foreach($this->employments as &$e)
		{
			if(empty($e->cv_id))
				$e->cv_id = $this->id;

			$e->save();
		}

		foreach($this->educations as &$e)
		{
			if(empty($e->cv_id))
				$e->cv_id = $this->id;

			$e->save();
		}





		return $this;
	}





}


/*
SimpleXMLElement Object
(
	[name] => Daniel Sabev Denev
	[address] => Patriarh Evtimii blvd., Sofia, Bulgaria
	[phone] => +359 878 510 454
	[email] => dusty@gbg.bg
	[nationality] => Bulgarian
	[birthday] => 1974-10-13
	[skype] => dusty_raven
	[linkedin] => https://bg.linkedin.com/in/danieldenev
	[driving_license] => 1
	[employments] => SimpleXMLElement Object
		(
			[employment] => Array
				(
					[0] => SimpleXMLElement Object
						(
							[date_from] => 2012
							[date_to] => SimpleXMLElement Object
								(
								)

							[employer] => Opencode Systems Ltd.
							[position] => R&D Engineer
							[activity] => Developing tools for processing large amounts of data and statistics.
						)

					[1] => SimpleXMLElement Object
						(
							[date_from] => 2008
							[date_to] => 2012
							[employer] => Sexwell EOOD
							[position] => CTO
							[activity] =>
				Managing the IT department
				Creating and managing the company web sites
				Creating and developing the intra-company CRM/ERP system

						)

					[2] => SimpleXMLElement Object
						(
							[date_from] => 2007
							[date_to] => 2008
							[employer] => StangaOne
							[position] => PHP & MySQL developer
							[activity] => Working on various company projects
						)

					[3] => SimpleXMLElement Object
						(
							[date_from] => 2004
							[date_to] => 2007
							[employer] => Self-employed
							[position] => WEB developer
							[activity] => Creating and developing web sites for a vast range of clients
						)

					[4] => SimpleXMLElement Object
						(
							[date_from] => 2003
							[date_to] => 2004
							[employer] => Balkan restaurant, Quality Inn Horizon Hotel, Dubai, U.A.E.
							[position] => Musician
							[activity] => Musician
						)

					[5] => SimpleXMLElement Object
						(
							[date_from] => 2001
							[date_to] => 2003
							[employer] => Supporting Victims of Crimes and Combating Corruption Foundation
							[position] => Project coordinator
							[activity] => SimpleXMLElement Object
								(
								)

						)

					[6] => SimpleXMLElement Object
						(
							[date_from] => 1993
							[date_to] => 2001
							[employer] => Self-employed
							[position] => Musician, tone producer
							[activity] =>
				Establishment of a music production and arrangement demo-studio;
				Performing at clubs with a number of bands

						)

					[7] => SimpleXMLElement Object
						(
							[date_from] => 1990
							[date_to] => 1993
							[employer] => Club 113+, Sofia
							[position] => Tone producer
							[activity] => SimpleXMLElement Object
								(
								)

						)

				)

		)

	[educations] => SimpleXMLElement Object
		(
			[education] => SimpleXMLElement Object
				(
					[date_from] => 1988
					[date_to] => 1992
					[facility] => Vocation college A.S. Popov
					[skills] => Electro-technician
					[qualification] => High school
				)

		)

	[languages] => SimpleXMLElement Object
		(
			[language] => Array
				(
					[0] => English
					[1] => Russian
				)

		)

	[skills] => SimpleXMLElement Object
		(
			[skill] => Array
				(
					[0] => PHP
					[1] => MySQL
					[2] => Firebird
					[3] => SQLite
					[4] => HTML
					[5] => CSS
					[6] => JavaScript
					[7] => Apache
					[8] => Nginx
					[9] => SVN
					[10] => GIT
					[11] => SEO
					[12] => UX
					[13] => DOS
					[14] => Linux
					[15] => Windows
					[16] => Office
					[17] => Photoshop
					[18] => CorelDraw
				)

		)

)

*/
