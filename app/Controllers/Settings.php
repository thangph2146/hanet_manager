<?php
/**
 * 9/16/2022
 * AUTHOR:PDV-PC
 */
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Setting;
use App\Models\SettingModel;

class Settings extends BaseController {

	protected $model;

	public function __construct()
	{
		$this->model = new SettingModel();
	}

	public function index()
	{
		$data = $this->model->getAllSettings();

		return view('Settings/index', ['data' => $data]);
	}

	public function new()
	{
		$data = new Setting();
		return view('Settings/new', [
			'data' => $data
		]);
	}

	public function create()
	{
		$data = $this->request->getpost();
		$rule = [
			'key' => 'required|trim|min_length[3]|max_length[255]',
		];
		if (! $this->validateData($data, $rule)) {
			return redirect()->back()
							 ->with('errors', $this->validator->getErrors())
							 ->with('warning', 'Quá trình tạo Setting có lỗi!')
							 ->withInput();
		}
		else {
			// Store a value
			service('settings')->set($data['key'], $data['value']);
			return redirect()->to('/Settings')
							 ->with('info', 'Setting đã được tạo!');
		}
	}

	public function edit($id)
	{
		$data = $this->getSettingOr404($id);

		return view('Settings/edit', [
			'data' => $data
		]);
	}

	public function update($id)
	{
		$data = $this->getSettingOr404($id);

		$post = $this->request->getPost();

		// Store a value
		service('settings')->set($post['key'], $post['value']);

		return redirect()->to('/Settings')
						 ->with('info', 'Bạn đã Edit Settings thành công!');
	}

	public function delete($id)
	{
		$data = $this->getSettingOr404($id);

		$this->model->delete($data->id);

		return redirect()->to('/Settings')
						 ->with('info', 'Deleted thành công Settings Id : ' . $data->id);

	}

	private function getSettingOr404($id)
	{
		$data = $this->model->findSetting($id);

		if ($data === null) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException('Không tìm thấy Setting có ID là' . ': ' . $id);

		}

		return $data;
	}
}
