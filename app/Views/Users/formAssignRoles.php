<div class="col-12 mb-md-4">
	<?= view_cell('\App\Libraries\MyButton::selectMulti', [
		'label' => 'Thuộc Nhóm',
		'type' => 'multiple-select',
		'name' => 'role_id[]',
		'select' => $select ?? [],
		'arraySelected' => $arraySelected ?? [],
	]) ?>
</div>

