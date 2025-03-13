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
