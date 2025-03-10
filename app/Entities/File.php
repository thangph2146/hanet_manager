<?php

/**
 * 9/16/2022
 * AUTHOR:PDV-PC
 */

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class File extends Entity {
	protected $dates = [
		'f_created_at',
		'f_updated_at'
	];
}
