/**
 * Phương thức xử lý AJAX để lấy HTML cho chế độ xem sự kiện
 */
public function getEventsView()
{
    // Kiểm tra yêu cầu AJAX
    if (!$this->request->isAJAX()) {
        return $this->response->setStatusCode(403)->setBody('Forbidden');
    }
    
    // Lấy dữ liệu từ request
    $json = $this->request->getJSON(true);
    $events = $json['events'] ?? [];
    $layout = $json['layout'] ?? 'grid';
    $category = $json['category'] ?? null;
    $search = $json['search'] ?? null;
    
    // Render view component
    $html = view('App\Modules\sukien\Views\components\event_list', [
        'events' => $events,
        'layout' => $layout,
        'show_featured' => true,
        'category' => $category,
        'search' => $search
    ]);
    
    // Trả về HTML
    return $this->response->setBody($html);
} 