<?php

/**
 * 9/16/2022
 * AUTHOR:PDV-PC
 */

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class User extends Entity {
	protected $dates = [
		'u_created_at',
		'u_updated_at',
		'u_deleted_at',
	];

	public function verifyPassword($password)
	{
		return password_verify($password, $this->u_password_hash);
	}
}
