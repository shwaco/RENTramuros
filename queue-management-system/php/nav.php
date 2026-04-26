<header>
    <nav class="top-nav" aria-label="Main Navigation">
        <div class="brand-container">
            <a href="#" onclick="switchView('dashboard'); return false;" style="display:flex; align-items:center; text-decoration:none; color:inherit;">
                <img src="../img/RENTRAMUROS_LOGO_WHITE_1920X775 (1).svg" alt="RENTramuros" class="nav-logo-img">
            </a>
        </div>

        <div class="nav-menu">
            <a href="#" onclick="switchView('history'); return false;" class="nav-link">Tour history</a>
            <a href="#" class="nav-link">About</a>

            <div class="profile-dropdown-container">
                <button class="profile-btn" id="profileBtn" aria-haspopup="menu" aria-expanded="false">
                    <div class="user-avatar"><i class="fas fa-user"></i></div>
                </button>

                <div class="profile-dropdown-menu" id="profileMenu" role="menu">
                    <div class="dropdown-header">
                        <div class="dropdown-avatar"><i class="fas fa-user"></i></div>
                        <div class="dropdown-user-info">
                            <strong><?php echo htmlspecialchars($guideName); ?></strong>
                            <span>Tour Guide</span>
                        </div>
                    </div>
                    <hr class="dropdown-divider">

                    <?php if ($currentStatus === 'Clocked In' || $currentStatus === 'Queuing'): ?>
                        <button onclick="handleClockOut()" class="dropdown-item" role="menuitem">
                            <i class="fas fa-power-off"></i> Clock Out
                        </button>
                        <hr class="dropdown-divider">
                    <?php endif; ?>

                    <button onclick="handleLogoutOnly()" class="dropdown-item text-danger" role="menuitem">
                        <i class="fas fa-sign-out-alt"></i> Sign Out
                    </button>
                </div>  
            </div>
        </div>
    </nav>
</header>
