<?php

/**
 * 9/21/2022
 * AUTHOR:PDV-PC
 */
?>
<label class="form-label"><?= $label ?? '' ?></label>
	<?php if ($type == 'multiple-select'): ?>
		<select class="multiple-select" name="<?= $name ?? '' ?>" data-placeholder="Choose anything" multiple="multiple">
	<?php endif;?>
	<?php if ($type == 'single-select'):?>
			<select class="single-select" name="<?= $name ?? '' ?>">
	<?php endif;?>
	<?php if (isset($select)): ?>
		<?php foreach ($select as $key => $value): ?>
			<option value="<?= $key ?>" <?= in_array($key, $arraySelected) ? 'selected' : '' ?>><?= $value ?></option>
		<?php endforeach; ?>
	<?php else:?>
			<option selected>Chưa có dữ liệu</option>
	<?php endif;?>
		</select>


