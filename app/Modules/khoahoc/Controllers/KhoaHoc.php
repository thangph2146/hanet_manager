<?php

namespace App\Modules\khoahoc\Controllers;

use App\Controllers\BaseController;
use App\Modules\khoahoc\Models\KhoaHocModel;
use CodeIgniter\HTTP\ResponseInterface;

class KhoaHoc extends BaseController
{
    protected $khoaHocModel;
    protected $validation;

    public function __construct()
    {
        $this->khoaHocModel = new KhoaHocModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        // Lấy dữ liệu từ model sử dụng phương thức getAllActive()
        $khoaHocs = $this->khoaHocModel->getAllActive();
        
        // Chuẩn bị dữ liệu cho view và helper tableRender
        $processedData = [];
        foreach ($khoaHocs as $khoa) {
            $processedData[] = [
                'id' => $khoa->khoa_hoc_id,
                'ten_khoa_hoc' => esc($khoa->ten_khoa_hoc),
                'nam_hoc' => $khoa->getThoiGianHoc(),
                'phong_khoa_id' => $khoa->phong_khoa_id,
                'status' => $khoa->status ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-warning">Không hoạt động</span>',
                'created_at' => $khoa->created_at
            ];
        }
        
        $data = [
            'title' => 'Danh sách khóa học',
            'khoa_hoc' => $processedData
        ];
        
        return view('App\Modules\khoahoc\Views\index', $data);
    }

    public function listdeleted()
    {
        // Lấy dữ liệu đã xóa từ model sử dụng phương thức getAllDeleted()
        $deletedKhoaHocs = $this->khoaHocModel->getAllDeleted();
        
        // Chuẩn bị dữ liệu cho view và helper tableRender
        $processedData = [];
        foreach ($deletedKhoaHocs as $khoa) {
            $processedData[] = [
                'id' => $khoa->khoa_hoc_id,
                'ten_khoa_hoc' => esc($khoa->ten_khoa_hoc),
                'nam_hoc' => $khoa->getThoiGianHoc(),
                'phong_khoa_id' => $khoa->phong_khoa_id,
                'status' => $khoa->status ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-warning">Không hoạt động</span>',
                'deleted_at' => $khoa->deleted_at
            ];
        }
        
        $data = [
            'title' => 'Danh sách khóa học đã xóa',
            'khoa_hoc' => $processedData
        ];

        return view('App\Modules\khoahoc\Views\listdeleted', $data);
    }

    public function new()
    {
        // Thêm logic để lấy danh sách phòng khoa (nếu cần)
        $data = [
            'title' => 'Thêm mới khóa học',
            'phong_khoa_list' => $this->getPhongKhoaList()
        ];
        
        return view('App\Modules\khoahoc\Views\new', $data);
    }

    /**
     * Lấy danh sách phòng khoa để hiển thị trong dropdown
     * 
     * @return array Danh sách phòng khoa
     */
    private function getPhongKhoaList()
    {
        // Đây là nơi bạn sẽ lấy danh sách phòng khoa từ model tương ứng
        // Ví dụ giả định:
        // $phongKhoaModel = new \App\Modules\phongkhoa\Models\PhongKhoaModel();
        // return $phongKhoaModel->where('status', 1)->findAll();
        
        // Tạm thời trả về một mảng giả định
        return [
            ['phong_khoa_id' => 1, 'ten_phong_khoa' => 'Khoa Công nghệ thông tin'],
            ['phong_khoa_id' => 2, 'ten_phong_khoa' => 'Khoa Kinh tế'],
            ['phong_khoa_id' => 3, 'ten_phong_khoa' => 'Phòng Đào tạo'],
        ];
    }

