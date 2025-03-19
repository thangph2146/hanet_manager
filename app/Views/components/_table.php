<?php
/**
 * @param string $caption Tiêu đề của bảng
 * @param array $headers Mảng các tiêu đề cột
 * @param array $data Mảng dữ liệu
 * @param array $columns Mảng các cột cần hiển thị và cách hiển thị
 * @param array $options Các tùy chọn bổ sung cho bảng
 * @param bool $show_footer Hiển thị footer của bảng hay không
 * @param string $card_title Tiêu đề của card (nếu có)
 * @param array $pagination Thông tin phân trang (current_page, per_page, total_items)
 */
?>

<div class="card shadow-sm border-0 rounded-lg overflow-hidden">
    <?php if (isset($card_title)): ?>
    <div class="card-header border-bottom bg-white">
        <div class="d-flex align-items-center justify-content-between py-2">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 fw-bold text-uppercase text-primary"><i class="fas fa-table me-2"></i><?= $card_title ?></h5>
            </div>
            
            <div class="d-flex gap-2">
                <?php if (isset($card_tools) && count($card_tools) > 0): ?>
                    <?php foreach ($card_tools as $tool): ?>
                        <a href="<?= $tool['url'] ?? '#' ?>" class="<?= $tool['class'] ?? 'btn btn-primary btn-sm' ?>">
                            <?php if (isset($tool['icon'])): ?>
                                <i class="<?= $tool['icon'] ?> me-1"></i>
                            <?php endif; ?>
                            <?= $tool['title'] ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <?php if (isset($options['add_button'])): ?>
                <a href="<?= $options['add_button']['url'] ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> <?= $options['add_button']['label'] ?? 'Thêm mới' ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="card-body p-0">
        <?php if (isset($bulk_actions) && count($bulk_actions) > 0): ?>
        <div class="bg-light p-3 border-bottom">
            <div class="d-flex gap-2">
                <?php foreach ($bulk_actions as $action): ?>
                    <button id="<?= $action['id'] ?? '' ?>" class="<?= $action['class'] ?? 'btn btn-sm btn-danger' ?>">
                        <?php if (isset($action['icon'])): ?>
                            <i class="<?= $action['icon'] ?> me-1"></i>
                        <?php endif; ?>
                        <?= $action['title'] ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="table-responsive" style="padding: 10px;">
            <?php
            $table = new \CodeIgniter\View\Table();

            // Thiết lập template mặc định
            $template = [
                'table_open' => '<table id="example2_wrapper" class="table table-hover table-striped table-bordered mb-0 w-100">',
                'thead_open' => '<thead>',
                'thead_close' => '</thead>',
                'heading_row_start' => '<tr>',
                'heading_row_end' => '</tr>',
                'heading_cell_start' => '<th>',
                'heading_cell_end' => '</th>',
                'tbody_open' => '<tbody>',
                'tbody_close' => '</tbody>',
                'row_start' => '<tr>',
                'row_end' => '</tr>',
                'cell_start' => '<td>',
                'cell_end' => '</td>'
            ];

            // Ghi đè template nếu có
            if (isset($options['template'])) {
                $template = array_merge($template, $options['template']);
            }

            $table->setTemplate($template);

            // Thiết lập caption nếu có
            if (isset($caption)) {
                $table->setCaption($caption);
            }

            // Thiết lập headers
            if (isset($headers)) {
                // Thêm checkbox vào header nếu cần
                if (isset($columns[0]['type']) && $columns[0]['type'] === 'checkbox') {
                    $headers[0] = '<input type="checkbox" class="check-all" />';
                }
                $table->setHeading($headers);
            }

            // Xử lý dữ liệu
            if (isset($data) && count($data) > 0) {
                foreach ($data as $item) {
                    $row = [];
                    foreach ($columns as $column) {
                        if (isset($column['type'])) {
                            switch ($column['type']) {
                                case 'checkbox':
                                    $row[] = view_cell('\App\Libraries\MyButton::inputCheck', [
                                        'class' => $column['class'] ?? 'check-select-p',
                                        'name' => $column['name'] ?? 'id[]',
                                        'id' => $item->{$column['id_field']},
                                        'array' => $column['array'] ?? [],
                                        'label' => $column['label'] ?? ''
                                    ]);
                                    break;

                                case 'status':
                                    $row[] = ($item->{$column['field']} == 1) 
                                        ? '<span class="badge bg-success">Hoạt động</span>'
                                        : '<span class="badge bg-danger">Đã khóa</span>';
                                    break;

                                case 'actions':
                                    $actions = '<div class="d-flex gap-1 justify-content-center">';
                                    foreach ($column['buttons'] as $button) {
                                        // Xử lý icon - có thể là closure hoặc chuỗi
                                        $icon = '';
                                        if (isset($button['icon'])) {
                                            if (is_callable($button['icon'])) {
                                                $iconClass = $button['icon']($item);
                                                $icon = "<i class='{$iconClass}'></i>";
                                            } else {
                                                $icon = "<i class='{$button['icon']}'></i>";
                                            }
                                        }
                                        
                                        // Kiểm tra xem title có cần định dạng không
                                        $title = '';
                                        if (isset($button['title'])) {
                                            if (is_callable($button['title'])) {
                                                $title = $button['title']($item);
                                                if (isset($button['title_field']) && strpos($title, '%s') !== false) {
                                                    $title = sprintf($title, $item->{$button['title_field']});
                                                }
                                            } else if (isset($button['title_field'])) {
                                                $title = sprintf($button['title'], $item->{$button['title_field']});
                                            } else {
                                                $title = $button['title'];
                                            }
                                        }
                                        
                                        // Xử lý class - có thể là closure hoặc chuỗi
                                        $class = 'btn btn-sm btn-outline-primary';
                                        if (isset($button['class'])) {
                                            if (is_callable($button['class'])) {
                                                $class = $button['class']($item);
                                            } else {
                                                $class = $button['class'];
                                            }
                                        }
                                        
                                        // Đảm bảo URL được tạo đúng
                                        if (isset($button['url_prefix'])) {
                                            $url = $button['url_prefix'] . $item->{$button['id_field']};
                                        } else if (isset($button['url'])) {
                                            $url = $button['url'] . '/' . $item->{$button['id_field']};
                                        } else {
                                            $url = '#';
                                        }
                                        
                                        // Thêm các thuộc tính JavaScript nếu có
                                        $js_attrs = '';
                                        if (isset($button['js'])) {
                                            $js_attrs = ' ' . $button['js'];
                                        }
                                        
                                        // Chỉ hiển thị icon, không hiển thị label
                                        $actions .= "<a href='{$url}' class='{$class}' title='{$title}'{$js_attrs}>{$icon}</a>";
                                    }
                                    $actions .= '</div>';
                                    $row[] = $actions;
                                    break;

                                case 'currency':
                                    $row[] = number_format($item->{$column['field']}, 0, ',', '.') . ' ₫';
                                    break;

                                case 'date':
                                    $row[] = date($column['format'] ?? 'd/m/Y', strtotime($item->{$column['field']}));
                                    break;

                                case 'custom':
                                    if (isset($column['render']) && is_callable($column['render'])) {
                                        $index = array_search($column, $columns);
                                        $row[] = $column['render']($item, $index);
                                    } else {
                                        $row[] = $item->{$column['field']} ?? '';
                                    }
                                    break;

                                default:
                                    $row[] = $item->{$column['field']};
                                    break;
                            }
                        } else {
                            $row[] = $item->{$column['field']};
                        }
                    }
                    $table->addRow($row);
                }
            }

            // Thiết lập footer nếu cần
            if (isset($show_footer) && $show_footer && isset($headers)) {
                $table->setFooting($headers);
            }

            // Thiết lập nội dung cho ô trống
            $table->setEmpty('&nbsp;');

            // Tạo và hiển thị bảng
            echo $table->generate();
            ?>
        </div>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableId =  'example2_wrapper';
    const table = $(`#${tableId}`).DataTable({
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, 'Tất cả']
        ],
        buttons: [
            {
                extend: 'copy',
                text: '<i class="fas fa-copy me-1"></i> Copy',
                className: 'btn btn-sm btn-outline-secondary'
            },
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel me-1"></i> Excel',
                className: 'btn btn-sm btn-outline-secondary',
                exportOptions: {
                    columns: ':not(:last-child)' // Không xuất cột cuối (thường là cột action)
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf me-1"></i> PDF',
                className: 'btn btn-sm btn-outline-secondary',
                exportOptions: {
                    columns: ':not(:last-child)' // Không xuất cột cuối
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print me-1"></i> Print',
                className: 'btn btn-sm btn-outline-secondary',
                exportOptions: {
                    columns: ':not(:last-child)' // Không xuất cột cuối
                }
            }
        ],
        dom: '<"row mb-3"<"col-md-6"B><"col-md-6"f>>rt<"row mt-3"<"col-md-6"l><"col-md-6"p>>',
        language: {
            search: "Tìm kiếm:",
            lengthMenu: "Hiển thị _MENU_ bản ghi",
            info: "Hiển thị _START_ đến _END_ của _TOTAL_ bản ghi",
            infoEmpty: "Hiển thị 0 đến 0 của 0 bản ghi",
            infoFiltered: "(lọc từ _MAX_ bản ghi)",
            zeroRecords: "Không tìm thấy bản ghi nào",
            emptyTable: "Không có dữ liệu",
            paginate: {
                first: "Đầu",
                previous: "Trước",
                next: "Tiếp",
                last: "Cuối"
            }
        },
        responsive: true,
        ordering: true,
        processing: true,
        autoWidth: false,
        pageLength: <?= $pagination['per_page'] ?? 10 ?>,
        order: [[1, 'asc']], // Sắp xếp mặc định theo cột thứ 2 (bỏ qua cột checkbox)
        columnDefs: [
            {
                targets: 0,
                orderable: false,
                className: 'text-center'
            },
            {
                targets: -1,
                orderable: false,
                className: 'text-center'
            }
        ]
    });

    // Xử lý check all
    $('.check-all').on('change', function() {
        const isChecked = $(this).prop('checked');
        $('.check-select-p').prop('checked', isChecked);
    });

    // Cập nhật check all khi check từng item
    $('.check-select-p').on('change', function() {
        const totalCheckboxes = $('.check-select-p').length;
        const checkedCheckboxes = $('.check-select-p:checked').length;
        $('.check-all').prop('checked', totalCheckboxes === checkedCheckboxes);
    });
    
    // Khởi tạo tooltips bằng jQuery
    $('[title]').tooltip({
        placement: 'top',
        trigger: 'hover'
    });
});
</script>

