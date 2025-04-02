<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Breadcrumb -->
<?= $breadcrumb ?>

<!-- Alerts -->
<?= view('App\Modules\\' . $module_name . '\Views\components\_alerts') ?>

<!-- Form Detail Component -->
<?= view('App\Modules\\' . $module_name . '\Views\components\_form_detail',) ?>

<?= $this->endSection() ?>
