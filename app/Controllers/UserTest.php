<?php
/**
 * UserTest Controller - Sử dụng các tính năng của thư viện mới
 */
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\UserTest;
use App\Models\PermissionModel;
use App\Models\RoleModel;
use App\Models\SettingModel;
use App\Models\UserTestModel;

class UserTestController extends BaseController 
{
    protected $model;

    public function __construct()
    {
        $this->model = new UserTestModel();
    }

    public function index()
    {
        // Sử dụng tính năng search mới
        $search = $this->request->getGet('search');
        $filter = $this->request->getGet('filter');
        $sort = $this->request->getGet('sort') ?? 'first_name';
        $sort_dir = $this->request->getGet('sort_dir') ?? 'ASC';
        $page = $this->request->getGet('page') ?? 1;
        $limit = $this->request->getGet('limit') ?? 10;

        $criteria = [];
        $options = [];

        if ($search) {
            $criteria['search'] = $search;
        }

        if ($filter) {
            $criteria['filters'] = ['status' => $filter];
        }

        $options['sort'] = $sort;
        $options['sort_direction'] = $sort_dir;
        $options['page'] = $page;
        $options['limit'] = $limit;

        $data = $this->model->search($criteria, $options);
        $total = $this->model->countAll(['status' => 1]);

        return view('users/index', [
            'data' => $data,
            'pager' => [
                'currentPage' => $page,
                'totalPages' => ceil($total / $limit),
                'total' => $total
            ],
            'search' => $search,
            'filter' => $filter,
            'sort' => $sort,
            'sort_dir' => $sort_dir
        ]);
    }

    public function dashboard()
    {
        $role = new RoleModel();
        $permission = new PermissionModel();
        $setting = new SettingModel();
        
        $data = [
            'user' => $this->model->countAll(['status' => 1]),
            'role' => $role->countAll(),
            'permission' => $permission->countAll(),
            'setting' => $setting->countAll(),
        ];
        
        return view('users/dashboard', [
            'data' => $data
        ]);
    }

    public function new()
    {
        $data = new UserTest();
        
        return view('users/new', [
            'data' => $data,
        ]);
    }

