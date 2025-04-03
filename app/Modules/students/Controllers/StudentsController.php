<?php

namespace App\Modules\students\Controllers;

use App\Controllers\BaseController;
class StudentsController extends BaseController
{
   
    
    public function __construct()
    {

    }
    
    public function dashboard()
    {
        return view('App\Modules\students\Views\dashboard\index');
    }
    
  
} 