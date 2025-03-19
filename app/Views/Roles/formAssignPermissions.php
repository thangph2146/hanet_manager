<?php
// Chuẩn bị dữ liệu cho component _table
$headers = ['<div class="form-check"><input type="checkbox" class="form-check-input" id="select-all" /><label class="form-check-label" for="select-all"></label></div>', 'Tên Code', 'Tên Hiển thị', 'Mô tả ngắn', 'Đã Chọn'];

$columns = [
    [
        'type' => 'checkbox',
        'class' => 'check-select-p',
        'name' => 'permission_id[]',
        'id_field' => 'p_id',
        'array' => $permissionsOfRole
    ],
    [
        'field' => 'p_name'
    ],
    [
        'field' => 'p_display_name'
    ],
    [
        'field' => 'p_description'
    ],
    [
        'type' => 'custom',
        'field' => 'p_id',
        'render' => function($item) use ($permissionsOfRole) {
            return in_array($item->p_id, $permissionsOfRole) 
                ? view_cell('\App\Libraries\MyButton::iconChecked', ['label' => 'checked']) 
                : '';
        }
    ]
];

$options = [
    'template' => [
        'table_open' => '<table id="example2" class="table table-bordered table-striped">',
        'heading_cell_start' => '<th class="all text-center">',
    ]
];

$pagination = [
    'per_page' => count($allPermissions)
];

echo view('components/_table', [
    'caption' => 'Danh Sách Permissions',
    'headers' => $headers,
    'data' => $allPermissions,
    'columns' => $columns,
    'options' => $options,
    'show_footer' => false,
    'card_title' => 'Danh Sách Permissions',
    'pagination' => $pagination
]);
?>

