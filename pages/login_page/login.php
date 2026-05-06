<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Imperial+Script&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto+Flex:opsz,wght,XOPQ,XTRA,YOPQ,YTDE,YTFI,YTLC,YTUC@8..144,100..1000,96,468,79,-203,738,514,712&family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="login.css?v=<?php echo filemtime('login.css'); ?>">
    <script src="login.js?v=<?php echo filemtime('login.js'); ?>" defer></script>
</head>
<body>
    <div class="main_container">
        <div class="form-box">
            <form id="LoginForm">
                <h1>Login</h1>
                <div class="wrapper">
                    <div class="email_wrapper">
                        <input class="inputs email" type="email" id="emailInput" name="email" placeholder="Email" required>
                    </div>
                    <div class="password_wrapper">
                        <input class="inputs pass" type="password" id="passwordInput" name="password" placeholder="Password" required>
                        <button type="button" id="pass_visibility">
                            <img id="visibility_icon" src="../../asset/img/pass_visibility.svg">
                        </button>
                    </div>
                    <div class="login_wrapper">
                        <button type="submit" id="submit_button">Login</button>
                    </div>
                    <div id="login_message"></div>
                    <p class="login_footer">Don't have an account? <a href="../sign_up_page/sign_up.php">Sign up</a></p>
                </div>
            </form>
        </div>
        
    </body>
</html>