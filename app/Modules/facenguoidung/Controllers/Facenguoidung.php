<?php

namespace App\Modules\facenguoidung\Controllers;

use App\Controllers\BaseController;
use App\Modules\facenguoidung\Models\FacenguoidungModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;

class Facenguoidung extends BaseController
{
    protected $model;
    protected $validation;
    protected $nguoidungModel;

    public function __construct()
    {
        $this->model = new FacenguoidungModel();
        $this->validation = \Config\Services::validation();
        
        // Không cần khởi tạo trực tiếp model người dùng khi đã sử dụng relation
        $this->nguoidungModel = new \App\Modules\nguoidung\Models\NguoidungModel();
    }

    /**
     * Display list of face recognition records
     */
    public function index()
    {
        // Lấy tham số tìm kiếm/lọc từ request
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $nguoi_dung_id = $this->request->getGet('nguoi_dung_id');
        
        // Thiết lập các điều kiện tìm kiếm
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
        
        if (!empty($nguoi_dung_id)) {
            $this->model->where('nguoi_dung_id', $nguoi_dung_id);
        }
        
        // Lấy danh sách người dùng cho dropdown filter
        $nguoidungs = $this->nguoidungModel->where('status', 1)
                                          ->where('bin', 0)
                                          ->findAll();
        
        // Lấy dữ liệu với phân trang
        $perPage = 10; // Số bản ghi trên mỗi trang
        $items = $this->model->paginate($perPage);
        
        // Lấy dữ liệu quan hệ nguoi_dung cho mỗi item
        foreach ($items as $key => $item) {
            $items[$key] = $this->model->findWithRelations($item->face_nguoi_dung_id, ['nguoi_dung']);
        }
        
        $pager = $this->model->pager;
        
        return view('App\Modules\facenguoidung\Views\index', [
            'items' => $items,
            'pager' => $pager,
            'search' => $search,
            'status' => $status,
            'nguoi_dung_id' => $nguoi_dung_id,
            'nguoidungs' => $nguoidungs
        ]);
    }
    
    /**
     * Display form to create new face recognition record
     */
    public function new()
    {
        // Sử dụng model người dùng để lấy danh sách người dùng active
        $nguoidungs = $this->nguoidungModel->where('status', 1)
                                          ->where('bin', 0)
                                          ->findAll();
        
        return view('App\Modules\facenguoidung\Views\new', [
            'nguoidungs' => $nguoidungs,
            'is_new' => true
        ]);
    }
    
    /**
     * Process creation of new face recognition record
     */
    public function create()
    {
        $data = $this->request->getPost();
        
        // Debug: Nhật ký dữ liệu gửi lên
        log_message('debug', 'POST data: ' . json_encode($data));
        log_message('debug', 'FILE data: ' . json_encode($_FILES));
        
        // Kiểm tra trường người dùng
        if (empty($data['nguoi_dung_id'])) {
            return redirect()->back()->withInput()->with('error', 'Vui lòng chọn người dùng');
        }
        
        // Handle file upload
        $file = $this->request->getFile('duong_dan_anh');
        
        // Debug: Kiểm tra file
        if ($file) {
            log_message('debug', 'File name: ' . $file->getName());
            log_message('debug', 'File is valid: ' . ($file->isValid() ? 'Yes' : 'No'));
        } else {
            log_message('debug', 'No file uploaded');
            return redirect()->back()->withInput()->with('error', 'Vui lòng chọn ảnh khuôn mặt');
        }
        
        if (!$file->isValid()) {
            return redirect()->back()->withInput()->with('error', 'File không hợp lệ: ' . $file->getErrorString());
        }
        
        if ($file->hasMoved()) {
            return redirect()->back()->withInput()->with('error', 'File đã được di chuyển');
        }
        
        // Di chuyển file
        try {
            // Define upload path
            $uploadPath = 'data/images/' . date('Y') . '/' . date('m') . '/' . date('d');
            
            // Create directory if not exists
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Move uploaded file
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            
            // Set path in data
            $data['duong_dan_anh'] = $uploadPath . '/' . $newName;
            $data['ngay_cap_nhat'] = Time::now()->toDateTimeString();
            
            log_message('debug', 'File uploaded successfully: ' . $data['duong_dan_anh']);
        } catch (\Exception $e) {
            log_message('error', 'File upload error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Lỗi khi tải lên ảnh: ' . $e->getMessage());
        }
        
        // Set default values if not provided
        if (!isset($data['status'])) {
            $data['status'] = 1;
        }
        if (!isset($data['bin'])) {
            $data['bin'] = 0;
        }
        
        // Debug: Final data for insertion
        log_message('debug', 'Data to insert: ' . json_encode($data));
        
        try {
            // Thực hiện insert dữ liệu
            $result = $this->model->insert($data);
            log_message('debug', 'Insert result: ' . json_encode($result));
            
            if (!$result) {
                log_message('error', 'Insert failed: ' . json_encode($this->model->errors()));
                return redirect()->back()->withInput()->with('error', 'Lỗi khi thêm dữ liệu: ' . json_encode($this->model->errors()));
            }
            
            return redirect()->to('facenguoidung')->with('message', 'Thêm mới thành công');
        } catch (\Exception $e) {
            log_message('error', 'Error during insert: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Lỗi khi thêm mới dữ liệu: ' . $e->getMessage());
        }
    }
    
