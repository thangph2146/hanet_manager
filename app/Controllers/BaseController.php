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
        'authnguoidung', 
        'file',
        'pagination'
    ];

    // Các thuộc tính chung cho module
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
    protected $uploadPath = 'uploads/';
    protected $allowedFileTypes = 'gif|jpg|jpeg|png|pdf|doc|docx|xls|xlsx';
    protected $maxFileSize = 2048; // KB
    protected $relations = [];
    protected $beforeSpaceRemoval = [];
    protected $concatFields = [];

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
    protected function uploadFile($field, $path = null, $allowedTypes = null)
    {
        $path = $path ?? $this->uploadPath;
        $allowedTypes = $allowedTypes ?? $this->allowedFileTypes;
        
        $file = $this->request->getFile($field);

        if ($file->isValid() && !$file->hasMoved()) {
            if ($file->getSize() > $this->maxFileSize * 1024) {
                return null;
            }

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

    // Các phương thức xử lý form
    protected function getFormData()
    {
        $data = $this->request->getPost();
        
        // Xử lý các trường đặc biệt
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = trim($value);
            }
        }
        
        return $data;
    }

    protected function validateForm($rules = null, $messages = null)
    {
        $rules = $rules ?? $this->validationRules;
        $messages = $messages ?? $this->validationMessages;
        
        return $this->validate($rules, $messages);
    }

    // Các phương thức xử lý phân trang
    protected function getPagination($total, $perPage = null)
    {
        $perPage = $perPage ?? $this->perPage;
        $pager = service('pager');
        
        return $pager->makeLinks(
            $this->request->getGet('page') ?? 1,
            $perPage,
            $total,
            'default_full'
        );
    }

    // Các phương thức xử lý tìm kiếm và lọc
    protected function getSearchParams()
    {
        return [
            'search' => $this->request->getGet('search'),
            'filters' => $this->request->getGet('filters') ?? [],
            'sort' => $this->request->getGet('sort') ?? $this->defaultSort,
            'page' => $this->request->getGet('page') ?? 1
        ];
    }

    // Các phương thức getter/setter
    public function getModuleName(): string
    {
        return $this->moduleName;
    }

    public function getModulePath(): string
    {
        return $this->modulePath;
    }

    public function getViewPath(): string
    {
        return $this->viewPath;
    }

    public function getModelName(): string
    {
        return $this->modelName;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function getValidationRules(): array
    {
        return $this->validationRules;
    }

    public function getValidationMessages(): array
    {
        return $this->validationMessages;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getSearchFields(): array
    {
        return $this->searchFields;
    }

    public function getFilterFields(): array
    {
        return $this->filterFields;
    }

    public function getSortFields(): array
    {
        return $this->sortFields;
    }

    public function getDefaultSort(): string
    {
        return $this->defaultSort;
    }

    public function getUploadPath(): string
    {
        return $this->uploadPath;
    }

    public function getAllowedFileTypes(): string
    {
        return $this->allowedFileTypes;
    }

    public function getMaxFileSize(): int
    {
        return $this->maxFileSize;
    }

    public function getRelations(): array
    {
        return $this->relations;
    }

    public function getBeforeSpaceRemoval(): array
    {
        return $this->beforeSpaceRemoval;
    }

    public function getConcatFields(): array
    {
        return $this->concatFields;
    }

    public function setModuleName(string $name): self
    {
        $this->moduleName = $name;
        return $this;
    }

    public function setModulePath(string $path): self
    {
        $this->modulePath = $path;
        return $this;
    }

    public function setViewPath(string $path): self
    {
        $this->viewPath = $path;
        return $this;
    }

    public function setModelName(string $name): self
    {
        $this->modelName = $name;
        return $this;
    }

    public function setEntityName(string $name): self
    {
        $this->entityName = $name;
        return $this;
    }

    public function setValidationRules(array $rules): self
    {
        $this->validationRules = $rules;
        return $this;
    }

    public function setValidationMessages(array $messages): self
    {
        $this->validationMessages = $messages;
        return $this;
    }

    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;
        return $this;
    }

    public function setSearchFields(array $fields): self
    {
        $this->searchFields = $fields;
        return $this;
    }

    public function setFilterFields(array $fields): self
    {
        $this->filterFields = $fields;
        return $this;
    }

    public function setSortFields(array $fields): self
    {
        $this->sortFields = $fields;
        return $this;
    }

    public function setDefaultSort(string $sort): self
    {
        $this->defaultSort = $sort;
        return $this;
    }

    public function setUploadPath(string $path): self
    {
        $this->uploadPath = $path;
        return $this;
    }

    public function setAllowedFileTypes(string $types): self
    {
        $this->allowedFileTypes = $types;
        return $this;
    }

    public function setMaxFileSize(int $size): self
    {
        $this->maxFileSize = $size;
        return $this;
    }

    public function setRelations(array $relations): self
    {
        $this->relations = $relations;
        return $this;
    }

    public function setBeforeSpaceRemoval(array $fields): self
    {
        $this->beforeSpaceRemoval = $fields;
        return $this;
    }

    public function setConcatFields(array $fields): self
    {
        $this->concatFields = $fields;
        return $this;
    }
}
