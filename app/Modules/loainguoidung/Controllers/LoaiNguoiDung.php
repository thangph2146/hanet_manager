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
        // Lấy dữ liệu từ model sử dụng phương thức getAllActive()
        $loaiNguoiDungs = $this->loaiNguoiDungModel->getAllActive();
        
        // Chuẩn bị dữ liệu cho view và helper tableRender
        $processedData = [];
        foreach ($loaiNguoiDungs as $loai) {
            $processedData[] = [
                'id' => $loai->loai_nguoi_dung_id,
                'ten_loai_nguoi_dung' => esc($loai->ten_loai),
                'mo_ta' => esc($loai->mo_ta),
                'status' => $loai->status ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-warning">Không hoạt động</span>',
                'created_at' => $loai->created_at
            ];
        }
        
        $data = [
            'title' => 'Danh sách loại người dùng',
            'loai_nguoi_dung' => $processedData
        ];
        
        return view('App\Modules\loainguoidung\Views\index', $data);
    }

    public function listdeleted()
    {
        // Lấy dữ liệu đã xóa từ model sử dụng phương thức getAllDeleted()
        $deletedLoaiNguoiDungs = $this->loaiNguoiDungModel->getAllDeleted();
        
        // Chuẩn bị dữ liệu cho view và helper tableRender
        $processedData = [];
        foreach ($deletedLoaiNguoiDungs as $loai) {
            $processedData[] = [
                'id' => $loai->loai_nguoi_dung_id,
                'ten_loai_nguoi_dung' => esc($loai->ten_loai),
                'mo_ta' => esc($loai->mo_ta),
                'status' => $loai->status ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-warning">Không hoạt động</span>',
                'deleted_at' => $loai->deleted_at
            ];
        }
        
        $data = [
            'title' => 'Danh sách loại người dùng đã xóa',
            'loai_nguoi_dung' => $processedData
        ];

        return view('App\Modules\loainguoidung\Views\listdeleted', $data);
    }

    public function new()
    {
        return view('App\Modules\loainguoidung\Views\new');
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
        if (!$id) {
            return redirect()->to('/loainguoidung')->with('error', 'ID loại người dùng không hợp lệ');
        }

        $loaiNguoiDung = $this->loaiNguoiDungModel->find($id);
        
        if (!$loaiNguoiDung) {
            return redirect()->to('/loainguoidung')->with('error', 'Loại người dùng không tồn tại');
        }
        
        return view('App\Modules\loainguoidung\Views\edit', [
            'loai_nguoi_dung' => $loaiNguoiDung
        ]);
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
                'csrf_hash' => csrf_hash()
            ];

            // Kiểm tra ID hợp lệ
            if (!$id || !$this->loaiNguoiDungModel->find($id)) {
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

    /**
     * Khôi phục một loại người dùng đã xóa
     *
     * @param int $id ID của loại người dùng
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function restore($id = null)
    {
        if (!$id) {
            return redirect()->to('loainguoidung/listdeleted')->with('error', 'ID loại người dùng không hợp lệ');
        }
        
        try {
            if ($this->loaiNguoiDungModel->restore($id)) {
                return redirect()->to('loainguoidung/listdeleted')->with('success', 'Đã khôi phục loại người dùng thành công.');
            } else {
                return redirect()->to('loainguoidung/listdeleted')->with('error', 'Không thể khôi phục loại người dùng. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('loainguoidung/listdeleted')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    
    /**
     * Xóa vĩnh viễn loại người dùng
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
            if (!$id || !$this->loaiNguoiDungModel->find($id)) {
                $response['message'] = 'Loại người dùng không tồn tại.';
                return $this->response->setJSON($response);
            }

            try {
                // Xóa loại người dùng vĩnh viễn
                if ($this->loaiNguoiDungModel->permanentDelete($id)) {
                    $response['success'] = true;
                    $response['message'] = 'Loại người dùng đã được xóa vĩnh viễn.';
                } else {
                    $response['message'] = 'Không thể xóa vĩnh viễn loại người dùng. Vui lòng thử lại.';
                }
            } catch (\Exception $e) {
                $response['message'] = 'Đã xảy ra lỗi: ' . $e->getMessage();
            }

            return $this->response->setJSON($response);
        }

        // Xử lý cho request thông thường (không phải AJAX)
        if (!$id) {
            return redirect()->to('loainguoidung/listdeleted')->with('error', 'ID loại người dùng không được cung cấp.');
        }

        try {
            if ($this->loaiNguoiDungModel->permanentDelete($id)) {
                return redirect()->to('loainguoidung/listdeleted')->with('success', 'Loại người dùng đã được xóa vĩnh viễn.');
            } else {
                return redirect()->to('loainguoidung/listdeleted')->with('error', 'Không thể xóa vĩnh viễn loại người dùng. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('loainguoidung/listdeleted')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    
    /**
     * Xóa nhiều loại người dùng
     */
    public function deleteMultiple()
    {
        $request = $this->request;
        $ids = $request->getPost('selected_ids');
        
        if (empty($ids)) {
            return redirect()->to('loainguoidung')->with('error', 'Không có mục nào được chọn để xóa.');
        }
        
        // selected_ids đã là một mảng, không cần explode
        $idArray = $ids;
        
        try {
            if ($this->loaiNguoiDungModel->softDeleteMultiple($idArray)) {
                return redirect()->to('loainguoidung')->with('success', 'Đã xóa thành công các loại người dùng đã chọn.');
            } else {
                return redirect()->to('loainguoidung')->with('error', 'Có lỗi xảy ra khi xóa các loại người dùng.');
            }
        } catch (\Exception $e) {
            return redirect()->to('loainguoidung')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    
    /**
     * Khôi phục nhiều loại người dùng
     */
    public function restoreMultiple()
    {
        $request = $this->request;
        $ids = $request->getPost('selected_ids');
        
        if (empty($ids)) {
            return redirect()->to('loainguoidung/listdeleted')->with('error', 'Không có mục nào được chọn để khôi phục.');
        }
        
        // selected_ids đã là một mảng, không cần explode
        $idArray = $ids;
        
        try {
            if ($this->loaiNguoiDungModel->restoreMultiple($idArray)) {
                return redirect()->to('loainguoidung/listdeleted')->with('success', 'Đã khôi phục thành công các loại người dùng đã chọn.');
            } else {
                return redirect()->to('loainguoidung/listdeleted')->with('error', 'Có lỗi xảy ra khi khôi phục các loại người dùng.');
            }
        } catch (\Exception $e) {
            return redirect()->to('loainguoidung/listdeleted')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    
    /**
     * Cập nhật trạng thái loại người dùng
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
            $loaiNguoiDung = $this->loaiNguoiDungModel->find($id);
            if (!$id || !$loaiNguoiDung) {
                $response['message'] = 'Loại người dùng không tồn tại.';
                return $this->response->setJSON($response);
            }

            try {
                // Đảo ngược trạng thái
                $newStatus = $loaiNguoiDung->status == 1 ? 0 : 1;
                
                if ($this->loaiNguoiDungModel->update($id, ['status' => $newStatus])) {
                    $response['success'] = true;
                    $response['message'] = 'Đã cập nhật trạng thái loại người dùng thành công.';
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
            return redirect()->to('loainguoidung')->with('error', 'ID loại người dùng không được cung cấp.');
        }

        $loaiNguoiDung = $this->loaiNguoiDungModel->find($id);
        if (!$loaiNguoiDung) {
            return redirect()->to('loainguoidung')->with('error', 'Loại người dùng không tồn tại.');
        }

        try {
            // Đảo ngược trạng thái
            $newStatus = $loaiNguoiDung->status == 1 ? 0 : 1;
            
            if ($this->loaiNguoiDungModel->update($id, ['status' => $newStatus])) {
                return redirect()->to('loainguoidung')->with('success', 'Đã cập nhật trạng thái loại người dùng thành công.');
            } else {
                return redirect()->to('loainguoidung')->with('error', 'Không thể cập nhật trạng thái. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('loainguoidung')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    
    /**
     * Xóa vĩnh viễn nhiều loại người dùng
     */
    public function permanentDeleteMultiple()
    {
        $request = $this->request;
        $ids = $request->getPost('selected_ids');
        
        if (empty($ids)) {
            return redirect()->to('loainguoidung/listdeleted')->with('error', 'Không có mục nào được chọn để xóa vĩnh viễn.');
        }
        
        // selected_ids đã là một mảng, không cần explode
        $idArray = $ids;
        
        try {
            if ($this->loaiNguoiDungModel->permanentDeleteMultiple($idArray)) {
                return redirect()->to('loainguoidung/listdeleted')->with('success', 'Đã xóa vĩnh viễn thành công các loại người dùng đã chọn.');
            } else {
                return redirect()->to('loainguoidung/listdeleted')->with('error', 'Có lỗi xảy ra khi xóa vĩnh viễn các loại người dùng.');
            }
        } catch (\Exception $e) {
            return redirect()->to('loainguoidung/listdeleted')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật trạng thái nhiều loại người dùng
     */
    public function statusMultiple()
    {
        $request = $this->request;
        $ids = $request->getPost('selected_ids');
        
        if (empty($ids)) {
            return redirect()->to('loainguoidung')->with('error', 'Không có mục nào được chọn để đổi trạng thái.');
        }
        
        // selected_ids đã là một mảng, không cần explode
        $idArray = $ids;
        
        try {
            $success = 0;
            $failed = 0;
            
            foreach ($idArray as $id) {
                $loaiNguoiDung = $this->loaiNguoiDungModel->find($id);
                if ($loaiNguoiDung) {
                    // Đảo ngược trạng thái
                    $newStatus = $loaiNguoiDung->status == 1 ? 0 : 1;
                    
                    if ($this->loaiNguoiDungModel->update($id, ['status' => $newStatus])) {
                        $success++;
                    } else {
                        $failed++;
                    }
                } else {
                    $failed++;
                }
            }
            
            if ($success > 0) {
                $message = 'Đã cập nhật trạng thái ' . $success . ' loại người dùng thành công.';
                if ($failed > 0) {
                    $message .= ' Có ' . $failed . ' loại người dùng không thể cập nhật.';
                }
                return redirect()->to('loainguoidung')->with('success', $message);
            } else {
                return redirect()->to('loainguoidung')->with('error', 'Không thể cập nhật trạng thái loại người dùng. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->to('loainguoidung')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
} 