    /**
     * Display form to edit face recognition record
     */
    public function edit($id)
    {
        // Sử dụng findWithRelations để lấy dữ liệu khuôn mặt với relation nguoi_dung
        $item = $this->model->findWithRelations($id, ['nguoi_dung']);
        
        if (!$item) {
            return redirect()->to('facenguoidung')->with('error', 'Không tìm thấy bản ghi');
        }
        
        // Sử dụng model người dùng để lấy danh sách người dùng active
        $nguoidungs = $this->nguoidungModel->where('status', 1)
                                          ->where('bin', 0)
                                          ->findAll();
        
        return view('App\Modules\facenguoidung\Views\edit', [
            'item' => $item,
            'nguoidungs' => $nguoidungs,
            'is_new' => false
        ]);
    }
    
    /**
     * Process update of face recognition record
     */
    public function update($id)
    {
        $data = $this->request->getPost();
        $item = $this->model->find($id);
        
        if (!$item) {
            return redirect()->to('facenguoidung')->with('error', 'Không tìm thấy bản ghi');
        }
        
        // Handle file upload
        $file = $this->request->getFile('duong_dan_anh');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Define upload path
            $uploadPath = 'public/data/images/' . date('Y') . '/' . date('m') . '/' . date('d');
            
            // Create directory if not exists
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Delete old file if exists
            if (!empty($item->duong_dan_anh) && file_exists($item->duong_dan_anh)) {
                unlink($item->duong_dan_anh);
            }
            
            // Move uploaded file
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            
            // Set path in data
            $data['duong_dan_anh'] = $uploadPath . '/' . $newName;
            $data['ngay_cap_nhat'] = Time::now()->toDateTimeString();
        }
        
        $this->model->update($id, $data);
        
