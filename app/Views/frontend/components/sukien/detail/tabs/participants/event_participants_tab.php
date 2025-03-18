<?php
/**
 * Component hiển thị tab danh sách người tham gia sự kiện
 */
?>

<div class="tab-pane fade" id="event-participants" role="tabpanel" aria-labelledby="event-participants-tab">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="card-title mb-4">Danh sách người tham gia</h3>

            <!-- Participant parameters  -->
            <?= $this->include('frontend\components\sukien\detail\tabs\participants\participant_parameters') ?>
            
            <!-- Participant progress bar  -->
            <?= $this->include('frontend\components\sukien\detail\tabs\participants\participant_progress_bar') ?>
            
            <!-- Participant table -->
            <?= $this->include('frontend\components\sukien\detail\tabs\participants\participant_table') ?>
            
            <!-- Participant note -->
            <?= $this->include('frontend\components\sukien\detail\tabs\participants\participant_note') ?>
            
            
        </div>
    </div>
</div> 