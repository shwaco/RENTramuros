<?php /** @var array $tourData */ ?>

<div class="queue-layout-wrapper">
    <header style="display: flex; justify-content: flex-start; align-items: center; width: 100%; margin-top: -0.5rem; margin-bottom: 1rem;">
        <h2 class="queue-status-header" style="margin: 0; font-family: 'Roboto', sans-serif; font-weight: 700;">
            STATUS: <span style="color: #dc2626; font-family: 'Roboto Serif', serif; font-weight: 400;">On tour</span>
        </h2>
    </header>

    <article style="background: #ffffff; padding: 0 2rem 2.5rem 2rem; border-radius: 4px; width: 100%; max-width: 500px; border: 1px solid #e5e7eb; box-shadow: 0 4px 10px rgba(0,0,0,0.05); margin: 0 auto; display: flex; flex-direction: column;">

        <!-- Inayos ang padding (1.5rem sa taas) at inilagay sa kaliwa ang ID tulad ng sa JS -->
        <div style="display: flex; justify-content: flex-start; align-items: center; margin: 0 -2rem; padding: 1.5rem 2rem 1.5rem 2rem; border-bottom: 1px solid #e5e7eb;">
            <div style="background-color: #000000; color: #ffffff; font-family: 'Roboto Condensed', sans-serif; font-size: 1.4rem; font-weight: 700; padding: 0.4rem 1.2rem; border-radius: 4px; display: inline-flex; justify-content: center; align-items: center; line-height: 1;">
                <?php echo $tourData['booking_request_id']; ?>
            </div>
        </div>

        <div style="text-align: right; font-size: 0.8rem; color: #000000; margin-top: 1.5rem; margin-bottom: 2rem; font-family: 'Roboto Condensed', sans-serif; font-weight: 400;">
            <?php echo date('F j, Y ; h:i A', strtotime($tourData['booking_date'])); ?>
        </div>

        <div style="font-weight:700; font-size:0.9rem; margin-bottom:1rem; color:#000; font-family: 'Roboto Condensed', sans-serif;">TOURIST</div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:0.6rem; font-size:0.85rem;">
            <span style="padding-left: 0.5rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">ADULTS & SENIORS</span>
            <span style="font-weight: 300; font-style:italic; font-size:0.8rem; text-align: center; font-family: 'Roboto Condensed', sans-serif; color: #000000;">(18 years old and above)</span>
            <span style="text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000;"><?php echo $tourData['adults_and_seniors'] ?: 0; ?></span>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:0.6rem; font-size:0.85rem;">
            <span style="padding-left: 0.5rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">CHILDREN</span>
            <span style="font-weight: 300; font-style:italic; font-size:0.8rem; text-align: center; font-family: 'Roboto Condensed', sans-serif; color: #000000;">(2 to 17 years old)</span>
            <span style="text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000;"><?php echo $tourData['children'] ?: 0; ?></span>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:1.5rem; font-size:0.85rem;">
            <span style="padding-left: 0.5rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">INFANTS</span>
            <span style="font-weight: 300; font-style:italic; font-size:0.8rem; text-align: center; font-family: 'Roboto Condensed', sans-serif; color: #000000;">(under 2 years old)</span>
            <span style="text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000;"><?php echo $tourData['infants'] ?: 0; ?></span>
        </div>

        <div style="display: flex; justify-content: space-between; margin-top: 1rem; font-size: 0.85rem;">
            <span style="font-weight:700; font-family: 'Roboto Condensed', sans-serif; color: #000000;">PACKAGE</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">
                <?php 
                    // Kinokopya ang computation ng package price multiplier sa JS
                    $isPackage = !empty($tourData['package_name']) && $tourData['package_name'] !== 'No Package';
                    $pkgName = $tourData['package_name'] ?: 'No Package';
                    $pkgFee = isset($tourData['package_price']) ? (float)$tourData['package_price'] : 0;
                    
                    $pax = ((int)($tourData['adults_and_seniors'] ?? 0)) + ((int)($tourData['children'] ?? 0));
                    $multiplier = $pax > 0 ? $pax : 1;
                    $totalPkgCost = $pkgFee * $multiplier;
                    
                    echo htmlspecialchars($pkgName);
                    if ($isPackage && $totalPkgCost > 0) {
                        echo '&nbsp;&nbsp;<span style="color: #109620; font-weight: 600; font-style: italic; font-size: 0.85rem;">₱' . number_format($totalPkgCost) . '</span>';
                    }
                ?>
            </span>
        </div>

        <hr style="border: 0; border-top: 1px dashed #d1d5db; margin: 1.5rem 0;">

        <div style="font-weight:700; font-size:0.9rem; margin-bottom:1rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">ITINERARY</div>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; font-size: 0.8rem; font-family: 'Roboto Condensed', sans-serif; color: #000000; margin-bottom: 1.5rem;">
            <?php
                $destinations = !empty($tourData['destinations']) ? explode(',', $tourData['destinations']) : [];
                if (empty($destinations)) {
                    echo '<span>No Custom Attractions Selected</span>';
                } else {
                    foreach ($destinations as $dest) {
                        $trimmed = trim($dest);
                        if ($trimmed === 'No Custom Attractions Selected' || $trimmed === '') {
                            echo '<span>' . htmlspecialchars($trimmed) . '</span>';
                            continue;
                        }
                        $parts = explode('|', $trimmed);
                        $name = trim($parts[0]);
                        $fee = isset($parts[1]) ? (float)$parts[1] : 0;
                        $totalFee = $fee * $multiplier;
                        
                        if ($totalFee > 0 && !$isPackage) {
                            echo '<span>' . htmlspecialchars($name) . '&nbsp;&nbsp;<span style="color: #109620; font-weight: 600; font-style: italic; font-size: 0.8rem;">₱' . number_format($totalFee) . '</span></span>';
                        } else {
                            echo '<span>' . htmlspecialchars($name) . '</span>';
                        }
                    }
                }
            ?>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; font-size: 0.85rem;">
            <span style="font-weight:700; font-family: 'Roboto Condensed', sans-serif; color: #000000;">VEHICLE</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000; text-transform: uppercase; text-align: center;">
                <?php 
                    $vType = $tourData['vehicle_type'] ?: 'NONE';
                    $vPrice = isset($tourData['vehicle_price']) ? (float)$tourData['vehicle_price'] : 0;
                    $vCount = (int)($tourData['number_of_vehicle'] ?? 0);
                    $vMultiplier = $vCount > 0 ? $vCount : 1;
                    $totalVCost = $vPrice * $vMultiplier;

                    echo htmlspecialchars($vType);
                    if ($vPrice > 0) {
                        echo '&nbsp;&nbsp;<span style="color: #109620; font-weight: 600; font-style: italic; font-size: 0.8rem;">₱' . number_format($totalVCost) . '</span>';
                    }
                ?>
            </span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000; text-align: right; font-weight: bold;"><?php echo $vCount; ?></span>
        </div>

        <hr style="border: 0; border-top: 1px dashed #d1d5db; margin: 1.5rem 0;">

        <div style="font-weight:700; font-size:0.9rem; margin-bottom:1rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">CONTACT INFORMATION</div>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.85rem;">
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">FULL NAME:</span>
            <span style="text-transform: uppercase; font-family: 'Roboto Condensed', sans-serif; color: #000000;"><?php echo htmlspecialchars($tourData['first_name'] . ' ' . $tourData['last_name']); ?></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.85rem;">
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">EMAIL ADDRESS:</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;"><?php echo htmlspecialchars($tourData['email_address']); ?></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; font-size: 0.85rem;">
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">PHONE NUMBER:</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;"><?php echo htmlspecialchars($tourData['phone_number']); ?></span>
        </div>

        <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 1.5rem 0;">

        <!-- Parehong breakdown format ng Grand Total galing sa JS modal -->
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 2rem;">
            <?php
                $baseTotal = $totalVCost;
                if ($isPackage) {
                    $baseTotal += $totalPkgCost;
                } elseif (!empty($tourData['destinations'])) {
                    foreach (explode(',', $tourData['destinations']) as $dest) {
                        $parts = explode('|', trim($dest));
                        $fee = isset($parts[1]) ? (float)$parts[1] : 0;
                        if ($fee > 0) $baseTotal += $fee * $multiplier;
                    }
                }

                $minGrandTotal = $baseTotal + 1000;
                $maxGrandTotal = $baseTotal + 1500;
            ?>
            <div style="display: grid; grid-template-columns: max-content auto; column-gap: 0.75rem; row-gap: 0.4rem; align-items: baseline;">
                <span style="font-weight: 500; font-family: 'Roboto Condensed', sans-serif; color: #4b5563; font-size: 0.85rem;">TOTAL FEE:</span>
                <span style="font-weight: 600; font-family: 'Roboto Condensed', sans-serif; color: #000; font-size: 0.85rem;">₱<?php echo number_format($baseTotal); ?></span>
                
                <span style="font-weight: 500; font-family: 'Roboto Condensed', sans-serif; color: #4b5563; font-size: 0.85rem;">TOUR GUIDE FEE:</span>
                <span style="font-weight: 600; font-family: 'Roboto Condensed', sans-serif; color: #000; font-size: 0.85rem;">₱1,000 - ₱1,500</span>
                
                <span style="font-weight: 700; font-family: 'Roboto Condensed', sans-serif; color: #000000; font-size: 0.95rem; margin-top: 0.3rem;">GRAND TOTAL:</span>
                <span style="font-weight: 700; font-family: 'Roboto Condensed', sans-serif; color: #109620; font-size: 1.05rem; font-style: italic; margin-top: 0.3rem;">₱<?php echo number_format($minGrandTotal); ?> - ₱<?php echo number_format($maxGrandTotal); ?></span>
            </div>
        </div>

    </article>
</div>