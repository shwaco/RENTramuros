document.addEventListener('DOMContentLoaded', () => {
    // #region agent log (debug-mode)
    console.log('[DEBUG 01db3a] sign_up.js loaded', { href: window.location.href, build: '2026-04-30T12:52+08' });
    fetch('http://127.0.0.1:7837/ingest/9eca1514-e8a6-44df-bee1-e70e11ae4dd2',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'01db3a'},keepalive:true,body:JSON.stringify({sessionId:'01db3a',runId:'pre-fix',hypothesisId:'H7',location:'asset/js/sign_up.js:loaded',message:'sign_up.js loaded',data:{href:window.location.href,build:'2026-04-30T12:52+08'},timestamp:Date.now()})}).catch(()=>{});
    // #endregion agent log (debug-mode)

    // ── Password visibility toggle ──────────────────────────────
    const passwordInput  = document.getElementById('password_input');
    const toggleButton   = document.getElementById('pass_visibility');
    const iconImage      = document.getElementById('visibility_icon');

    toggleButton.addEventListener('click', () => {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            iconImage.src = '../asset/img/pass_visibilityoff.svg';
        } else {
            passwordInput.type = 'password';
            iconImage.src = '../asset/img/pass_visibility.svg';
        }
    });

    // ── OTP digit box auto-advance ──────────────────────────────
    const otpDigits = document.querySelectorAll('.otp_digit');

    otpDigits.forEach((input, index) => {
        input.addEventListener('input', () => {
            // Allow only numbers
            input.value = input.value.replace(/\D/g, '');
            if (input.value && index < otpDigits.length - 1) {
                otpDigits[index + 1].focus();
            }
        });
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && index > 0) {
                otpDigits[index - 1].focus();
            }
        });
        // Allow paste into first box and distribute digits
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pasted = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
            pasted.split('').forEach((char, i) => {
                if (otpDigits[i]) otpDigits[i].value = char;
            });
            otpDigits[Math.min(pasted.length, 5)].focus();
        });
    });

    // ── State ───────────────────────────────────────────────────
    let userEmail      = '';
    let countdownTimer = null;
    let resendTimer    = null;

    // ── Signup form submit ──────────────────────────────────────
    const signupForm    = document.getElementById('signup_form');
    const submitBtn     = document.getElementById('submit_button');
    const signupMessage = document.getElementById('signup_message');

    signupForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        submitBtn.disabled    = true;
        submitBtn.textContent = 'Signing up...';
        signupMessage.textContent = '';

        const formData = new FormData(signupForm);

        try {
            const res  = await fetch('backend/api/signup_api.php', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();

            if (data.status === 'success') {
                userEmail = formData.get('email');
                showOtpPanel(userEmail);
            } else {
                signupMessage.style.color   = '#d32f2f';
                signupMessage.textContent   = data.message;
                submitBtn.disabled    = false;
                submitBtn.textContent = 'Sign up';
            }
        } catch (err) {
            signupMessage.style.color   = '#d32f2f';
            signupMessage.textContent   = 'Something went wrong. Please try again.';
            submitBtn.disabled    = false;
            submitBtn.textContent = 'Sign up';
        }
    });

    // ── Show OTP panel ──────────────────────────────────────────
    function showOtpPanel(email) {
        document.getElementById('sign_up').style.display      = 'none';
        document.getElementById('otp_email_display').textContent = email;

        const panel = document.getElementById('otp_panel');
        panel.style.display = 'block';
        // Trigger transition on next frame
        requestAnimationFrame(() => panel.classList.add('visible'));

        otpDigits[0].focus();
        startCountdown(15 * 60);   // 15 min expiry
        startResendCooldown(60);   // 60 sec before resend
    }

    // ── 15-min expiry countdown ─────────────────────────────────
    function startCountdown(seconds) {
        clearInterval(countdownTimer);
        const countdownEl = document.getElementById('countdown');
        countdownTimer = setInterval(() => {
            seconds--;
            const m = String(Math.floor(seconds / 60)).padStart(2, '0');
            const s = String(seconds % 60).padStart(2, '0');
            countdownEl.textContent = `${m}:${s}`;
            if (seconds <= 0) {
                clearInterval(countdownTimer);
                countdownEl.textContent = 'Expired';
                document.getElementById('verify_button').disabled = true;
            }
        }, 1000);
    }

    // ── 60-sec resend cooldown ──────────────────────────────────
