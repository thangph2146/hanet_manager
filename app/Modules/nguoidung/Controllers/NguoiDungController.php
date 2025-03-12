<?php

namespace App\Modules\nguoidung\Controllers;

use App\Controllers\BaseController;
use App\Modules\nguoidung\Models\NguoiDungModel;

class NguoiDungController extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new NguoiDungModel();
    }

    public function index()
    {
        $data = $this->model->getAll();
        return view('App\Modules\nguoidung\Views\index', ['data' => $data]);
    }

    public function delete($id)
    {
        if ($this->model->softDeleteRecord($id)) {
            return redirect()->to('/nguoidung')->with('message', 'Xóa người dùng thành công');
        } else {
            return redirect()->to('/nguoidung')->with('error', 'Xóa người dùng thất bại');
        }
    }

    public function resetPassword()
    {
        $ids = $this->request->getPost('id');
        if ($ids && is_array($ids)) {
            foreach ($ids as $id) {
                if ($id) {
                    $nguoidung = $this->model->getById($id);
                    if ($nguoidung) {
                        $nguoidung->fill(['PW' => password_hash(setting('App.resetPassWord'), PASSWORD_DEFAULT)]);
                        if (!$nguoidung->hasChanged()) {
                            return redirect()->back()->with('warning', 'Không có gì xảy ra!')->withInput();
                        }
                        if ($this->model->protect(FALSE)->save($nguoidung)) {
                            return redirect()->to('/nguoidung')->with('info', 'Reset mật khẩu thành công!');
                        } else {
                            return redirect()->back()->with('errors', $this->model->errors())->with('warning', 'Reset mật khẩu đã có lỗi xảy ra!')->withInput();
                        }
                    }
                }
            }
            return redirect()->to('/nguoidung')->with('message', 'Reset mật khẩu thành công');
        }
        return redirect()->to('/nguoidung')->with('error', 'Không có người dùng nào được chọn để reset mật khẩu');
    }

    public function listDeleted()
    {
        $data = $this->model->getAll(true); // Lấy tất cả bao gồm cả những bản ghi đã bị xóa mềm
        return view('App\Modules\nguoidung\Views\listdeleted', ['data' => $data]);
    }

    public function restore($id = NULL)
    {
        if ($id) {
            // Sử dụng query builder trực tiếp
            $result = $this->model->builder()
                                ->set('deleted_at', null)
                                ->where('id', $id)
                                ->update();
            
            if ($result) {
                return redirect()->to('/nguoidung/listdeleted')->with('message', 'Khôi phục người dùng thành công');
            }
        }
        return redirect()->to('/nguoidung/listdeleted')->with('error', 'Khôi phục người dùng thất bại hoặc không có người dùng nào được chọn');
    }

    // Định nghĩa các phương thức CRUD khác như create, store, edit, update, delete, show, trash, restore, purge, search
}
