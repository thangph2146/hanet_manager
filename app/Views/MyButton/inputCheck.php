<?php
/**
 * 9/19/2022
 * AUTHOR:PDV-PC
 */
?>
<div class="form-check">
	<input class="<?= $class ?? '' ?>"
		type="checkbox"
		id="gridCheck"
		name="<?= $name ?? '' ?>"
		value="<?= $id ?? ''?>"
		<?= in_array($id, $array) ? 'checked' : '' ?>>
	<label class="form-check-label" for="gridCheck"><?= $label ?? '' ?></label>
</div>
