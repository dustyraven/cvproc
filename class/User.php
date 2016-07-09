<?php

class User extends Model {

	public $id;



	public function getByName($name)
	{
		$query = 'select `id` from `user` where `name` = :name';

		return self::$db->fetch_value($query, ['name' => $name]);

	}




	public function save()
	{

		$params = [
			'name'				=> $this->name,
			'birthday'			=> $this->birthday,
			'address'			=> $this->address,
			'phone'				=> $this->phone,
			'email'				=> $this->email,
			'skype'				=> $this->skype,
			'linkedin'			=> $this->linkedin,
			'driving_license'	=> $this->driving_license,
			'nationality'		=> $this->nationality,
			'languages'			=> $this->languages,
			'skills'			=> $this->skills,
		];

		if($this->id)
		{
			self::$db->update('user', $params, ['id' => $this->id]);
		}
		else
		{
			$this->id = self::$db->insert('user', $params);
		}

		return $this;
	}


}
