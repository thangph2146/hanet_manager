<div class="tab-pane fade" id="event-registration" role="tabpanel" aria-labelledby="event-registration-tab">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="card-title mb-4">Đăng ký tham gia</h3>
            
            <!-- Registration Event Meta -->
            <?= $this->include('frontend\components\sukien\detail\tabs\registration\event_registration_meta') ?>
            
            <!-- Registration Progress Bar -->
            <?= $this->include('frontend\components\sukien\detail\tabs\registration\event_registration_progress_bar') ?>
            
            <!-- Registration Form -->
            <?= $this->include('frontend\components\sukien\detail\tabs\registration\event_registration_form') ?>
            
            <!-- Registration Contact -->
            <?= $this->include('frontend\components\sukien\detail\tabs\registration\event_registration_contact') ?>
            
        </div>
    </div>
    
   
</div> 