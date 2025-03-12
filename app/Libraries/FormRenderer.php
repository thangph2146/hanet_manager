<?php

namespace App\Libraries;

use CodeIgniter\HTTP\RequestInterface;

class FormRenderer
{
    protected $data = [];
    protected $request;
    protected $validation;
    protected $formAttributes = [];
    protected $fields = [];
    protected $layout = null;

    public function __construct()
    {
        $this->request = service('request');
        $this->validation = service('validation');
    }

    // --- Thiết lập Form ---
    public function form($attributes = [])
    {
        $this->formAttributes = $attributes;
        return $this;
    }

    // --- Các loại trường Form ---
    public function input($name, $type = 'text', $attributes = [])
    {
        $value = old($name) ?? $this->request->getPost($name) ?? ($attributes['value'] ?? '');
        $this->fields[$name] = form_input(array_merge(['name' => $name, 'type' => $type, 'value' => $value], $attributes));
        return $this;
    }

    public function textarea($name, $attributes = [])
    {
        $value = old($name) ?? $this->request->getPost($name) ?? ($attributes['value'] ?? '');
        $this->fields[$name] = form_textarea(array_merge(['name' => $name, 'value' => $value], $attributes));
        return $this;
    }

    public function select($name, $options = [], $selected = null, $attributes = [])
    {
        $this->fields[$name] = form_dropdown(
            $name,
            $options,
            old($name) ?? $this->request->getPost($name) ?? $selected,
            $attributes
        );
        return $this;
    }

    public function checkbox($name, $value = '1', $checked = false, $attributes = [])
    {
        $this->fields[$name] = form_checkbox(
            $name,
            $value,
            old($name) ? true : $checked,
            $attributes
        );
        return $this;
    }

    public function radio($name, $value = '1', $checked = false, $attributes = [])
    {
        $this->fields[$name] = form_radio(
            $name,
            $value,
            old($name) ? true : $checked,
            $attributes
        );
        return $this;
    }

    public function file($name, $attributes = [])
    {
        $this->fields[$name] = form_upload(array_merge(['name' => $name], $attributes));
        return $this;
    }

    public function hidden($name, $value = '', $attributes = [])
    {
        $this->fields[$name] = form_hidden($name, $value, $attributes);
        return $this;
    }

    public function submit($value = 'Submit', $attributes = [])
    {
        $this->fields['submit'] = form_submit('', $value, $attributes);
        return $this;
    }

    public function button($value = 'Button', $attributes = [])
    {
        $this->fields['button'] = form_button('', $value, $attributes);
        return $this;
    }

    // --- Validation ---
    public function validate(array $rules)
    {
        $this->validation->setRules($rules);
        if (!$this->validation->withRequest($this->request)->run()) {
            $this->data['errors'] = $this->validation->getErrors();
        }
        return $this;
    }

    // --- Xử lý File Upload ---
    public function handleUpload($fieldName, $basePath = 'writable/uploads/', $allowedTypes = ['image' => ['jpg', 'png', 'gif', 'webp', 'jpeg'], 'document' => ['pdf', 'doc', 'docx', 'xlsx', 'txt', 'pptx']], $maxSize = 2048)
    {
        // Lấy ngày hiện tại để tạo thư mục theo từng cấp
        $year = date('Y'); // 2025
        $month = date('m'); // 02
        $day = date('d'); // 28

        // Lấy danh sách file upload
        $files = $this->request->getFileMultiple($fieldName);

        foreach ($files as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $extension = strtolower($file->getExtension()); // Lấy đuôi file (vd: jpg, pdf)
                $fileType = $this->determineFileType($extension, $allowedTypes); // Xác định loại file

                if (!$fileType) {
                    $this->data['errors'][$fieldName] = "File format ($extension) is not allowed.";
                    continue; // Bỏ qua file không hợp lệ
                }

                // Tạo đường dẫn thư mục
                $fullPath = ROOTPATH . $basePath . $fileType . '/' . $year . '/' . $month . '/' . $day . '/';

                // Tạo thư mục nếu chưa tồn tại
                if (!is_dir($fullPath)) {
                    mkdir($fullPath, 0777, true);
                }

                // Lưu file vào thư mục tương ứng
                if ($file->move($fullPath)) {
                    $this->data['uploaded'][$fieldName][] = $basePath . $fileType . '/' . $year . '/' . $month . '/' . $day . '/' . $file->getName();
                } else {
                    $this->data['errors'][$fieldName] = $file->getErrorString();
                }
            }
        }

        return $this;
    }

    /**
     * Xác định loại file dựa vào đuôi file
     */
    protected function determineFileType($extension, $allowedTypes)
    {
        foreach ($allowedTypes as $type => $extensions) {
            if (in_array($extension, $extensions)) {
                return $type; // Trả về loại file (image, document)
            }
        }
        return null; // Không hợp lệ
    }


    // --- Gán dữ liệu bổ sung ---
    public function with($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
        return $this;
    }

    // --- Thiết lập Layout ---
    public function setLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    // --- Render ---
    public function render($view, $options = [], $return = false)
    {
        $this->data['form_open'] = form_open(
            $this->formAttributes['action'] ?? '',
            array_merge(['enctype' => 'multipart/form-data'], $this->formAttributes)
        );
        $this->data['form_close'] = form_close();
        $this->data['fields'] = $this->fields;
        $this->data['request'] = $this->request;

        if ($this->layout) {
            $this->data['content'] = view($view, $this->data, $options);
            $output = view($this->layout, $this->data, $options);
        } else {
            $output = view($view, $this->data, $options);
        }

        if ($return) {
            return $output;
        } else {
            echo $output;
        }
    }

    // --- Render Partial ---
    public function partial($partial, $data = [], $return = false)
    {
        $partialData = array_merge($this->data, $data);
        if ($return) {
            return view($partial, $partialData);
        } else {
            echo view($partial, $partialData);
        }
    }

    // --- Xóa dữ liệu ---
    public function clear()
    {
        $this->data = [];
        $this->fields = [];
        $this->formAttributes = [];
        $this->layout = null;
        return $this;
    }

    // --- Lấy dữ liệu hiện tại ---
    public function getData()
    {
        return $this->data;
    }
}
