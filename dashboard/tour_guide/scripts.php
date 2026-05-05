<script>
    const IS_QUEUE_NUMBER_ONE = <?php echo ($currentStatus === 'Queuing' && $queuePosition === 1) ? 'true' : 'false'; ?>;
    const CURRENT_GUIDE_STATUS = "<?php echo $currentStatus; ?>";
    const queuePosition = <?php echo $queuePosition; ?>;
</script>

<script src="../../shared/components/receipt/overall_receipt/overall_receipt_calculator.js"></script>
<script src="../../shared/components/receipt/overall_receipt/overall_receipt_builder.js"></script>
<script src="../../shared/components/receipt/overall_receipt/overall_receipt_actions.js"></script>
<script src="../../shared/components/receipt/tour_details/tour_details.js"></script>
<script src="../../reusable_mybookings_and_receipt/tourist_bookings.js"></script>
<script src="js/confirmation.js"></script>
<script src="js/history.js"></script>
<script src="js/lobby.js"></script>
<script src="js/queue.js"></script>
<script src="js/ui.js"></script>
<script src="js/main.js"></script>