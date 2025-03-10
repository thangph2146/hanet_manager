<div class="col-md-6 mb-md-4">
	<label for="p_name" class="form-label">Tên Role</label>
	<input type="text" class="form-control" id="r_name" name="r_name" value="<?= old('r_name', esc($data->r_name)) ?>" placeholder="Controller_method">
</div>
<div class="col-md-12 mb-md-4">
	<label for="p_description" class="form-label">Mô tả ngắn</label>
	<input type="text" class="form-control" id="r_description" name="r_description" value="<?= old('r_description', esc($data->r_description)) ?>" placeholder="Ghi chú cho chức năng">
</div>
<div class="clearfix"></div>
<div class="col-12 mb-md-4">
	<div class="form-check">
		<input type="hidden" class="form-check-input" name="r_status" value="0">

		<input type="checkbox" class="form-check-input" id="r_status" name="r_status" value="1"
			   <?php if (old('r_status', esc($data->r_status))): ?>checked<?php endif; ?>>
		<label class="form-check-label" for="gridCheck">
			Active
		</label>
	</div>
</div>
