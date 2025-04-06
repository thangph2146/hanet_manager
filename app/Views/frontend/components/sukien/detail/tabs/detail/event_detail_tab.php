<?php
/**
 * Component hiển thị tab chi tiết sự kiện
 */
?>

<div class="tab-pane fade show active" id="event-details" role="tabpanel" aria-labelledby="event-details-tab">
    <!-- Event Banner -->
    <?= $this->include('frontend\components\sukien\detail\tabs\detail\event_banner') ?>

    <!-- Event Meta -->
    <?= $this->include('frontend\components\sukien\detail\tabs\detail\event_meta') ?>

    <!-- Event Countdown -->
    <?= $this->include('frontend\components\sukien\detail\tabs\detail\event_countdown') ?>

    <!-- Event Description -->
    <?= $this->include('frontend\components\sukien\detail\tabs\detail\event_description') ?>

    <!-- Event Schedule -->
    <?= $this->include('frontend\components\sukien\detail\tabs\detail\event_schedule') ?>

    <!-- Event Speakers -->
    <?= $this->include('frontend\components\sukien\detail\tabs\detail\event_speaker') ?>

    <!-- Social Share -->
    <?= $this->include('frontend\components\sukien\detail\tabs\detail\social_share') ?>
</div> 