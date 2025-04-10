<?php if (isset($total) && $total > 0) : ?>
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Hiển thị <?= $perPage ?> / <?= $total ?> kết quả
            </div>
            <div class="pagination-container">
                <?php
                // Custom pagination implementation
                $currentPage = $page;
                $totalPages = ceil($total / $perPage);
                $moduleUrl = site_url($module_name);
                
                // Build query string for pagination links
                $queryParams = [];
                if (!empty($keyword)) $queryParams['keyword'] = $keyword;
                if (!empty($start_date)) $queryParams['start_date'] = $start_date;
                if (!empty($end_date)) $queryParams['end_date'] = $end_date;
                if ($perPage != 10) $queryParams['perPage'] = $perPage;
                
                // Start pagination HTML
                echo '<ul class="pagination mb-0">';
                
                // Previous button
                if ($currentPage > 1) {
                    $prevParams = $queryParams;
                    $prevParams['page'] = $currentPage - 1;
                    $prevUrl = $moduleUrl . '?' . http_build_query($prevParams);
                    echo '<li class="page-item"><a class="page-link" href="' . $prevUrl . '">&laquo;</a></li>';
                } else {
                    echo '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
                }
                
                // Page numbers
                $startPage = max(1, $currentPage - 2);
                $endPage = min($totalPages, $currentPage + 2);
                
                if ($startPage > 1) {
                    $firstParams = $queryParams;
                    $firstParams['page'] = 1;
                    $firstUrl = $moduleUrl . '?' . http_build_query($firstParams);
                    echo '<li class="page-item"><a class="page-link" href="' . $firstUrl . '">1</a></li>';
                    if ($startPage > 2) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }
                }
                
                for ($i = $startPage; $i <= $endPage; $i++) {
                    $pageParams = $queryParams;
                    $pageParams['page'] = $i;
                    $pageUrl = $moduleUrl . '?' . http_build_query($pageParams);
                    
                    if ($i == $currentPage) {
                        echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link" href="' . $pageUrl . '">' . $i . '</a></li>';
                    }
                }
                
                if ($endPage < $totalPages) {
                    if ($endPage < $totalPages - 1) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }
                    $lastParams = $queryParams;
                    $lastParams['page'] = $totalPages;
                    $lastUrl = $moduleUrl . '?' . http_build_query($lastParams);
                    echo '<li class="page-item"><a class="page-link" href="' . $lastUrl . '">' . $totalPages . '</a></li>';
                }
                
                // Next button
                if ($currentPage < $totalPages) {
                    $nextParams = $queryParams;
                    $nextParams['page'] = $currentPage + 1;
                    $nextUrl = $moduleUrl . '?' . http_build_query($nextParams);
                    echo '<li class="page-item"><a class="page-link" href="' . $nextUrl . '">&raquo;</a></li>';
                } else {
                    echo '<li class="page-item disabled"><span class="page-link">&raquo;</span></li>';
                }
                
                echo '</ul>';
                ?>
            </div>
        </div>
    </div>
<?php elseif (isset($perPage) && isset($total)) : ?>
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Hiển thị <?= $perPage ?> / <?= $total ?> kết quả
            </div>
        </div>
    </div>
<?php endif ?> 