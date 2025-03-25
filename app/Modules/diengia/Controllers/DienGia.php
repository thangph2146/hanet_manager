<?php

namespace App\Modules\diengia\Controllers;

use App\Controllers\BaseController;
use App\Modules\diengia\Models\DienGiaModel;
use App\Modules\diengia\Entities\DienGia;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;
use CodeIgniter\Pager\Pager;

class DienGia extends BaseController
{
    protected $model;
    protected $validation;

    public function __construct()
    {
        $this->model = new DienGiaModel();
        $this->validation = \Config\Services::validation();
    }

    /**
     * Display list of speakers
     */
    public function index()
    {
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        
        $this->model->where('bin', 0);
        
        if (!empty($search)) {
            $this->model->groupStart();
            foreach ($this->model->searchableFields as $field) {
                $this->model->orLike($field, $search);
            }
            $this->model->groupEnd();
        }
        
        if (isset($status) && $status !== '') {
            $this->model->where('status', $status);
        }
        
        $perPage = 10;
        $items = $this->model->paginate($perPage);
        $pager = $this->model->pager;
        
        return view('App\Modules\diengia\Views\index', [
            'items' => $items,
            'pager' => $pager,
            'search' => $search,
            'status' => $status
        ]);
    }

    /**
     * Display form to create new speaker
     */
    public function new()
    {
        return view('App\Modules\diengia\Views\new', [
            'is_new' => true
        ]);
    }

    /**
     * Process creation of new speaker
     */
    public function create()
    {
        $data = $this->request->getPost();
        
        if (empty($data['ten_dien_gia'])) {
            return redirect()->back()->withInput()->with('error', 'Vui lòng nhập tên diễn giả');
        }
        
        $file = $this->request->getFile('avatar');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadPath = 'data/images/' . date('Y') . '/' . date('m') . '/' . date('d');
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $data['avatar'] = $uploadPath . '/' . $newName;
        }
        
        $data['created_at'] = Time::now()->toDateTimeString();
        $data['updated_at'] = Time::now()->toDateTimeString();
        
        if (!isset($data['bin'])) {
            $data['bin'] = 0;
        }
        
        try {
            $result = $this->model->insert($data);
            if (!$result) {
                return redirect()->back()->withInput()->with('error', 'Lỗi khi thêm dữ liệu: ' . json_encode($this->model->errors()));
            }
            return redirect()->to('diengia')->with('message', 'Thêm mới thành công');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Lỗi khi thêm mới dữ liệu: ' . $e->getMessage());
        }
    }

    /**
     * Display form to edit speaker
     */
    public function edit($id)
    {
        $item = $this->model->find($id);
        if (!$item) {
            return redirect()->to('diengia')->with('error', 'Không tìm thấy bản ghi');
        }
        return view('App\Modules\diengia\Views\edit', [
            'item' => $item
        ]);
    }

    /**
     * Process update of speaker
     */
    public function update($id)
    {
        $data = $this->request->getPost();
        $file = $this->request->getFile('avatar');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadPath = 'data/images/' . date('Y') . '/' . date('m') . '/' . date('d');
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $data['avatar'] = $uploadPath . '/' . $newName;
        }
        
        $data['updated_at'] = Time::now()->toDateTimeString();
        
        try {
            $result = $this->model->update($id, $data);
            if (!$result) {
                return redirect()->back()->withInput()->with('error', 'Lỗi khi cập nhật dữ liệu: ' . json_encode($this->model->errors()));
            }
            return redirect()->to('diengia')->with('message', 'Cập nhật thành công');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Lỗi khi cập nhật dữ liệu: ' . $e->getMessage());
        }
    }
} 