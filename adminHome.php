<?php

//Connects loginData.php to gather session data from login
require_once "library/loginData.php";

//Starts session in order to grab/store session variables
session_start();

//Checks if user is logged in, if not redirects user to login page
if($_SESSION['status']!="Logged In")
{
    header("Location: ./login.php");
}

//Sets user credentials session variables from login data
$userId = $_SESSION['userId'];
$firstnameDisplay = $_SESSION['firstname'];

//Connects adminData.php for page functionality
require_once "library/adminData.php";

?>

<!--WEBPAGE INTERFACE-->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>HelpDesk: Online Support Ticket System</title>
        <script src="https://kit.fontawesome.com/2956115494.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="css/global.css" />
        <link rel="stylesheet" href="css/user_admin.css" />
    </head>
    <header>
        <h1>HelpDesk<i class="fas fa-hands-helping"></i></h1>
        <div class="headerContent">
            <h2>Welcome, <?= $firstnameDisplay; ?>!</h2>
            <a class="logoutBtn" href="logout.php">Log Out</a>
        </div>
    </header>
    <body>
        <div class="page">
        <div class="ticketList">
            <h2>Support Tickets - Admin Portal</h2>
            <table class="supportTickets">
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Subject</th>
                        <th>Creation Date</th>
                        <th>Status</th>
                        <th>Client ID</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        echo $rows;
                    ?>
                </tbody>
            </table>
        </div>
        <div class="signUp">
            <?= $signUpMsg ?>
            <h2 <?= $hide ?>>Create an Admin Account</h2>
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
                <p style="color:#ffcccb; text-align:center;"><?= $error; ?></p>
                <div class="signUp__field">
                    <input id="signUpBtn" type="submit" name="submitAdmin" value="Create Admin" />
                </div>
            </form>
        </div>
        <footer>
            <p> &#169; Daniel Guinto, 2021. All Rights Reserved - This is a fake webpage created for HTTP 5203. </p>
        </footer>
    </body>
</html>