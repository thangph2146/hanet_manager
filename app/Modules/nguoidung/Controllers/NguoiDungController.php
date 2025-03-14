<?php

namespace App\Modules\nguoidung\Controllers;

use App\Controllers\BaseController;
use App\Modules\nguoidung\Models\NguoiDungModel;
use App\Entities\NguoiDungEntity;
use CodeIgniter\HTTP\ResponseInterface;

class NguoiDungController extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new NguoiDungModel();
    }

    public function listDeleted()
    {
        // Lấy danh sách người dùng đã bị xóa mềm
        $data = $this->model->onlyDeleted()->findAll();
        return view('App\Modules\nguoidung\Views\listdeleted', ['data' => $data]);
    }

    public function index()
    {
        $data = $this->model->getAll();
        return view('App\Modules\nguoidung\Views\index', ['data' => $data]);
    }

    public function new()
    {
        return view('App\Modules\nguoidung\Views\new');
    }

    public function store()
    {
        // Validate dữ liệu đầu vào
        $rules = [
            'AccountId' => 'required|min_length[3]|is_unique[students.AccountId]',
            'FullName'  => 'required|min_length[3]',
            'password'  => 'required|min_length[6]',
            'Email'     => 'permit_empty|valid_email|is_unique[students.Email]',
            'FirstName' => 'permit_empty|min_length[2]',
            'AccountType' => 'permit_empty',
            'MobilePhone' => 'permit_empty|min_length[10]',
            'HomePhone' => 'permit_empty',
            'HomePhone1' => 'permit_empty',
            'loai_nguoi_dung_id' => 'permit_empty|numeric',
            'nam_hoc_id' => 'permit_empty|numeric',
            'bac_hoc_id' => 'permit_empty|numeric',
            'he_dao_tao_id' => 'permit_empty|numeric',
            'nganh_id' => 'permit_empty|numeric',
            'phong_khoa_id' => 'permit_empty|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            // Chuẩn bị dữ liệu cơ bản
            $data = [
                'AccountId' => $this->request->getPost('AccountId'),
                'FirstName' => $this->request->getPost('FirstName'),
                'AccountType' => $this->request->getPost('AccountType'),
                'FullName' => $this->request->getPost('FullName'),
                'MobilePhone' => $this->request->getPost('MobilePhone'),
                'Email' => $this->request->getPost('Email'),
                'HomePhone1' => $this->request->getPost('HomePhone1'),
                'HomePhone' => $this->request->getPost('HomePhone'),
                'PW' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'mat_khau_local' => $this->request->getPost('mat_khau_local'),
                'status' => 1,
                'bin' => 0
            ];

            // Thêm các trường ID tham chiếu
            $referenceFields = [
                'loai_nguoi_dung_id',
                'nam_hoc_id',
                'bac_hoc_id',
                'he_dao_tao_id',
                'nganh_id',
                'phong_khoa_id'
            ];

            foreach ($referenceFields as $field) {
                $value = $this->request->getPost($field);
                if (!empty($value)) {
                    $data[$field] = (int)$value;
                }
            }

            // Thêm thời gian
            $now = date('Y-m-d H:i:s');
            $data['created_at'] = $now;
            $data['updated_at'] = $now;

            // Bắt đầu transaction
            $this->model->db->transBegin();

            if ($this->model->insertRecord($data)) {
                $this->model->db->transCommit();
                return redirect()->to('/nguoidung')
                               ->with('message', 'Tạo người dùng mới thành công');
            }

            $this->model->db->transRollback();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Không thể tạo người dùng. Vui lòng thử lại.');

        } catch (\Exception $e) {
            $this->model->db->transRollback();
            log_message('error', 'Error creating user: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Có lỗi xảy ra khi tạo người dùng');
        }
    }

    public function edit($student_id)
    {
        $data['user'] = $this->model->find($student_id);

        if (!$data['user']) {
            return redirect()->to('/nguoidung')->with('error', 'Không tìm thấy người dùng.');
        }

        return view('App\Modules\nguoidung\Views\edit', $data);
    }

    public function update($student_id)
    {
        $data = $this->request->getPost();
        if (!empty($data['PW'])) {
            $data['PW'] = password_hash($data['PW'], PASSWORD_DEFAULT);
        } else {
            unset($data['PW']);
        }

        if ($this->model->update($student_id, $data)) {
            return redirect()->to('/nguoidung')->with('success', 'Cập nhật thành công.');
        }

        return redirect()->back()->withInput()->with('errors', $this->model->errors());
    }

    public function deleteUsers($student_id = null)
    {
        if ($student_id !== null) {
            // Xử lý xóa một người dùng
            if ($this->model->delete($student_id)) {
                // Kiểm tra nếu là yêu cầu Ajax
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Xóa người dùng thành công.',
                        'csrf_hash' => csrf_hash()
                    ]);
                }
                return redirect()->to('/nguoidung')->with('success', 'Xóa người dùng thành công.');
            } else {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Không thể xóa người dùng. Vui lòng thử lại.',
                        'csrf_hash' => csrf_hash()
                    ]);
                }
            }
        } else {
            // Xử lý xóa nhiều người dùng
            $ids = $this->request->getPost('student_ids');
            if (is_array($ids) && $this->model->softDeleteMultiple($ids)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Xóa người dùng thành công.',
                        'csrf_hash' => csrf_hash()
                    ]);
                }
                return redirect()->to('/nguoidung')->with('success', 'Xóa người dùng thành công.');
            } else {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Không thể xóa người dùng. Vui lòng thử lại.',
                        'csrf_hash' => csrf_hash()
                    ]);
                }
            }
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Không thể xóa người dùng. Vui lòng thử lại.',
                'csrf_hash' => csrf_hash()
            ]);
        }
        return redirect()->back()->with('error', 'Không thể xóa người dùng. Vui lòng thử lại.');
    }

    public function restoreUsers($student_id = null)
    {
        // Kiểm tra nếu là yêu cầu Ajax
        $isAjax = $this->request->isAJAX();
        
        // Log để debug
        log_message('debug', 'restoreUsers called with ID: ' . $student_id);
        log_message('debug', 'Request method: ' . $this->request->getMethod());
        log_message('debug', 'Is Ajax request: ' . ($isAjax ? 'Yes' : 'No'));
        
        if ($student_id !== null) {
            // Xử lý khôi phục một người dùng
            $user = $this->model->onlyDeleted()->find($student_id);
            log_message('debug', 'User found: ' . ($user ? 'Yes' : 'No'));
            
            if ($user) {
                $this->model->protect(false);
                $data = [
                    'deleted_at' => null,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                if ($this->model->update($student_id, $data)) {
                    if ($isAjax || $this->request->getMethod() === 'post') {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Khôi phục người dùng thành công.',
                            'user' => [
                                'student_id' => $student_id,
                                'AccountId' => $user['AccountId'],
                                'FullName' => $user['FullName'],
                                'status' => $user['status']
                            ],
                            'csrf_hash' => csrf_hash()
                        ]);
                    }
                    return redirect()->to('/nguoidung/listdeleted')
                                   ->with('success', 'Khôi phục người dùng thành công.');
                } else {
                    log_message('error', 'Failed to update user: ' . json_encode($this->model->errors()));
                    if ($isAjax || $this->request->getMethod() === 'post') {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Không thể khôi phục người dùng. Vui lòng thử lại.',
                            'csrf_hash' => csrf_hash()
                        ]);
                    }
                }
            } else {
                log_message('error', 'User not found with ID: ' . $student_id);
                if ($isAjax || $this->request->getMethod() === 'post') {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Không tìm thấy người dùng để khôi phục.',
                        'csrf_hash' => csrf_hash()
                    ]);
                }
            }
        } else {
            // Xử lý khôi phục nhiều người dùng
            $ids = $this->request->getPost('student_ids');
            log_message('debug', 'IDs received: ' . json_encode($ids));
            
            if (is_array($ids) && !empty($ids)) {
                // Lấy thông tin người dùng trước khi khôi phục
                $users = $this->model->onlyDeleted()->whereIn('student_id', $ids)->findAll();
                log_message('debug', 'Users found: ' . count($users));
                
                if (!empty($users)) {
                    $this->model->protect(false);
                    $data = [
                        'deleted_at' => null,
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    
                    if ($this->model->update($ids, $data)) {
                        if ($isAjax || $this->request->getMethod() === 'post') {
                            return $this->response->setJSON([
                                'success' => true,
                                'message' => 'Khôi phục người dùng thành công.',
                                'users' => $users,
                                'csrf_hash' => csrf_hash()
                            ]);
                        }
                        return redirect()->to('/nguoidung/listdeleted')
                                       ->with('success', 'Khôi phục người dùng thành công.');
                    } else {
                        log_message('error', 'Failed to update users: ' . json_encode($this->model->errors()));
                        if ($isAjax || $this->request->getMethod() === 'post') {
                            return $this->response->setJSON([
                                'success' => false,
                                'message' => 'Không thể khôi phục người dùng. Vui lòng thử lại.',
                                'csrf_hash' => csrf_hash()
                            ]);
                        }
                    }
                } else {
                    log_message('error', 'No users found with IDs: ' . json_encode($ids));
                    if ($isAjax || $this->request->getMethod() === 'post') {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Không tìm thấy người dùng để khôi phục.',
                            'csrf_hash' => csrf_hash()
                        ]);
                    }
                }
            } else {
                log_message('error', 'No IDs received or invalid IDs');
                if ($isAjax || $this->request->getMethod() === 'post') {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Vui lòng chọn ít nhất một người dùng để khôi phục.',
                        'csrf_hash' => csrf_hash()
                    ]);
                }
            }
        }
        
        if ($isAjax || $this->request->getMethod() === 'post') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Không thể khôi phục người dùng. Vui lòng thử lại.',
                'csrf_hash' => csrf_hash()
            ]);
        }
        return redirect()->back()->with('error', 'Không thể khôi phục người dùng. Vui lòng thử lại.');
    }

    public function forceDelete()
    {
        $ids = $this->request->getPost('student_ids');
        $isAjax = $this->request->isAJAX();

        if (is_array($ids) && !empty($ids)) {
            if ($this->model->forceDeleteMultiple($ids)) {
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Xóa vĩnh viễn người dùng thành công.',
                        'csrf_hash' => csrf_hash()
                    ]);
                }
                return redirect()->to('/nguoidung/listdeleted')->with('success', 'Xóa vĩnh viễn người dùng thành công.');
            } else {
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Không thể xóa vĩnh viễn người dùng. Vui lòng thử lại.',
                        'csrf_hash' => csrf_hash()
                    ]);
                }
            }
        } else {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Vui lòng chọn ít nhất một người dùng để xóa vĩnh viễn.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
        }

        if ($isAjax) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Không thể xóa vĩnh viễn người dùng. Vui lòng thử lại.',
                'csrf_hash' => csrf_hash()
            ]);
        }
        return redirect()->back()->with('error', 'Không thể xóa vĩnh viễn người dùng.');
    }

    public function resetPassword()
	{
		$post = $this->request->getPost();

		if ($post) {
			$u_password_hash = password_hash(service('settings')->get('Config\App.resetPassWord'), PASSWORD_DEFAULT);
			$this->model->set('PW', $u_password_hash)
						->whereIn('student_id', $post['student_id'])
						->update();

			return redirect()->to('/nguoidung')
							 ->with('info', 'Người dùng đã reset PassWord thành công!');
		}
		else {
			return redirect()->back()
							 ->with('warning', 'Bạn vui lòng chọn Người dùng để reset PassWord');
		}
	}
}
