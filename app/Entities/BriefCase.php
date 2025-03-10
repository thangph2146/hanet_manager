<?php

/**
 * 9/16/2022
 * AUTHOR:PDV-PC
 */

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class BriefCase extends Entity {
	protected $dates = [
		'bc_created_at',
		'bc_updated_at',
		'bc_deleted_at',
	];
}
