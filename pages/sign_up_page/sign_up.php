<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Imperial+Script&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto+Flex:opsz,wght,XOPQ,XTRA,YOPQ,YTDE,YTFI,YTLC,YTUC@8..144,100..1000,96,468,79,-203,738,514,712&family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="sign_up.css?v=<?php echo filemtime('sign_up.css'); ?>">
    <script src="sign_up.js?v=<?php echo filemtime('sign_up.js'); ?>" defer></script>

</head>
<body>
    <div class="main_container">
        <div class="form-box" id="sign_up">
            <form action="backend/api/signup_api.php" method="post" id="signup_form">
                <h1>Sign up</h1>
                <div class="wrapper">
                    <div class="name_wrapper">
                        <input type="text" name="first_name" placeholder="First name" class="inputs first_name" required></input>
                        <input type="text" name="last_name" placeholder="Last name" class="inputs last_name"required></input>
                    </div>
                    <div class="email_wrapper">
                        <input class="inputs email" type="email" name="email" placeholder="Email" required></input>
                    </div>
                    <div class="password_wrapper">
                        <input class="inputs pass" type="password" name="password_hash" placeholder="Password" id="password_input" required>
                        <button type="button" id="pass_visibility">
                            <img id="visibility_icon" src="../asset/img/pass_visibility.svg">
                        </button>
                    </div>
                    <div class="phone_number_wrapper">
                        <input id="phone_num" class="inputs num" type="number" name="phone_number" placeholder="Phone Number">
                    </div>
                    <div class="sign_up_wrapper">
                        <button type="submit" id="submit_button">Sign up</button>
                    </div>
                    <div id="signup_message"></div>
                </div>
            </form>
        </div>  

        <div class="form-box" id="otp_panel">
            <h1>Verify Email</h1>
            <div class="wrapper">
                <p class="otp_email_hint">
                    We sent a 6-digit code to<br>
                    <span id="otp_email_display"></span>
                </p>
                <div class="otp_inputs_wrapper">
                    <input class="otp_digit" type="text" inputmode="numeric" maxlength="1">
                    <input class="otp_digit" type="text" inputmode="numeric" maxlength="1">
                    <input class="otp_digit" type="text" inputmode="numeric" maxlength="1">
                    <input class="otp_digit" type="text" inputmode="numeric" maxlength="1">
                    <input class="otp_digit" type="text" inputmode="numeric" maxlength="1">
                    <input class="otp_digit" type="text" inputmode="numeric" maxlength="1">
                </div>
                <div id="otp_timer">Code expires in <b id="countdown">15:00</b></div>
                <div id="otp_message"></div>
                <div class="sign_up_wrapper">
                    <button type="button" id="verify_button">Verify</button>
                </div>
                <button id="resend_btn" disabled>Resend code (<span id="resend_countdown">60</span>s)</button>
            </div>
        </div>
    </div>

</body>
</html>