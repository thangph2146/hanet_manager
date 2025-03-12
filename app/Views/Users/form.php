<div class="col-md-4 mb-md-4">
	<label for="u_LastName" class="form-label">Họ:</label>
	<input type="text" class="form-control" id="u_LastName" name="u_LastName" value="<?= old('u_LastName', esc($data->u_LastName)) ?>" placeholder="Nhập Họ">
</div>
<div class="col-md-4 mb-md-4">
	<label for="u_MiddleName" class="form-label">Chữ đệm:</label>
	<input type="text" class="form-control" id="u_MiddleName" name="u_MiddleName" value="<?= old('u_MiddleName', esc($data->u_MiddleName)) ?>" placeholder="Nhập tên đệm">
</div>
<div class="col-md-4 mb-md-4">
	<label for="u_FirstName" class="form-label">Tên:</label>
	<input type="text" class="form-control" id="u_FirstName" name="u_FirstName" value="<?= old('u_FirstName', esc($data->u_FirstName)) ?>" placeholder="Nhập tên">
</div>
<div class="col-md-12 mb-md-4">
	<label for="u_username" class="form-label">UserName:</label>
	<input type="text" class="form-control" id="u_username" name="u_username" value="<?= old('u_username', esc($data->u_username)) ?>" placeholder="username để đăng nhập">
</div>
<div class="col-md-12 mb-md-4">
	<label for="u_email" class="form-label">Email:</label>
	<input type="text" class="form-control" id="u_email" name="u_email" value="<?= old('u_email', esc($data->u_email)) ?>" placeholder="Nhập Email">
</div>
<div class="clearfix"></div>
<div class="col-md-6 mb-md-4">
	<label for="password" class="form-label">PassWord:</label>
	<input type="password" class="form-control" id="password" name="password" placeholder="Nhập Password">
</div>
<div class="col-md-6 mb-md-4">
	<label for="password_confirmation" class="form-label">Confirm PassWord:</label>
	<input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Xác nhận lại PassWord lại 1 lần nữa">
</div>
<?php if (isset($role)): ?>
<div class="col-12 mb-md-4">
<?= view_cell('\App\Libraries\MyButton::selectMulti', [
	'label' => 'Thuộc Nhóm',
	'type' => 'multiple-select',
	'name' => 'role_id',
	'select' => $role ?? [],
	'arrayKeys' => $arrayKeys ?? [],
]) ?>
</div>
<?php endif; ?>
<div class="col-12 mb-md-4">
	<div class="form-check">
		<input type="hidden" class="form-check-input" name="u_status" value="0">

		<input type="checkbox" class="form-check-input" id="u_status" name="u_status" value="1"
			   <?php if (old('u_status', esc($data->u_status))): ?>checked<?php endif; ?>>
		<label class="form-check-label" for="gridCheck">
			Active
		</label>
	</div>
</div>
