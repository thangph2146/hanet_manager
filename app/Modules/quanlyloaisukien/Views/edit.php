<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php 
$masterScriptClass = "\App\Modules\\" . $module_name . '\Libraries\MasterScript';
$masterScript = new $masterScriptClass($module_name);
?>
<?= $masterScript->pageCss('form') ?>
<?= $masterScript->pageSectionCss('form') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>$title<?= $this->endSection() ?>

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

<?= $this->section("content") ?>
<div class="card shadow-sm">
	<div class="card-body">
		<?= form_open($action, ['class' => 'row g-3 needs-validation', 'novalidate' => true, 'id' => 'form-' . $module_name]) ?>
			<?= view('App\Modules\\' . $module_name . '\Views\components\_form', [
                'module_name' => $module_name,
                'data' => $data ?? null,
                'validation' => $validation ?? null
            ]) ?>
		<?= form_close() ?>
	</div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $masterScript->pageJs('form') ?>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const form = document.getElementById('form-<?= $module_name ?>');
		
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
<?= $this->endSection() ?> 