<?php
/**
 * 9/16/2022
 * AUTHOR:PDV-PC
 */
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Role;
use App\Models\RoleModel;

class Roles extends BaseController {

	protected $model;

	public function __construct()
	{
		$this->model = new RoleModel();
	}

	public function index()
	{
		$data = $this->model->getAllRoles();

		return view('roles/index', ['data' => $data]);
	}

	public function new()
	{
		$data = new Role();
		return view('roles/new', [
			'data' => $data
		]);
	}

	public function create()
	{
		$data = new Role($this->request->getpost());

		if ($this->model->protect(FALSE)->insert($data)) {
			return redirect()->to('/Roles')
							 ->with('info', 'Role đã được tạo!');
		}
		else {
			return redirect()->back()
							 ->with('errors', $this->model->errors())
							 ->with('warning', 'Quá trình tạo Role có lỗi!')
							 ->withInput();
		}
	}

	public function edit($id)
	{
		$data = $this->getRoleOr404($id);

		return view('roles/edit', [
			'data' => $data
		]);
	}

	public function update($id)
	{
		$data = $this->getRoleOr404($id);

		$post = $this->request->getPost();

		$data->fill($post);

		if (! $data->hasChanged()) {
			return redirect()->back()
							 ->with('warning', 'Không có gì xảy ra!')
							 ->withInput();
		}
		if ($this->model->protect(FALSE)->save($data)) {
			return redirect()->to('/Roles')
							 ->with('info', 'Edit Role thành công!');
		}
		else {
			return redirect()->back()
				->with('errors', $this->model->errors())
				->with('warning', 'Đã có lỗi xảy ra!')
				->withInput();
		}
	}

	public function delete($id)
	{
		$data = $this->getRoleOr404($id);

		$this->model->delete($data->r_id);

		return redirect()->to('/Roles')
						 ->with('info', 'Deleted thành công Roles Id : ' . $data->r_id);

	}

	public function listDeleted()
	{
		$data = $this->model->getAllRolesDeleted();

		return view('Roles/listDeleted', ['data' => $data]);
	}

	public function restoreRole($id)
	{
		$data = $this->getRoleDeletedOr404($id);

		$data->r_deleted_at = NULL;

		if ($this->model->protect(FALSE)->save($data)) {
			return redirect()->to('/Roles')
							 ->with('info', 'Role đã được restored thành công!');
		}

		return redirect()->back()
						 ->with('warning', 'Đã có lỗi xảy ra!');

	}

	public function assignPermissions($id)
	{
		$data = $this->getRoleOr404($id);

		$permissionsOfRole = $this->model->getInnerJoinPermissions($id);

		$permissionsOfRole = $permissionsOfRole ? array_column($permissionsOfRole, 'permission_id') : [];

		$getPermissions = new \App\Models\PermissionModel();

		$allPermissions = $getPermissions->getAllPermissions(1);

		return view('Roles/assignPermissions', [
			'data' => $data,
			'allPermissions' => $allPermissions,
			'permissionsOfRole' => $permissionsOfRole,
		]);
	}

	public function UpdateAssignPermissions($id)
	{
		$data = $this->getRoleOr404($id);

		$PermRole = new \App\Models\PermissionRoleModel();

		$PermRole->where('role_id', $id)->delete();

		$post = $this->request->getPost();

		if ($post) {
			$data = [];
			foreach ($post['permission_id'] as $permission_id) {
				$data[] = [
					'permission_id' => $permission_id,
					'role_id' => $id,
				];
			}

			$PermRole->insertBatch($data);
		}

		return redirect()->to('/roles/assignpermissions/' . $id)
						 ->with('info', 'Bạn đã cập nhật bản phân quyền cho Role này!');
	}

	private function getRoleOr404($id)
	{
		$data = $this->model->findRole($id);

		if ($data === null) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException('Không tìm thấy Role có ID là' . ': ' . $id);

		}

		return $data;
	}

	private function getRoleDeletedOr404($id)
	{
		$data = $this->model->findRoleDeleted($id);

		if ($data === null) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException('Không tìm thấy Role có ID là' . ': ' . $id);

		}

		return $data;
	}
}
