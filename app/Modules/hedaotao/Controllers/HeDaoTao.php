<?php

namespace App\Modules\hedaotao\Controllers;

use App\Controllers\BaseController;
use App\Modules\hedaotao\Models\HeDaoTaoModel;
use CodeIgniter\HTTP\ResponseInterface;

class HeDaoTao extends BaseController
{
    protected $heDaoTaoModel;
    protected $validation;

    public function __construct()
    {
        $this->heDaoTaoModel = new HeDaoTaoModel();
        $this->validation = \Config\Services::validation();
    }

    /**
     * Hiển thị danh sách hệ đào tạo
     */
    public function index()
    {
        // Lấy dữ liệu từ model sử dụng phương thức getAllActive()
        $heDaoTaos = $this->heDaoTaoModel->getAllActive();
        
        // Chuẩn bị dữ liệu cho view
        $processedData = [];
        foreach ($heDaoTaos as $hdt) {
            $processedData[] = [
                'id' => $hdt->he_dao_tao_id,
                'ten_he_dao_tao' => esc($hdt->ten_he_dao_tao),
                'ma_he_dao_tao' => esc($hdt->ma_he_dao_tao),
                'status' => $hdt->status ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-warning">Không hoạt động</span>',
                'created_at' => $hdt->created_at
            ];
        }
        
        $data = [
            'title' => 'Danh sách hệ đào tạo',
            'he_dao_tao' => $processedData
        ];
        
        return view('App\Modules\hedaotao\Views\index', $data);
    }

    /**
     * Hiển thị danh sách hệ đào tạo đã xóa
     */
    public function listdeleted()
    {
        $heDaoTaos = $this->heDaoTaoModel->getAllDeleted();
        
        $data = [
            'title' => 'Danh sách hệ đào tạo đã xóa',
            'he_dao_tao' => $heDaoTaos
        ];
        
        return view('App\Modules\hedaotao\Views\listdeleted', $data);
    }

    /**
     * Hiển thị form tạo mới hệ đào tạo
     */
    public function new()
    {
        return view('App\Modules\hedaotao\Views\new');
    }

