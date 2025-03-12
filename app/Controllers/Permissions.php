<?php
/**
 * 9/16/2022
 * AUTHOR:PDV-PC
 */
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Permission;
use App\Models\PermissionModel;

class Permissions extends BaseController {

	protected $model;

	public function __construct()
	{
		$this->model = new PermissionModel();
	}

	public function index()
	{
		$data = $this->model->getAllPermissions();

		return view('Permissions/index', ['data' => $data]);
	}

	public function new()
	{
		$data = new Permission();
		return view('Permissions/new', [
			'data' => $data
		]);
	}

	public function create()
	{
		$data = new Permission($this->request->getpost());

		if ($this->model->protect(FALSE)->insert($data)) {
			return redirect()->to('/permissions')
							 ->with('info', 'Chức năng đã được tạo!');
		}
		else {
			return redirect()->back()
							 ->with('errors', $this->model->errors())
							 ->with('warning', 'Quá trình tạo chức năng có lỗi!')
							 ->withInput();
		}
	}

	public function edit($id)
	{
		$data = $this->getPermissionOr404($id);

		return view('Permissions/edit', [
			'data' => $data
		]);
	}

	public function update($id)
	{
		$data = $this->getPermissionOr404($id);

		$post = $this->request->getPost();

		$data->fill($post);

		echo '<pre>';
		var_dump($data);
		echo '</pre>';
		die;

		if (! $data->hasChanged()) {
			return redirect()->back()
							 ->with('warning', 'Không có gì xảy ra!')
							 ->withInput();
		}
		if ($this->model->protect(FALSE)->save($data)) {
			return redirect()->to('/permissions')
							 ->with('info', 'Edit Permission thành công!');
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
		$data = $this->getPermissionOr404($id);

		$this->model->delete($data->p_id);

		return redirect()->to('/permissions')
						 ->with('info', 'Deleted thành công Permissions Id : ' . $data->p_id);

	}

	public function listDeleted()
	{
		$data = $this->model->getAllPermissionsDeleted();

		return view('Permissions/listDeleted', ['data' => $data]);
	}

	public function restorePermission($id)
	{
		$data = $this->getPermissionDeletedOr404($id);

		$data->p_deleted_at = NULL;

		if ($this->model->protect(FALSE)->save($data)) {
			return redirect()->to('/permissions')
							 ->with('info', 'Permission đã được restored thành công!');
		}

		return redirect()->back()
						 ->with('warning', 'Đã có lỗi xảy ra!');

	}

	private function getPermissionOr404($id)
	{
		$data = $this->model->findPermission($id);

		if ($data === null) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException('Không tìm thấy Permission có ID là' . ': ' . $id);

		}

		return $data;
	}

	private function getPermissionDeletedOr404($id)
	{
		$data = $this->model->findPermissionDeleted($id);

		if ($data === null) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException('Không tìm thấy Permission có ID là' . ': ' . $id);

		}

		return $data;
	}
}