function startResendCooldown(seconds) {
    clearInterval(resendTimer);
    const resendBtn = document.getElementById('resend_btn');
    
    // FIX: Re-inject the span into the button so it exists!
    resendBtn.innerHTML = `Resend code (<span id="resend_countdown">${seconds}</span>s)`;
    
    const resendCount = document.getElementById('resend_countdown');
    resendBtn.disabled = true;
    
    resendTimer = setInterval(() => {
        seconds--;
        resendCount.textContent = seconds;
        if (seconds <= 0) {
            clearInterval(resendTimer);
            resendBtn.disabled = false;
            // It's okay to destroy the span here, because we rebuild it above next time!
            resendBtn.textContent = 'Resend code'; 
        }
    }, 1000);
}

    // ── Resend OTP ──────────────────────────────────────────────
    document.getElementById('resend_btn').addEventListener('click', async () => {
        const msg = document.getElementById('otp_message');
        try {
            const res  = await fetch('backend/api/resend_otp.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email: userEmail })
            });
            const data = await res.json();
            msg.className     = data.status === 'success' ? 'success' : 'error';
            msg.textContent   = data.message;
            if (data.status === 'success') {
                startCountdown(15 * 60);
                startResendCooldown(60);
            }
        } catch {
            msg.className   = 'error';
            msg.textContent = 'Failed to resend. Try again.';
        }
    });

    // ── Verify OTP ──────────────────────────────────────────────
    document.getElementById('verify_button').addEventListener('click', async () => {
        const otp = Array.from(otpDigits).map(i => i.value).join('');
        const msg = document.getElementById('otp_message');
        const verifyBtn = document.getElementById('verify_button');

        if (otp.length < 6) {
            msg.className   = 'error';
            msg.textContent = 'Please enter the full 6-digit code.';
            return;
        }

        verifyBtn.disabled    = true;
        verifyBtn.textContent = 'Verifying...';
        msg.textContent       = '';

        try {
            const res  = await fetch('backend/api/verify_otp_api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email: userEmail, otp: otp })
            });
            const data = await res.json();

            if (data.status === 'success') {
                msg.className   = 'success';
                msg.textContent = '✓ Verified! Redirecting...';
                clearInterval(countdownTimer);
                // #region agent log (debug-mode)
                fetch('http://127.0.0.1:7837/ingest/9eca1514-e8a6-44df-bee1-e70e11ae4dd2',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'01db3a'},body:JSON.stringify({sessionId:'01db3a',runId:'pre-fix',hypothesisId:'H5',location:'asset/js/sign_up.js:otp-success',message:'OTP verify success branch taken',data:{page:window.location.href},timestamp:Date.now()})}).catch(()=>{});
                // #endregion agent log (debug-mode)
                setTimeout(() => {
                    // #region agent log (debug-mode)
                    fetch('http://127.0.0.1:7837/ingest/9eca1514-e8a6-44df-bee1-e70e11ae4dd2',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'01db3a'},body:JSON.stringify({sessionId:'01db3a',runId:'pre-fix',hypothesisId:'H1',location:'asset/js/sign_up.js:redirect',message:'OTP verified; about to redirect',data:{from:window.location.href,to:'log_in.php',resolvedTo:new URL('log_in.php',window.location.href).href},timestamp:Date.now()})}).catch(()=>{});
                    // #endregion agent log (debug-mode)
                    // #region agent log (debug-mode)
                    fetch('http://127.0.0.1:7837/ingest/9eca1514-e8a6-44df-bee1-e70e11ae4dd2',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'01db3a'},keepalive:true,body:JSON.stringify({sessionId:'01db3a',runId:'pre-fix',hypothesisId:'H5',location:'asset/js/sign_up.js:redirect-keepalive',message:'Redirect keepalive log (navigation-safe)',data:{from:window.location.href,to:'log_in.php'},timestamp:Date.now()})}).catch(()=>{});
                    // #endregion agent log (debug-mode)
                    // #region agent log (debug-mode)
                    fetch('http://127.0.0.1:7837/ingest/9eca1514-e8a6-44df-bee1-e70e11ae4dd2',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'01db3a'},keepalive:true,body:JSON.stringify({sessionId:'01db3a',runId:'post-fix',hypothesisId:'H1',location:'asset/js/sign_up.js:redirect-fixed',message:'Redirect target updated (fix for 404)',data:{from:window.location.href,to:'login.php',resolvedTo:new URL('login.php',window.location.href).href},timestamp:Date.now()})}).catch(()=>{});
                    // #endregion agent log (debug-mode)
                    window.location.href = 'login.php';
                }, 1500);
            } else {
                msg.className     = 'error';
                msg.textContent   = data.message;
                verifyBtn.disabled    = false;
                verifyBtn.textContent = 'Verify';
            }
        } catch {
            msg.className     = 'error';
            msg.textContent   = 'Something went wrong. Please try again.';
            verifyBtn.disabled    = false;
            verifyBtn.textContent = 'Verify';
        }
    });
});