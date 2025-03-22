<?php

namespace App\Modules\bachoc\Controllers;

use App\Controllers\BaseController;
use App\Modules\bachoc\Models\BacHocModel;
use CodeIgniter\HTTP\ResponseInterface;

class BacHoc extends BaseController
{
    protected $bacHocModel;
    protected $validation;

    public function __construct()
    {
        $this->bacHocModel = new BacHocModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        // Lấy dữ liệu từ model sử dụng phương thức getAllActive()
        $bacHocs = $this->bacHocModel->getAllActive();
        
        // Chuẩn bị dữ liệu cho view và helper tableRender
        $processedData = [];
        foreach ($bacHocs as $bac) {
            $processedData[] = [
                'id' => $bac->bac_hoc_id,
                'ten_bac_hoc' => esc($bac->ten_bac_hoc),
                'ma_bac_hoc' => esc($bac->ma_bac_hoc) ?: '<span class="text-muted">Chưa có</span>',
                'status' => $bac->status ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-warning">Không hoạt động</span>',
                'created_at' => $bac->created_at
            ];
        }
        
        $data = [
            'title' => 'Danh sách bậc học',
            'bac_hoc' => $processedData
        ];
        
        return view('App\Modules\bachoc\Views\index', $data);
    }

    public function listdeleted()
    {
        // Lấy dữ liệu đã xóa từ model sử dụng phương thức getAllDeleted()
        $deletedBacHocs = $this->bacHocModel->getAllDeleted();
        
        // Chuẩn bị dữ liệu cho view và helper tableRender
        $processedData = [];
        foreach ($deletedBacHocs as $bac) {
            $processedData[] = [
                'id' => $bac->bac_hoc_id,
                'ten_bac_hoc' => esc($bac->ten_bac_hoc),
                'ma_bac_hoc' => esc($bac->ma_bac_hoc) ?: '<span class="text-muted">Chưa có</span>',
                'status' => $bac->status ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-warning">Không hoạt động</span>',
                'deleted_at' => $bac->deleted_at
            ];
        }
        
        $data = [
            'title' => 'Danh sách bậc học đã xóa',
            'bac_hoc' => $processedData
        ];

        return view('App\Modules\bachoc\Views\listdeleted', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Thêm mới bậc học'
        ];
        
        return view('App\Modules\bachoc\Views\new', $data);
    }

