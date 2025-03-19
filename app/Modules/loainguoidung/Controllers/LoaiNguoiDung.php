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
        // Kiểm tra nếu là request AJAX
        if ($this->request->isAJAX()) {
            $response = [
                'success' => false,
                'message' => '',
            ];

            // Kiểm tra ID hợp lệ
            if (!$id || !$this->loaiNguoiDungModel->exists($id)) {
                $response['message'] = 'Loại người dùng không tồn tại.';
                return $this->response->setJSON($response);
            }

            try {
                // Xóa loại người dùng (soft delete)
                if ($this->loaiNguoiDungModel->softDelete($id)) {
                    $response['success'] = true;
                    $response['message'] = 'Loại người dùng đã được xóa thành công.';
                } else {
                    $response['message'] = 'Không thể xóa loại người dùng. Vui lòng thử lại.';
                }
            } catch (\Exception $e) {
                $response['message'] = 'Đã xảy ra lỗi: ' . $e->getMessage();
            }

            return $this->response->setJSON($response);
        }

        // Xử lý cho request thông thường (không phải AJAX)
        if (!$id) {
            return redirect()->to('loainguoidung')->with('error', 'ID loại người dùng không được cung cấp.');
        }

        try {
            if ($this->loaiNguoiDungModel->softDelete($id)) {
                return redirect()->to('loainguoidung')->with('success', 'Loại người dùng đã được xóa thành công.');
            } else {
                return redirect()->to('loainguoidung')->with('error', 'Không thể xóa loại người dùng. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('loainguoidung')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    public function restore($id = null)
    {
        if (!$id) {
            return redirect()->to('loainguoidung/deleted')->with('error', 'ID loại người dùng không được cung cấp.');
        }

        try {
            if ($this->loaiNguoiDungModel->restoreDeleted($id)) {
                return redirect()->to('loainguoidung/deleted')->with('success', 'Loại người dùng đã được khôi phục thành công.');
            } else {
                return redirect()->to('loainguoidung/deleted')->with('error', 'Không thể khôi phục loại người dùng. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('loainguoidung/deleted')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
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
        // Lấy danh sách ID từ form
        $ids = $this->request->getPost('selected_ids');
        
        if (empty($ids)) {
            return redirect()->to('loainguoidung')->with('error', 'Không có loại người dùng nào được chọn để xóa.');
        }
        
        // Chuyển chuỗi ID thành mảng
        $idArray = explode(',', $ids);
        
        try {
            // Xóa các loại người dùng đã chọn
            if ($this->loaiNguoiDungModel->softDeleteMultiple($idArray)) {
                return redirect()->to('loainguoidung')->with('success', 'Đã xóa thành công ' . count($idArray) . ' loại người dùng.');
            } else {
                return redirect()->to('loainguoidung')->with('error', 'Không thể xóa một số loại người dùng. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('loainguoidung')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    public function restoreMultiple()
    {
        // Lấy danh sách ID từ form
        $ids = $this->request->getPost('selected_ids');
        
        if (empty($ids)) {
            return redirect()->to('loainguoidung/deleted')->with('error', 'Không có loại người dùng nào được chọn để khôi phục.');
        }
        
        // Chuyển chuỗi ID thành mảng
        $idArray = explode(',', $ids);
        
        try {
            // Khôi phục các loại người dùng đã chọn
            if ($this->loaiNguoiDungModel->restoreMultiple($idArray)) {
                return redirect()->to('loainguoidung/deleted')->with('success', 'Đã khôi phục thành công ' . count($idArray) . ' loại người dùng.');
            } else {
                return redirect()->to('loainguoidung/deleted')->with('error', 'Không thể khôi phục một số loại người dùng. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('loainguoidung/deleted')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    public function permanentDelete($id = null)
    {
        if (!$id) {
            return redirect()->to('loainguoidung/deleted')->with('error', 'ID loại người dùng không được cung cấp.');
        }

        try {
            if ($this->loaiNguoiDungModel->permanentDelete($id)) {
                return redirect()->to('loainguoidung/deleted')->with('success', 'Loại người dùng đã được xóa vĩnh viễn.');
            } else {
                return redirect()->to('loainguoidung/deleted')->with('error', 'Không thể xóa vĩnh viễn loại người dùng. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('loainguoidung/deleted')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
} 