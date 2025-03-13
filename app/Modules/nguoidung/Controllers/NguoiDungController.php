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
            'AccountId' => 'required|min_length[3]|is_unique[nguoi_dung.AccountId]',
            'FullName'  => 'required|min_length[3]',
            'password'  => 'required|min_length[6]',
            'Email'     => 'permit_empty|valid_email|is_unique[nguoi_dung.Email]',
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

    public function edit($id)
    {
        $data['user'] = $this->model->find($id);

        if (!$data['user']) {
            return redirect()->to('/nguoidung')->with('error', 'Không tìm thấy người dùng.');
        }

        return view('App\Modules\nguoidung\Views\edit', $data);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        if (!empty($data['PW'])) {
            $data['PW'] = password_hash($data['PW'], PASSWORD_DEFAULT);
        } else {
            unset($data['PW']);
        }

        if ($this->model->update($id, $data)) {
            return redirect()->to('/nguoidung')->with('success', 'Cập nhật thành công.');
        }

        return redirect()->back()->withInput()->with('errors', $this->model->errors());
    }

    public function deleteUsers($id = null)
    {
        if ($id !== null) {
            // Xử lý xóa một người dùng
            if ($this->model->delete($id)) {
                return redirect()->to('/nguoidung')->with('success', 'Xóa người dùng thành công.');
            }
        } else {
            // Xử lý xóa nhiều người dùng
            $ids = $this->request->getPost('ids');
            if (is_array($ids) && $this->model->softDeleteMultiple($ids)) {
                return redirect()->to('/nguoidung')->with('success', 'Xóa người dùng thành công.');
            }
        }

        return redirect()->back()->with('error', 'Không thể xóa người dùng. Vui lòng thử lại.');
    }

    public function restoreUsers($id = null)
    {
        if ($id !== null) {
            // Xử lý khôi phục một người dùng
            $user = $this->model->onlyDeleted()->find($id);
            if ($user) {
                $this->model->protect(false);
                $data = [
                    'deleted_at' => null,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                if ($this->model->update($id, $data)) {
                    return redirect()->to('/nguoidung/listdeleted')
                                   ->with('success', 'Khôi phục người dùng thành công.');
                }
            }
        } else {
            // Xử lý khôi phục nhiều người dùng
            $ids = $this->request->getPost('ids');
            if (is_array($ids)) {
                $this->model->protect(false);
                $data = [
                    'deleted_at' => null,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                if ($this->model->update($ids, $data)) {
                    return redirect()->to('/nguoidung/listdeleted')
                                   ->with('success', 'Khôi phục người dùng thành công.');
                }
            }
        }

        return redirect()->back()
                       ->with('error', 'Không thể khôi phục người dùng. Vui lòng thử lại.');
    }

    public function forceDeleteUsers()
    {
        $ids = $this->request->getPost('ids');

        if ($this->model->forceDeleteMultiple($ids)) {
            return redirect()->to('/nguoidung')->with('success', 'Xóa vĩnh viễn người dùng.');
        }

        return redirect()->back()->with('error', 'Không thể xóa vĩnh viễn.');
    }

    public function resetPassWord()
	{
		$post = $this->request->getPost();

		if ($post) {
			$u_password_hash = password_hash(service('settings')->get('Config\App.resetPassWord'), PASSWORD_DEFAULT);
			$this->model->set('PW', $u_password_hash)
						->whereIn('id', $post['id'])
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
