<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Imperial+Script&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto+Flex:opsz,wght,XOPQ,XTRA,YOPQ,YTDE,YTFI,YTLC,YTUC@8..144,100..1000,96,468,79,-203,738,514,712&family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../asset/css/sign_up.css?v=<?php echo filemtime('../asset/css/sign_up.css'); ?>">
    <script src="../asset/js/sign_up.js?v=<?php echo filemtime('../asset/js/sign_up.js'); ?>" defer></script>

</head>
<body>
    <div class="main_container">
        <div class="form-box" id="sign_up">
            <form action="destinationURL">
                <h1>Sign up</h1>
                <div class="wrapper">
                    <div class="name_wrapper">
                        <input type="text" name="firstname" placeholder="First name" required></input>
                        <input type="text" name="lastname" placeholder="Last name" required></input>
                    </div>
                    <div class="email_wrapper">
                        <input class="inputs email" type="email" name="email" placeholder="Email" required></input>
                    </div>
                    <div class="password_wrapper">
                         <input class="inputs pass" id="password_input" type="password" name="password" placeholder="Password" required>
    
                        <button type="button" id="pass_visibility">
                            <img id="visibility_icon" src="../asset/img/pass_visibility.svg" alt="Toggle Password">
                        </button>
                    </div>
                    <div class="phone_number_wrapper">
                        <input id="phone_num" class="inputs num" type="number" name="number" placeholder="Phone Number">
                    </div>
                    <div class="sign_up_wrapper">
                        <button type="submit" id="submit_button" name="signup">Sign up</button>
                    </div>
                </div>
            </form>
        </div>  
    </div>

</body>
</html>