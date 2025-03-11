<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>TẠO HỒ SƠ<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="breadcrumb-title pe-3">Tạo Hồ Sơ</div>
	<div class="ps-3">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a href="<?= site_url('users/dashboard') ?>"><i class="bx bx-home-alt"></i></a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Tạo Hồ Sơ</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
		<div class="btn-group">
			<button type="button" class="btn btn-primary">Chức năng</button>
			<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
			</button>
			<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
				<a class="dropdown-item" href="<?= site_url('#') ?>">Tạo Hồ Sơ</a>
			</div>
		</div>
	</div>
</div>
<!--end breadcrumb-->
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<?= form_open_multipart("BriefCases/create", ['class' => 'row g3']) ?>
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-2">
	<div class="col">
		<div class="card radius-10">
			<div class="card-body">
				<?= $this->include('BriefCases/form'); ?>
				<div class="col-12">
					<button type="submit" class="btn btn-primary">Tạo Hồ sơ</button>
				</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card radius-10">
			<div class="card-body">
				<button type="button" onclick="uploadFile(event)" aria-label="Browse, drag-and-drop, or paste files to upload">-</button>
				<input class="hidden" type="file" id="fancy-file-upload" onchange="fileList(event)" name="f_name[]" multiple>
				<!--<input id="fancy-file-upload" type="file" name="files" accept=".jpg, .png, image/jpeg, image/png" multiple>-->
				<table id="ff_fileupload_uploads" class="table table-striped table-bordered">
					<th class="ms-3" >Tên file (Chỉ cho phép upload File pdf/doc, size < 10 MB là hợp lệ!)</th>
					<th class="ms-3" colspan="1">Del</th>
					<tbody id="tbody">
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</form>
<script>
  function uploadFile(evt) {
    evt.preventDefault();
    $('#fancy-file-upload').click();
  }

  function fileList(e) {
    var files = $('#fancy-file-upload').prop("files");
    var names = $.map(files, function(val) { return val.name +" | "+ (val.size/1048576).toFixed(2) + " MB"; });
    for (n in names)    {
      $("#tbody").append("<tr id=preload_"+n+" title='"+names[n]+"'><td class='ms-2'>"+names[n]+"</td><td class='ms-2'><a  onclick=deleteFile("+n+")>" +
        "<i class='lni lni-trash'></i></a></td></tr>");
    }
  }
  function deleteFile(index)  {
    filelistall = $('#fancy-file-upload').prop("files");
    var fileBuffer=[];
    Array.prototype.push.apply( fileBuffer, filelistall );
    fileBuffer.splice(index, 1);
    const dT = new ClipboardEvent('').clipboardData || new DataTransfer();
    for (let file of fileBuffer) { dT.items.add(file); }
    filelistall = $('#fancy-file-upload').prop("files",dT.files);
    $("#preload_"+index).remove()
  }
</script>
<?= $this->endSection() ?>
