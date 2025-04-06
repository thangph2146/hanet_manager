<?php
/**
 * Component hiển thị sidebar của trang chi tiết sự kiện
 */
?>

<div class="col-lg-12">
    <!-- Social Share -->
    <?= $this->include('frontend/components/sukien/detail/sidebar/social_share') ?>
    
    <!-- Event Stats -->
    <?= $this->include('frontend/components/sukien/detail/sidebar/event_stats') ?>
    
    <!-- Event Organizer -->
    <?= $this->include('frontend/components/sukien/detail/sidebar/event_organizer') ?>

    <!-- Related Events -->
    <?= $this->include('frontend/components/sukien/detail/sidebar/related_event') ?>
    
    
</div> 