<script>
    const IS_QUEUE_NUMBER_ONE = <?php echo ($currentStatus === 'Queuing' && $queuePosition === 1) ? 'true' : 'false'; ?>;
    const CURRENT_GUIDE_STATUS = "<?php echo $currentStatus; ?>";
    const queuePosition = <?php echo $queuePosition; ?>;
</script>
<script src="../../queue-management-system/js/receipt.js"></script>
<script src="../../queue-management-system/js/tour_details.js"></script>
<script src="../../reusable_mybookings_and_receipt/tourist_bookings.js"></script>
<script src="../../shared/components/confirmation-modal/confirmation.js"></script>
<script src="js/history.js"></script>
<script src="js/lobby.js"></script>
<script src="js/queue.js"></script>
<script src="js/ui.js"></script>
<script src="js/main.js"></script>