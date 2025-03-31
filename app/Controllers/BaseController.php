<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Config\Services;

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

    // Các thuộc tính chung cho module
    protected $route_url = '';
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
        $this->moduleName = $namespaceParts[2] ?? '';
        $this->modulePath = "app/Modules/{$this->moduleName}";
        
        // Đặt viewPath thành tên namespace (tên module)
        $this->viewPath = "App\\Modules\\{$this->moduleName}\\Views\\"; 
        
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

        return view($this->viewPath . '::index', $data);
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

        return view($this->viewPath . '::create', $data);
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
            return $this->update($id);
        }

        return view($this->viewPath . 'edit', $data);
    }

    /**
     * Xử lý cập nhật dữ liệu
     * 
     * @param int $id ID của bản ghi cần cập nhật
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    protected function update($id)
    {
        if ($this->validate($this->validationRules, $this->validationMessages)) {
            $model = new $this->modelName();
            $entity = new $this->entityName($this->request->getPost());
            
            if ($model->update($id, $entity)) {
                return redirect()->to("/{$this->moduleName}")->with('success', 'Cập nhật thành công');
            }
        }

        // Nếu có lỗi validation, quay lại form với dữ liệu cũ
        return redirect()->back()
            ->withInput()
            ->with('error', 'Vui lòng kiểm tra lại thông tin');
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

        return view($this->viewPath . 'show', $data);
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

    /**
     * Xử lý request và trả về response dạng JSON
     * 
     * @param mixed $data Dữ liệu cần trả về
     * @param int $status Mã trạng thái HTTP
     * @param string $message Thông báo
     * @return \CodeIgniter\HTTP\Response
     */
    protected function jsonResponse($data = null, int $status = 200, string $message = '')
    {
        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
        
        return $this->response->setJSON($response)->setStatusCode($status);
    }

    /**
     * Xử lý request và trả về response dạng JSON với phân trang
     * 
     * @param array $data Dữ liệu cần trả về
     * @param int $total Tổng số bản ghi
     * @param int $page Trang hiện tại
     * @param int $limit Số bản ghi mỗi trang
     * @param string $message Thông báo
     * @return \CodeIgniter\HTTP\Response
     */
    protected function jsonResponseWithPagination(array $data, int $total, int $page, int $limit, string $message = '')
    {
        $response = [
            'status' => 200,
            'message' => $message,
            'data' => $data,
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'total_pages' => ceil($total / $limit)
            ]
        ];
        
        return $this->response->setJSON($response);
    }

    /**
     * Xử lý request và trả về response dạng JSON với lỗi
     * 
     * @param string $message Thông báo lỗi
     * @param int $status Mã trạng thái HTTP
     * @param array $errors Chi tiết lỗi
     * @return \CodeIgniter\HTTP\Response
     */
    protected function jsonErrorResponse(string $message, int $status = 400, array $errors = [])
    {
        $response = [
            'status' => $status,
            'message' => $message,
            'errors' => $errors
        ];
        
        return $this->response->setJSON($response)->setStatusCode($status);
    }

    /**
     * Xử lý request và trả về response dạng JSON với validation
     * 
     * @param array $data Dữ liệu cần validate
     * @param array $rules Quy tắc validation
     * @param array $messages Thông báo lỗi
     * @return \CodeIgniter\HTTP\Response
     */
    protected function jsonValidationResponse(array $data, array $rules, array $messages = [])
    {
        $validation = \Config\Services::validation();
        $validation->setRules($rules, $messages);
        
        if (!$validation->run($data)) {
            return $this->jsonErrorResponse(
                'Dữ liệu không hợp lệ',
                422,
                $validation->getErrors()
            );
        }
        
        return $this->jsonResponse($data);
    }

    /**
     * Xử lý request và trả về response dạng JSON với file upload
     * 
     * @param string $field Tên trường file
     * @param string $path Đường dẫn lưu file
     * @param array $options Tùy chọn upload
     * @return \CodeIgniter\HTTP\Response
     */
    protected function jsonFileUploadResponse(string $field, string $path, array $options = [])
    {
        $file = $this->request->getFile($field);
        
        if (!$file->isValid()) {
            return $this->jsonErrorResponse(
                'File không hợp lệ',
                400,
                ['file' => $file->getErrorString()]
            );
        }
        
        $newName = $file->getRandomName();
        $file->move($path, $newName);
        
        return $this->jsonResponse([
            'filename' => $newName,
            'path' => $path . $newName
        ]);
    }

    /**
     * Xử lý request và trả về response dạng JSON với file download
     * 
     * @param string $path Đường dẫn file
     * @param string $filename Tên file
     * @return \CodeIgniter\HTTP\Response
     */
    protected function jsonFileDownloadResponse(string $path, string $filename)
    {
        if (!file_exists($path)) {
            return $this->jsonErrorResponse('File không tồn tại', 404);
        }
        
        return $this->response->download($path, $filename);
    }

    /**
     * Xử lý request và trả về response dạng JSON với file delete
     * 
     * @param string $path Đường dẫn file
     * @return \CodeIgniter\HTTP\Response
     */
    protected function jsonFileDeleteResponse(string $path)
    {
        if (!file_exists($path)) {
            return $this->jsonErrorResponse('File không tồn tại', 404);
        }
        
        unlink($path);
        
        return $this->jsonResponse(null, 200, 'Xóa file thành công');
    }

    /**
     * Xử lý request và trả về response dạng JSON với cache
     * 
     * @param string $key Khóa cache
     * @param callable $callback Hàm lấy dữ liệu
     * @param int $ttl Thời gian cache (giây)
     * @return \CodeIgniter\HTTP\Response
     */
    protected function jsonCacheResponse(string $key, callable $callback, int $ttl = 3600)
    {
        $cache = \Config\Services::cache();
        
        if ($data = $cache->get($key)) {
            return $this->jsonResponse($data);
        }
        
        $data = $callback();
        $cache->save($key, $data, $ttl);
        
        return $this->jsonResponse($data);
    }

    /**
     * Xử lý request và trả về response dạng JSON với queue
     * 
     * @param string $queue Tên queue
     * @param array $data Dữ liệu cần xử lý
     * @return \CodeIgniter\HTTP\Response
     */
    protected function jsonQueueResponse(string $queue, array $data)
    {
        $queue = \Config\Services::queue();
        
        $queue->push($queue, $data);
        
        return $this->jsonResponse(null, 202, 'Đã thêm vào queue');
    }

    /**
     * Xử lý request và trả về response dạng JSON với event
     * 
     * @param string $event Tên event
     * @param array $data Dữ liệu cần xử lý
     * @return \CodeIgniter\HTTP\Response
     */
    protected function jsonEventResponse(string $event, array $data)
    {
        $events = \Config\Services::events();
        
        $events->trigger($event, $data);
        
        return $this->jsonResponse(null, 202, 'Đã kích hoạt event');
    }

    /**
     * Xử lý request và trả về response dạng JSON với log
     * 
     * @param string $level Cấp độ log
     * @param string $message Nội dung log
     * @param array $context Ngữ cảnh log
     * @return \CodeIgniter\HTTP\Response
     */
    protected function jsonLogResponse(string $level, string $message, array $context = [])
    {
        $logger = \Config\Services::logger();
        
        $logger->log($level, $message, $context);
        
        return $this->jsonResponse(null, 200, 'Đã ghi log');
    }

    /**
     * Xử lý request và trả về response dạng JSON với session
     * 
     * @param string $key Khóa session
     * @param mixed $value Giá trị session
     * @return \CodeIgniter\HTTP\Response
     */
    protected function jsonSessionResponse(string $key, $value)
    {
        $session = \Config\Services::session();
        
        $session->set($key, $value);
        
        return $this->jsonResponse(null, 200, 'Đã lưu session');
    }

    /**
     * Xử lý request và trả về response dạng JSON với cookie
     * 
     * @param string $key Khóa cookie
     * @param mixed $value Giá trị cookie
     * @param array $options Tùy chọn cookie
     * @return \CodeIgniter\HTTP\Response
     */
    protected function jsonCookieResponse(string $key, $value, array $options = [])
    {
        $response = $this->response;
        
        $response->setCookie($key, $value, $options);
        
        return $this->jsonResponse(null, 200, 'Đã lưu cookie');
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

    // Các phương thức export (cần cài đặt thư viện ví dụ: TCPDF, PhpSpreadsheet)
    public function exportPdf()
    {
        $params = $this->getSearchParams();
        $model = new $this->modelName();
        $items = $model->getAllByParams($params); // Sử dụng phương thức mới

        // Logic tạo PDF (ví dụ sử dụng TCPDF)
        // $pdf = new \TCPDF();
        // ... Cấu hình và thêm dữ liệu vào PDF ...
        // $pdf->Output('danh_sach_' . $this->moduleName . '.pdf', 'D'); 

        // Tạm thời trả về thông báo
        return redirect()->back()->with('info', 'Chức năng Export PDF đang được phát triển.');
    }

    public function exportExcel()
    {
        $params = $this->getSearchParams();
        $model = new $this->modelName();
        $items = $model->getAllByParams($params); // Sử dụng phương thức mới

        // Logic tạo Excel (ví dụ sử dụng PhpSpreadsheet)
        // $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // $sheet = $spreadsheet->getActiveSheet();
        // ... Thêm dữ liệu vào sheet ...
        // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment;filename="danh_sach_' . $this->moduleName . '.xlsx"');
        // $writer->save('php://output');
        // exit;

        // Tạm thời trả về thông báo
        return redirect()->back()->with('info', 'Chức năng Export Excel đang được phát triển.');
    }

    public function exportDeletedPdf()
    {
        $params = $this->getSearchParams();
        $model = new $this->modelName();
        $items = $model->onlyDeleted()->getAllByParams($params); // Lấy dữ liệu đã xóa

        // Logic tạo PDF cho dữ liệu đã xóa
        // ...

        return redirect()->back()->with('info', 'Chức năng Export Deleted PDF đang được phát triển.');
    }

    public function exportDeletedExcel()
    {
        $params = $this->getSearchParams();
        $model = new $this->modelName();
        $items = $model->onlyDeleted()->getAllByParams($params); // Lấy dữ liệu đã xóa

        // Logic tạo Excel cho dữ liệu đã xóa
        // ...

        return redirect()->back()->with('info', 'Chức năng Export Deleted Excel đang được phát triển.');
    }

    /**
     * Hiển thị danh sách các bản ghi đã bị xóa (Soft Delete).
     */
    public function listdeleted()
    {
        $model = new $this->modelName();
        $params = $this->getSearchParams();
        
        // Sử dụng onlyDeleted() để chỉ lấy các bản ghi đã xóa
        // Và sử dụng getByParams để lấy dữ liệu với phân trang
        $items = $model->onlyDeleted()->getByParams($params, [
            'limit' => $this->perPage,
            'page' => $params['page'] 
        ]);
        
        $pager = $model->pager ?? service('pager');

        $data = [
            'title' => 'Danh sách đã xóa - ' . ucfirst($this->moduleName),
            'items' => $items,
            'pager' => $pager->links(),
            'search' => $params['search'],
            'filters' => $params['filters'],
            'sort' => $params['sort'],
            'searchFields' => $this->searchFields,
            'filterFields' => $this->filterFields,
            'sortFields' => $this->sortFields,
            'moduleName' => $this->moduleName, 
            'viewPath' => $this->viewPath 
        ];
        
        return view($this->viewPath . 'listdeleted', $data);
    }

    /**
     * Placeholder method to get headers for Excel export.
     * Override this method in your specific controller.
     *
     * @return array
     */
    protected function getExportHeaders(): array
    {
        // Example: return ['ID', 'Name', 'Email', 'Created At'];
        // Lấy các trường từ $allowedFields của model hoặc định nghĩa cứng
        $model = new $this->modelName();
        $allowedFields = $model->allowedFields ?? [];
        // Loại bỏ các trường không cần thiết cho export (ví dụ: timestamps, deleted_at nếu không muốn)
        return array_diff($allowedFields, [$model->createdField, $model->updatedField, $model->deletedField]);
    }

    /**
     * Placeholder method to get row data for Excel export.
     * Override this method in your specific controller.
     *
     * @param object|array $item The item entity or array.
     * @return array
     */
    protected function getExportRowData($item): array
    {
         // Example: return [$item->id, $item->name, $item->email, $item->created_at];
         $data = [];
         $headers = $this->getExportHeaders(); // Lấy danh sách headers đã định nghĩa
         foreach ($headers as $field) {
             // Xử lý để lấy giá trị đúng từ object hoặc array
             if (is_object($item) && isset($item->$field)) {
                 $value = $item->$field;
             } elseif (is_array($item) && isset($item[$field])) {
                 $value = $item[$field];
             } else {
                 $value = ''; // Hoặc giá trị mặc định khác
             }
             
             // Bạn có thể thêm định dạng dữ liệu ở đây nếu cần
             // Ví dụ: định dạng ngày tháng, số,...
             // if ($field === 'status') $value = ($value == 1) ? 'Active' : 'Inactive';
             // if ($field === 'created_at' && $value instanceof \CodeIgniter\I18n\Time) $value = $value->toDateTimeString();
             
             $data[] = $value;
         }
         return $data;
    }

     /**
      * Placeholder method to get headers for deleted data Excel export.
      * Override this method in your specific controller.
      *
      * @return array
      */
     protected function getDeletedExportHeaders(): array
     {
         // Thường sẽ giống getExportHeaders() nhưng có thể thêm cột ngày xóa
         $headers = $this->getExportHeaders();
         $model = new $this->modelName();
         if ($model->useSoftDeletes && !in_array($model->deletedField, $headers)) {
             $headers[] = $model->deletedField; // Thêm cột ngày xóa
         }
         return $headers;
     }

     /**
      * Placeholder method to get row data for deleted data Excel export.
      * Override this method in your specific controller.
      *
      * @param object|array $item The item entity or array.
      * @return array
      */
     protected function getDeletedExportRowData($item): array
     {
         // Thường sẽ giống getExportRowData() nhưng lấy thêm dữ liệu cột ngày xóa
         $data = [];
         $headers = $this->getDeletedExportHeaders();
         $model = new $this->modelName();
         
         foreach ($headers as $field) {
              if (is_object($item) && isset($item->$field)) {
                  $value = $item->$field;
              } elseif (is_array($item) && isset($item[$field])) {
                  $value = $item[$field];
              } else {
                  $value = ''; 
              }
              
               // Định dạng ngày xóa nếu cần
              // if ($field === $model->deletedField && $value instanceof \CodeIgniter\I18n\Time) {
              //     $value = $value->toDateTimeString();
              // }
              
             $data[] = $value;
         }
         return $data;
     }
}
