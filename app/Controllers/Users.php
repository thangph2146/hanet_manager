<?php
/**
 * 9/16/2022
 * AUTHOR:PDV-PC
 */
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\User;
use App\Models\PermissionModel;
use App\Models\RoleModel;
use App\Models\SettingModel;
use App\Models\UserModel;

class Users extends BaseController {

	protected $model;

	public function __construct()
	{
		$this->model = new UserModel();
	}

	public function index()
	{
		$data = $this->model->getAllUsers();

		return view('users/index', ['data' => $data]);
	}

	public function dashboard()
    {
		$role = new RoleModel();
		$permission = new PermissionModel();
		$setting = new SettingModel();
		$data = [
			'user' => count($this->model->getAllUsers()),
			'role' => count($role->getAllRoles()),
			'permission' => count($permission->getAllPermissions()),
			'setting' => count($setting->getAllSettings()),
		];
		return view('users/dashboard', [
			'data' => $data
		]);
	}

	public function new()
	{
		$data = new User();

		return view('users/new', [
			'data' => $data,
		]);
	}

	public function create()
	{
		$data = new User($this->request->getpost());

		if ($this->model->protect(FALSE)->insert($data)) {
			return redirect()->to('/Users')
							 ->with('info', 'User đã được tạo thành công!');
		}
		else {
			return redirect()->back()
							 ->with('errors', $this->model->errors())
							 ->with('warning', 'Quá trình tạo User có lỗi!')
							 ->withInput();
		}
	}

	public function edit($id)
	{
		$data = $this->getUserOr404($id);

		return view('users/edit', [
			'data' => $data
		]);
	}

	public function update($id)
	{
		$data = $this->getUserOr404($id);

		$post = $this->request->getPost();

		if (empty($post['password'])) {

			$this->model->disablePasswordValidation();

			unset($post['password']);
			unset($post['password_confirmation']);
		}

		$data->fill($post);

		if (! $data->hasChanged()) {
			return redirect()->back()
							 ->with('warning', 'Không có gì xảy ra!')
							 ->withInput();
		}
		if ($this->model->protect(FALSE)->save($data)) {
			return redirect()->to('/users/edit/' . $id)
							 ->with('info', 'Edit User thành công!');
		}
		else {
			return redirect()->back()
				->with('errors', $this->model->errors())
				->with('warning', 'Edit user đã có lỗi xảy ra!')
				->withInput();
		}
	}

	public function delete($id)
	{
		$data = $this->getUserOr404($id);

		$this->model->delete($data->u_id);

		return redirect()->to('/users')
						 ->with('info', 'Deleted thành công Users Id : ' . $data->u_id);

	}

	public function listDeleted()
	{
		$data = $this->model->getAllUsersDeleted();
		return view('users/listdeleted', ['data' => $data]);
	}

	public function restoreUser($id)
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

	public function assignRoles($id)
	{
		$data = $this->getUserOr404($id);

		$rolesOfUser = $this->model->getInnerJoinRoles($id);

		$rolesOfUser = $rolesOfUser ? array_column($rolesOfUser, 'role_id') : [];

		$getRoles = new RoleModel();

		$allRoles = array_column($getRoles->getAllRoles(1), 'r_name', 'r_id');

		return view('users/assignRoles', [
			'data' => $data,
			'select' => $allRoles,
			'arraySelected' => $rolesOfUser,
		]);
	}

	public function UpdateAssignRoles($id)
	{
		$data = $this->getUserOr404($id);

		$RoleUser = new \App\Models\RoleUserModel();

		$RoleUser->where('user_id', $id)->delete();

		$post = $this->request->getPost();

		if ($post) {
			$data = [];
			foreach ($post['role_id'] as $role_id) {
				$data[] = [
					'role_id' => $role_id,
					'user_id' => $id,
				];
			}

			$RoleUser->insertBatch($data);
		}

		return redirect()->to('/users/assignroles/' . $id)
						 ->with('info', 'Bạn đã cập nhật Role cho User này!');
	}

	public function resetPassWord()
	{
		$post = $this->request->getPost();

		if ($post) {
			$u_password_hash = password_hash(service('settings')->get('Config\App.resetPassWord'), PASSWORD_DEFAULT);
			$this->model->set('u_password_hash', $u_password_hash)
						->whereIn('u_id', $post['u_id'])
						->update();

			return redirect()->to('/users')
							 ->with('info', 'User đã reset PassWord thành công!');
		}
		else {
			return redirect()->back()
							 ->with('warning', 'Bạn vui lòng chọn User để reset PassWord');
		}
	}

	private function getUserOr404($id)
	{
		$data = $this->model->select("*, CONCAT(u_LastName, ' ', u_MiddleName, ' ', u_FirstName) AS u_FullName")->findUser($id);

		if ($data === null) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException('Không tìm thấy User có ID là' . ': ' . $id);

		}

		return $data;
	}

	private function getUserDeletedOr404($id)
	{
		$data = $this->model->findUserDeleted($id);

		if ($data === null) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException('Không tìm thấy User có ID là' . ': ' . $id);

		}

		return $data;
	}
}
