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


<div class="card">
    <?php if (isset($card_title)): ?>
    <div class="card-header border-bottom">
        <div class="d-flex align-items-center justify-content-between py-2">
            <h6 class="mb-0 fw-bold text-uppercase"><?= $card_title ?></h6>
            <?php if (isset($options['add_button'])): ?>
            <a href="<?= $options['add_button']['url'] ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> <?= $options['add_button']['label'] ?? 'Thêm mới' ?>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="card-body">
        <div class="table-responsive">
            <?php
            $table = new \CodeIgniter\View\Table();

            // Thiết lập template mặc định
            $template = [
                'table_open' => '<table id="' . ($options['table_id'] ?? 'example2') . '" class="table table-hover table-striped table-bordered mb-0 w-100">',
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
                                        $icon = isset($button['icon']) ? "<i class='{$button['icon']}'></i>" : '';
                                        $label = isset($button['label']) ? $button['label'] : '';
                                        $title = sprintf($button['title'], $item->{$button['title_field']});
                                        $class = $button['class'] ?? 'btn btn-sm btn-outline-primary';
                                        $url = site_url($button['url_prefix'] . $item->{$button['id_field']});
                                        
                                        $actions .= "<a href='{$url}' class='{$class}' title='{$title}'>{$icon} {$label}</a>";
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
    const tableId = '<?= $options['table_id'] ?? 'example2' ?>';
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
});
</script>

<style>
.table {
    margin-bottom: 0;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    white-space: nowrap;
}

.table td {
    font-size: 0.9rem;
    vertical-align: middle;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,.075);
}

.dataTables_wrapper .btn-group {
    gap: 0.25rem;
}

.dataTables_wrapper .dataTables_filter {
    margin-bottom: 0.5rem;
}

.dataTables_wrapper .dataTables_length select {
    min-width: 80px;
}

.badge {
    font-size: 0.85rem;
    padding: 0.35em 0.65em;
}

.check-all,
.check-select-p {
    width: 1.2rem;
    height: 1.2rem;
    cursor: pointer;
}

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
}
</style>


