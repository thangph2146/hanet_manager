<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?= $this->include('App\Modules\\' . $module_name . '\Views\components\_form_detail') ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
