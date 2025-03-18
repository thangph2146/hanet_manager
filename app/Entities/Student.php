<?php

/**
 * 9/16/2022
 * AUTHOR:PDV-PC
 */

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Student extends Entity {
	protected $dates = [
		'updated_at'
	];

	public function verifyPassword($password)
	{
        return password_verify($password, $this->PW);

	}
}
