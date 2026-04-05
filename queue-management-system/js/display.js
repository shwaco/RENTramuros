async function handleLogoutTourist() {
    try {
        const response = await fetch('api/logout_tourist.php', { method: 'POST' });
        const result = await response.json();
        
        if (result.success) {
            window.location.href = "auth/log in/login_tourist.php";
        }
    } catch (error) {
        console.error("Logout failed:", error);
    }
}