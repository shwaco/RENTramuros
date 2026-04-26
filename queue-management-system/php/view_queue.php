<article class="queue-layout-wrapper">
    <header style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-top: -0.5rem; margin-bottom: 0.5rem;">
        <h2 class="queue-status-header" style="margin: 0; font-family: 'Roboto', sans-serif; font-weight: 900;">
            STATUS: <span class="text-green" style="font-family: 'Roboto Serif', serif; font-weight: 400;">Queuing #<?php echo $queuePosition; ?></span>
        </h2>

        <?php if ($queuePosition === 1): ?>
            <div style="background-color: #fee2e2; color: #dc2626; padding: 0.5rem 1.25rem; border-radius: 50px; font-weight: 700; font-size: 0.95rem; display: flex; align-items: center; gap: 8px; border: 1px solid #f87171; font-family: 'Roboto', sans-serif; white-space: nowrap; flex-shrink: 0;" aria-live="polite">
                <i class="far fa-clock"></i>
                <span>Select Tourist: <span style="font-family: 'Roboto Serif', serif;"><span id="selection-timer">15</span>s</span></span>
            </div>
        <?php endif; ?>
    </header>

    <section class="tourist-selection-container" aria-label="Tourist Selection Area" style="margin-top: 1rem;">
        <div style="display: flex; align-items: center; justify-content: center; gap: 0.75rem; width: 100%; margin-bottom: 1.5rem;">
            <i class="fas fa-user-friends" style="font-size: 1.25rem; color: #000;"></i>
            <h3 style="margin: 0; font-size: 1.2rem; font-weight: 900; color: #000; letter-spacing: -0.5px; font-family: 'Roboto Condensed', sans-serif;">
                <?php echo ($queuePosition === 1) ? 'Select a Tourist' : 'Waiting Tourists (View Only)'; ?>
            </h3>
        </div>
        <div id="tourist-lobby" class="tourist-blocks-container" aria-label="Waiting Tourists"></div>
    </section>

    <section class="welcome-card-container">
        <div style="background-color: #ffffff; width: 9rem; height: 9rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 2.5rem; border: 1px solid #e5e7eb; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <i class="fas fa-user" style="font-size: 4rem; color: #9ca3af;"></i>
        </div>
        <h2 style="font-size: 3rem; font-weight: 900; color: #000; margin: 0; letter-spacing: -1px; font-family: 'Roboto Condensed', sans-serif;">
            Welcome, <?php echo htmlspecialchars($guideName); ?>!
        </h2>
    </section>
</article>
