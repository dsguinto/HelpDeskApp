<?php

//Connects loginData.php to gather session data from login
require_once "library/loginData.php";

//VALID LOGIN CREDENTIALS
//Username: A_Martinez92, Password: Apples123 (Client)
//Username: owen_johnson, Password: Banana123 (Client)
//Username: jsmith01, Password: Orange123 (Admin)


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
        <a class="loginBtn" href="login.php">Log In</a>
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
                    <input id="loginBtn" type="submit" name="submitLogin" value="Login" />
                </div>
            </form>
        </div>
        <div class="signUp">
            <?= $signUpMsg ?>
            <h2 <?= $hide ?>>SIGN UP</h2>
            <form method="POST" action="" <?= $hide ?>>
                <div class="signUp__field">
                    <label for="title">Title: </label>
                    <select id="title" name="title">
                        <option value="volvo">Select a Title</option>
                        <option value="Mr">Mr.</option>
                        <option value="Mrs">Mrs.</option>
                        <option value="Ms">Ms.</option>
                        <option value="Mx">Mx.</option>
                    </select>
                    <span></span>
                </div>
                <div class="signUp__field">
                    <label for="firstname">First Name: </label>
                    <input type="text" name="firstname" id="firstname" />
                    <span></span>
                </div>
                <div class="signUp__field">
                    <label for="lastname">Last Name: </label>
                    <input type="text" name="lastname" id="lastname" />
                    <span></span>
                </div>
                <div class="signUp__field">
                    <label for="email">Email: </label>
                    <input type="text" name="email" id="email" />
                    <span></span>
                </div>
                <div class="signUp__field">
                    <label for="username">Username: </label>
                    <input type="text" name="username" id="username" />
                    <span></span>
                </div>
                <div class="signUp__field">
                    <label for="password">Password: </label>
                    <input type="password" name="password" id="password" />
                    <span></span>
                </div>
                <div class="signUp__field">
                    <label for="country">Country: </label>
                    <input type="text" name="country" id="country" />
                    <span></span>
                </div>
                <div class="signUp__field">
                    <label for="city">City: </label>
                    <input type="text" name="city" id="city" />
                    <span></span>
                </div>
                <p style="color:#ffcccb; text-align:center;"><?= $error2; ?></p>
                <div class="signUp__field">
                    <input id="signUpBtn" type="submit" name="submitSignUp" value="Sign Up" />
                </div>
            </form>
        </div>
    </body>
    <footer>
        <p> &#169; Daniel Guinto, 2021. All Rights Reserved - This is a fake webpage created for HTTP 5203. </p>
    </footer>
</html>