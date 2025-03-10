<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>Edit HỒ SƠ<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="breadcrumb-title pe-3">Edit Hồ Sơ</div>
	<div class="ps-3">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a href="<?= site_url('Users/dashboard') ?>"><i class="bx bx-home-alt"></i></a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Edit Hồ Sơ</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
		<div class="btn-group">
			<button type="button" class="btn btn-primary">Chức năng</button>
			<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
			</button>
			<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
				<a class="dropdown-item" href="<?= site_url('BriefCases/new') ?>">Tạo Hồ Sơ</a>
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
				<?= form_open("BriefCases/update/" . $data->bc_id, ['class' => 'row g3']) ?>
					<?= $this->include('BriefCases/form'); ?>
					<div class="col-12">
						<button type="submit" class="btn btn-primary">Edit Hồ sơ</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="card radius-12">
		<div class="card-body">
			<button type="button" onclick="upFile(<?= $data->bc_id ?>)" id="<?= $data->bc_id ?>" aria-label="Browse, drag-and-drop, or paste files to upload">UpLoadFile</button>
			<span>(Chỉ cho phép upload File pdf/doc, size < 10 MB là hợp lệ!)</span>
			<!--<input class="hidden" type="file" id="fancy-file-upload" onchange="fileList(event)" name="f_name" multiple>-->
			<!--<input id="fancy-file-upload" type="file" name="files" accept=".jpg, .png, image/jpeg, image/png" multiple>-->
			<?php
			$table = new \CodeIgniter\View\Table();

			$template = [
				'table_open' => '<table id="example1" class="table table-striped table-bordered">',
				'heading_cell_start' => '<th class="all text-center">'
			];

			$table->setCaption('Danh Sách File')->setTemplate($template);
			$table->setHeading(['Tên File', 'Đường dẫn', 'Size (MB)','Del']);

			$table->setEmpty('&nbsp;');
			echo $table->generate();
			?>
		</div>
	</div>
</div>
<form method="post" enctype="multipart/form-data" />
<input type="file" id="uploadfile" name="file" style="display:none" />
</form>
<script>
  $(document).ready(function() {
    $('#example1').DataTable({
      searching: false,
      scrollY: '200px',
      scrollCollapse: true,
      paging: false,
      processing: true,
      serverSide:true,
      ajax: {
        url:"<?= site_url('API/ajax_fetch_file') ?>",
        type: "POST",
        dataType: "json",
        data: {
          briefcase_id: <?= $data->bc_id ?>,
        }
      }
    });
  });

  function upFile(id){
    // lay gia tri id cua ho so
    $("#uploadfile").click();
    $("#uploadfile").change(function () {
      var file_data = $('#uploadfile').prop('files')[0];
      var form_data = new FormData();
      form_data.append('file', file_data);
      form_data.append('briefcase_id', id);
      $.ajax({
        url: "<?= site_url('API/ajax_upload_file') ?>",
        type: "POST",
        data: form_data,
        contentType: false,
        cache: false,
        processData:false,
        success: function(data){
          var Obj = $.parseJSON(data);
          alert(Obj.message);
          $('#example1').DataTable().ajax.reload(null, false);
        }
      });
      //alert($("#uploadfile").val().substring($("#uploadfile").val().lastIndexOf('\\') + 1));
    });
  }

  function deleteFile(id)  {
    $.ajax({
      url: "<?= site_url('API/delete_file_briefcase') ?>",
      type: "POST",
      dataType: "json",
      data: {
        f_id: id,
      },
      success: function(data){
        alert('Bạn đã delete thành công file!');
        $('#example1').DataTable().ajax.reload(null, false);
        /*setTimeout(function(){$('#example1').DataTable().ajax.reload(null, false);}, 1000);*/
      }
    });
  }
</script>
<?= $this->endSection() ?>
