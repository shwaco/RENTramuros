<section class="tourist-selection-container" aria-label="Tourist Selection Area" style="margin-top: 3rem;">
    <div style="display: flex; align-items: center; justify-content: center; gap: 0.75rem; width: 100%; margin-bottom: 1.5rem;">
        <i class="fas fa-user-friends" style="font-size: 1.25rem; color: #000;"></i>
        <h3 style="margin: 0; font-size: 1.2rem; font-weight: 900; color: #000; letter-spacing: -0.5px; font-family: 'Roboto Condensed', sans-serif;">
            Waiting Tourists (View Only)
        </h3>
    </div>
    <div id="tourist-lobby" class="tourist-blocks-container" aria-label="Waiting Tourists"></div>
</section>

<article class="minimal-state-wrapper">
    <div style="display: flex; gap: 1rem; justify-content: center; align-items: center;">
        <?php if ($currentStatus === 'Online'): ?>
            <button onclick="clockIn()" class="btn-minimal-primary" style="background-color: #000000; border-color: #000000; color: #ffffff;">Clock In</button>
            <button class="btn-minimal-primary" style="opacity: 0.4; cursor: not-allowed;" disabled>Join Queue</button>
        <?php else: ?>
            <button class="btn-minimal-primary" style="background-color: #109620; border-color: #109620; color: #ffffff; opacity: 0.8; cursor: default;" disabled>
                <i class="fas fa-check"></i> Clocked In
            </button>
            <button onclick="joinQueue()" class="btn-minimal-primary">Join Queue</button>
        <?php endif; ?>
    </div>
</article>
