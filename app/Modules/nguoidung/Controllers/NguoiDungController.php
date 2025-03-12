<?php

namespace App\Modules\nguoidung\Controllers;

use App\Controllers\BaseController;
use App\Modules\nguoidung\Models\NguoiDungModel;

class NguoiDungController extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new NguoiDungModel();
    }

    public function index()
    {
        $data = $this->model->findAll();
        return view('App\Modules\nguoidung\Views\index', ['data' => $data]);
    }

    // Định nghĩa các phương thức CRUD khác như create, store, edit, update, delete, show, trash, restore, purge, search
}