    public function create()
    {
        $data = new UserTest($this->request->getPost());
        
        // Sử dụng validation tích hợp trong entity
        if (!$data->validate()) {
            return redirect()->back()
                             ->with('errors', $data->getErrors())
                             ->with('warning', 'Quá trình tạo User có lỗi!')
                             ->withInput();
        }
        
        // Sử dụng createWithRelations để tạo user và các quan hệ cùng lúc
        $userId = $this->model->protect(false)->createWithRelations(
            $data->toArray(), 
            [] // Không có relation trong lúc tạo mới
        );
        
        if ($userId) {
            return redirect()->to('/usertest')
                            ->with('info', 'User đã được tạo thành công!');
        } else {
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
        
        // Nếu password rỗng, bỏ qua validation
        if (empty($post['password'])) {
            $this->model->disablePasswordValidation();
        }
        
        // Fill và validate dữ liệu
        $data->fill($post);
        
        if (!$data->hasChanged()) {
            return redirect()->back()
                            ->with('warning', 'Không có gì xảy ra!')
                            ->withInput();
        }
        
        // Validate entity trước khi lưu
        if (!$data->validate()) {
            return redirect()->back()
                            ->with('errors', $data->getErrors())
                            ->with('warning', 'Edit user đã có lỗi xảy ra!')
                            ->withInput();
        }
        
        // Sử dụng updateWithRelations để cập nhật cả quan hệ
        if ($this->model->protect(false)->updateWithRelations($id, $data->toArray(), [])) {
            return redirect()->to('/usertest/edit/' . $id)
                            ->with('info', 'Edit User thành công!');
        } else {
            return redirect()->back()
                            ->with('errors', $this->model->errors())
                            ->with('warning', 'Edit user đã có lỗi xảy ra!')
                            ->withInput();
        }
    }

    public function delete($id)
    {
        $data = $this->getUserOr404($id);
        
        // Sử dụng soft delete
        if ($this->model->delete($id)) {
            return redirect()->to('/usertest')
                            ->with('info', 'Deleted thành công User ID: ' . $id);
        } else {
            return redirect()->back()
                            ->with('warning', 'Xóa user thất bại!');
        }
    }

    public function listDeleted()
    {
        $data = $this->model->getDeleted();
        
        return view('users/listdeleted', ['data' => $data]);
    }

    public function restoreUser($id)
    {
        $data = $this->getUserDeletedOr404($id);
        
        // Sử dụng phương thức restore của BaseModel
        if ($this->model->restore($id)) {
            return redirect()->to('/usertest')
                            ->with('info', 'User đã được restored thành công!');
        }
        
        return redirect()->back()
                        ->with('warning', 'Đã có lỗi xảy ra!');
    }

    public function assignRoles($id)
    {
        $data = $this->getUserOr404($id);
        
        // Sử dụng phương thức withRelations để lấy roles
        $user = $this->model->withRelations(['roles' => [
            'type' => 'n-n',
            'table' => 'roles',
            'pivotTable' => 'roles_users_test',
            'pivotLocalKey' => 'user_id',
            'pivotForeignKey' => 'role_id',
            'entity' => 'App\Entities\Role'
        ]])->findWithRelations($id);
        
        $userRoles = [];
        if ($user && isset($user->roles)) {
            foreach ($user->roles as $role) {
                $userRoles[] = $role->id;
            }
        }
        
        $roleModel = new RoleModel();
        $allRoles = array_column($roleModel->getAllRoles(1), 'r_name', 'r_id');
        
        return view('users/assignRoles', [
            'data' => $data,
            'select' => $allRoles,
            'arraySelected' => $userRoles,
        ]);
    }

    public function updateAssignRoles($id)
    {
        $data = $this->getUserOr404($id);
        $post = $this->request->getPost();
        
        if (isset($post['role_id'])) {
            // Sử dụng phương thức updateWithRelations với quan hệ roles
            $this->model->updateWithRelations($id, [], ['roles' => $post['role_id']]);
            
            return redirect()->to('/usertest/assignroles/' . $id)
                            ->with('info', 'Bạn đã cập nhật Role cho User này!');
        }
        
        return redirect()->back()
                        ->with('warning', 'Không có Role nào được chọn!');
    }

    public function resetPassword()
    {
        $post = $this->request->getPost();
        
        if (isset($post['id']) && is_array($post['id'])) {
            $defaultPassword = service('settings')->get('Config\App.resetPassWord') ?? 'password123';
            $passwordHash = password_hash($defaultPassword, PASSWORD_DEFAULT);
            
            $this->model->builder()
                       ->set('password_hash', $passwordHash)
                       ->whereIn('id', $post['id'])
                       ->update();
                       
            return redirect()->to('/usertest')
                           ->with('info', 'User đã reset Password thành công!');
        } else {
            return redirect()->back()
                           ->with('warning', 'Bạn vui lòng chọn User để reset Password');
        }
    }
    
    // Lấy thông tin user detail với relations trong một request
    public function detail($id)
    {
        // Sử dụng withRelations để lấy cả user và các quan hệ
        $data = $this->model->withRelations([
            'roles' => [
                'type' => 'n-n',
                'table' => 'roles',
                'pivotTable' => 'roles_users_test',
                'pivotLocalKey' => 'user_id',
                'pivotForeignKey' => 'role_id',
                'entity' => 'App\Entities\Role',
                'conditions' => [
                    [
                        'field' => 'status',
                        'value' => 1
                    ]
                ]
            ],
            'permissions' => [
                'type' => 'n-n',
                'table' => 'permissions',
                'pivotTable' => 'permission_roles',
                'pivotLocalKey' => 'role_id',
                'pivotForeignKey' => 'permission_id',
                'entity' => 'App\Entities\Permission',
                'conditions' => [
                    [
                        'field' => 'status',
                        'value' => 1
                    ]
                ]
            ]
        ])->findWithRelations($id);
        
        if (!$data) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Không tìm thấy User có ID là: ' . $id);
        }
        
        // Dữ liệu đã được tự động load quan hệ
        return view('users/detail', ['data' => $data]);
    }

    // Helper methods
    private function getUserOr404($id)
    {
        $data = $this->model->findUser($id);
        
        if ($data === null) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Không tìm thấy User có ID là: ' . $id);
        }
        
        return $data;
    }

    private function getUserDeletedOr404($id)
    {
        $data = $this->model->findUserDeleted($id);
        
        if ($data === null) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Không tìm thấy User có ID là: ' . $id);
        }
        
        return $data;
    }
} 