<div class="col-md-6 mb-md-4">
	<label for="p_name" class="form-label">Tên chức năng</label>
	<input type="text" class="form-control" id="p_name" name="p_name" value="<?= old('p_name', esc($data->p_name)) ?>" placeholder="Controller_method">
</div>
<div class="col-md-6 mb-md-4">
	<label for="p_display_name" class="form-label">Tên hiển thị</label>
	<input type="text" class="form-control" id="p_display_name" name="p_display_name" value="<?= old('p_display_name', esc($data->p_display_name)) ?>" placeholder="Đặt tên cho chức năng">
</div>
<div class="col-md-6 mb-md-4">
	<label for="p_description" class="form-label">Mô tả ngắn</label>
	<input type="text" class="form-control" id="p_description" name="p_description" value="<?= old('p_description', esc($data->p_description)) ?>" placeholder="Ghi chú cho chức năng">
</div>
<div class="clearfix"></div>
<div class="col-12 mb-md-4">
	<div class="form-check">
		<input type="hidden" class="form-check-input" name="p_status" value="0">

		<input type="checkbox" class="form-check-input" id="p_status" name="p_status" value="1"
			   <?php if (old('p_status', esc($data->p_status))): ?>checked<?php endif; ?>>
		<label class="form-check-label" for="gridCheck">
			Active
		</label>
	</div>
</div>