        return redirect()->to('facenguoidung')->with('message', 'Cập nhật thành công');
    }
    
    /**
     * Soft delete a face recognition record
     */
    public function delete($id)
    {
        $this->model->update($id, ['bin' => 1]);
        
        return redirect()->to('facenguoidung')->with('message', 'Xóa thành công');
    }
    
    /**
     * Display list of deleted face recognition records
     */
    public function listdeleted()
    {
        // Lấy tham số tìm kiếm từ request
        $search = $this->request->getGet('search');
        
        // Thiết lập điều kiện tìm kiếm
        $this->model->where('bin', 1);
        
        if (!empty($search)) {
            $this->model->groupStart();
            foreach ($this->model->searchableFields as $field) {
                $this->model->orLike($field, $search);
            }
            $this->model->groupEnd();
        }
        
        // Lấy danh sách người dùng cho dropdown filter
        $nguoidungs = $this->nguoidungModel->where('status', 1)
                                          ->findAll();
        
        // Lấy dữ liệu với phân trang
        $perPage = 10; // Số bản ghi trên mỗi trang
        $items = $this->model->paginate($perPage);
        
        // Lấy dữ liệu quan hệ nguoi_dung cho mỗi item
        foreach ($items as $key => $item) {
            $items[$key] = $this->model->findWithRelations($item->face_nguoi_dung_id, ['nguoi_dung']);
        }
        
        $pager = $this->model->pager;
        
        return view('App\Modules\facenguoidung\Views\listdeleted', [
            'items' => $items,
            'pager' => $pager,
            'search' => $search,
            'nguoidungs' => $nguoidungs
        ]);
    }
    
    /**
     * Restore a deleted face recognition record
     */
    public function restore($id)
    {
        $this->model->update($id, ['bin' => 0]);
        
        return redirect()->to('facenguoidung/listdeleted')->with('message', 'Khôi phục thành công');
    }
    
    /**
     * Permanently delete a face recognition record
     */
    public function permanentDelete($id = null)
    {
        if (empty($id)) {
            return redirect()->back()->with('error', 'ID không hợp lệ');
        }
        
        // Tìm item để lấy thông tin file ảnh
        $item = $this->model->find($id);
        
        if ($item) {
            // Xóa file ảnh nếu tồn tại
            if (!empty($item->duong_dan_anh)) {
                $filePath = FCPATH . $item->duong_dan_anh;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            // Xóa vĩnh viễn bản ghi
            if ($this->model->delete($id, true)) {
                return redirect()->to('facenguoidung/listdeleted')->with('success', 'Đã xóa vĩnh viễn khuôn mặt thành công');
            } else {
                return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa vĩnh viễn khuôn mặt');
            }
        } else {
            return redirect()->back()->with('error', 'Không tìm thấy bản ghi để xóa');
        }
    }
    
    /**
     * Update status of a face recognition record
     */
    public function status($id)
    {
        $item = $this->model->find($id);
        
        if (!$item) {
            return redirect()->to('facenguoidung')->with('error', 'Không tìm thấy bản ghi');
        }
        
        $newStatus = $item->status == 1 ? 0 : 1;
        $this->model->update($id, ['status' => $newStatus]);
        
        return redirect()->to('facenguoidung')->with('message', 'Cập nhật trạng thái thành công');
    }
    
    /**
     * Delete multiple face recognition records
     */
    public function deleteMultiple()
    {
        $selectedIds = $this->request->getPost('selected_ids');
        
        if (empty($selectedIds)) {
            return redirect()->to('facenguoidung')->with('error', 'Không có bản ghi nào được chọn');
        }
        
        foreach ($selectedIds as $id) {
            $this->model->update($id, ['bin' => 1]);
        }
        
        return redirect()->to('facenguoidung')->with('message', 'Xóa thành công ' . count($selectedIds) . ' bản ghi');
    }
    
    /**
     * Restore multiple deleted face recognition records
     */
    public function restoreMultiple()
    {
        $selectedIds = $this->request->getPost('selected_ids');
        
        if (empty($selectedIds)) {
            return redirect()->to('facenguoidung/listdeleted')->with('error', 'Không có bản ghi nào được chọn');
        }
        
        foreach ($selectedIds as $id) {
            $this->model->update($id, ['bin' => 0]);
        }
        
        return redirect()->to('facenguoidung/listdeleted')->with('message', 'Khôi phục thành công ' . count($selectedIds) . ' bản ghi');
    }
    
    /**
     * Permanently delete multiple face recognition records
     */
    public function permanentDeleteMultiple()
    {
        $selectedIds = $this->request->getPost('selected_ids');
        
        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'Không có mục nào được chọn để xóa vĩnh viễn');
        }
        
        $countSuccess = 0;
        
        foreach ($selectedIds as $id) {
            // Tìm item để lấy thông tin file ảnh
            $item = $this->model->find($id);
            
            if ($item) {
                // Xóa file ảnh nếu tồn tại
                if (!empty($item->duong_dan_anh)) {
                    $filePath = FCPATH . $item->duong_dan_anh;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
                
                // Xóa vĩnh viễn bản ghi
                if ($this->model->delete($id, true)) {
                    $countSuccess++;
                }
            }
        }
        
        if ($countSuccess > 0) {
            return redirect()->to('facenguoidung/listdeleted')->with('success', "Đã xóa vĩnh viễn {$countSuccess} khuôn mặt thành công");
        } else {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa vĩnh viễn các mục đã chọn');
        }
    }
    
    /**
     * Update status of multiple face recognition records
     */
    public function statusMultiple()
    {
        $selectedIds = $this->request->getPost('selected_ids');
        $status = $this->request->getPost('status');
        
        if (empty($selectedIds)) {
            return redirect()->to('facenguoidung')->with('error', 'Không có bản ghi nào được chọn');
        }
        
        if (!isset($status) || $status === '') {
            return redirect()->to('facenguoidung')->with('error', 'Trạng thái không hợp lệ');
        }
        
        foreach ($selectedIds as $id) {
            $this->model->update($id, ['status' => $status]);
        }
        
        $statusText = $status == 1 ? 'hoạt động' : 'không hoạt động';
        return redirect()->to('facenguoidung')->with('message', 'Đã cập nhật ' . count($selectedIds) . ' bản ghi thành ' . $statusText);
    }
    
    /**
     * View details of a face recognition record
     */
    public function view($id)
    {
        $item = $this->model->find($id);
        
        if (!$item) {
            return redirect()->to('facenguoidung')->with('error', 'Không tìm thấy bản ghi');
        }
        
        return view('App\Modules\facenguoidung\Views\view', [
            'item' => $item
        ]);
    }
    
    /**
     * Export list to PDF
     */
    public function exportPdf()
    {
        $items = $this->model->getAllActive();
        
        return view('App\Modules\facenguoidung\Views\export_pdf', [
            'items' => $items
        ]);
    }
    
    /**
     * Export list to Excel
     */
    public function exportExcel()
    {
        $items = $this->model->getAllActive();
        
        // Create Excel file
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Người dùng ID');
        $sheet->setCellValue('C1', 'Đường dẫn ảnh');
        $sheet->setCellValue('D1', 'Ngày cập nhật');
        $sheet->setCellValue('E1', 'Trạng thái');
        
        // Set data
        $row = 2;
        foreach ($items as $item) {
            $sheet->setCellValue('A' . $row, $item->face_nguoi_dung_id);
            $sheet->setCellValue('B' . $row, $item->nguoi_dung_id);
            $sheet->setCellValue('C' . $row, $item->duong_dan_anh);
            $sheet->setCellValue('D' . $row, $item->ngay_cap_nhat);
            $sheet->setCellValue('E' . $row, $item->status ? 'Hoạt động' : 'Không hoạt động');
            $row++;
        }
        
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="face_nguoi_dung.xlsx"');
        header('Cache-Control: max-age=0');
        
        // Write to output
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Statistics method for dashboard
     */
    public function statistics()
    {
        $totalRecords = $this->model->countAll();
        $activeRecords = $this->model->where('status', 1)->where('bin', 0)->countAllResults();
        $inactiveRecords = $this->model->where('status', 0)->where('bin', 0)->countAllResults();
        $deletedRecords = $this->model->where('bin', 1)->countAllResults();
        
        $data = [
            'total' => $totalRecords,
            'active' => $activeRecords,
            'inactive' => $inactiveRecords,
            'deleted' => $deletedRecords
        ];
        
        return $this->response->setJSON($data);
    }

    /**
     * Permanently delete all face recognition records from trash
     */
    public function permanentDeleteAll()
    {
        // Lấy tất cả các bản ghi trong thùng rác
        $items = $this->model->where('bin', 1)->findAll();
        
        $count = 0;
        foreach ($items as $item) {
            // Xóa file ảnh nếu tồn tại
            if (!empty($item->duong_dan_anh) && file_exists(FCPATH . $item->duong_dan_anh)) {
                unlink(FCPATH . $item->duong_dan_anh);
            }
            
            // Xóa bản ghi
            $this->model->delete($item->face_nguoi_dung_id, true);
            $count++;
        }
        
        return redirect()->to('facenguoidung/listdeleted')->with('message', 'Đã xóa vĩnh viễn ' . $count . ' bản ghi');
    }
} 