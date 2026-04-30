<!-- receipt modal — populated dynamically by JS -->
<div id="tourist-receipt-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(2px);">
    <div id="tourist-receipt-content" style="background: #ffffff; padding: 0 2rem 2.5rem 2rem; border-radius: 4px; width: 95%; max-width: 500px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); max-height: 90vh; overflow-y: auto; display: flex; flex-direction: column;">
    </div>
</div>

<!-- dynamic confirmation modal — ginagamit para sa accept tour, clock out, etc. -->
<div id="dynamic-confirm-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.4); z-index: 10000; align-items: center; justify-content: center; backdrop-filter: blur(2px);">
    <div style="background: #ffffff; border-radius: 8px; width: 90%; max-width: 420px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); overflow: hidden; display: flex; flex-direction: column; font-family: 'Roboto', sans-serif;">

        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.2rem 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 id="dynamic-modal-title" style="margin: 0; font-size: 1.4rem; font-weight: 700; font-family: 'Roboto Condensed', sans-serif; color: #000000;">Title</h3>
            <button onclick="closeDynamicModal()" style="background: none; border: none; font-size: 1.8rem; cursor: pointer; color: #000000; padding: 0; line-height: 1;">&times;</button>
        </div>

        <div style="padding: 1.5rem; color: #374151; font-size: 1rem; line-height: 1.5; font-weight: 300;">
            <p id="dynamic-modal-msg" style="margin: 0;">Message</p>
        </div>

        <div style="padding: 1rem 1.5rem; background-color: #e2e4e9; display: flex; justify-content: flex-end; gap: 0.75rem;">
            <button onclick="closeDynamicModal()" style="background: #ffffff; border: none; padding: 0.6rem 1.2rem; border-radius: 4px; font-weight: 700; font-size: 1rem; cursor: pointer; color: #000;">
                Cancel
            </button>
            <button id="dynamic-modal-btn" style="border: none; padding: 0.6rem 1.2rem; border-radius: 4px; font-weight: 700; font-size: 1rem; cursor: pointer; color: #ffffff;">
                Confirm
            </button>
        </div>
    </div>
</div>
