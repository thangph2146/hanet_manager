<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>EDIT PERMISSIONS<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="breadcrumb-title pe-3">Edit Permissons</div>
	<div class="ps-3">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a href="<?= site_url('users/dashboard') ?>"><i class="bx bx-home-alt"></i></a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><a href="<?= site_url('permissions') ?>">Quản lý Permissions</a></li>
				<li class="breadcrumb-item active" aria-current="page">Edit Permissons</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
		<div class="btn-group">
			<button type="button" class="btn btn-primary">Chức Năng</button>
			<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
			</button>
			<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
				<a class="dropdown-item" href="<?= site_url('permissions/new') ?>">Tạo Permission</a>
				<a class="dropdown-item" href="<?= site_url('permissions/listdeleted') ?>">List Deleted Permissions</a>
			</div>
		</div>
	</div>
</div>
<!--end breadcrumb-->
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<?= form_open("permissions/update/" . $data->p_id, ['class' => 'row g3']) ?>

<?= $this->include('Permissions/form'); ?>

<div class="col-12">
	<button type="submit" class="btn btn-primary">Edit Chức năng</button>
</div>
</form>
<?= $this->endSection() ?>
