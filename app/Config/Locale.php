<?php

namespace App\Config;

/**
 * Class to handle importing the PHP Locale class
 * This is a simple workaround for the "Class 'Locale' not found" error
 */
class Locale extends \Locale
{
    // This class extends the PHP built-in Locale class
    // No additional implementation needed
} 