    /**
     * Xử lý tạo mới hệ đào tạo
     */
    public function create()
    {
        $request = $this->request;

        // Validate dữ liệu đầu vào
        if (!$this->validate($this->heDaoTaoModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tenHeDaoTao = $request->getPost('ten_he_dao_tao');
        $maHeDaoTao = $request->getPost('ma_he_dao_tao');

        // Kiểm tra tên đã tồn tại chưa
        if ($this->heDaoTaoModel->isNameExists($tenHeDaoTao)) {
            return redirect()->back()->withInput()->with('error', 'Tên hệ đào tạo đã tồn tại');
        }

        // Kiểm tra mã đã tồn tại chưa (nếu có)
        if (!empty($maHeDaoTao) && $this->heDaoTaoModel->isCodeExists($maHeDaoTao)) {
            return redirect()->back()->withInput()->with('error', 'Mã hệ đào tạo đã tồn tại');
        }

        $data = [
            'ten_he_dao_tao' => $tenHeDaoTao,
            'ma_he_dao_tao' => $maHeDaoTao,
            'status' => $request->getPost('status') ?? 1,
            'bin' => 0
        ];

        if ($this->heDaoTaoModel->insert($data)) {
            return redirect()->to('/hedaotao')->with('success', 'Đã thêm hệ đào tạo thành công');
        } else {
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    /**
     * Hiển thị form chỉnh sửa hệ đào tạo
     */
    public function edit($id = null)
    {
        if (!$id) {
            return redirect()->to('/hedaotao')->with('error', 'ID hệ đào tạo không hợp lệ');
        }

        $heDaoTao = $this->heDaoTaoModel->find($id);
        
        if (!$heDaoTao) {
            return redirect()->to('/hedaotao')->with('error', 'Hệ đào tạo không tồn tại');
        }
        
        return view('App\Modules\hedaotao\Views\edit', [
            'he_dao_tao' => $heDaoTao
        ]);
    }

    /**
     * Xử lý cập nhật hệ đào tạo
     */
    public function update($id = null)
    {
        $request = $this->request;

        if (!$this->validate($this->heDaoTaoModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $heDaoTao = $this->heDaoTaoModel->find($id);

        if (!$heDaoTao) {
            return redirect()->to('/hedaotao')->with('error', 'Không tìm thấy hệ đào tạo');
        }

        $tenHeDaoTao = $request->getPost('ten_he_dao_tao');
        $maHeDaoTao = $request->getPost('ma_he_dao_tao');

        // Kiểm tra tên đã tồn tại chưa (trừ chính nó)
        if ($this->heDaoTaoModel->isNameExists($tenHeDaoTao, $id)) {
            return redirect()->back()->withInput()->with('error', 'Tên hệ đào tạo đã tồn tại');
        }

        // Kiểm tra mã đã tồn tại chưa (trừ chính nó)
        if (!empty($maHeDaoTao) && $this->heDaoTaoModel->isCodeExists($maHeDaoTao, $id)) {
            return redirect()->back()->withInput()->with('error', 'Mã hệ đào tạo đã tồn tại');
        }

        $data = [
            'ten_he_dao_tao' => $tenHeDaoTao,
            'ma_he_dao_tao' => $maHeDaoTao,
            'status' => $request->getPost('status') ?? 1
        ];

        if ($this->heDaoTaoModel->update($id, $data)) {
            return redirect()->to('/hedaotao')->with('success', 'Cập nhật hệ đào tạo thành công');
        } else {
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra khi cập nhật');
        }
    }

    /**
     * Xóa tạm thời hệ đào tạo
     */
    public function delete($id = null)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'ID hệ đào tạo không hợp lệ');
        }

        $heDaoTao = $this->heDaoTaoModel->find($id);
        
        if (!$heDaoTao) {
            return redirect()->back()->with('error', 'Hệ đào tạo không tồn tại');
        }

        if ($this->heDaoTaoModel->delete($id)) {
            return redirect()->to('/hedaotao')->with('success', 'Đã xóa hệ đào tạo thành công');
        } else {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa hệ đào tạo');
        }
    }

    /**
     * Khôi phục hệ đào tạo đã xóa
     */
    public function restore($id = null)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'ID hệ đào tạo không hợp lệ');
        }

        if ($this->heDaoTaoModel->restore($id)) {
            return redirect()->to('/hedaotao/listdeleted')->with('success', 'Đã khôi phục hệ đào tạo thành công');
        } else {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi khôi phục hệ đào tạo');
        }
    }

