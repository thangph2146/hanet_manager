<?php

/**
 * 9/16/2022
 * AUTHOR:PDV-PC
 */

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Permission extends Entity {
	protected $dates = [
		'p_created_at',
		'p_updated_at',
		'p_deleted_at',
	];
}
