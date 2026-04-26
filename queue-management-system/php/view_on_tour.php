<div class="queue-layout-wrapper">
    <header style="display: flex; justify-content: flex-start; align-items: center; width: 100%; margin-top: -0.5rem; margin-bottom: 1rem;">
        <h2 class="queue-status-header" style="margin: 0; font-family: 'Roboto', sans-serif; font-weight: 700;">
            STATUS: <span style="color: #dc2626; font-family: 'Roboto Serif', serif; font-weight: 400;">On tour</span>
        </h2>
    </header>

    <article style="background: #ffffff; padding: 0 2rem 2.5rem 2rem; border-radius: 4px; width: 100%; max-width: 500px; border: 1px solid #e5e7eb; box-shadow: 0 4px 10px rgba(0,0,0,0.05); margin: 0 auto; display: flex; flex-direction: column;">

        <div style="display: flex; justify-content: center; align-items: center; margin: 0 -2rem; padding: 0.5rem; border-bottom: 1px solid #e5e7eb;">
            <div style="background-color: #000000; color: #ffffff; font-family: 'Roboto Condensed', sans-serif; font-size: 1.4rem; font-weight: 700; padding: 0.4rem 1.2rem; border-radius: 4px; display: inline-flex; justify-content: center; align-items: center; line-height: 1;">
                <?php echo $tourData['customer_id']; ?>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 0 -2rem 1.5rem -2rem;">

        <div style="text-align: right; font-size: 0.8rem; color: #000000; margin-bottom: 2rem; font-family: 'Roboto Condensed', sans-serif; font-weight: 700;">
            <?php echo date('F j, Y ; h:i A', strtotime($tourData['created_at'])); ?>
        </div>

        <div style="font-weight:700; font-size:0.9rem; margin-bottom:1rem; color:#000; font-family: 'Roboto Condensed', sans-serif;">TOURIST</div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:0.6rem; font-size:0.85rem;">
            <span style="font-weight: 400; padding-left: 0.5rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">ADULTS & SENIORS</span>
            <span style="font-weight: 200; font-style:italic; font-size:0.8rem; text-align: center; font-family: 'Roboto Condensed', sans-serif; color: #000000;">(18 years old and above)</span>
            <span style="font-weight: 300; text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000;"><?php echo $tourData['adult_count'] ?: 0; ?></span>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:0.6rem; font-size:0.85rem;">
            <span style="font-weight: 400; padding-left: 0.5rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">CHILDREN</span>
            <span style="font-weight: 200; font-style:italic; font-size:0.8rem; text-align: center; font-family: 'Roboto Condensed', sans-serif; color: #000000;">(2 to 17 years old)</span>
            <span style="font-weight: 300; text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000;"><?php echo $tourData['children_count'] ?: 0; ?></span>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:1.5rem; font-size:0.85rem;">
            <span style="font-weight: 400; padding-left: 0.5rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">INFANTS</span>
            <span style="font-weight: 200; font-style:italic; font-size:0.8rem; text-align: center; font-family: 'Roboto Condensed', sans-serif; color: #000000;">(under 2 years old)</span>
            <span style="font-weight: 300; text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000;"><?php echo $tourData['infant_count'] ?: 0; ?></span>
        </div>

        <div style="display: flex; justify-content: space-between; margin-top: 1rem; font-size: 0.85rem;">
            <span style="font-weight:700; font-family: 'Roboto Condensed', sans-serif; color: #000000;">PACKAGE</span>
            <span style="font-weight: 300; font-family: 'Roboto Condensed', sans-serif; color: #000000;"><?php echo $tourData['package_name'] ?: 'NO'; ?></span>
        </div>

        <hr style="border: 0; border-top: 1px dashed #d1d5db; margin: 1.5rem 0;">

        <div style="font-weight: 700; font-size:0.9rem; margin-bottom:1rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">ITINERARY</div>
        <div style="font-weight: 300; padding-left: 0.25rem; display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; font-size: 0.8rem; font-family: 'Roboto Condensed', sans-serif; color: #000000; margin-bottom: 1.5rem;">
            <?php
                $destinations = $tourData['destinations'] ? explode(',', $tourData['destinations']) : ['No itineraries listed'];
                foreach ($destinations as $dest) {
                    if (trim($dest) === 'No itineraries listed') {
                        echo '<span>' . htmlspecialchars(trim($dest)) . '</span>';
                        continue;
                    }
                    $parts = explode('|', $dest);
                    $name = trim($parts[0]);
                    $fee = isset($parts[1]) ? (int)$parts[1] : 0;
                    if ($fee > 0) {
                        echo '<span>' . htmlspecialchars($name) . '&nbsp;&nbsp;<span style="color: #16a34a; font-weight: 600; font-style: italic; font-size: 0.8rem;">₱' . $fee . '</span></span>';
                    } else {
                        echo '<span>' . htmlspecialchars($name) . '</span>';
                    }
                }
            ?>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; font-size: 0.85rem;">
            <span style="font-weight: 700; font-family: 'Roboto Condensed', sans-serif; color: #000000;">VEHICLE</span>
            <span style="font-weight: 300; font-family: 'Roboto Condensed', sans-serif; color: #000000; text-transform: uppercase; text-align: center;"><?php echo $tourData['vehicle_type'] ?: 'NONE'; ?></span>
            <span style="font-weight: 300; font-family: 'Roboto Condensed', sans-serif; color: #000000; text-align: right;"><?php echo $tourData['vehicle_count'] ?: 0; ?></span>
        </div>

        <hr style="border: 0; border-top: 1px dashed #d1d5db; margin: 1.5rem 0;">

        <div style="font-weight:700; font-size:0.9rem; margin-bottom:1rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">CONTACT INFORMATION</div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.85rem;">
            <span style="font-weight: 300; font-family: 'Roboto Condensed', sans-serif; color: #000000;">FULL NAME:</span>
            <span style="font-weight: 300; font-family: 'Roboto Condensed', sans-serif; color: #000000;"><?php echo htmlspecialchars($tourData['first_name'] . ' ' . $tourData['last_name']); ?></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.85rem;">
            <span style="font-weight: 300; font-family: 'Roboto Condensed', sans-serif; color: #000000;">EMAIL ADDRESS:</span>
            <span style="font-weight: 300; font-family: 'Roboto Condensed', sans-serif; color: #000000;"><?php echo htmlspecialchars($tourData['email']); ?></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; font-size: 0.85rem;">
            <span style="font-weight: 300; font-family: 'Roboto Condensed', sans-serif; color: #000000;">PHONE NUMBER:</span>
            <span style="font-weight: 300; font-family: 'Roboto Condensed', sans-serif; color: #000000;"><?php echo htmlspecialchars($tourData['phone_number']); ?></span>
        </div>

        <div style="display: flex; gap: 0.75rem; align-items: center;">
            <span style="font-weight: 300; font-family: 'Roboto Condensed', sans-serif; color: #000000; font-size: 0.9rem;">TOTAL FEE:</span>
            <span style="font-weight: 400; font-family: 'Roboto Condensed', sans-serif; color: #109620; font-size: 0.9rem; font-style: italic;">₱4,500-5000</span>
        </div>

    </article>
</div>
