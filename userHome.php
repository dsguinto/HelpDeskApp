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
$firstname = $_SESSION['firstname'];

//Connects userData.php for page functionality
require_once "library/userData.php";


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
            <h2>Welcome, <?= $firstname; ?>!</h2>
            <a class="logoutBtn" href="logout.php">Log Out</a>
        </div>
    </header>
    <body>
        <div class="page">
            <div class="ticketCreate">
                <h2>Submit a Ticket</h2>
                <form method="POST" action="">
                    <div class="ticketCreate__field">
                        <label for="subject">Subject: </label>
                        <input type="text" name="subject" id="subject" />
                    </div>
                    <div class="ticketCreate__field">
                        <label for="postMessage">Message: </label>
                        <textarea class="messageForm__text" type="textbox" name="postMessage" id="postMessage"></textarea>
                    </div>
                    <p style="color:red; text-align:center;"><?= $error; ?></p>
                    <div class="ticketCreate__field">
                        <input type="submit" class="inputBtn" name="submitTicket" value="Submit" />
                    </div>
                </form>
            </div>
            <div class="ticketList">
                <h2>Support Tickets</h2>
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
        </div>
    </body>
</html>