    public function create()
    {
        $request = $this->request;

        if (!$this->validate($this->bacHocModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Kiểm tra tên đã tồn tại chưa
        if ($this->bacHocModel->isNameExists($request->getPost('ten_bac_hoc'))) {
            return redirect()->back()->withInput()->with('error', 'Tên bậc học đã tồn tại');
        }

        $data = [
            'ten_bac_hoc' => $request->getPost('ten_bac_hoc'),
            'ma_bac_hoc' => $request->getPost('ma_bac_hoc'),
            'status' => $request->getPost('status') ?? 1,
            'bin' => 0
        ];

        if ($this->bacHocModel->insert($data)) {
            return redirect()->to('/bachoc')->with('success', 'Đã thêm bậc học thành công');
        } else {
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    public function edit($id = null)
    {
        if (!$id) {
            return redirect()->to('/bachoc')->with('error', 'ID bậc học không hợp lệ');
        }

        $bacHoc = $this->bacHocModel->find($id);
        
        if (!$bacHoc) {
            return redirect()->to('/bachoc')->with('error', 'Bậc học không tồn tại');
        }
        
        return view('App\Modules\bachoc\Views\edit', [
            'bac_hoc' => $bacHoc
        ]);
    }

    public function update($id = null)
    {
        $request = $this->request;

        if (!$this->validate($this->bacHocModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $bacHoc = $this->bacHocModel->find($id);

        if (!$bacHoc) {
            return redirect()->to('/bachoc')->with('error', 'Không tìm thấy bậc học');
        }

        // Kiểm tra tên đã tồn tại chưa
        if ($this->bacHocModel->isNameExists($request->getPost('ten_bac_hoc'), $id)) {
            return redirect()->back()->withInput()->with('error', 'Tên bậc học đã tồn tại');
        }

        $data = [
            'ten_bac_hoc' => $request->getPost('ten_bac_hoc'),
            'ma_bac_hoc' => $request->getPost('ma_bac_hoc'),
            'status' => $request->getPost('status') ?? 1
        ];

        if ($this->bacHocModel->update($id, $data)) {
            return redirect()->to('/bachoc')->with('success', 'Đã cập nhật bậc học thành công');
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
            if (!$id || !$this->bacHocModel->find($id)) {
                $response['message'] = 'Bậc học không tồn tại.';
                return $this->response->setJSON($response);
            }

            try {
                // Xóa bậc học (soft delete)
                if ($this->bacHocModel->softDelete($id)) {
                    $response['success'] = true;
                    $response['message'] = 'Bậc học đã được xóa thành công.';
                } else {
                    $response['message'] = 'Không thể xóa bậc học. Vui lòng thử lại.';
                }
            } catch (\Exception $e) {
                $response['message'] = 'Đã xảy ra lỗi: ' . $e->getMessage();
            }

            return $this->response->setJSON($response);
        }

        // Xử lý cho request thông thường (không phải AJAX)
        if (!$id) {
            return redirect()->to('bachoc')->with('error', 'ID bậc học không được cung cấp.');
        }

        try {
            if ($this->bacHocModel->softDelete($id)) {
                return redirect()->to('bachoc')->with('success', 'Bậc học đã được xóa thành công.');
            } else {
                return redirect()->to('bachoc')->with('error', 'Không thể xóa bậc học. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('bachoc')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Khôi phục một bậc học đã xóa
     *
     * @param int $id ID của bậc học
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function restore($id = null)
    {
        if (!$id) {
            return redirect()->to('bachoc/listdeleted')->with('error', 'ID bậc học không hợp lệ');
        }
        
        try {
            if ($this->bacHocModel->restore($id)) {
                return redirect()->to('bachoc/listdeleted')->with('success', 'Đã khôi phục bậc học thành công.');
            } else {
                return redirect()->to('bachoc/listdeleted')->with('error', 'Không thể khôi phục bậc học. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('bachoc/listdeleted')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    
    /**
     * Xóa vĩnh viễn bậc học
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
            if (!$id || !$this->bacHocModel->find($id)) {
                $response['message'] = 'Bậc học không tồn tại.';
                return $this->response->setJSON($response);
            }

            try {
                // Xóa bậc học vĩnh viễn
                if ($this->bacHocModel->permanentDelete($id)) {
                    $response['success'] = true;
                    $response['message'] = 'Bậc học đã được xóa vĩnh viễn.';
                } else {
                    $response['message'] = 'Không thể xóa vĩnh viễn bậc học. Vui lòng thử lại.';
                }
            } catch (\Exception $e) {
                $response['message'] = 'Đã xảy ra lỗi: ' . $e->getMessage();
            }

            return $this->response->setJSON($response);
        }

        // Xử lý cho request thông thường (không phải AJAX)
        if (!$id) {
            return redirect()->to('bachoc/listdeleted')->with('error', 'ID bậc học không được cung cấp.');
        }

        try {
            if ($this->bacHocModel->permanentDelete($id)) {
                return redirect()->to('bachoc/listdeleted')->with('success', 'Bậc học đã được xóa vĩnh viễn.');
            } else {
                return redirect()->to('bachoc/listdeleted')->with('error', 'Không thể xóa vĩnh viễn bậc học. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('bachoc/listdeleted')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    
    /**
     * Xóa nhiều bậc học
     */
    public function deleteMultiple()
    {
        $request = $this->request;
        $ids = $request->getPost('selected_ids');
        
        if (empty($ids)) {
            return redirect()->to('bachoc')->with('error', 'Không có mục nào được chọn để xóa.');
        }
        
        // selected_ids đã là một mảng, không cần explode
        $idArray = $ids;
        
        try {
            if ($this->bacHocModel->softDeleteMultiple($idArray)) {
                return redirect()->to('bachoc')->with('success', 'Đã xóa thành công các bậc học đã chọn.');
            } else {
                return redirect()->to('bachoc')->with('error', 'Có lỗi xảy ra khi xóa các bậc học.');
            }
        } catch (\Exception $e) {
            return redirect()->to('bachoc')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    
    /**
     * Khôi phục nhiều bậc học
     */
    public function restoreMultiple()
    {
        $request = $this->request;
        $ids = $request->getPost('selected_ids');
        
        if (empty($ids)) {
            return redirect()->to('bachoc/listdeleted')->with('error', 'Không có mục nào được chọn để khôi phục.');
        }
        
        // selected_ids đã là một mảng, không cần explode
        $idArray = $ids;
        
        try {
            if ($this->bacHocModel->restoreMultiple($idArray)) {
                return redirect()->to('bachoc/listdeleted')->with('success', 'Đã khôi phục thành công các bậc học đã chọn.');
            } else {
                return redirect()->to('bachoc/listdeleted')->with('error', 'Có lỗi xảy ra khi khôi phục các bậc học.');
            }
        } catch (\Exception $e) {
            return redirect()->to('bachoc/listdeleted')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    
    /**
     * Cập nhật trạng thái hoạt động
     */
    public function toggleStatus($id = null)
    {
        // Kiểm tra nếu là request AJAX
        if ($this->request->isAJAX()) {
            $response = [
                'success' => false,
                'message' => '',
                'new_status' => 0,
                'csrf_hash' => csrf_hash()
            ];

            // Kiểm tra ID hợp lệ
            if (!$id) {
                $response['message'] = 'ID bậc học không được cung cấp.';
                return $this->response->setJSON($response);
            }

            $bacHoc = $this->bacHocModel->find($id);
            if (!$bacHoc) {
                $response['message'] = 'Bậc học không tồn tại.';
                return $this->response->setJSON($response);
            }

            try {
                // Đảo ngược trạng thái
                $newStatus = $bacHoc->status == 1 ? 0 : 1;
                
                if ($this->bacHocModel->update($id, ['status' => $newStatus])) {
                    $response['success'] = true;
                    $response['message'] = 'Đã cập nhật trạng thái bậc học thành công.';
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
            return redirect()->to('bachoc')->with('error', 'ID bậc học không được cung cấp.');
        }

        $bacHoc = $this->bacHocModel->find($id);
        if (!$bacHoc) {
            return redirect()->to('bachoc')->with('error', 'Bậc học không tồn tại.');
        }

        try {
            // Đảo ngược trạng thái
            $newStatus = $bacHoc->status == 1 ? 0 : 1;
            
            if ($this->bacHocModel->update($id, ['status' => $newStatus])) {
                return redirect()->to('bachoc')->with('success', 'Đã cập nhật trạng thái bậc học thành công.');
            } else {
                return redirect()->to('bachoc')->with('error', 'Không thể cập nhật trạng thái. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('bachoc')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    
    /**
     * Xóa vĩnh viễn nhiều bậc học
     */
    public function permanentDeleteMultiple()
    {
        $request = $this->request;
        $ids = $request->getPost('selected_ids');
        
        if (empty($ids)) {
            return redirect()->to('bachoc/listdeleted')->with('error', 'Không có mục nào được chọn để xóa vĩnh viễn.');
        }
        
        // selected_ids đã là một mảng, không cần explode
        $idArray = $ids;
        
        try {
            if ($this->bacHocModel->permanentDeleteMultiple($idArray)) {
                return redirect()->to('bachoc/listdeleted')->with('success', 'Đã xóa vĩnh viễn thành công các bậc học đã chọn.');
            } else {
                return redirect()->to('bachoc/listdeleted')->with('error', 'Có lỗi xảy ra khi xóa vĩnh viễn các bậc học.');
            }
        } catch (\Exception $e) {
            return redirect()->to('bachoc/listdeleted')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
} 