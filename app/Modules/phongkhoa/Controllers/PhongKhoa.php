<?php

namespace App\Modules\phongkhoa\Controllers;

use App\Controllers\BaseController;
use App\Modules\phongkhoa\Models\PhongKhoaModel;
use CodeIgniter\HTTP\ResponseInterface;

class PhongKhoa extends BaseController
{
    protected $phongKhoaModel;
    protected $validation;

    public function __construct()
    {
        $this->phongKhoaModel = new PhongKhoaModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $data = [
            'title' => 'Danh sách phòng khoa',
            'phong_khoas' => $this->phongKhoaModel->where('bin', 0)->orderBy('ten_phong_khoa', 'ASC')->findAll()
        ];

        return view('App\Modules\phongkhoa\Views\index', $data);
    }

    public function listdeleted()
    {
        $data = [
            'title' => 'Danh sách phòng khoa đã xóa',
            'phong_khoas' => $this->phongKhoaModel->getSoftDeleted()
        ];

        return view('App\Modules\phongkhoa\Views\listdeleted', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Thêm phòng khoa mới',
            'phongKhoa' => new \App\Modules\phongkhoa\Entities\PhongKhoa()
        ];

        return view('App\Modules\phongkhoa\Views\new', $data);
    }

    public function create()
    {
        $request = $this->request;

        if (!$this->validate($this->phongKhoaModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Kiểm tra mã phòng khoa đã tồn tại chưa
        if ($this->phongKhoaModel->isCodeExists($request->getPost('ma_phong_khoa'))) {
            return redirect()->back()->withInput()->with('error', 'Mã phòng khoa đã tồn tại');
        }

        $data = [
            'ma_phong_khoa' => $request->getPost('ma_phong_khoa'),
            'ten_phong_khoa' => $request->getPost('ten_phong_khoa'),
            'ghi_chu' => $request->getPost('ghi_chu'),
            'status' => $request->getPost('status') ?? 1,
            'bin' => 0
        ];

        if ($this->phongKhoaModel->insert($data)) {
            return redirect()->to('/phongkhoa')->with('success', 'Đã thêm phòng khoa thành công');
        } else {
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    public function edit($id = null)
    {
        $phongKhoa = $this->phongKhoaModel->find($id);

        if (!$phongKhoa) {
            return redirect()->to('/phongkhoa')->with('error', 'Không tìm thấy phòng khoa');
        }

        $data = [
            'title' => 'Cập nhật phòng khoa',
            'phongKhoa' => $phongKhoa
        ];

        return view('App\Modules\phongkhoa\Views\edit', $data);
    }

    public function update($id = null)
    {
        $request = $this->request;

        if (!$this->validate($this->phongKhoaModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $phongKhoa = $this->phongKhoaModel->find($id);

        if (!$phongKhoa) {
            return redirect()->to('/phongkhoa')->with('error', 'Không tìm thấy phòng khoa');
        }

        // Kiểm tra mã phòng khoa đã tồn tại chưa
        if ($this->phongKhoaModel->isCodeExists($request->getPost('ma_phong_khoa'), $id)) {
            return redirect()->back()->withInput()->with('error', 'Mã phòng khoa đã tồn tại');
        }

        $data = [
            'ma_phong_khoa' => $request->getPost('ma_phong_khoa'),
            'ten_phong_khoa' => $request->getPost('ten_phong_khoa'),
            'ghi_chu' => $request->getPost('ghi_chu'),
            'status' => $request->getPost('status') ?? 1
        ];

        if ($this->phongKhoaModel->update($id, $data)) {
            return redirect()->to('/phongkhoa/edit/' . $id)->with('success', 'Đã cập nhật phòng khoa thành công');
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
                'csrf_hash' => csrf_hash()
            ];

            // Kiểm tra ID hợp lệ
            if (!$id || !$this->phongKhoaModel->find($id)) {
                $response['message'] = 'Phòng khoa không tồn tại.';
                return $this->response->setJSON($response);
            }

            try {
                // Xóa phòng khoa (soft delete)
                if ($this->phongKhoaModel->softDelete($id)) {
                    $response['success'] = true;
                    $response['message'] = 'Phòng khoa đã được xóa thành công.';
                } else {
                    $response['message'] = 'Không thể xóa phòng khoa. Vui lòng thử lại.';
                }
            } catch (\Exception $e) {
                $response['message'] = 'Đã xảy ra lỗi: ' . $e->getMessage();
            }

            return $this->response->setJSON($response);
        }

        // Xử lý cho request thông thường (không phải AJAX)
        if (!$id) {
            return redirect()->to('phongkhoa')->with('error', 'ID phòng khoa không được cung cấp.');
        }

        try {
            if ($this->phongKhoaModel->softDelete($id)) {
                return redirect()->to('phongkhoa')->with('success', 'Phòng khoa đã được xóa thành công.');
            } else {
                return redirect()->to('phongkhoa')->with('error', 'Không thể xóa phòng khoa. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('phongkhoa')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    public function restore($id = null)
    {
        // Kiểm tra nếu là request AJAX
        if ($this->request->isAJAX()) {
            $response = [
                'success' => false,
                'message' => '',
                'csrf_hash' => csrf_hash()
            ];

            // Kiểm tra ID hợp lệ
            if (!$id || !$this->phongKhoaModel->find($id)) {
                $response['message'] = 'Phòng khoa không tồn tại.';
                return $this->response->setJSON($response);
            }

            try {
                // Khôi phục phòng khoa
                if ($this->phongKhoaModel->restoreDeleted($id)) {
                    $response['success'] = true;
                    $response['message'] = 'Phòng khoa đã được khôi phục thành công.';
                } else {
                    $response['message'] = 'Không thể khôi phục phòng khoa. Vui lòng thử lại.';
                }
            } catch (\Exception $e) {
                $response['message'] = 'Đã xảy ra lỗi: ' . $e->getMessage();
            }

            return $this->response->setJSON($response);
        }

        // Xử lý cho request thông thường (không phải AJAX)
        if (!$id) {
            return redirect()->to('phongkhoa/listdeleted')->with('error', 'ID phòng khoa không được cung cấp.');
        }

        try {
            if ($this->phongKhoaModel->restoreDeleted($id)) {
                return redirect()->to('phongkhoa/listdeleted')->with('success', 'Phòng khoa đã được khôi phục thành công.');
            } else {
                return redirect()->to('phongkhoa/listdeleted')->with('error', 'Không thể khôi phục phòng khoa. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('phongkhoa/listdeleted')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    public function purge($id = null)
    {
        $phongKhoa = $this->phongKhoaModel->find($id);

        if (!$phongKhoa) {
            return $this->response->setJSON(['success' => false, 'message' => 'Không tìm thấy phòng khoa']);
        }

        if ($this->phongKhoaModel->delete($id, true)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Đã xóa phòng khoa vĩnh viễn']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại']);
        }
    }

    public function status($id = null)
    {
        $phongKhoa = $this->phongKhoaModel->find($id);

        if (!$phongKhoa) {
            return $this->response->setJSON(['success' => false, 'message' => 'Không tìm thấy phòng khoa']);
        }

        $newStatus = $phongKhoa->status == 1 ? 0 : 1;

        if ($this->phongKhoaModel->update($id, ['status' => $newStatus])) {
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Đã ' . ($newStatus == 1 ? 'kích hoạt' : 'vô hiệu hóa') . ' phòng khoa',
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
            return redirect()->to('phongkhoa')->with('error', 'Không có phòng khoa nào được chọn để xóa.');
        }
        
        // Chuyển chuỗi ID thành mảng
        $idArray = explode(',', $ids);
        
        try {
            // Xóa các phòng khoa đã chọn
            if ($this->phongKhoaModel->softDeleteMultiple($idArray)) {
                return redirect()->to('phongkhoa')->with('success', 'Đã xóa thành công ' . count($idArray) . ' phòng khoa.');
            } else {
                return redirect()->to('phongkhoa')->with('error', 'Không thể xóa một số phòng khoa. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('phongkhoa')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    public function restoreMultiple()
    {
        // Lấy danh sách ID từ form
        $ids = $this->request->getPost('selected_ids');
        
        // Kiểm tra nếu là request AJAX
        if ($this->request->isAJAX()) {
            $response = [
                'success' => false,
                'message' => '',
                'csrf_hash' => csrf_hash()
            ];
            
            if (empty($ids)) {
                $response['message'] = 'Không có phòng khoa nào được chọn để khôi phục.';
                return $this->response->setJSON($response);
            }
            
            // Chuyển chuỗi ID thành mảng
            $idArray = explode(',', $ids);
            
            try {
                // Khôi phục các phòng khoa đã chọn
                if ($this->phongKhoaModel->restoreMultiple($idArray)) {
                    $response['success'] = true;
                    $response['message'] = 'Đã khôi phục thành công ' . count($idArray) . ' phòng khoa.';
                } else {
                    $response['message'] = 'Không thể khôi phục một số phòng khoa. Vui lòng thử lại.';
                }
            } catch (\Exception $e) {
                $response['message'] = 'Đã xảy ra lỗi: ' . $e->getMessage();
            }
            
            return $this->response->setJSON($response);
        }
        
        // Xử lý cho request thông thường (không phải AJAX)
        if (empty($ids)) {
            return redirect()->to('phongkhoa/listdeleted')->with('error', 'Không có phòng khoa nào được chọn để khôi phục.');
        }
        
        // Chuyển chuỗi ID thành mảng
        $idArray = explode(',', $ids);
        
        try {
            // Khôi phục các phòng khoa đã chọn
            if ($this->phongKhoaModel->restoreMultiple($idArray)) {
                return redirect()->to('phongkhoa/listdeleted')->with('success', 'Đã khôi phục thành công ' . count($idArray) . ' phòng khoa.');
            } else {
                return redirect()->to('phongkhoa/listdeleted')->with('error', 'Không thể khôi phục một số phòng khoa. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('phongkhoa/listdeleted')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    public function permanentDelete($id = null)
    {
        // Kiểm tra nếu là request AJAX
        if ($this->request->isAJAX()) {
            $response = [
                'success' => false,
                'message' => '',
                'csrf_hash' => csrf_hash()
            ];

            // Kiểm tra ID hợp lệ
            if (!$id) {
                $response['message'] = 'ID phòng khoa không được cung cấp.';
                return $this->response->setJSON($response);
            }

            try {
                if ($this->phongKhoaModel->permanentDelete($id)) {
                    $response['success'] = true;
                    $response['message'] = 'Phòng khoa đã được xóa vĩnh viễn.';
                } else {
                    $response['message'] = 'Không thể xóa vĩnh viễn phòng khoa. Vui lòng thử lại.';
                }
            } catch (\Exception $e) {
                $response['message'] = 'Đã xảy ra lỗi: ' . $e->getMessage();
            }

            return $this->response->setJSON($response);
        }

        // Xử lý cho request thông thường (không phải AJAX)
        if (!$id) {
            return redirect()->to('phongkhoa/listdeleted')->with('error', 'ID phòng khoa không được cung cấp.');
        }

        try {
            if ($this->phongKhoaModel->permanentDelete($id)) {
                return redirect()->to('phongkhoa/listdeleted')->with('success', 'Phòng khoa đã được xóa vĩnh viễn.');
            } else {
                return redirect()->to('phongkhoa/listdeleted')->with('error', 'Không thể xóa vĩnh viễn phòng khoa. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('phongkhoa/listdeleted')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
} 