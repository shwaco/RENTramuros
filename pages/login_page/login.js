document.getElementById('LoginForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const email = document.getElementById('emailInput').value;
            const password = document.getElementById('passwordInput').value;
            const alertBox = document.getElementById('alertbox');

            fetch('../../backend/mailer/api/login_api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email: email, password: password })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alertBox.innerHTML = `<div class="alert alert-success" role="alert">${data.message}</div>`;
                        setTimeout(() => {
                            if (data.admin_id) {
                                window.location.href = '../admin_dashboard.html';
                            } else if (data.guide_id) {
                                window.location.href = '../../queue-management-system/index.php';
                            } else if (data.tourist_id) {
                                window.location.href = '../../landing_page.php';
                            }
                        }, 1500);
                } else if (data.status === 'unverified') {
                    alertBox.innerHTML = `<div class="alert alert-warning" role="alert">${data.message}</div>`;
                } else {
                    alertBox.innerHTML = `<div class="alert alert-danger" role="alert">${data.message}</div>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alertBox.innerHTML = `<div class="alert alert-danger" role="alert">Server error. Check console.</div>`;
            });
        });