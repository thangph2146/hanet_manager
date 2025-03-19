<?php

namespace App\Modules\loainguoidung\Controllers;

use App\Controllers\BaseController;
use App\Modules\loainguoidung\Models\LoaiNguoiDungModel;
use CodeIgniter\HTTP\ResponseInterface;

class LoaiNguoiDung extends BaseController
{
    protected $loaiNguoiDungModel;
    protected $validation;

    public function __construct()
    {
        $this->loaiNguoiDungModel = new LoaiNguoiDungModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $data = [
            'title' => 'Danh sách loại người dùng',
            'loai_nguoi_dungs' => $this->loaiNguoiDungModel->where('bin', 0)->orderBy('ten_loai', 'ASC')->findAll()
        ];

        return view('App\Modules\loainguoidung\Views\index', $data);
    }

    public function deleted()
    {
        $data = [
            'title' => 'Danh sách loại người dùng đã xóa',
            'loai_nguoi_dungs' => $this->loaiNguoiDungModel->getBinnedItems()
        ];

        return view('App\Modules\loainguoidung\Views\listdeleted', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Thêm loại người dùng mới',
            'loaiNguoiDung' => new \App\Modules\loainguoidung\Entities\LoaiNguoiDung()
        ];

        return view('App\Modules\loainguoidung\Views\new', $data);
    }

    public function create()
    {
        $request = $this->request;

        if (!$this->validate($this->loaiNguoiDungModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Kiểm tra tên đã tồn tại chưa
        if ($this->loaiNguoiDungModel->isNameExists($request->getPost('ten_loai'))) {
            return redirect()->back()->withInput()->with('error', 'Tên loại người dùng đã tồn tại');
        }

        $data = [
            'ten_loai' => $request->getPost('ten_loai'),
            'mo_ta' => $request->getPost('mo_ta'),
            'status' => $request->getPost('status') ?? 1,
            'bin' => 0
        ];

        if ($this->loaiNguoiDungModel->insert($data)) {
            return redirect()->to('/loainguoidung')->with('success', 'Đã thêm loại người dùng thành công');
        } else {
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    public function edit($id = null)
    {
        $loaiNguoiDung = $this->loaiNguoiDungModel->find($id);

        if (!$loaiNguoiDung) {
            return redirect()->to('/loainguoidung')->with('error', 'Không tìm thấy loại người dùng');
        }

        $data = [
            'title' => 'Cập nhật loại người dùng',
            'loaiNguoiDung' => $loaiNguoiDung
        ];

        return view('App\Modules\loainguoidung\Views\edit', $data);
    }

    public function update($id = null)
    {
        $request = $this->request;

        if (!$this->validate($this->loaiNguoiDungModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $loaiNguoiDung = $this->loaiNguoiDungModel->find($id);

        if (!$loaiNguoiDung) {
            return redirect()->to('/loainguoidung')->with('error', 'Không tìm thấy loại người dùng');
        }

        // Kiểm tra tên đã tồn tại chưa
        if ($this->loaiNguoiDungModel->isNameExists($request->getPost('ten_loai'), $id)) {
            return redirect()->back()->withInput()->with('error', 'Tên loại người dùng đã tồn tại');
        }

        $data = [
            'ten_loai' => $request->getPost('ten_loai'),
            'mo_ta' => $request->getPost('mo_ta'),
            'status' => $request->getPost('status') ?? 1
        ];

        if ($this->loaiNguoiDungModel->update($id, $data)) {
            return redirect()->to('/loainguoidung')->with('success', 'Đã cập nhật loại người dùng thành công');
        } else {
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    public function delete($id = null)
    {
        $loaiNguoiDung = $this->loaiNguoiDungModel->find($id);

        if (!$loaiNguoiDung) {
            return $this->response->setJSON(['success' => false, 'message' => 'Không tìm thấy loại người dùng']);
        }

        if ($this->loaiNguoiDungModel->moveToBin($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Đã chuyển loại người dùng vào thùng rác']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại']);
        }
    }

    public function restore($id = null)
    {
        $loaiNguoiDung = $this->loaiNguoiDungModel->find($id);

        if (!$loaiNguoiDung) {
            return $this->response->setJSON(['success' => false, 'message' => 'Không tìm thấy loại người dùng']);
        }

        if ($this->loaiNguoiDungModel->restoreFromBin($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Đã khôi phục loại người dùng thành công']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại']);
        }
    }

    public function purge($id = null)
    {
        $loaiNguoiDung = $this->loaiNguoiDungModel->find($id);

        if (!$loaiNguoiDung) {
            return $this->response->setJSON(['success' => false, 'message' => 'Không tìm thấy loại người dùng']);
        }

        if ($this->loaiNguoiDungModel->delete($id, true)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Đã xóa loại người dùng vĩnh viễn']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại']);
        }
    }

    public function status($id = null)
    {
        $loaiNguoiDung = $this->loaiNguoiDungModel->find($id);

        if (!$loaiNguoiDung) {
            return $this->response->setJSON(['success' => false, 'message' => 'Không tìm thấy loại người dùng']);
        }

        $newStatus = $loaiNguoiDung->status == 1 ? 0 : 1;

        if ($this->loaiNguoiDungModel->update($id, ['status' => $newStatus])) {
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Đã ' . ($newStatus == 1 ? 'kích hoạt' : 'vô hiệu hóa') . ' loại người dùng',
                'status' => $newStatus
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại']);
        }
    }

    public function deleteMultiple()
    {
        $request = $this->request;
        $ids = $request->getPost('ids');
        
        if (!is_array($ids) || empty($ids)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Không có bản ghi nào được chọn']);
        }
        
        $db = \Config\Database::connect();
        $db->transStart();
        
        $success = true;
        $count = 0;
        
        foreach ($ids as $id) {
            $loaiNguoiDung = $this->loaiNguoiDungModel->find($id);
            if ($loaiNguoiDung) {
                if ($this->loaiNguoiDungModel->moveToBin($id)) {
                    $count++;
                } else {
                    $success = false;
                    break;
                }
            }
        }
        
        $db->transComplete();
        
        if ($success && $db->transStatus() && $count > 0) {
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Đã chuyển ' . $count . ' loại người dùng vào thùng rác'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Có lỗi xảy ra, vui lòng thử lại'
            ]);
        }
    }
} 