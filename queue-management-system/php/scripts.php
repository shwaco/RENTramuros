<!-- PHP constants exposed to JS — kailangan bago mag-load ang mga JS modules -->
<script>
    const IS_QUEUE_NUMBER_ONE = <?php echo ($currentStatus === 'Queuing' && $queuePosition === 1) ? 'true' : 'false'; ?>;
    const CURRENT_GUIDE_STATUS = "<?php echo $currentStatus; ?>";
    const queuePosition = <?php echo $queuePosition; ?>;
</script>
<script src="js/modal.js"></script>
<script src="js/receipt.js"></script>
<script src="js/queue.js"></script>
<script src="js/lobby.js"></script>
<script src="js/tour_details.js"></script>
<script src="js/history.js"></script>
<script src="js/ui.js"></script>
<script src="js/main.js"></script>
