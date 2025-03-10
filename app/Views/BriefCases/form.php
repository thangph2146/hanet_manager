<div class="col-md-12 mb-md-4">
	<label for="bc_name" class="form-label">Tên Hồ sơ:</label>
	<input type="text" class="form-control" id="bc_name" name="bc_name" value="<?= old('bc_name', esc($data->bc_name)) ?>" placeholder="Tên hồ sơ">
</div>
<div class="clearfix"></div>
<div class="col-12 mb-md-4">
	<div class="form-check">
		<input type="hidden" class="form-check-input" name="bc_status" value="0">

		<input type="checkbox" class="form-check-input" id="bc_status" name="bc_status" value="1"
			   <?php if (old('bc_status', esc($data->bc_status))): ?>checked<?php endif; ?>>
		<label class="form-check-label" for="gridCheck">
			Active
		</label>
	</div>
</div>
