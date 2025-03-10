<?php

/**
 * 9/16/2022
 * AUTHOR:PDV-PC
 */

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Term extends Entity {
	protected $dates = [
		'BeginDate',
		'EndDate'
	];
}
