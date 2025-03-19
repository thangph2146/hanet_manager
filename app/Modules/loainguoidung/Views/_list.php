<?php
/**
 * Component hiển thị danh sách loại người dùng
 * 
 * @param array $loai_nguoi_dungs Mảng các loại người dùng
 * @param string $title Tiêu đề, mặc định là "Danh sách loại người dùng"
 */

// Giá trị mặc định nếu không được cung cấp
$loai_nguoi_dungs = $loai_nguoi_dungs ?? [];
$title = $title ?? 'Danh sách loại người dùng';
?>

<!-- Danh sách loại người dùng -->
<?= view('components/_table', [
    'caption' => $title,
    'headers' => [
        '<input type="checkbox" id="select-all" />', 
        'STT',
        'Tên loại',
        'Mô tả',
        'Trạng thái',
        'Thao tác'
    ],
    'data' => $loai_nguoi_dungs,
    'columns' => [
        [
            'type' => 'checkbox',
            'id_field' => 'loai_nguoi_dung_id',
            'name' => 'loai_nguoi_dung_id[]'
        ],
        [
            'type' => 'custom',
            'field' => 'stt',
            'render' => function($item) {
                return $item->loai_nguoi_dung_id;
            }
        ],
        [
            'field' => 'ten_loai'
        ],
        [
            'field' => 'mo_ta'
        ],
        [
            'type' => 'status',
            'field' => 'status',
            'active_label' => 'Hoạt động',
            'inactive_label' => 'Đã khóa'
        ],
        [
            'type' => 'actions',
            'buttons' => [
                [
                    'url_prefix' => site_url('loainguoidung/edit/'),
                    'id_field' => 'loai_nguoi_dung_id',
                    'title_field' => 'ten_loai',
                    'icon' => 'fas fa-edit',
                    'class' => 'btn btn-sm btn-info rounded-circle action-btn'
                ],
                [
                    'url_prefix' => '#',
                    'id_field' => 'loai_nguoi_dung_id',
                    'title_field' => 'ten_loai',
                    'icon' => 'fas fa-trash',
                    'class' => 'btn btn-sm btn-danger rounded-circle action-btn',
                    'js' => 'data-id="' . '{{$item->loai_nguoi_dung_id}}' . '"'
                ],
                [
                    'url_prefix' => '#',
                    'id_field' => 'loai_nguoi_dung_id',
                    'title_field' => 'ten_loai',
                    'icon' => function($item) {
                        return $item->status == 1 ? 'fas fa-lock' : 'fas fa-lock-open';
                    },
                    'class' => function($item) {
                        return $item->status == 1 ? 'btn btn-sm btn-warning rounded-circle action-btn' : 'btn btn-sm btn-success rounded-circle action-btn';
                    },
                    'js' => 'data-id="' . '{{$item->loai_nguoi_dung_id}}' . '"'
                ]
            ]
        ]
    ],
    'options' => [
        'table_id' => setting('App.table_id') ?? 'example1',
        'template' => [
            'table_open' => '<table id="' . (setting('App.table_id') ?? 'example1') . '" class="table table-hover table-striped table-bordered mb-0 w-100">'
        ]
    ],
    'card_title' => $title,
    'card_tools' => [
        [
            'url' => site_url('loainguoidung/new'),
            'title' => 'Thêm mới',
            'icon' => 'fas fa-plus',
            'class' => 'btn btn-primary btn-sm'
        ],
        [
            'url' => site_url('loainguoidung/deleted'),
            'title' => 'Thùng rác',
            'icon' => 'fas fa-trash',
            'class' => 'btn btn-danger btn-sm ml-1'
        ]
    ],
    'bulk_actions' => [
        [
            'title' => 'Xóa mục đã chọn',
            'icon' => 'fas fa-trash',
            'class' => 'btn btn-danger btn-sm btn-delete-multiple',
            'id' => 'btn-delete-multiple'
        ]
    ],
    'pagination' => [
        'per_page' => 10
    ]
]) ?>

<!-- Đưa scripts vào component này -->
<?= $this->include('App\Modules\loainguoidung\Views\_scripts') ?> 