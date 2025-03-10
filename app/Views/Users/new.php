<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>TẠO USER<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="breadcrumb-title pe-3">Tạo User</div>
	<div class="ps-3">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a href="<?= site_url('Users/dashboard') ?>"><i class="bx bx-home-alt"></i></a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><a href="<?= site_url('/Users') ?>">Quản lý Users</a></li>
				<li class="breadcrumb-item active" aria-current="page">Tạo User</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
		<div class="btn-group">
			<button type="button" class="btn btn-primary">Chức năng</button>
			<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
			</button>
			<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
				<a class="dropdown-item" href="javascript:;">Tạo User</a>
				<a class="dropdown-item" href="<?= site_url('/Users/listDeleted') ?>">List Deleted Role</a>
			</div>
		</div>
	</div>
</div>
<!--end breadcrumb-->
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<?= form_open("Users/create", ['class' => 'row g3']) ?>

	<?= $this->include('Users/form'); ?>

	<div class="col-12">
		<button type="submit" class="btn btn-primary">Tạo User</button>
	</div>
</form>
<?= $this->endSection() ?>
