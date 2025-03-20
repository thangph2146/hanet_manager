<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<?= $this->include('components/_css') ?>
	<?= $this->renderSection("linkHref") ?>
	<title><?= $this->renderSection("title") ?></title>
</head>

<body>
<!--wrapper-->
<div class="wrapper">
	<!--sidebar wrapper -->
	<div class="sidebar-wrapper" data-simplebar="true">
		<?= $this->include('components/_sidebar') ?>
		<!--navigation-->
		<?= $this->include('components/_nav') ?>
		<!--end navigation-->
	</div>
	<!--end sidebar wrapper -->
	<!--start header -->
	<?= $this->include('components/_header') ?>
	<!--end header -->
	<!--start page wrapper -->
	<div class="page-wrapper">
		<div class="page-content">
			<?= $this->renderSection("bread_cum_link") ?>
			<!-- content -->
			<div class="row">
				<div class="col-xl-12 mx-auto">
					<h6 class="mb-0 text-uppercase"><?= $this->renderSection("title_content") ?></h6>
					<hr/>
					<!-- warning -->
					<?= $this->include('components/_warning') ?>
					<!-- end warning -->
					<!--Error list -->
					<?= $this->include('components/_errors_list'); ?>
					<!--end Error list -->
				</div>
			</div>
			<?= $this->renderSection("content") ?>
			<!-- end content -->
		</div>
	</div>
	<!--end page wrapper -->
	<!-- footer -->
	<?= $this->include('components/_footer') ?>
	<!--end footer -->
</div>
<!--end wrapper-->
<!--start switcher-->
<?= $this->include('components/_switcher') ?>
<!--end switcher-->
<!-- js -->
<?= $this->include('components/_js') ?>
<!-- end js -->

<!-- DataTables Buttons Extensions -->
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

<!-- Các thư viện xuất file -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

<!-- CSS để đảm bảo biểu tượng hiển thị đúng -->
<style>
    .bi {
        display: inline-block !important;
    }
    .btn-group .btn i, 
    .action-buttons .btn i {
        display: inline-block !important;
    }
    .dt-buttons {
        display: none !important;
    }
    .btn-excel i.bi-file-earmark-excel, 
    .btn-pdf i.bi-file-earmark-pdf {
        display: inline-block !important;
        margin-right: 4px;
    }
</style>

<!-- Luôn tải các thư viện cần thiết -->
<script src="<?= site_url('assets/js/table-builder.js') ?>"></script>

<!-- Auto-load thư viện FormBuilder nếu cần -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Kiểm tra xem trang có sử dụng FormBuilder không
        if (document.documentElement.classList.contains('form-builder-enabled')) {
            // Tải file JS của FormBuilder
            var formBuilderScript = document.createElement('script');
            formBuilderScript.src = '<?= site_url('assets/js/form-builder.js') ?>';
            document.body.appendChild(formBuilderScript);
        }
        
        // Đảm bảo TableBuilder được khởi tạo đúng cách
        if (window.TableBuilder && window.TableBuilder.reinitAll) {
            console.log('Khởi tạo lại TableBuilder từ layout');
            window.TableBuilder.reinitAll();
        }
    });
</script>

<!-- Xử lý popup khi xuất dữ liệu -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý thông báo của muster.vn khi xuất dữ liệu
    window.addEventListener('beforeunload', function(e) {
        // Chặn thông báo nếu đang xuất dữ liệu
        if (document.querySelector('.manual-export-buttons')) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
    
    // Tự động đóng dialog khi xuất hiện
    setInterval(function() {
        // Tìm nút OK trong dialog
        var okButton = document.querySelector('.swal-button--confirm, button.ok, #alertify-ok');
        if (okButton) {
            okButton.click();
        }
        
        // Tìm checkbox "Đừng hỏi lại"
        var dontAskAgain = document.querySelector('input[type="checkbox"][id*="dont-ask"], input[type="checkbox"][name*="dont-ask"]');
        if (dontAskAgain) {
            dontAskAgain.checked = true;
        }
    }, 500);
});
</script>

<?= $this->renderSection("script") ?>
</body>

</html>
