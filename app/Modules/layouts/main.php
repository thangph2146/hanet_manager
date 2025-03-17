<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - Hệ thống quản lý</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Base CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/base.css') ?>">
    
    <!-- Custom CSS -->
    <?= $this->renderSection('css') ?>
    
    <!-- Favicon -->
    <link rel="icon" href="<?= base_url('favicon.ico') ?>" type="image/x-icon">
</head>
<body class="<?= $this->renderSection('body_class') ?>">
    <!-- Header -->
    <header class="main-header">
        <?= $this->renderSection('header') ?>
    </header>
    
    <!-- Main Content -->
    <main class="main-content">
        <?= $this->renderSection('content') ?>
    </main>
    
    <!-- Footer -->
    <footer class="main-footer">
        <?= $this->renderSection('footer') ?>
        
        <div class="copyright text-center py-3">
            <div class="container">
                <p class="mb-0">&copy; <?= date('Y') ?> Hệ thống quản lý. Bản quyền thuộc về trường Đại học.</p>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
    <!-- Base JS -->
    <script src="<?= base_url('assets/js/base.js') ?>"></script>
    
    <!-- Custom JS -->
    <?= $this->renderSection('js') ?>
</body>
</html> 