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
                    // Ipapakita yung green success banner mo
                    alert(data.message); 

                    // DITO KA NA-STUCK: Kailangan mong i-redirect ang user based sa role nila
                    setTimeout(() => {
                        if (data.role === 'tourist') {
                            // Palitan ito ng tamang path papunta sa tourist dashboard mo
                            window.location.href = '../../pages/landing_page/landing_page.php'; 
                        } 
                        else if (data.role === 'guide') {
                            window.location.href = '../../dashboard/tour_guide/index.php';
                        } 
                        else if (data.role === 'admin') {
                            window.location.href = '../../dashboard/admin/index.php';
                        }
                    }, 1000);
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