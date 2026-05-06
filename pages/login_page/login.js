document.addEventListener('DOMContentLoaded', () => {
    // --- 1. PASSWORD VISIBILITY TOGGLE ---
    const passwordInput = document.getElementById('passwordInput');
    const toggleButton = document.getElementById('pass_visibility');
    const iconImage = document.getElementById('visibility_icon');

    if (toggleButton) {
        toggleButton.addEventListener('click', () => {
            // I-check kung password o text ang kasalukuyang type
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                iconImage.src = '../../asset/img/pass_visibilityoff.svg'; // Palit sa "off" icon
            } else {
                passwordInput.type = 'password';
                iconImage.src = '../../asset/img/pass_visibility.svg'; // Balik sa "on" icon
            }
        });
    }

    // --- 2. LOGIN FORM SUBMISSION ---
    const loginForm = document.getElementById('LoginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const email = document.getElementById('emailInput').value;
            const password = document.getElementById('passwordInput').value;
            const alertBox = document.getElementById('login_message'); // Siguraduhing tugma ang ID sa HTML mo

            fetch('../../backend/mailer/api/login_api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email: email, password: password })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    setTimeout(() => {
                        if (data.role === 'tourist') {
                            window.location.href = '../../pages/landing_page/landing_page.php';
                        } else if (data.role === 'guide') {
                            window.location.href = '../../dashboard/tour_guide/index.php';
                        } else if (data.role === 'admin') {
                            window.location.href = '../../dashboard/admin/index.php';
                        }
                    }, 1000);
                } else {
                    // Magpapakita ng error message sa div
                    if (alertBox) {
                        alertBox.style.color = "red";
                        alertBox.innerText = data.message;
                    } else {
                        alert(data.message);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    }
});