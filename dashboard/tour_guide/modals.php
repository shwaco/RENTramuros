<?php include '../../shared/components/receipt/overall_receipt/overall_receipt.php'; ?>

<!-- Receipt Overlay: Controlled by overall_receipt_builder.js -->
<div id="tourist-receipt-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(2px);">
    <div id="tourist-receipt-content" style="background: #ffffff; padding: 0 2rem 2.5rem 2rem; border-radius: 4px; width: 95%; max-width: 500px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); max-height: 90vh; overflow-y: auto; display: flex; flex-direction: column;">
    </div>
</div>

<!-- Refined Confirmation Modal: Controlled by confirmation.js -->
<div id="dynamic-confirm-overlay" class="confirm-modal-overlay" style="display: none;">
    <div class="confirm-modal-box">
        <div class="confirm-modal-header">
            <h3 id="dynamic-modal-title">Accept tour?</h3>
            <button onclick="closeDynamicModal()" class="confirm-modal-close">&times;</button>
        </div>
        <div class="confirm-modal-body">
            <p id="dynamic-modal-msg">Message</p>
        </div>
        <div class="confirm-modal-footer">
            <button onclick="closeDynamicModal()" class="btn-modal-cancel">Cancel</button>
            <button id="dynamic-modal-btn" class="btn-modal-confirm">Confirm</button>
        </div>
    </div>
</div>