    /**
     * Xóa vĩnh viễn hệ đào tạo
     */
    public function permanentDelete($id = null)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'ID hệ đào tạo không hợp lệ');
        }

        if ($this->heDaoTaoModel->permanentDelete($id)) {
            return redirect()->to('/hedaotao/listdeleted')->with('success', 'Đã xóa vĩnh viễn hệ đào tạo thành công');
        } else {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa vĩnh viễn hệ đào tạo');
        }
    }

    /**
     * Đổi trạng thái hệ đào tạo
     */
    public function status($id = null)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'ID hệ đào tạo không hợp lệ');
        }

        $heDaoTao = $this->heDaoTaoModel->find($id);
        
        if (!$heDaoTao) {
            return redirect()->back()->with('error', 'Hệ đào tạo không tồn tại');
        }

        // Đảo ngược trạng thái
        $newStatus = $heDaoTao->status ? 0 : 1;
        
        if ($this->heDaoTaoModel->update($id, ['status' => $newStatus])) {
            return redirect()->back()->with('success', 'Đã thay đổi trạng thái hệ đào tạo thành công');
        } else {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi thay đổi trạng thái');
        }
    }

    /**
     * Xóa tạm thời nhiều hệ đào tạo
     */
    public function deleteMultiple()
    {
        $request = $this->request;
        $ids = $request->getPost('selected_ids');
        
        if (empty($ids)) {
            return redirect()->to('hedaotao')->with('error', 'Không có mục nào được chọn để xóa.');
        }
        
        try {
            if ($this->heDaoTaoModel->delete($ids)) {
                return redirect()->to('hedaotao')->with('success', 'Đã xóa thành công các hệ đào tạo đã chọn.');
            } else {
                return redirect()->to('hedaotao')->with('error', 'Có lỗi xảy ra khi xóa các hệ đào tạo.');
            }
        } catch (\Exception $e) {
            return redirect()->to('hedaotao')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Khôi phục nhiều hệ đào tạo
     */
    public function restoreMultiple()
    {
        $request = $this->request;
        $ids = $request->getPost('selected_ids');
        
        if (empty($ids)) {
            return redirect()->to('hedaotao/listdeleted')->with('error', 'Không có mục nào được chọn để khôi phục.');
        }
        
        try {
            if ($this->heDaoTaoModel->restoreMultiple($ids)) {
                return redirect()->to('hedaotao/listdeleted')->with('success', 'Đã khôi phục thành công các hệ đào tạo đã chọn.');
            } else {
                return redirect()->to('hedaotao/listdeleted')->with('error', 'Có lỗi xảy ra khi khôi phục các hệ đào tạo.');
            }
        } catch (\Exception $e) {
            return redirect()->to('hedaotao/listdeleted')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Đổi trạng thái nhiều hệ đào tạo
     */
    public function statusMultiple()
    {
        $request = $this->request;
        $ids = $request->getPost('selected_ids');
        
        if (empty($ids)) {
            return redirect()->to('hedaotao')->with('error', 'Không có mục nào được chọn để thay đổi trạng thái.');
        }
        
        try {
            $success = true;
            $db = \Config\Database::connect();
            $db->transStart();
            
            foreach ($ids as $id) {
                $heDaoTao = $this->heDaoTaoModel->find($id);
                if ($heDaoTao) {
                    // Đảo ngược trạng thái
                    $newStatus = $heDaoTao->status ? 0 : 1;
                    if (!$this->heDaoTaoModel->update($id, ['status' => $newStatus])) {
                        $success = false;
                        break;
                    }
                }
            }
            
            $db->transComplete();
            
            if ($success && $db->transStatus()) {
                return redirect()->to('hedaotao')->with('success', 'Đã thay đổi trạng thái thành công các hệ đào tạo đã chọn.');
            } else {
                return redirect()->to('hedaotao')->with('error', 'Có lỗi xảy ra khi thay đổi trạng thái các hệ đào tạo.');
            }
        } catch (\Exception $e) {
            return redirect()->to('hedaotao')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Xóa vĩnh viễn nhiều hệ đào tạo
     */
    public function permanentDeleteMultiple()
    {
        $request = $this->request;
        $ids = $request->getPost('selected_ids');
        
        if (empty($ids)) {
            return redirect()->to('hedaotao/listdeleted')->with('error', 'Không có mục nào được chọn để xóa vĩnh viễn.');
        }
        
        try {
            if ($this->heDaoTaoModel->permanentDeleteMultiple($ids)) {
                return redirect()->to('hedaotao/listdeleted')->with('success', 'Đã xóa vĩnh viễn thành công các hệ đào tạo đã chọn.');
            } else {
                return redirect()->to('hedaotao/listdeleted')->with('error', 'Có lỗi xảy ra khi xóa vĩnh viễn các hệ đào tạo.');
            }
        } catch (\Exception $e) {
            return redirect()->to('hedaotao/listdeleted')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    
    /**
     * Alias cho permanentDeleteMultiple (để tương thích)
     */
    public function deletePermanentMultiple()
    {
        return $this->permanentDeleteMultiple();
    }
}
    
