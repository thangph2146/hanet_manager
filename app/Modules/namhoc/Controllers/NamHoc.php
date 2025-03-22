<?php

namespace App\Modules\namhoc\Controllers;

use App\Controllers\BaseController;
use App\Modules\namhoc\Models\NamHocModel;
use CodeIgniter\HTTP\ResponseInterface;

class NamHoc extends BaseController
{
    protected $namHocModel;
    protected $validation;

    public function __construct()
    {
        $this->namHocModel = new NamHocModel();
        $this->validation = \Config\Services::validation();
    }

    /**
     * Phương thức debug để kiểm tra kết nối database và bảng nam_hoc
     */
    public function debug()
    {
        $db = \Config\Database::connect();
        echo "<h2>Database Debug</h2>";
        
        // Kiểm tra kết nối
        echo "<p>Kết nối Database: " . ($db->connected ? "Thành công" : "Thất bại") . "</p>";
        
        // Kiểm tra bảng nam_hoc
        try {
            $tables = $db->listTables();
            echo "<p>Danh sách bảng: " . implode(', ', $tables) . "</p>";
            
            echo "<p>Kiểm tra bảng nam_hoc: " . (in_array('nam_hoc', $tables) ? "Tồn tại" : "Không tồn tại") . "</p>";
            
            if (in_array('nam_hoc', $tables)) {
                // Lấy cấu trúc bảng
                $fields = $db->getFieldData('nam_hoc');
                echo "<h3>Cấu trúc bảng nam_hoc:</h3>";
                echo "<ul>";
                foreach ($fields as $field) {
                    echo "<li>{$field->name} - {$field->type} " . ($field->primary_key ? "(Primary Key)" : "") . "</li>";
                }
                echo "</ul>";
                
                // Đếm số bản ghi
                $query = $db->query("SELECT COUNT(*) as count FROM nam_hoc");
                $row = $query->getRow();
                echo "<p>Tổng số bản ghi: {$row->count}</p>";
                
                // Kiểm tra dữ liệu
                $query = $db->query("SELECT * FROM nam_hoc LIMIT 5");
                $results = $query->getResult();
                
                if (count($results) > 0) {
                    echo "<h3>Mẫu dữ liệu (tối đa 5 bản ghi):</h3>";
                    echo "<table border='1'><tr>";
                    
                    // Headers
                    foreach ($fields as $field) {
                        echo "<th>{$field->name}</th>";
                    }
                    echo "</tr>";
                    
                    // Data
                    foreach ($results as $row) {
                        echo "<tr>";
                        foreach ($fields as $field) {
                            $fieldName = $field->name;
                            echo "<td>" . ($row->$fieldName ?? 'NULL') . "</td>";
                        }
                        echo "</tr>";
                    }
                    
                    echo "</table>";
                } else {
                    echo "<p>Không có dữ liệu trong bảng.</p>";
                }
            }
        } catch (\Exception $e) {
            echo "<p>Lỗi: " . $e->getMessage() . "</p>";
        }
        
        exit;
    }

