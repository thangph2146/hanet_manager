 <!-- Tabs Navigation -->
 <div class="w-100">
    <ul class="nav nav-tabs mb-4" id="eventTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="event-details-tab" data-bs-toggle="tab" data-bs-target="#event-details" type="button" role="tab" aria-controls="event-details" aria-selected="true">
                <i class="lni lni-information"></i> Chi tiết sự kiện
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="event-registration-tab" data-bs-toggle="tab" data-bs-target="#event-registration" type="button" role="tab" aria-controls="event-registration" aria-selected="false">
                <i class="lni lni-pencil-alt"></i> Tình trạng đăng ký
            </button>
        </li>
        <?php if (!service('authstudent')->isLoggedInStudent()) : ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="event-registration-form-tab" data-bs-toggle="tab" data-bs-target="#event-registration-form" type="button" role="tab" aria-controls="event-registration-form" aria-selected="false">
                <i class="lni lni-pencil-alt"></i> Form đăng ký
            </button>
        </li>
        <?php endif; ?>
    </ul>
                
    <!-- Tabs Content -->
    <div class="tab-content" id="eventTabsContent">
        <!-- Tab Chi tiết sự kiện -->
        <?= $this->include('frontend\components\sukien\detail\tabs\detail\event_detail_tab') ?>
                    
        <!-- Tab Tình trạng đăng ký -->
        <?= $this->include('frontend\components\sukien\detail\tabs\registration\event_registration_tab') ?>
        
        <?php if (!service('authstudent')->isLoggedInStudent()) : ?>
        <!-- Tab Form đăng ký -->
        <?= $this->include('frontend\components\sukien\detail\tabs\registration\event_registration_form_tab') ?>
        <?php endif; ?>
    </div>
</div>