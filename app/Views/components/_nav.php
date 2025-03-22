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
			<li> <a href="<?= site_url('permissions') ?>"><i class="bx bx-right-arrow-alt"></i>Danh Sách Permissions</a>
			</li>
			<li> <a href="<?= site_url('permissions/listdeleted') ?>"><i class="bx bx-right-arrow-alt"></i>Danh sách Permissions bị xóa</a>
			</li>
			<li> <a href="<?= site_url('permissions/new') ?>"><i class="bx bx-right-arrow-alt"></i>Thêm Permission mới</a>
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
		<a href="javascript:;" class="has-arrow">
			<div class="parent-icon"><i class='bx bx-user-pin'></i>
			</div>
			<div class="menu-title">Quản lý Người Dùng</div>
		</a>
		<ul>
			<li> <a href="<?= site_url('nguoidung') ?>"><i class="bx bx-right-arrow-alt"></i>Danh Sách Người Dùng</a>
			</li>
			<li> <a href="<?= site_url('nguoidung/listdeleted') ?>"><i class="bx bx-right-arrow-alt"></i>Danh sách Người Dùng bị xóa</a>
			</li>
			<li> <a href="<?= site_url('nguoidung/new') ?>"><i class="bx bx-right-arrow-alt"></i>Thêm Người Dùng mới</a>
			</li>
		</ul>
	</li>
	<li>
		<a href="javascript:;" class="has-arrow">
			<div class="parent-icon"><i class='bx bx-user-pin'></i>
			</div>
			<div class="menu-title">Quản lý Loại Người Dùng</div>	
		</a>
		<ul>
			<li> <a href="<?= site_url('loainguoidung') ?>"><i class="bx bx-right-arrow-alt"></i>Danh Sách Loại Người Dùng</a>
			</li>
			<li> <a href="<?= site_url('loainguoidung/listdeleted') ?>"><i class="bx bx-right-arrow-alt"></i>Danh sách Loại Người Dùng bị xóa</a>
			</li>
			<li> <a href="<?= site_url('loainguoidung/new') ?>"><i class="bx bx-right-arrow-alt"></i>Thêm Loại Người Dùng mới</a>
			</li>
		</ul>
	</li>
	<li>
		<a href="javascript:;" class="has-arrow">
			<div class="parent-icon"><i class='bx bx-user-pin'></i>
			</div>
			<div class="menu-title">Quản lý Phòng Khoa</div>
		</a>
		<ul>
			<li> <a href="<?= site_url('phongkhoa') ?>"><i class="bx bx-right-arrow-alt"></i>Danh Sách Phòng Khoa</a>
			</li>
			<li> <a href="<?= site_url('phongkhoa/listdeleted') ?>"><i class="bx bx-right-arrow-alt"></i>Danh sách Phòng Khoa bị xóa</a>
			</li>
			<li> <a href="<?= site_url('phongkhoa/new') ?>"><i class="bx bx-right-arrow-alt"></i>Thêm Phòng Khoa mới</a>
			</li>
		</ul>
	</li>
	<li>
		<a href="javascript:;" class="has-arrow">
			<div class="parent-icon"><i class='bx bx-user-pin'></i>
			</div>
			<div class="menu-title">Quản lý Năm Học</div>
		</a>
		<ul>
			<li> <a href="<?= site_url('namhoc') ?>"><i class="bx bx-right-arrow-alt"></i>Danh Sách Năm học</a>
			</li>
			<li> <a href="<?= site_url('namhoc/listdeleted') ?>"><i class="bx bx-right-arrow-alt"></i>Danh sách Năm học bị xóa</a>
			</li>
			<li> <a href="<?= site_url('namhoc/new') ?>"><i class="bx bx-right-arrow-alt"></i>Thêm Năm học mới</a>
			</li>
		</ul>
	</li>	
	<li>
		<a href="javascript:;" class="has-arrow">
			<div class="parent-icon"><i class='bx bx-user-pin'></i>
			</div>
			<div class="menu-title">Quản lý Khóa Học</div>
		</a>
		<ul>
			<li> <a href="<?= site_url('khoahoc') ?>"><i class="bx bx-right-arrow-alt"></i>Danh Sách Khóa Học</a>
			</li>
			<li> <a href="<?= site_url('khoahoc/listdeleted') ?>"><i class="bx bx-right-arrow-alt"></i>Danh sách Khóa Học bị xóa</a>
			</li>
			<li> <a href="<?= site_url('khoahoc/new') ?>"><i class="bx bx-right-arrow-alt"></i>Thêm Khóa Học mới</a>
			</li>
		</ul>
	</li>	
	<li>
		<a href="javascript:;" class="has-arrow">
			<div class="parent-icon"><i class='bx bx-user-pin'></i>
			</div>
			<div class="menu-title">Quản lý Bậc Học</div>
		</a>
		<ul>
			<li> <a href="<?= site_url('bachoc') ?>"><i class="bx bx-right-arrow-alt"></i>Danh Sách Bậc Học</a>
			</li>
			<li> <a href="<?= site_url('bachoc/listdeleted') ?>"><i class="bx bx-right-arrow-alt"></i>Danh sách Bậc Học bị xóa</a>
			</li>
			<li> <a href="<?= site_url('bachoc/new') ?>"><i class="bx bx-right-arrow-alt"></i>Thêm Bậc Học mới</a>
			</li>
		</ul>
	</li>
	<li>
		<a href="javascript:;" class="has-arrow">
			<div class="parent-icon"><i class='bx bx-user-pin'></i>
			</div>
			<div class="menu-title">Quản lý Hệ Đào Tạo</div>
		</a>
		<ul>
			<li> <a href="<?= site_url('hedaotao') ?>"><i class="bx bx-right-arrow-alt"></i>Danh Sách Hệ Đào Tạo</a>
			</li>
			<li> <a href="<?= site_url('hedaotao/listdeleted') ?>"><i class="bx bx-right-arrow-alt"></i>Danh sách Hệ Đào Tạo bị xóa</a>
			</li>
			<li> <a href="<?= site_url('hedaotao/new') ?>"><i class="bx bx-right-arrow-alt"></i>Thêm Hệ Đào Tạo mới</a>
			</li>

		</ul>
	</li>
	<li>
		<a href="javascript:;" class="has-arrow">
			<div class="parent-icon"><i class='bx bx-user-pin'></i>
			</div>
			<div class="menu-title">Quản lý Ngành</div>
		</a>
		<ul>
			<li> <a href="<?= site_url('nganh') ?>"><i class="bx bx-right-arrow-alt"></i>Danh Sách Ngành</a>
			</li>
			<li> <a href="<?= site_url('nganh/listdeleted') ?>"><i class="bx bx-right-arrow-alt"></i>Danh sách Ngành bị xóa</a>
			</li>
			<li> <a href="<?= site_url('nganh/new') ?>"><i class="bx bx-right-arrow-alt"></i>Thêm Ngành mới</a>
			</li>
		</ul>
	</li>	
	<li>
		<a href="https://phongqlcntt.hub.edu.vn/" target="_blank">
			<div class="parent-icon"><i class="bx bx-support"></i>
			</div>
			<div class="menu-title">Support</div>
		</a>
	</li>
</ul>
