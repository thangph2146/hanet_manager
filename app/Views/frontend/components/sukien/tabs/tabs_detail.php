 <!-- Tabs Navigation -->
 <div class="w-100">
    <ul class="nav nav-tabs mb-4" id="eventTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="event-details-tab" data-bs-toggle="tab" data-bs-target="#event-details" type="button" role="tab" aria-controls="event-details" aria-selected="true">
                                <i class="lni lni-information"></i> Chi tiết sự kiện
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="event-participants-tab" data-bs-toggle="tab" data-bs-target="#event-participants" type="button" role="tab" aria-controls="event-participants" aria-selected="false">
                                <i class="lni lni-users"></i> Người tham gia
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="event-registration-tab" data-bs-toggle="tab" data-bs-target="#event-registration" type="button" role="tab" aria-controls="event-registration" aria-selected="false">
                                <i class="lni lni-pencil-alt"></i> Đăng ký tham gia
                            </button>
                        </li>
                    </ul>
                
                <!-- Tabs Content -->
                <div class="tab-content" id="eventTabsContent">
                    <!-- Tab Chi tiết sự kiện -->
                    <?= $this->include('frontend\components\sukien\tabs\event_detail_tab') ?>
                    
        <!-- Tab Danh sách người tham gia -->
        <?= $this->include('frontend\components\sukien\tabs\event_participants_tab') ?>
                    
        <!-- Tab Form đăng ký -->
        <?= $this->include('frontend\components\sukien\tabs\event_registration_tab') ?>
    </div>
</div>