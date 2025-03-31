<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [
        'my_timer', 
        'my_connectDB', 
        'my_array', 
        'my_string', 
        'form', 
        'url', 
        'auth', 
        'setting', 
        'authstudent', 
        'file',
        'pagination'
    ];

    // Các thuộc tính chung
    protected $moduleName = '';
    protected $modulePath = '';
    protected $viewPath = '';
    protected $modelName = '';
    protected $entityName = '';
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $perPage = 10;
    protected $searchFields = [];
    protected $filterFields = [];
    protected $sortFields = [];
    protected $defaultSort = 'created_at DESC';

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();

        // Khởi tạo các thuộc tính chung
        $this->initializeCommonProperties();
    }

    protected function initializeCommonProperties()
    {
        // Lấy tên module từ namespace
        $className = get_class($this);
        $namespaceParts = explode('\\', $className);
        $this->moduleName = $namespaceParts[1] ?? '';
        $this->modulePath = "app/Modules/{$this->moduleName}";
        $this->viewPath = "Modules/{$this->moduleName}/Views";
        
        // Tạo tên model và entity
        $controllerName = end($namespaceParts);
        $baseName = str_replace('Controller', '', $controllerName);
        $this->modelName = "App\\Modules\\{$this->moduleName}\\Models\\{$baseName}Model";
        $this->entityName = "App\\Modules\\{$this->moduleName}\\Entities\\{$baseName}";
    }

    // Các phương thức chung cho CRUD
    public function index()
    {
        $model = new $this->modelName();
        
        // Xử lý tìm kiếm
        $search = $this->request->getGet('search');
        $filters = $this->request->getGet('filters') ?? [];
        $sort = $this->request->getGet('sort') ?? $this->defaultSort;
        
        $data = [
            'title' => ucfirst($this->moduleName),
            'search' => $search,
            'filters' => $filters,
            'sort' => $sort,
            'searchFields' => $this->searchFields,
            'filterFields' => $this->filterFields,
            'sortFields' => $this->sortFields
        ];

        // Lấy dữ liệu với phân trang
        $data['items'] = $model->search([
            'search' => $search,
            'filters' => $filters
        ], [
            'sort' => $sort,
            'page' => $this->request->getGet('page') ?? 1,
            'limit' => $this->perPage
        ]);

        return view("{$this->viewPath}/index", $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Thêm mới ' . ucfirst($this->moduleName),
            'validation' => \Config\Services::validation()
        ];

        if ($this->request->getMethod() === 'post') {
            if ($this->validate($this->validationRules, $this->validationMessages)) {
                $model = new $this->modelName();
                $entity = new $this->entityName($this->request->getPost());
                
                if ($model->insert($entity)) {
                    return redirect()->to("/{$this->moduleName}")->with('success', 'Thêm mới thành công');
                }
            }
        }

        return view("{$this->viewPath}/create", $data);
    }

    public function edit($id)
    {
        $model = new $this->modelName();
        $item = $model->find($id);

        if (!$item) {
            return redirect()->to("/{$this->moduleName}")->with('error', 'Không tìm thấy dữ liệu');
        }

        $data = [
            'title' => 'Chỉnh sửa ' . ucfirst($this->moduleName),
            'item' => $item,
            'validation' => \Config\Services::validation()
        ];

        if ($this->request->getMethod() === 'post') {
            if ($this->validate($this->validationRules, $this->validationMessages)) {
                $entity = new $this->entityName($this->request->getPost());
                
                if ($model->update($id, $entity)) {
                    return redirect()->to("/{$this->moduleName}")->with('success', 'Cập nhật thành công');
                }
            }
        }

        return view("{$this->viewPath}/edit", $data);
    }

    public function delete($id)
    {
        $model = new $this->modelName();
        
        if ($model->delete($id)) {
            return redirect()->to("/{$this->moduleName}")->with('success', 'Xóa thành công');
        }

        return redirect()->to("/{$this->moduleName}")->with('error', 'Xóa thất bại');
    }

    public function show($id)
    {
        $model = new $this->modelName();
        $item = $model->find($id);

        if (!$item) {
            return redirect()->to("/{$this->moduleName}")->with('error', 'Không tìm thấy dữ liệu');
        }

        $data = [
            'title' => 'Chi tiết ' . ucfirst($this->moduleName),
            'item' => $item
        ];

        return view("{$this->viewPath}/show", $data);
    }

    // Các phương thức tiện ích
    protected function uploadFile($field, $path, $allowedTypes = 'gif|jpg|jpeg|png|pdf')
    {
        $file = $this->request->getFile($field);

        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move($path, $newName);
            return $newName;
        }

        return null;
    }

    protected function deleteFile($path, $filename)
    {
        if (file_exists($path . $filename)) {
            unlink($path . $filename);
        }
    }

    protected function formatDate($date)
    {
        if (empty($date)) {
            return null;
        }
        return date('Y-m-d H:i:s', strtotime($date));
    }

    protected function jsonResponse($data, $status = 200)
    {
        return $this->response->setJSON($data)->setStatusCode($status);
    }
}
