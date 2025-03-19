<?php
/**
 * View cập nhật phòng khoa
 */
?>

<?= $this->extend('layouts/default'); ?>

<?= $this->section('title') ?>CẬP NHẬT PHÒNG KHOA<?= $this->endSection() ?>

<?= $this->section('content'); ?>

<?= $this->include('App\Modules\phongkhoa\Views\form'); ?>

<?= $this->endSection(); ?> 