<style>
.table {
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    white-space: nowrap;
    border-top: 0;
    padding: 0.75rem 1rem;
    position: relative;
}

.table th:after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 1px;
    background-color: #dee2e6;
}

.table td {
    font-size: 0.9rem;
    vertical-align: middle;
    padding: 0.85rem 1rem;
    border-color: #edf2f9;
}

/* Zebra striping with softer colors */
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0,0,0,.02);
}

.table-hover tbody tr:hover {
    background-color: rgba(90, 141, 238, 0.05);
    transition: background-color 0.2s ease;
}

.dataTables_wrapper .btn-group {
    gap: 0.25rem;
}

.dataTables_wrapper .dataTables_filter {
    margin-bottom: 0.5rem;
}

.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #dce7f1;
    border-radius: 0.375rem;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    box-shadow: inset 0 1px 2px rgba(0,0,0,.075);
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.dataTables_wrapper .dataTables_length select {
    min-width: 80px;
    border: 1px solid #dce7f1;
    border-radius: 0.375rem;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

/* Styling cho badge (status) */
.badge {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.35em 0.65em;
    border-radius: 50rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge.bg-success {
    background-color: #28a745 !important;
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.2);
}

.badge.bg-danger {
    background-color: #dc3545 !important;
    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);
}

/* Styling cho checkbox */
.check-all,
.check-select-p {
    width: 1.2rem;
    height: 1.2rem;
    cursor: pointer;
    border-radius: 4px;
    border: 1px solid #dce7f1;
    position: relative;
    appearance: none;
    -webkit-appearance: none;
    transition: all 0.3s;
    vertical-align: middle;
}

