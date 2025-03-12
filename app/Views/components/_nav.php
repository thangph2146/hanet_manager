<ul class="metismenu" id="menu">
	<li>
		<a href="<?= site_url('users/dashboard') ?>">
			<div class="parent-icon"><i class='bx bx-home-circle'></i>
			</div>
			<div class="menu-title">Dashboard</div>
		</a>
	</li>
	<li class="menu-label">Phần Quản trị</li>
	<li>
		<a href="javascript:;" class="has-arrow">
			<div class="parent-icon"><i class='bx bx-user-pin'></i>
			</div>
			<div class="menu-title">Quản lý Users</div>
		</a>
		<ul>
			<li> <a href="<?= site_url('users') ?>"><i class="bx bx-right-arrow-alt"></i>Danh Sách Users</a>
			</li>
			<li> <a href="<?= site_url('users/listdeleted') ?>"><i class="bx bx-right-arrow-alt"></i>Danh sách Users bị xóa</a>
			</li>
			<li> <a href="<?= site_url('users/new') ?>"><i class="bx bx-right-arrow-alt"></i>Thêm User mới</a>
			</li>
		</ul>
	</li>
	<li>
		<a href="javascript:;" class="has-arrow">
			<div class="parent-icon"><i class='bx bxs-group'></i>
			</div>
			<div class="menu-title">Quản lý Roles</div>
		</a>
		<ul>
			<li> <a href="<?= site_url('roles') ?>"><i class="bx bx-right-arrow-alt"></i>Danh Sách Roles</a>
			</li>
			<li> <a href="<?= site_url('roles/listdeleted') ?>"><i class="bx bx-right-arrow-alt"></i>Danh sách Roles bị xóa</a>
			</li>
			<li> <a href="<?= site_url('roles/new') ?>"><i class="bx bx-right-arrow-alt"></i>Thêm Role mới</a>
			</li>
		</ul>
	</li>
	<li>
		<a href="javascript:;" class="has-arrow">
			<div class="parent-icon"><i class='fadeIn animated bx bx-accessibility'></i>
			</div>
			<div class="menu-title">Quản lý Permissions</div>
		</a>
		<ul>
			<li> <a href="<?= site_url('Permissions') ?>"><i class="bx bx-right-arrow-alt"></i>Danh Sách Permissions</a>
			</li>
			<li> <a href="<?= site_url('Permissions/listDeleted') ?>"><i class="bx bx-right-arrow-alt"></i>Danh sách Permissions bị xóa</a>
			</li>
			<li> <a href="<?= site_url('Permissions/new') ?>"><i class="bx bx-right-arrow-alt"></i>Thêm Permission mới</a>
			</li>
		</ul>
	</li>
	<li>
		<a href="javascript:;" class="has-arrow">
			<div class="parent-icon"><i class='bx bx-cog'></i>
			</div>
			<div class="menu-title">Quản lý Settings</div>
		</a>
		<ul>
			<li> <a href="<?= site_url('settings') ?>"><i class="bx bx-right-arrow-alt"></i>Danh Sách Settings</a>
			</li>
			<li> <a href="<?= site_url('settings/new') ?>"><i class="bx bx-right-arrow-alt"></i>Thêm Setting mới</a>
			</li>
		</ul>
	</li>
	<li>
		<a href="https://phongqlcntt.buh.edu.vn/" target="_blank">
			<div class="parent-icon"><i class="bx bx-support"></i>
			</div>
			<div class="menu-title">Support</div>
		</a>
	</li>
</ul>
