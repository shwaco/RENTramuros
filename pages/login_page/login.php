<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login.css?v=<?php echo filemtime('login.css'); ?>">

    <script src="login.js?v=<?php echo filemtime('login.js'); ?>" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<body>
    <div class="main_container">
        <div class="form-box">
            <form id="LoginForm">
                <h1>Login</h1>
                <div class="wrapper">
                    <input class="inputs" type="email" id="emailInput" name="email" placeholder="Email" required>
                    <div class="password_wrapper">
                        <input class="inputs" type="password" id="passwordInput" name="password" placeholder="Password" required>
                        <button type="button" id="pass_visibility">
                            <img id="visibility_icon" src="../../asset/img/pass_visibility.svg">
                        </button>
                    </div>
                    <div class="sign_up_wrapper">
                        <button type="submit" id="submit_button">LOGIN</button>
                    </div>
                    <div id="alertbox"></div>
                    <p class="login_footer">Don't have an account? <a href="../sign_up_page/sign_up.php">Sign up</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>