    public function index()
    {
        // Lấy dữ liệu từ model sử dụng phương thức getAllActive()
        $namHocs = $this->namHocModel->getAllActive();
        
        // Debug - kiểm tra dữ liệu từ model
        if (empty($namHocs)) {
            log_message('debug', 'Không có dữ liệu năm học nào được tìm thấy trong database');
        } else {
            log_message('debug', 'Tìm thấy ' . count($namHocs) . ' bản ghi năm học');
        }
        
        // Chuẩn bị dữ liệu cho view và helper tableRender
        $processedData = [];
        foreach ($namHocs as $namHoc) {
            // Kiểm tra xem $namHoc có phải là object hay array
            if (is_object($namHoc)) {
                $processedData[] = [
                    'id' => $namHoc->nam_hoc_id,
                    'ten_nam_hoc' => esc($namHoc->ten_nam_hoc),
                    'ngay_bat_dau' => $namHoc->ngay_bat_dau ? $namHoc->getNgayBatDauFormatted() : '',
                    'ngay_ket_thuc' => $namHoc->ngay_ket_thuc ? $namHoc->getNgayKetThucFormatted() : '',
                    'status' => $namHoc->status ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-warning">Không hoạt động</span>',
                    'created_at' => $namHoc->created_at
                ];
            } else if (is_array($namHoc)) {
                $processedData[] = [
                    'id' => $namHoc['nam_hoc_id'],
                    'ten_nam_hoc' => esc($namHoc['ten_nam_hoc']),
                    'ngay_bat_dau' => isset($namHoc['ngay_bat_dau']) && $namHoc['ngay_bat_dau'] ? date('d/m/Y', strtotime($namHoc['ngay_bat_dau'])) : '',
                    'ngay_ket_thuc' => isset($namHoc['ngay_ket_thuc']) && $namHoc['ngay_ket_thuc'] ? date('d/m/Y', strtotime($namHoc['ngay_ket_thuc'])) : '',
                    'status' => isset($namHoc['status']) && $namHoc['status'] ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-warning">Không hoạt động</span>',
                    'created_at' => isset($namHoc['created_at']) ? $namHoc['created_at'] : ''
                ];
            }
        }
        
        // Debug - kiểm tra dữ liệu đã xử lý
        if (empty($processedData)) {
            log_message('debug', 'Không có dữ liệu năm học nào được xử lý cho view');
        } else {
            log_message('debug', 'Đã xử lý ' . count($processedData) . ' bản ghi năm học cho view');
        }
        
        $data = [
            'title' => 'Danh sách năm học',
            'nam_hoc' => $processedData
        ];
        
        return view('App\Modules\namhoc\Views\index', $data);
    }

    public function listdeleted()
    {
        // Lấy dữ liệu đã xóa từ model sử dụng phương thức getAllDeleted()
        $deletedNamHocs = $this->namHocModel->getAllDeleted();
        
        // Chuẩn bị dữ liệu cho view và helper tableRender
        $processedData = [];
        foreach ($deletedNamHocs as $namHoc) {
            $processedData[] = [
                'id' => $namHoc->nam_hoc_id,
                'ten_nam_hoc' => esc($namHoc->ten_nam_hoc),
                'ngay_bat_dau' => $namHoc->ngay_bat_dau ? $namHoc->getNgayBatDauFormatted() : '',
                'ngay_ket_thuc' => $namHoc->ngay_ket_thuc ? $namHoc->getNgayKetThucFormatted() : '',
                'status' => $namHoc->status ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-warning">Không hoạt động</span>',
                'deleted_at' => $namHoc->deleted_at,
                'created_at' => $namHoc->created_at
            ];
        }
        
        $data = [
            'title' => 'Danh sách năm học đã xóa',
            'nam_hoc' => $processedData
        ];
        
        return view('App\Modules\namhoc\Views\listdeleted', $data);
    }
    
    public function new()
    {
        // Return the new view instead of form view
        return view('App\Modules\namhoc\Views\new');
    }
    
    public function create()
    {
        // Kiểm tra nếu request không phải POST
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/namhoc');
        }
        
        // Lấy dữ liệu từ form
        $data = $this->request->getPost();
        
        // Kiểm tra tên năm học đã tồn tại chưa
        if ($this->namHocModel->isNameExists($data['ten_nam_hoc'])) {
            return redirect()->back()->withInput()->with('error', 'Tên năm học đã tồn tại');
        }
        
        // Kiểm tra ngày bắt đầu và kết thúc ngay tại controller
        if (isset($data['ngay_bat_dau']) && isset($data['ngay_ket_thuc']) && !empty($data['ngay_bat_dau']) && !empty($data['ngay_ket_thuc'])) {
            $startDate = strtotime($data['ngay_bat_dau']);
            $endDate = strtotime($data['ngay_ket_thuc']);
            
            if ($startDate > $endDate) {
                return redirect()->back()->withInput()->with('errors', [
                    'ngay_bat_dau' => 'Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày kết thúc',
                    'ngay_ket_thuc' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu'
                ]);
            }
        }
        
