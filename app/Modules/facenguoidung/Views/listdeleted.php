<?= $this->extend('layouts/default') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>

<?= $this->section('head') ?>
<?= facenguoidung_css('table') ?>
<?= facenguoidung_section_css('modal') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="panel">
    <div class="panel-container">
        <div class="panel-content py-3">
            <h1 class="mb-0 fs-3 fw-500">
                <i class="subheader-icon fal fa-trash-alt mr-1"></i>
                THÙNG RÁC - KHUÔN MẶT NGƯỜI DÙNG
            </h1>
        </div>
    </div>
</div>

<div class="panel mb-g">
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="<?= site_url() ?>">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('facenguoidung') ?>">Khuôn mặt người dùng</a></li>
        <li class="breadcrumb-item active" aria-current="page">Thùng rác</li>
    </ol>
</div>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-export" class="panel">
            <div class="panel-hdr">
                <h2>DANH SÁCH CÁC KHUÔN MẶT ĐÃ XÓA</h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip"
                            data-offset="0,10" data-original-title="Thu gọn">
                    </button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <!-- Các nút hành động cho các mục được chọn -->
                            <div class="btn-group mb-2" role="group" aria-label="Bulk Actions">
                                <button type="button" id="restore-selected" class="btn btn-outline-success waves-effect waves-themed" disabled>
                                    <i class="fal fa-undo-alt mr-1"></i> Khôi phục mục đã chọn
                                </button>
                                <button type="button" id="permanent-delete-selected" class="btn btn-outline-danger waves-effect waves-themed" disabled>
                                    <i class="fal fa-times-circle mr-1"></i> Xóa vĩnh viễn mục đã chọn
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <!-- Ô tìm kiếm -->
                            <div class="input-group mb-2 float-right">
                                <input type="text" id="search-box" class="form-control" placeholder="Tìm kiếm...">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-default waves-effect waves-themed" type="button" id="search-button">
                                        <i class="fal fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table id="dt-basic" class="table table-bordered table-hover table-striped w-100">
                        <thead class="thead-dark">
                            <tr>
                                <th width="2%">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="select-all">
                                        <label class="custom-control-label" for="select-all"></label>
                                    </div>
                                </th>
                                <th width="5%">ID</th>
                                <th width="18%">Người dùng</th>
                                <th width="20%">Thông tin</th>
                                <th width="20%">Hình ảnh</th>
                                <th width="15%">Thời gian xóa</th>
                                <th width="20%">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input checkbox-item" id="select-<?= $item->id ?>" value="<?= $item->id ?>">
                                        <label class="custom-control-label" for="select-<?= $item->id ?>"></label>
                                    </div>
                                </td>
                                <td><?= $item->id ?></td>
                                <td><?= $item->ten_nguoi_dung ?? 'Không xác định' ?></td>
                                <td>
                                    <div>ID người dùng: <?= $item->id_nguoi_dung ?? 'N/A' ?></div>
                                    <div>ID khuôn mặt: <?= $item->id_khuon_mat ?? 'N/A' ?></div>
                                </td>
                                <td>
                                    <?php if (!empty($item->duong_dan_anh) && file_exists(FCPATH . $item->duong_dan_anh)): ?>
                                        <img src="<?= base_url($item->duong_dan_anh) ?>" alt="Khuôn mặt" class="img-fluid" style="max-height: 100px;">
                                    <?php else: ?>
                                        <span class="text-muted">Không có hình ảnh</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $item->deleted_at ? date('d/m/Y H:i', strtotime($item->deleted_at)) : 'N/A' ?></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-success waves-effect waves-themed restore-btn" data-id="<?= $item->id ?>" data-toggle="modal" data-target="#modal-restore">
                                            <i class="fal fa-undo-alt mr-1"></i> Khôi phục
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger waves-effect waves-themed permanent-delete-btn" data-id="<?= $item->id ?>" data-toggle="modal" data-target="#modal-permanent-delete">
                                            <i class="fal fa-times-circle mr-1"></i> Xóa vĩnh viễn
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- end panel-content -->
            </div>
            <!-- end panel-container -->
        </div>
        <!-- end panel -->
    </div>
</div>

<!-- Modal xác nhận xóa vĩnh viễn một mục -->
<div class="modal fade" id="modal-permanent-delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa vĩnh viễn</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa vĩnh viễn mục này? Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-footer">
                <form method="POST" id="permanent-delete-form" action="<?= site_url('facenguoidung/permanentDelete') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="deleteItemId" id="deleteItemId" value="">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xóa vĩnh viễn</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận khôi phục một mục -->
<div class="modal fade" id="modal-restore" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận khôi phục</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn khôi phục mục này?</p>
            </div>
            <div class="modal-footer">
                <form method="POST" id="restore-form" action="<?= site_url('facenguoidung/restore') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="restoreItemId" id="restoreItemId" value="">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">Khôi phục</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa vĩnh viễn nhiều mục đã chọn -->
