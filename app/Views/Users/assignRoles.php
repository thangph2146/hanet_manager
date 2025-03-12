<?php
/**
 * 9/19/2022
 * AUTHOR:PDV-PC
 */
?>
<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>ASSIGN ROLES FOR USER<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="breadcrumb-title pe-3"><?= $data->u_FullName ?></div>
	<div class="ps-3">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a href="<?= site_url('users/dashboard') ?>"><i class="bx bx-home-alt"></i></a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><a href="<?= site_url('/Users') ?>">Quản lý Users</a></li>
				<li class="breadcrumb-item active" aria-current="page">Assign Permissions for <?= $data->u_FullName ?></li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
		<div class="btn-group">
			<button type="button" class="btn btn-primary">Chức năng</button>
			<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
			</button>
			<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
				<a class="dropdown-item" href="<?= site_url('/users/new') ?>">Tạo User</a>
				<a class="dropdown-item" href="<?= site_url('/users/listdeleted') ?>">List Deleted Users</a>
			</div>
		</div>
	</div>
</div>
<!--end breadcrumb-->
<?= $this->endSection() ?>

<?= $this->section("content") ?>

<div class="responsive">
	<?= form_open("users/UpdateAssignroles/" . $data->u_id, ['class' => 'row g3']) ?>

	<?= $this->include('users/formAssignRoles') ?>

	<div class="col-12">
		<button type="submit" class="btn btn-primary">Assign Roles</button>
	</div>
	</form>
</div>

<?= $this->endSection() ?>