.check-all:checked,
.check-select-p:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.check-all:checked:after,
.check-select-p:checked:after {
    content: '✓';
    position: absolute;
    color: #fff;
    font-size: 12px;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.check-all:focus,
.check-select-p:focus {
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Thiết lập các style cho nút action */
.action-btn {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    margin: 0 3px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border: none;
    position: relative;
    overflow: hidden;
    border-radius: 50%;
}

.action-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 12px rgba(0,0,0,0.18);
}

.action-btn:active {
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(0,0,0,0.15);
}

.action-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,0.1);
    transform: scale(0);
    transition: 0.3s;
    border-radius: 50%;
}

.action-btn:hover::before {
    transform: scale(1.2);
}

.action-btn i {
    font-size: 14px;
    color: white;
    z-index: 2;
    transition: all 0.3s;
}

.action-btn:hover i {
    transform: scale(1.2);
}

.d-flex.gap-1 {
    gap: 0.75rem !important;
    justify-content: center;
}

/* Phân trang */
.pagination .page-link {
    color: #6c757d;
    border: 1px solid #dee2e6;
    margin: 0 2px;
    border-radius: 4px;
    transition: all 0.2s;
}

.pagination .page-link:hover {
    background-color: #f8f9fa;
    color: #0d6efd;
    border-color: #dee2e6;
}

