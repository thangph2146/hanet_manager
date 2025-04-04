<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Trang chủ -->
    <url>
        <loc><?= site_url('su-kien') ?></loc>
        <lastmod><?= $current_date ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    
    <!-- Trang danh sách sự kiện -->
    <url>
        <loc><?= site_url('su-kien/list') ?></loc>
        <lastmod><?= $current_date ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    
    <!-- Trang danh mục -->
    <?php foreach ($categories as $category): ?>
    <url>
        <loc><?= site_url('su-kien/loai/' . $category['slug']) ?></loc>
        <lastmod><?= $current_date ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <?php endforeach; ?>
    
    <!-- Trang chi tiết sự kiện -->
    <?php foreach ($events as $event): ?>
    <url>
        <loc><?= site_url('su-kien/chi-tiet/' . $event['slug']) ?></loc>
        <lastmod><?= date('Y-m-d\TH:i:sP', strtotime($event['updated_at'] ?? $current_date)) ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    <?php endforeach; ?>
</urlset> 