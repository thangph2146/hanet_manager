<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/style.css') ?>">
</head>
<body>
    <div class="d-flex flex-column flex-lg-row">
        <!-- Sidebar -->
        <div class="sidebar-container">
            <?= $this->include('layouts/admin/components/sidebar') ?>
        </div>
        
        <div class="w-100">
            <!-- Header -->
            <?= $this->include('layouts/admin/components/header') ?>
            
            <!-- Main Content -->
            <main id="content" class="p-3 p-md-4">
                <?= $this->renderSection('content') ?>
            </main>
            
            <!-- Footer -->
            <?= $this->include('layouts/admin/components/footer') ?>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?= base_url('assets/admin/js/script.js') ?>"></script>
</body>
</html>
