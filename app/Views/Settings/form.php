<div class="col-md-6 mb-md-4">
	<label for="key" class="form-label">Key</label>
	<input type="text" class="form-control" id="key" name="key" value="<?= old('key', $data->class ? esc($data->class . '.' .$data->key) : esc($data->key)) ?>" placeholder="Class.Key hoặc .Key" <?= $data->id ? 'readonly' : '' ?>>
</div>
<div class="col-md-6 mb-md-4">
	<label for="value" class="form-label">Value</label>
	<input type="text" class="form-control" id="value" name="value" value="<?= old('value', esc($data->value)) ?>" placeholder="nhập giá trị">
</div>