        // Tạo đối tượng và lưu vào database
        if ($this->namHocModel->save($data)) {
            return redirect()->to('/namhoc')->with('success', 'Thêm năm học thành công');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->namHocModel->errors());
        }
    }
    
    public function edit($id = null)
    {
        if ($id === null) {
            return redirect()->to('/namhoc');
        }
        
        $namHoc = $this->namHocModel->find($id);
        if ($namHoc === null) {
            return redirect()->to('/namhoc')->with('error', 'Năm học không tồn tại');
        }
        
        $data = [
            'nam_hoc' => $namHoc
        ];
        
        // Return the edit view instead of form view
        return view('App\Modules\namhoc\Views\edit', $data);
    }
    
    public function update($id = null)
    {
        if ($id === null) {
            return redirect()->to('/namhoc');
        }
        
        // Kiểm tra nếu request không phải POST
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/namhoc');
        }
        
        // Lấy dữ liệu từ form
        $data = $this->request->getPost();
        
        // Kiểm tra tên năm học đã tồn tại chưa (ngoại trừ ID hiện tại)
        if ($this->namHocModel->isNameExists($data['ten_nam_hoc'], $id)) {
            return redirect()->back()->withInput()->with('error', 'Tên năm học đã tồn tại');
        }
        
        // Kiểm tra ngày bắt đầu và kết thúc ngay tại controller
        if (isset($data['ngay_bat_dau']) && isset($data['ngay_ket_thuc']) && !empty($data['ngay_bat_dau']) && !empty($data['ngay_ket_thuc'])) {
            $startDate = strtotime($data['ngay_bat_dau']);
            $endDate = strtotime($data['ngay_ket_thuc']);
            
            if ($startDate > $endDate) {
                return redirect()->back()->withInput()->with('errors', [
                    'ngay_bat_dau' => 'Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày kết thúc',
                    'ngay_ket_thuc' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu'
                ]);
            }
        }
        
        // Cập nhật dữ liệu
        if ($this->namHocModel->update($id, $data)) {
            return redirect()->to('/namhoc')->with('success', 'Cập nhật năm học thành công');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->namHocModel->errors());
        }
    }
    
    public function delete($id = null)
    {
        if ($id === null) {
            return redirect()->to('/namhoc')->with('error', 'ID không hợp lệ');
        }
        
        // Kiểm tra nếu request không phải POST
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/namhoc')->with('error', 'Phương thức không hợp lệ');
        }
        
        $namHoc = $this->namHocModel->find($id);
        if ($namHoc === null) {
            return redirect()->to('/namhoc')->with('error', 'Năm học không tồn tại');
        }
        
        // Kiểm tra xem năm học đã bị đưa vào thùng rác chưa
        if ($namHoc->isInBin()) {
            // Nếu đã ở trong thùng rác, thực hiện xóa mềm (soft delete)
            if ($this->namHocModel->softDelete($id)) {
                return redirect()->to('/namhoc')->with('success', 'Xóa năm học thành công');
            } else {
                return redirect()->to('/namhoc')->with('error', 'Không thể xóa năm học. Vui lòng thử lại sau.');
            }
        } else {
            // Nếu chưa ở trong thùng rác, đưa vào thùng rác trước
            if ($this->namHocModel->moveToBin($id)) {
                return redirect()->to('/namhoc')->with('success', 'Đã đưa năm học vào thùng rác');
            } else {
                return redirect()->to('/namhoc')->with('error', 'Không thể đưa năm học vào thùng rác. Vui lòng thử lại sau.');
            }
        }
    }
    
    public function restore($id = null)
    {
        if ($id === null) {
            return redirect()->to('/namhoc/listdeleted');
        }
        
        // Kiểm tra nếu request không phải POST
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/namhoc/listdeleted')->with('error', 'Phương thức không hợp lệ');
        }
        
        // Khôi phục năm học bị xóa mềm
        if ($this->namHocModel->restoreDeleted($id)) {
            return redirect()->to('/namhoc')->with('success', 'Khôi phục năm học thành công');
        } else {
            return redirect()->to('/namhoc/listdeleted')->with('error', 'Không thể khôi phục năm học. Vui lòng thử lại sau.');
        }
    }
    
    public function permanentDelete($id = null)
    {
        if ($id === null) {
            return redirect()->to('/namhoc/listdeleted');
        }
        
        // Kiểm tra nếu request không phải POST
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/namhoc/listdeleted')->with('error', 'Phương thức không hợp lệ');
        }
        
        $namHoc = $this->namHocModel->onlyDeleted()->find($id);
        if ($namHoc === null) {
            return redirect()->to('/namhoc/listdeleted')->with('error', 'Năm học không tồn tại hoặc chưa bị xóa tạm thời');
        }
        
        // Kiểm tra xem năm học có đang được sử dụng không
        // TODO: Thêm logic kiểm tra năm học có đang được sử dụng trong các phần khác của hệ thống
        
        // Thực hiện xóa vĩnh viễn
        if ($this->namHocModel->permanentDelete($id)) {
            return redirect()->to('/namhoc/listdeleted')->with('success', 'Xóa vĩnh viễn năm học thành công');
        } else {
            return redirect()->to('/namhoc/listdeleted')->with('error', 'Không thể xóa vĩnh viễn năm học. Vui lòng thử lại sau.');
        }
    }
    
    public function bulkRestore()
    {
        // Kiểm tra nếu request không phải POST
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/namhoc/listdeleted')->with('error', 'Phương thức không hợp lệ');
        }
        
        // Lấy danh sách ID đã chọn
        $ids = $this->request->getPost('selected_ids');
        
        // Kiểm tra nếu không có ID nào được chọn
        if (empty($ids)) {
            return redirect()->to('/namhoc/listdeleted')->with('error', 'Vui lòng chọn ít nhất một năm học để khôi phục');
        }
        
        // Khôi phục nhiều năm học
        if ($this->namHocModel->restoreMultiple($ids)) {
            return redirect()->to('/namhoc')->with('success', 'Khôi phục các năm học đã chọn thành công');
        } else {
            return redirect()->to('/namhoc/listdeleted')->with('error', 'Không thể khôi phục một số năm học. Vui lòng thử lại sau.');
        }
    }
    
    public function bulkPermanentDelete()
    {
        // Kiểm tra nếu request không phải POST
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/namhoc/listdeleted')->with('error', 'Phương thức không hợp lệ');
        }
        
        // Lấy danh sách ID đã chọn
        $ids = $this->request->getPost('selected_ids');
        
        // Kiểm tra nếu không có ID nào được chọn
        if (empty($ids)) {
            return redirect()->to('/namhoc/listdeleted')->with('error', 'Vui lòng chọn ít nhất một năm học để xóa vĩnh viễn');
        }
        
        // Xóa vĩnh viễn nhiều năm học
        if ($this->namHocModel->permanentDeleteMultiple($ids)) {
            return redirect()->to('/namhoc/listdeleted')->with('success', 'Xóa vĩnh viễn các năm học đã chọn thành công');
        } else {
            return redirect()->to('/namhoc/listdeleted')->with('error', 'Không thể xóa vĩnh viễn một số năm học. Vui lòng thử lại sau.');
        }
    }
    
    public function status($id = null)
    {
        if ($id === null) {
            return redirect()->to('/namhoc')->with('error', 'ID năm học không hợp lệ');
        }
        
        // Kiểm tra nếu request không phải POST
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/namhoc')->with('error', 'Phương thức không hợp lệ');
        }
        
        $namHoc = $this->namHocModel->find($id);
        if ($namHoc === null) {
            return redirect()->to('/namhoc')->with('error', 'Năm học không tồn tại');
        }
        
        // Lấy trạng thái hiện tại và đảo ngược
        $currentStatus = $namHoc->isActive();
        $newStatus = !$currentStatus;
        
        // Cập nhật trạng thái
        if ($this->namHocModel->update($id, ['status' => (int)$newStatus])) {
            return redirect()->to('/namhoc')->with('success', 'Cập nhật trạng thái năm học thành công');
        } else {
            return redirect()->to('/namhoc')->with('error', 'Không thể cập nhật trạng thái năm học. Vui lòng thử lại sau.');
        }
    }
    
    public function statusMultiple()
    {
        // Kiểm tra nếu request không phải POST
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/namhoc')->with('error', 'Phương thức không hợp lệ');
        }
        
        // Lấy danh sách ID và trạng thái mới
        $ids = $this->request->getPost('selected_ids');
        $status = $this->request->getPost('status');
        
        // Debug để kiểm tra dữ liệu nhận được
        log_message('debug', 'POST data received: ' . print_r($this->request->getPost(), true));
        log_message('debug', 'Selected IDs: ' . (is_array($ids) ? json_encode($ids) : $ids));
        
        if (empty($ids)) {
            return redirect()->to('/namhoc')->with('error', 'Không có năm học nào được chọn');
        }
        
        // Đảm bảo $ids luôn là array
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        
        // Nếu không có status được chỉ định, đảo ngược trạng thái hiện tại của mỗi item
        if ($status === null) {
            log_message('debug', 'No status specified, toggling current status for each item');
            $success = true;
            foreach ($ids as $id) {
                $namHoc = $this->namHocModel->find($id);
                if ($namHoc) {
                    $currentStatus = $namHoc->status;
                    $newStatus = $currentStatus == 1 ? 0 : 1;
                    log_message('debug', "ID: $id - Changing status from $currentStatus to $newStatus");
                    if (!$this->namHocModel->update($id, ['status' => $newStatus])) {
                        $success = false;
                        log_message('error', "Failed to update status for ID: $id");
                    }
                } else {
                    log_message('error', "Item with ID: $id not found");
                    $success = false;
                }
            }
        } else {
            // Cập nhật trạng thái cho tất cả các ID với giá trị status được chỉ định
            $success = true;
            foreach ($ids as $id) {
                if (!$this->namHocModel->update($id, ['status' => (int)$status])) {
                    $success = false;
                    log_message('error', "Failed to update status for ID: $id");
                }
            }
        }
        
        if ($success) {
            return redirect()->to('/namhoc')->with('success', 'Cập nhật trạng thái các năm học đã chọn thành công');
        } else {
            return redirect()->to('/namhoc')->with('error', 'Có lỗi xảy ra khi cập nhật trạng thái một số năm học');
        }
    }

    public function changeStatus($id = null)
    {
        if ($id === null) {
            return redirect()->to('/namhoc')->with('error', 'ID không hợp lệ');
        }
        
        // Kiểm tra nếu request không phải POST
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/namhoc')->with('error', 'Phương thức không hợp lệ');
        }
        
        $namHoc = $this->namHocModel->find($id);
        if ($namHoc === null) {
            return redirect()->to('/namhoc')->with('error', 'Năm học không tồn tại');
        }
        
        // Lấy trạng thái hiện tại và đảo ngược
        $currentStatus = $namHoc->isActive();
        $newStatus = !$currentStatus;
        
        // Cập nhật trạng thái
        if ($this->namHocModel->update($id, ['status' => (int)$newStatus])) {
            return redirect()->to('/namhoc')->with('success', 'Cập nhật trạng thái năm học thành công');
        } else {
            return redirect()->to('/namhoc')->with('error', 'Không thể cập nhật trạng thái năm học. Vui lòng thử lại sau.');
        }
    }

    /**
     * Hàm xóa hàng loạt các mục được chọn
     */
    public function bulkDelete()
    {
        // Lấy toàn bộ dữ liệu POST để debug
        $postData = $this->request->getPost();
        log_message('debug', 'POST data received: ' . print_r($postData, true));
        
        // Kiểm tra nếu request không phải POST
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/namhoc')->with('error', 'Phương thức không hợp lệ');
        }
        
        // Lấy danh sách ID đã chọn
        $ids = $this->request->getPost('selected_ids');
        log_message('debug', 'selected_ids: ' . (is_array($ids) ? print_r($ids, true) : $ids));
        
        // Kiểm tra nếu không có ID nào được chọn
        if (empty($ids)) {
            return redirect()->to('/namhoc')->with('error', 'Vui lòng chọn ít nhất một năm học để xóa');
        }
        
        // Đảm bảo $ids luôn là array
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        
        // Đưa các mục đã chọn vào thùng rác
        $success = true;
        $count = 0;
        
        foreach ($ids as $id) {
            log_message('debug', "Processing ID: $id");
            
            // Gọi moveToBin và ghi nhận kết quả
            if ($this->namHocModel->moveToBin($id)) {
                $count++;
                log_message('debug', "Successfully moved ID $id to bin");
            } else {
                $success = false;
                log_message('debug', "Failed to move ID $id to bin");
            }
        }
        
        // Trả về kết quả cho người dùng
        if ($count > 0) {
            return redirect()->to('/namhoc')->with('success', "Đã đưa {$count} năm học vào thùng rác");
        } else {
            return redirect()->to('/namhoc')->with('info', 'Không có năm học nào được đưa vào thùng rác');
        }
    }
} 