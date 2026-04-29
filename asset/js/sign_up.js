document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('password_input');
    const toggleButton = document.getElementById('pass_visibility');
    const iconImage = document.getElementById('visibility_icon');

    toggleButton.addEventListener('click', () => {
        
        if (passwordInput.type === 'password') {
            
            passwordInput.type = 'text';
            
            iconImage.src = '../asset/img/pass_visibilityoff.svg'; 
            
        } else {
            
            passwordInput.type = 'password';
            
            iconImage.src = '../asset/img/pass_visibility.svg'; 
        }
    });
});