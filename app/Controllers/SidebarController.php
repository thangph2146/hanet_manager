<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;

class SidebarController extends Controller
{
    use ResponseTrait;

    /**
     * Xử lý Ajax request để cập nhật trạng thái sidebar
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function updateState()
    {
        // Kiểm tra xem request có phải là Ajax không
        if (!$this->request->isAJAX()) {
            return $this->fail('Chỉ chấp nhận Ajax request', 400);
        }

        // Lấy dữ liệu từ request
        $requestData = $this->request->getJSON(true);
        
        if (empty($requestData) || !isset($requestData['action']) || $requestData['action'] !== 'update_sidebar_state') {
            return $this->fail('Dữ liệu không hợp lệ', 400);
        }

        // Lấy key và value từ request
        $stateKey = $requestData['state_key'] ?? '';
        $stateValue = $requestData['state_value'] ?? false;

        // Kiểm tra tính hợp lệ của key
        $validKeys = ['sidebar-mini', 'toggled', 'sidebar-hover'];
        if (!in_array($stateKey, $validKeys)) {
            return $this->fail('Key không hợp lệ', 400);
        }

        // Lưu trạng thái vào session
        session()->set('sidebar_' . $stateKey, $stateValue);

        // Trả về phản hồi thành công
        return $this->respond([
            'success' => true,
            'message' => 'Trạng thái sidebar đã được cập nhật',
            'state' => [
                'key' => $stateKey,
                'value' => $stateValue
            ]
        ]);
    }

    /**
     * Lấy trạng thái sidebar từ session
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function getState()
    {
        // Kiểm tra xem request có phải là Ajax không
        if (!$this->request->isAJAX()) {
            return $this->fail('Chỉ chấp nhận Ajax request', 400);
        }

        // Lấy tất cả trạng thái từ session
        $states = [
            'sidebar-mini' => session()->get('sidebar_sidebar-mini', false),
            'toggled' => session()->get('sidebar_toggled', false),
            'sidebar-hover' => session()->get('sidebar_sidebar-hover', false)
        ];

        // Trả về phản hồi thành công
        return $this->respond([
            'success' => true,
            'states' => $states
        ]);
    }
} 