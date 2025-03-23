<?php $this->extend('layouts/default') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>

<?php $this->section('styles') ?>
<?= nganh_css('form') ?>
<?= nganh_section_css('modal') ?>
<?php $this->endSection() ?>

<?php $this->section('title') ?>CẬP NHẬT KHUÔN MẶT NGƯỜI DÙNG<?php $this->endSection() ?>

<?php $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Cập nhật khuôn mặt người dùng',
    'dashboard_url' => site_url('facenguoidung'),
    'breadcrumbs' => [
        ['title' => 'Quản lý khuôn mặt người dùng', 'url' => site_url('facenguoidung')],
        ['title' => 'Cập nhật', 'active' => true]
    ],
    'actions' => [
        ['url' => site_url('/facenguoidung'), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
    ]
]) ?>
<?php $this->endSection() ?>

<?php $this->section('content') ?>
<?= $this->include('App\Modules\facenguoidung\Views\form', [
    'action' => site_url('facenguoidung/update/' . $item->face_nguoi_dung_id),
    'method' => 'POST',
    'item' => $item,
    'nguoidungs' => $nguoidungs ?? [],
    'is_new' => false
]) ?>
<?php $this->endSection() ?>  

<?php $this->section('scripts') ?>
<?= nganh_js('form') ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('form-nganh');
        
        // Validate form khi submit
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        });
    });
</script>
<?php $this->endSection() ?> 