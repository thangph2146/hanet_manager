<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>DASHBOARD<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
	<div class="col">
		<a href="<?= site_url('Roles') ?>">
			<div class="card radius-10 bg-primary bg-gradient">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div>
							<p class="mb-0 text-white">Roles</p>
							<h4 class="my-1 text-white"><?= $data['role'] ?></h4>
						</div>
						<div class="text-white ms-auto font-35"><i class='bx bxs-group'></i>
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>
	<div class="col">
		<a href="<?= site_url('Permissions') ?>">
			<div class="card radius-10 bg-danger bg-gradient">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div>
							<p class="mb-0 text-white">Total Permissions</p>
							<h4 class="my-1 text-white"><?= $data['permission'] ?></h4>
						</div>
						<div class="text-white ms-auto font-35"><i class='fadeIn animated bx bx-accessibility'></i>
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>
	<div class="col">
		<a href="<?= site_url('users') ?>">
			<div class="card radius-10 bg-warning bg-gradient">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div>
							<p class="mb-0 text-dark">Total Users</p>
							<h4 class="text-dark my-1"><?= $data['user'] ?></h4>
						</div>
						<div class="text-dark ms-auto font-35"><i class='bx bx-user-pin'></i>
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>
	<div class="col">
		<a href="<?= site_url('Settings') ?>">
			<div class="card radius-10 bg-success bg-gradient">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div>
							<p class="mb-0 text-white">Settings Systems</p>
							<h4 class="my-1 text-white"><?= $data['setting'] ?></h4>
						</div>
						<div class="text-white ms-auto font-35"><i class='bx bx-cog bx-spin'></i>
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>
	<div class="col">
		<div class="card radius-10 bg-info">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div>
						<p class="mb-0 text-dark">Điểm Danh sự kiện</p>
						<h4 class="my-1 text-dark">Coming soon!!!!</h4>
						<p class="mb-0 font-13 text-dark"><i class="bx bxs-up-arrow align-middle"></i>Đang trong quá trình xây dựng</p>
					</div>
					<div class="widgets-icons bg-white text-dark ms-auto"><i class="bx bxs-group"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card radius-10 bg-danger">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div>
						<p class="mb-0 text-white">Thống kê các loại</p>
						<h4 class="my-1 text-white">Coming soon!!!!</h4>
						<p class="mb-0 font-13 text-white"><i class="bx bxs-down-arrow align-middle"></i>Đang trong quá trình xây dựng</p>
					</div>
					<div class="widgets-icons bg-white text-danger ms-auto"><i class="bx bxs-binoculars"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card radius-10 bg-warning">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div>
						<p class="mb-0 text-dark">Các chức năng khác</p>
						<h4 class="my-1 text-dark">Coming soon!!!!</h4>
						<p class="mb-0 font-13 text-dark"><i class="bx bxs-down-arrow align-middle"></i>Đang trong quá trình xây dựng</p>
					</div>
					<div class="widgets-icons bg-white text-dark ms-auto"><i class='bx bx-line-chart-down'></i>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--end row-->
<?= $this->endSection() ?>
