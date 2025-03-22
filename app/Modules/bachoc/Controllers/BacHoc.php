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
        $bacHocs = $this->bacHocModel->getAllActive();
        
        $processedData = [];
        foreach ($bacHocs as $bac) {
            $processedData[] = [
                'id' => $bac->bac_hoc_id,
                'ten_bac_hoc' => esc($bac->ten_bac_hoc),
                'ma_bac_hoc' => esc($bac->ma_bac_hoc),
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
        $deletedBacHocs = $this->bacHocModel->getAllDeleted();
        
        $processedData = [];
        foreach ($deletedBacHocs as $bac) {
            $processedData[] = [
                'id' => $bac->bac_hoc_id,
                'ten_bac_hoc' => esc($bac->ten_bac_hoc),
                'ma_bac_hoc' => esc($bac->ma_bac_hoc),
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
        return view('App\Modules\bachoc\Views\new');
    }

    public function create()
    {
        $request = $this->request;

        if (!$this->validate($this->bacHocModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

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
        if ($this->request->isAJAX()) {
            $response = [
                'success' => false,
                'message' => '',
                'csrf_hash' => csrf_hash()
            ];

            if (!$id || !$this->bacHocModel->find($id)) {
                $response['message'] = 'Bậc học không tồn tại.';
                return $this->response->setJSON($response);
            }

            try {
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
    
    public function permanentDelete($id = null)
    {
        if ($this->request->isAJAX()) {
            $response = [
                'success' => false,
                'message' => '',
                'csrf_hash' => csrf_hash()
            ];

            if (!$id || !$this->bacHocModel->find($id)) {
                $response['message'] = 'Bậc học không tồn tại.';
                return $this->response->setJSON($response);
            }

            try {
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
    
    public function deleteMultiple()
    {
        $request = $this->request;
        $ids = $request->getPost('selected_ids');
        
        if (empty($ids)) {
            return redirect()->to('bachoc')->with('error', 'Không có mục nào được chọn để xóa.');
        }
        
        try {
            if ($this->bacHocModel->softDeleteMultiple($ids)) {
                return redirect()->to('bachoc')->with('success', 'Đã xóa thành công các bậc học đã chọn.');
            } else {
                return redirect()->to('bachoc')->with('error', 'Có lỗi xảy ra khi xóa các bậc học.');
            }
        } catch (\Exception $e) {
            return redirect()->to('bachoc')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    
    public function restoreMultiple()
    {
        $request = $this->request;
        $ids = $request->getPost('selected_ids');
        
        if (empty($ids)) {
            return redirect()->to('bachoc/listdeleted')->with('error', 'Không có mục nào được chọn để khôi phục.');
        }
        
        try {
            if ($this->bacHocModel->restoreMultiple($ids)) {
                return redirect()->to('bachoc/listdeleted')->with('success', 'Đã khôi phục thành công các bậc học đã chọn.');
            } else {
                return redirect()->to('bachoc/listdeleted')->with('error', 'Có lỗi xảy ra khi khôi phục các bậc học.');
            }
        } catch (\Exception $e) {
            return redirect()->to('bachoc/listdeleted')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    
    public function status($id = null)
    {
        if ($this->request->isAJAX()) {
            $response = [
                'success' => false,
                'message' => '',
                'csrf_hash' => csrf_hash()
            ];

            $bacHoc = $this->bacHocModel->find($id);
            if (!$id || !$bacHoc) {
                $response['message'] = 'Bậc học không tồn tại.';
                return $this->response->setJSON($response);
            }

            try {
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

        if (!$id) {
            return redirect()->to('bachoc')->with('error', 'ID bậc học không được cung cấp.');
        }

        $bacHoc = $this->bacHocModel->find($id);
        if (!$bacHoc) {
            return redirect()->to('bachoc')->with('error', 'Bậc học không tồn tại.');
        }

        try {
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

    public function permanentDeleteMultiple()
    {
        $request = $this->request;
        $ids = $request->getPost('selected_ids');
        
        if (empty($ids)) {
            return redirect()->to('bachoc/listdeleted')->with('error', 'Không có mục nào được chọn để xóa vĩnh viễn.');
        }
        
        try {
            if ($this->bacHocModel->permanentDeleteMultiple($ids)) {
                return redirect()->to('bachoc/listdeleted')->with('success', 'Đã xóa vĩnh viễn thành công các bậc học đã chọn.');
            } else {
                return redirect()->to('bachoc/listdeleted')->with('error', 'Có lỗi xảy ra khi xóa vĩnh viễn các bậc học.');
            }
        } catch (\Exception $e) {
            return redirect()->to('bachoc/listdeleted')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    public function statusMultiple()
    {
        $request = $this->request;
        $ids = $request->getPost('selected_ids');
        
        if (empty($ids)) {
            return redirect()->to('bachoc')->with('error', 'Không có mục nào được chọn để đổi trạng thái.');
        }
        
        try {
            $success = 0;
            $failed = 0;
            
            foreach ($ids as $id) {
                $bacHoc = $this->bacHocModel->find($id);
                if ($bacHoc) {
                    $newStatus = $bacHoc->status == 1 ? 0 : 1;
                    
                    if ($this->bacHocModel->update($id, ['status' => $newStatus])) {
                        $success++;
                    } else {
                        $failed++;
                    }
                } else {
                    $failed++;
                }
            }
            
            if ($success > 0) {
                $message = 'Đã cập nhật trạng thái ' . $success . ' bậc học thành công.';
                if ($failed > 0) {
                    $message .= ' Có ' . $failed . ' bậc học không thể cập nhật.';
                }
                return redirect()->to('bachoc')->with('success', $message);
            } else {
                return redirect()->to('bachoc')->with('error', 'Không thể cập nhật trạng thái bậc học. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('bachoc')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
}