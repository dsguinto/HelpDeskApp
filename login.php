<?php

//Connects loginData.php to gather session data from login
require_once "library/loginData.php";


?>

<!--WEBPAGE INTERFACE-->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>HelpDesk: Online Support Ticket System</title>
        <script src="https://kit.fontawesome.com/2956115494.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="css/global.css" />
        <link rel="stylesheet" href="css/login.css" />
    </head>
    <header>
        <h1>HelpDesk<i class="fas fa-hands-helping"></i></h1>
        <a class="loginBtn" href="views/login.php">Log In</a>
    </header>
    <body>
        <div class="login">
            <h2>LOGIN</h2>
            <form method="POST" action="">
                <div class="login__field">
                    <label for="username">Username: </label>
                    <input type="text" name="username" id="username" />
                    <span></span>
                </div>
                <div class="login__field">
                    <label for="password">Password: </label>
                    <input type="password" name="password" id="password" />
                    <span></span>
                </div>
                <p style="color:red; text-align:center;"><?= $error; ?></p>
                <div class="login__field">
                    <input type="submit" name="submitLogin" value="Login" />
                </div>
            </form>
        </div>
    </body>
</html>