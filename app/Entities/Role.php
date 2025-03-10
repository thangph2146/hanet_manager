<?php

/**
 * 9/16/2022
 * AUTHOR:PDV-PC
 */

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Role extends Entity {
	protected $dates = [
		'r_created_at',
		'r_updated_at',
		'r_deleted_at',
	];
}