<div class="modal fade" id="modal-permanent-delete-selected" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa vĩnh viễn nhiều mục</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa vĩnh viễn các mục đã chọn? Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-footer">
                <form method="POST" id="permanent-delete-selected-form" action="<?= site_url('facenguoidung/permanentDeleteMultiple') ?>">
                    <?= csrf_field() ?>
                    <div id="selected-ids-container"></div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xóa vĩnh viễn</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận khôi phục nhiều mục đã chọn -->
<div class="modal fade" id="modal-restore-selected" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận khôi phục nhiều mục</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn khôi phục các mục đã chọn?</p>
            </div>
            <div class="modal-footer">
                <form method="POST" id="restore-selected-form" action="<?= site_url('facenguoidung/restoreMultiple') ?>">
                    <?= csrf_field() ?>
                    <div id="restore-selected-ids-container"></div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">Khôi phục</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= facenguoidung_section_js('modal') ?>
<?= facenguoidung_js('table') ?>
<script>
    $(document).ready(function() {
        // Khởi tạo DataTable
        var table = $('#dt-basic').DataTable({
            responsive: true,
            lengthChange: false,
            dom:
                "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    titleAttr: 'Generate Excel',
                    className: 'btn-outline-success btn-sm mr-1'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    titleAttr: 'Generate PDF',
                    className: 'btn-outline-danger btn-sm mr-1'
                },
                {
                    extend: 'print',
                    text: 'In',
                    titleAttr: 'Print Table',
                    className: 'btn-outline-primary btn-sm'
                }
            ]
        });

        // Xử lý "Chọn tất cả"
        $('#select-all').change(function() {
            $('.checkbox-item').prop('checked', this.checked);
            updateBulkActionButtons();
        });

        // Cập nhật trạng thái nút hành động hàng loạt khi checkbox thay đổi
        $(document).on('change', '.checkbox-item', function() {
            updateBulkActionButtons();
            
            // Nếu một checkbox bị bỏ chọn, bỏ chọn "Chọn tất cả"
            if (!this.checked) {
                $('#select-all').prop('checked', false);
            } else {
                // Kiểm tra nếu tất cả đã được chọn
                if ($('.checkbox-item:checked').length === $('.checkbox-item').length) {
                    $('#select-all').prop('checked', true);
                }
            }
        });

        // Cập nhật trạng thái nút hành động hàng loạt
        function updateBulkActionButtons() {
            var hasChecked = $('.checkbox-item:checked').length > 0;
            $('#restore-selected, #permanent-delete-selected').prop('disabled', !hasChecked);
        }

        // Xử lý nút "Xóa vĩnh viễn" cho từng mục
        $('.permanent-delete-btn').click(function() {
            var id = $(this).data('id');
            $('#deleteItemId').val(id);
        });

        // Xử lý nút "Khôi phục" cho từng mục
        $('.restore-btn').click(function() {
            var id = $(this).data('id');
            $('#restoreItemId').val(id);
        });

        // Xử lý nút "Xóa vĩnh viễn mục đã chọn"
        $('#permanent-delete-selected').click(function() {
            var selectedIds = [];
            $('.checkbox-item:checked').each(function() {
                selectedIds.push($(this).val());
            });
            
            if (selectedIds.length > 0) {
                // Xóa các input hidden cũ
                $('#selected-ids-container').empty();
                
                // Thêm input hidden mới cho mỗi ID
                selectedIds.forEach(function(id) {
                    $('#selected-ids-container').append('<input type="hidden" name="selected_ids[]" value="' + id + '">');
                });
                
                // Hiển thị modal xác nhận
                $('#modal-permanent-delete-selected').modal('show');
            }
        });

        // Xử lý nút "Khôi phục mục đã chọn"
        $('#restore-selected').click(function() {
            var selectedIds = [];
            $('.checkbox-item:checked').each(function() {
                selectedIds.push($(this).val());
            });
            
            if (selectedIds.length > 0) {
                // Xóa các input hidden cũ
                $('#restore-selected-ids-container').empty();
                
                // Thêm input hidden mới cho mỗi ID
                selectedIds.forEach(function(id) {
                    $('#restore-selected-ids-container').append('<input type="hidden" name="selected_ids[]" value="' + id + '">');
                });
                
                // Hiển thị modal xác nhận
                $('#modal-restore-selected').modal('show');
            }
        });

        // Xử lý tìm kiếm
        $('#search-button, #search-box').on('click keyup', function(e) {
            if (e.type === 'click' || e.keyCode === 13) {
                var searchTerm = $('#search-box').val();
                table.search(searchTerm).draw();
            }
        });

        // Refresh page khi đóng modal
        $('.modal').on('hidden.bs.modal', function() {
            // Không refresh trang để tránh mất dữ liệu người dùng đã chọn
        });
    });
</script>
<?= $this->endSection() ?>