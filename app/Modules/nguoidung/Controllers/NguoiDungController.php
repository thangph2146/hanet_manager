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
            'email'     => 'permit_empty|valid_email|is_unique[nguoi_dung.Email]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Chuẩn bị dữ liệu
        $data = [
            'AccountId' => $this->request->getPost('AccountId'),
            'FullName'  => $this->request->getPost('FullName'),
            'Email'     => $this->request->getPost('email'),
            'PW'        => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'status'    => 1,
            'bin'       => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Thêm người dùng mới
        try {
            if ($this->model->insertRecord($data)) {
                return redirect()->to('/nguoidung')->with('message', 'Tạo người dùng mới thành công');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error creating user: ' . $e->getMessage());
        }
        
        return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra khi tạo người dùng');
    }

    public function edit($id)
    {
        $data['user'] = $this->model->getUserById($id);

        if (!$data['user']) {
            return redirect()->to('/nguoidung')->with('error', 'Không tìm thấy người dùng.');
        }

        return view('nguoidung/edit', $data);
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

    public function restoreUsers($id)
	{
		$data = $this->getUserDeletedOr404($id);

		$data->u_deleted_at = NULL;

		if ($this->model->protect(FALSE)->save($data)) {
			return redirect()->to('/users')
							 ->with('info', 'User đã được restored thành công!');
		}

		return redirect()->back()
						 ->with('warning', 'Đã có lỗi xảy ra!');

	}

    public function forceDeleteUsers()
    {
        $ids = $this->request->getPost('ids');

        if ($this->model->forceDeleteMultiple($ids)) {
            return redirect()->to('/nguoidung')->with('success', 'Xóa vĩnh viễn người dùng.');
        }

        return redirect()->back()->with('error', 'Không thể xóa vĩnh viễn.');
    }
}
