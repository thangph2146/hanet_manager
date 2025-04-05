<?php
/**
 * 9/15/2022
 * AUTHOR:PDV-PC
 */
?>
<?php if (session()->has('warning')): ?>
	<div class="alert alert-warning border-0 bg-warning alert-dismissible fade show py-2">
		<div class="d-flex align-items-center">
			<div class="font-35 text-dark"><i class='bx bx-info-circle'></i>
			</div>
			<div class="ms-3">
				<h6 class="mb-0 text-dark">Warning Alerts</h6>
				<div class="text-dark"><?= session('warning') ?></div>
			</div>
		</div>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
<?php endif; ?>

<?php if (session()->has('info')): ?>
	<div class="alert alert-info border-0 bg-info alert-dismissible fade show py-2">
		<div class="d-flex align-items-center">
			<div class="font-35 text-dark"><i class='bx bx-info-square'></i>
			</div>
			<div class="ms-3">
				<h6 class="mb-0 text-dark">Info Alerts</h6>
				<div class="text-dark"><?= session('info') ?></div>
			</div>
		</div>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
<?php endif; ?>

<?php if (session()->has('error')): ?>
	<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
		<div class="d-flex align-items-center">
			<div class="font-35 text-white"><i class='bx bxs-message-square-x'></i>
			</div>
			<div class="ms-3">
				<h6 class="mb-0 text-white">Lỗi</h6>
				<div class="text-white">
					<?php 
					$error = session('error');
					if (is_array($error)) {
						echo '<ul class="mb-0">';
						foreach ($error as $key => $value) {
							echo '<li>' . esc($value) . '</li>';
						}
						echo '</ul>';
					} else {
						echo esc($error);
					}
					?>
				</div>
			</div>
		</div>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
<?php endif; ?>
