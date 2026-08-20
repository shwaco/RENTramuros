<link rel="stylesheet" href="../../shared/components/receipt/tour_details/tour_details.css">

<article class="receipt-card">

    <div class="rcpt-header-container">
        <div class="rcpt-id-badge">
            <?php echo htmlspecialchars($tourData['unique_id'] ?? 'N/A'); ?>
        </div>
        <!-- Note: Close button removed since this is embedded on the dashboard, not a popup -->
    </div>

    <div class="rcpt-date-text">
        <?php 
            $rawDate = $tourData['booking_date'];
            if (!empty($tourData['booking_time'])) {
                $rawDate .= ' ' . $tourData['booking_time'];
            }
            echo date('F j, Y ; h:i A', strtotime($rawDate)); 
        ?>
    </div>

    <div class="section-label">TOURIST</div>
    <div class="tourist-grid">
        <span class="tourist-label">ADULTS & SENIORS</span>
        <span class="tourist-sub">(18 years old and above)</span>
        <span class="tourist-val"><?php echo $tourData['adults_and_seniors'] ?: 0; ?></span>
    </div>
    <div class="tourist-grid">
        <span class="tourist-label">CHILDREN</span>
        <span class="tourist-sub">(2 to17 years old)</span>
        <span class="tourist-val"><?php echo $tourData['children'] ?: 0; ?></span>
    </div>
    <div class="tourist-grid" style="margin-bottom: 1.5rem;">
        <span class="tourist-label">INFANTS</span>
        <span class="tourist-sub">(under 2 years old)</span>
        <span class="tourist-val"><?php echo $tourData['infants'] ?: 0; ?></span>
    </div>

    <div class="pkg-row" style="margin-top: 1rem;">
        <span style="font-weight:700;">PACKAGE</span>
        <span id="js-package-display">Loading...</span> 
    </div>

    <hr class="divider-dashed">

    <div class="section-label">ITINERARY</div>
    <div id="js-itinerary-container" class="itinerary-grid">
    </div>

    <div class="vehicle-grid">
        <span style="font-weight:700;">VEHICLE</span>
        <span id="js-vehicle-name" style="text-transform: uppercase; text-align: center;">NONE</span> 
        <span style="text-align: right; font-weight: bold;"><?php echo $tourData['number_of_vehicle'] ?: 0; ?></span>
    </div>

    <hr class="divider-dashed">

    <div class="section-label">CONTACT INFORMATION</div>
    <div class="contact-row">
        <span>FULL NAME:</span>
        <span style="text-transform: uppercase;"><?php echo htmlspecialchars($tourData['first_name'] . ' ' . $tourData['last_name']); ?></span>
    </div>
    <div class="contact-row">
        <span>EMAIL ADDRESS:</span>
        <span><?php echo htmlspecialchars($tourData['email_address']); ?></span>
    </div>
    <div class="contact-row" style="margin-bottom: 1.5rem;">
        <span>PHONE NUMBER:</span>
        <span><?php echo htmlspecialchars($tourData['phone_number']); ?></span>
    </div>

    <hr class="divider-solid">

    <div class="totals-wrapper">
        <div class="totals-grid">
            <span class="total-label">TOTAL FEE:</span>
            <span id="js-total-fee" class="total-val">₱0</span> 
            <span class="total-label">TOUR GUIDE FEE:</span>
            <span class="total-val">₱1,000 - ₱1,500</span>
            
            <span class="grand-label">GRAND TOTAL:</span>
            <span id="js-grand-total" class="grand-val">₱0</span>
        </div>
    </div>
</article>

<script src="../../shared/components/receipt/tour_details/tour_details.js"></script>
<script>
    const tourData = <?php echo json_encode($tourData); ?>;
    renderTourDetails(tourData);
</script>