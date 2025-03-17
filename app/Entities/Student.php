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
		// return $this->PW == md5("UisStaffID=" . $this->student_id . ";UisPassword=" . $password);

        return password_verify($password, $this->PW);

	}
}