.pagination .page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
}

.pagination .page-item.disabled .page-link {
    color: #dee2e6;
    pointer-events: none;
}

/* Card styling */
.card.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    transition: all 0.3s;
}

.card.shadow-sm:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.10) !important;
}

.text-primary {
    color: #0d6efd !important;
}

/* Buttons styling */
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 0.25rem;
    transition: all 0.2s;
}

.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
    box-shadow: 0 2px 6px rgba(13, 110, 253, 0.2);
}

.btn-primary:hover {
    background-color: #0b5ed7;
    border-color: #0a58ca;
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
    box-shadow: 0 2px 6px rgba(220, 53, 69, 0.2);
}

.btn-danger:hover {
    background-color: #bb2d3b;
    border-color: #b02a37;
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.btn-info {
    background-color: #0dcaf0;
    border-color: #0dcaf0;
    box-shadow: 0 2px 6px rgba(13, 202, 240, 0.2);
}

.btn-info:hover {
    background-color: #31d2f2;
    border-color: #25cff2;
    box-shadow: 0 4px 12px rgba(13, 202, 240, 0.3);
}

.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    box-shadow: 0 2px 6px rgba(255, 193, 7, 0.2);
}

.btn-warning:hover {
    background-color: #ffca2c;
    border-color: #ffc720;
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
}

/* Responsive styling */
@media (max-width: 768px) {
    .dataTables_wrapper .dataTables_filter {
        margin-top: 0.5rem;
        width: 100%;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        width: 100%;
    }
    
    .btn-group {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .btn-group .btn {
        flex: 1;
    }

    .table td,
    .table th {
        white-space: nowrap;
    }
    
    .card-header {
        flex-direction: column;
    }
    
    .card-header .d-flex {
        margin-bottom: 0.5rem;
    }
}
</style>


