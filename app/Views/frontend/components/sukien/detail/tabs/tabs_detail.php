<!-- Tab Navigation Styles -->
<style>
    /* Responsive tab styles */
    .event-tabs {
        border-bottom: 1px solid #dee2e6;
        margin-bottom: 2rem;
        position: relative;
    }

    .event-tabs .nav-item {
        margin-bottom: -1px;
    }

    .event-tabs .nav-link {
        position: relative;
        transition: all 0.3s ease;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 0.75rem 1rem;
        font-weight: 500;
        color: #6c757d;
    }

    .event-tabs .nav-link:hover {
        color: #2b3035;
        background-color: rgba(0,0,0,0.02);
    }

    .event-tabs .nav-link.active {
        color: #0d6efd;
        border-color: #0d6efd;
        background-color: transparent;
    }

    .event-tabs .nav-link i {
        margin-right: 0.5rem;
        font-size: 1.1em;
    }

    /* Badge for participant count */
    .badge-tab {
        font-size: 0.7em;
        vertical-align: middle;
        margin-left: 0.4rem;
        padding: 0.25em 0.6em;
        border-radius: 50px;
    }

    /* Tab content animation */
    .tab-pane {
        transition: all 0.3s ease-in-out;
        animation-duration: 0.5s;
    }

    .tab-pane.fade {
        opacity: 0;
    }
    
    .tab-pane.show {
        opacity: 1;
    }

    /* Tab indicator */
    .tab-indicator {
        position: absolute;
        bottom: 0;
        height: 3px;
        background-color: #0d6efd;
        transition: all 0.35s;
    }

    /* Responsive styles */
    @media (max-width: 767.98px) {
        .event-tabs {
            display: flex;
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 5px;
        }

        .event-tabs .nav-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }
        
        .event-tabs::-webkit-scrollbar {
            height: 2px;
        }
        
        .event-tabs::-webkit-scrollbar-thumb {
            background-color: rgba(0,0,0,0.2);
        }
    }
</style>

<!-- Tabs Navigation -->
<div class="w-100">
    <ul class="nav nav-tabs event-tabs" id="eventTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="event-details-tab" data-bs-toggle="tab" data-bs-target="#event-details" type="button" role="tab" aria-controls="event-details" aria-selected="true">
                <i class="lni lni-information"></i><span class="tab-text">Chi tiết sự kiện</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="event-participants-tab" data-bs-toggle="tab" data-bs-target="#event-participants" type="button" role="tab" aria-controls="event-participants" aria-selected="false">
                <i class="lni lni-users"></i><span class="tab-text">Người tham gia</span>
                <?php if(isset($registrations) && !empty($registrations)): ?>
                <span class="badge bg-primary badge-tab"><?= count($registrations) ?></span>
                <?php endif; ?>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="event-registration-tab" data-bs-toggle="tab" data-bs-target="#event-registration" type="button" role="tab" aria-controls="event-registration" aria-selected="false">
                <i class="lni lni-pencil-alt"></i><span class="tab-text">Đăng ký tham gia</span>
            </button>
        </li>
    </ul>
                
    <!-- Tabs Content -->
    <div class="tab-content" id="eventTabsContent">
        <!-- Tab Chi tiết sự kiện -->
        <?= $this->include('frontend\components\sukien\detail\tabs\detail\event_detail_tab') ?>
                    
        <!-- Tab Danh sách người tham gia -->
        <?= $this->include('frontend\components\sukien\detail\tabs\participants\event_participants_tab') ?>
                    
        <!-- Tab Form đăng ký -->
        <?= $this->include('frontend\components\sukien\detail\tabs\registration\event_registration_tab') ?>
    </div>
</div>

<!-- Tab Animation Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cải thiện hiệu ứng chuyển tab
    const tabLinks = document.querySelectorAll('#eventTabs .nav-link');
    const tabContents = document.querySelectorAll('.tab-pane');
    
    // Thêm hiệu ứng khi chuyển tab
    tabLinks.forEach(tabLink => {
        tabLink.addEventListener('click', function() {
            // Di chuyển đến tab được chọn khi ở mobile
            if (window.innerWidth < 768) {
                this.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                    inline: 'center'
                });
            }
            
            // Thêm hiệu ứng ripple khi click
            const rippleEffect = document.createElement('span');
            rippleEffect.classList.add('ripple-effect');
            this.appendChild(rippleEffect);
            
            setTimeout(() => {
                rippleEffect.remove();
            }, 500);
        });
    });
    
    // Hiển thị tab đầu tiên khi tải trang
    const firstTabContent = document.querySelector('.tab-pane');
    if (firstTabContent) {
        firstTabContent.classList.add('show', 'active');
    }
    
    // Lưu tab đang xem vào localStorage để duy trì khi tải lại trang
    const storedTab = localStorage.getItem('activeEventTab');
    if (storedTab) {
        const tabToActivate = document.querySelector(storedTab);
        if (tabToActivate) {
            const bsTab = new bootstrap.Tab(tabToActivate);
            bsTab.show();
        }
    }
    
    // Lưu tab đang xem khi chuyển tab
    tabLinks.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function() {
            localStorage.setItem('activeEventTab', '#' + this.id);
        });
    });
});
</script>