    public function create()
    {
        $request = $this->request;

        if (!$this->validate($this->khoaHocModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Kiểm tra tên đã tồn tại chưa
        if ($this->khoaHocModel->isNameExists($request->getPost('ten_khoa_hoc'))) {
            return redirect()->back()->withInput()->with('error', 'Tên khóa học đã tồn tại');
        }

        $data = [
            'ten_khoa_hoc' => $request->getPost('ten_khoa_hoc'),
            'nam_bat_dau' => $request->getPost('nam_bat_dau'),
            'nam_ket_thuc' => $request->getPost('nam_ket_thuc'),
            'phong_khoa_id' => $request->getPost('phong_khoa_id'),
            'status' => $request->getPost('status') ?? 1,
            'bin' => 0
        ];

        if ($this->khoaHocModel->insert($data)) {
            return redirect()->to('/khoahoc')->with('success', 'Đã thêm khóa học thành công');
        } else {
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    public function edit($id = null)
    {
        if (!$id) {
            return redirect()->to('/khoahoc')->with('error', 'ID khóa học không hợp lệ');
        }

        $khoaHoc = $this->khoaHocModel->find($id);
        
        if (!$khoaHoc) {
            return redirect()->to('/khoahoc')->with('error', 'Khóa học không tồn tại');
        }
        
        return view('App\Modules\khoahoc\Views\edit', [
            'khoa_hoc' => $khoaHoc,
            'phong_khoa_list' => $this->getPhongKhoaList()
        ]);
    }

    public function update($id = null)
    {
        $request = $this->request;

        if (!$this->validate($this->khoaHocModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $khoaHoc = $this->khoaHocModel->find($id);

        if (!$khoaHoc) {
            return redirect()->to('/khoahoc')->with('error', 'Không tìm thấy khóa học');
        }

        // Kiểm tra tên đã tồn tại chưa
        if ($this->khoaHocModel->isNameExists($request->getPost('ten_khoa_hoc'), $id)) {
            return redirect()->back()->withInput()->with('error', 'Tên khóa học đã tồn tại');
        }

        $data = [
            'ten_khoa_hoc' => $request->getPost('ten_khoa_hoc'),
            'nam_bat_dau' => $request->getPost('nam_bat_dau'),
            'nam_ket_thuc' => $request->getPost('nam_ket_thuc'),
            'phong_khoa_id' => $request->getPost('phong_khoa_id'),
            'status' => $request->getPost('status') ?? 1
        ];

        if ($this->khoaHocModel->update($id, $data)) {
            return redirect()->to('/khoahoc')->with('success', 'Đã cập nhật khóa học thành công');
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
            if (!$id || !$this->khoaHocModel->find($id)) {
                $response['message'] = 'Khóa học không tồn tại.';
                return $this->response->setJSON($response);
            }

            try {
                // Xóa khóa học (soft delete)
                if ($this->khoaHocModel->softDelete($id)) {
                    $response['success'] = true;
                    $response['message'] = 'Khóa học đã được xóa thành công.';
                } else {
                    $response['message'] = 'Không thể xóa khóa học. Vui lòng thử lại.';
                }
            } catch (\Exception $e) {
                $response['message'] = 'Đã xảy ra lỗi: ' . $e->getMessage();
            }

            return $this->response->setJSON($response);
        }

        // Xử lý cho request thông thường (không phải AJAX)
        if (!$id) {
            return redirect()->to('khoahoc')->with('error', 'ID khóa học không được cung cấp.');
        }

        try {
            if ($this->khoaHocModel->softDelete($id)) {
                return redirect()->to('khoahoc')->with('success', 'Khóa học đã được xóa thành công.');
            } else {
                return redirect()->to('khoahoc')->with('error', 'Không thể xóa khóa học. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('khoahoc')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Khôi phục một khóa học đã xóa
     *
     * @param int $id ID của khóa học
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function restore($id = null)
    {
        if (!$id) {
            return redirect()->to('khoahoc/listdeleted')->with('error', 'ID khóa học không hợp lệ');
        }
        
        try {
            if ($this->khoaHocModel->restore($id)) {
                return redirect()->to('khoahoc/listdeleted')->with('success', 'Đã khôi phục khóa học thành công.');
            } else {
                return redirect()->to('khoahoc/listdeleted')->with('error', 'Không thể khôi phục khóa học. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('khoahoc/listdeleted')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    
    /**
     * Xóa vĩnh viễn khóa học
     */
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
            if (!$id || !$this->khoaHocModel->find($id)) {
                $response['message'] = 'Khóa học không tồn tại.';
                return $this->response->setJSON($response);
            }

            try {
                // Xóa khóa học vĩnh viễn
                if ($this->khoaHocModel->permanentDelete($id)) {
                    $response['success'] = true;
                    $response['message'] = 'Khóa học đã được xóa vĩnh viễn.';
                } else {
                    $response['message'] = 'Không thể xóa vĩnh viễn khóa học. Vui lòng thử lại.';
                }
            } catch (\Exception $e) {
                $response['message'] = 'Đã xảy ra lỗi: ' . $e->getMessage();
            }

            return $this->response->setJSON($response);
        }

        // Xử lý cho request thông thường (không phải AJAX)
        if (!$id) {
            return redirect()->to('khoahoc/listdeleted')->with('error', 'ID khóa học không được cung cấp.');
        }

        try {
            if ($this->khoaHocModel->permanentDelete($id)) {
                return redirect()->to('khoahoc/listdeleted')->with('success', 'Khóa học đã được xóa vĩnh viễn.');
            } else {
                return redirect()->to('khoahoc/listdeleted')->with('error', 'Không thể xóa vĩnh viễn khóa học. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('khoahoc/listdeleted')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    
    /**
     * Xóa nhiều khóa học
     */
    public function deleteMultiple()
    {
        $request = $this->request;
        $ids = $request->getPost('selected_ids');
        
        if (empty($ids)) {
            return redirect()->to('khoahoc')->with('error', 'Không có mục nào được chọn để xóa.');
        }
        
        // selected_ids đã là một mảng, không cần explode
        $idArray = $ids;
        
        try {
            if ($this->khoaHocModel->softDeleteMultiple($idArray)) {
                return redirect()->to('khoahoc')->with('success', 'Đã xóa thành công các khóa học đã chọn.');
            } else {
                return redirect()->to('khoahoc')->with('error', 'Có lỗi xảy ra khi xóa các khóa học.');
            }
        } catch (\Exception $e) {
            return redirect()->to('khoahoc')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    
    /**
     * Khôi phục nhiều khóa học
     */
    public function restoreMultiple()
    {
        $request = $this->request;
        $ids = $request->getPost('selected_ids');
        
        if (empty($ids)) {
            return redirect()->to('khoahoc/listdeleted')->with('error', 'Không có mục nào được chọn để khôi phục.');
        }
        
        // selected_ids đã là một mảng, không cần explode
        $idArray = $ids;
        
        try {
            if ($this->khoaHocModel->restoreMultiple($idArray)) {
                return redirect()->to('khoahoc/listdeleted')->with('success', 'Đã khôi phục thành công các khóa học đã chọn.');
            } else {
                return redirect()->to('khoahoc/listdeleted')->with('error', 'Có lỗi xảy ra khi khôi phục các khóa học.');
            }
        } catch (\Exception $e) {
            return redirect()->to('khoahoc/listdeleted')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    
    /**
     * Cập nhật trạng thái khóa học
     */
    public function status($id = null)
    {
        // Kiểm tra nếu là request AJAX
        if ($this->request->isAJAX()) {
            $response = [
                'success' => false,
                'message' => '',
                'csrf_hash' => csrf_hash()
            ];

            // Kiểm tra ID hợp lệ
            $khoaHoc = $this->khoaHocModel->find($id);
            if (!$id || !$khoaHoc) {
                $response['message'] = 'Khóa học không tồn tại.';
                return $this->response->setJSON($response);
            }

            try {
                // Đảo ngược trạng thái
                $newStatus = $khoaHoc->status == 1 ? 0 : 1;
                
                if ($this->khoaHocModel->update($id, ['status' => $newStatus])) {
                    $response['success'] = true;
                    $response['message'] = 'Đã cập nhật trạng thái khóa học thành công.';
                    $response['new_status'] = $newStatus;
                } else {
                    $response['message'] = 'Không thể cập nhật trạng thái. Vui lòng thử lại.';
                }
            } catch (\Exception $e) {
                $response['message'] = 'Đã xảy ra lỗi: ' . $e->getMessage();
            }

            return $this->response->setJSON($response);
        }

        // Xử lý cho request thông thường (không phải AJAX)
        if (!$id) {
            return redirect()->to('khoahoc')->with('error', 'ID khóa học không được cung cấp.');
        }

        $khoaHoc = $this->khoaHocModel->find($id);
        if (!$khoaHoc) {
            return redirect()->to('khoahoc')->with('error', 'Khóa học không tồn tại.');
        }

        try {
            // Đảo ngược trạng thái
            $newStatus = $khoaHoc->status == 1 ? 0 : 1;
            
            if ($this->khoaHocModel->update($id, ['status' => $newStatus])) {
                return redirect()->to('khoahoc')->with('success', 'Đã cập nhật trạng thái khóa học thành công.');
            } else {
                return redirect()->to('khoahoc')->with('error', 'Không thể cập nhật trạng thái. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('khoahoc')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    
    /**
     * Xóa vĩnh viễn nhiều khóa học
     */
    public function permanentDeleteMultiple()
    {
        $request = $this->request;
        $ids = $request->getPost('selected_ids');
        
        if (empty($ids)) {
            return redirect()->to('khoahoc/listdeleted')->with('error', 'Không có mục nào được chọn để xóa vĩnh viễn.');
        }
        
        // selected_ids đã là một mảng, không cần explode
        $idArray = $ids;
        
        try {
            if ($this->khoaHocModel->permanentDeleteMultiple($idArray)) {
                return redirect()->to('khoahoc/listdeleted')->with('success', 'Đã xóa vĩnh viễn thành công các khóa học đã chọn.');
            } else {
                return redirect()->to('khoahoc/listdeleted')->with('error', 'Có lỗi xảy ra khi xóa vĩnh viễn các khóa học.');
            }
        } catch (\Exception $e) {
            return redirect()->to('khoahoc/listdeleted')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật trạng thái nhiều khóa học
     */
    public function statusMultiple()
    {
        $request = $this->request;
        $ids = $request->getPost('selected_ids');
        
        if (empty($ids)) {
            return redirect()->to('khoahoc')->with('error', 'Không có mục nào được chọn để đổi trạng thái.');
        }
        
        // selected_ids đã là một mảng, không cần explode
        $idArray = $ids;
        
        try {
            $success = 0;
            $failed = 0;
            
            foreach ($idArray as $id) {
                $khoaHoc = $this->khoaHocModel->find($id);
                if ($khoaHoc) {
                    // Đảo ngược trạng thái
                    $newStatus = $khoaHoc->status == 1 ? 0 : 1;
                    
                    if ($this->khoaHocModel->update($id, ['status' => $newStatus])) {
                        $success++;
                    } else {
                        $failed++;
                    }
                } else {
                    $failed++;
                }
            }
            
            if ($success > 0) {
                $message = 'Đã cập nhật trạng thái ' . $success . ' khóa học thành công.';
                if ($failed > 0) {
                    $message .= ' Có ' . $failed . ' khóa học không thể cập nhật.';
                }
                return redirect()->to('khoahoc')->with('success', $message);
            } else {
                return redirect()->to('khoahoc')->with('error', 'Không thể cập nhật trạng thái khóa học. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('khoahoc')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
} 