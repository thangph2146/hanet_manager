<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => $title_home,
	'dashboard_url' => site_url($module_name),
	'breadcrumbs' => [
		['title' => $title_home, 'url' => site_url($module_name)],
		['title' => $title, 'active' => true]
	],
]) ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>


<!-- Alerts -->
<?= view('App\Modules\\' . $module_name . '\Views\components\_alerts') ?>

<!-- Form Detail Component -->
<?= view('App\Modules\\' . $module_name . '\Views\components\_form_detail', [
    'data' => $data,
    'module_name' => $module_name,
    'title' => $title
]) ?>

<?= $this->endSection() ?>
