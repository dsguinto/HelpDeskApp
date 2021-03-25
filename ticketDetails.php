<?php

//Gets values from login data
require_once "library/loginData.php";
require_once "library/adminData.php";
require_once "library/userData.php";

//Starts session
session_start();

//Checks if user is logged in, if not redirects user to login page
if($_SESSION['status']!="Logged In")
{
    header("Location: ./login.php");
}

//Gets userID and firstname values stored in session from loginData file
$userId = $_SESSION['userId'];
$firstname = $_SESSION['firstname'];
$userType = $_SESSION['userType'];

//Checks to see if POST data exist (from initial 'View Ticket' button on list page)
//If POST data exist from that page, it will use the corresponding ticketId to access the respective ticket view
//If POST data does not exist (e.g destroyed from header redirect), it will recall the ticketId saved within the session (set during message submit)
if (empty($_POST)) {
    $ticketId = $_SESSION['post-data']['id'];
}
else{
    $ticketId = $_POST['id'];
}

//Sends user back to respective ticket list view dependant on user type
$backLocation = "";
$style = "";
if (($userType == "admin")){
    $backLocation = "adminHome.php";
}
else if (($userType == "client")){
    $style = "style='display:none;'";
    $backLocation = "userHome.php";
}

//Connects ticketDetailsData.php for functionality
require_once "library/ticketDetailsData.php";

?>

<!--WEBPAGE INTERFACE-->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>HelpDesk: Online Support Ticket System</title>
        <script src="https://kit.fontawesome.com/2956115494.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="css/global.css" />
        <link rel="stylesheet" href="css/ticketDetails.css" />
    </head>
    <header>
        <h1>HelpDesk<i class="fas fa-hands-helping"></i></h1>
        <div class="headerContent">
            <h2>Welcome, <?= $firstname; ?>!</h2>
            <a class="logoutBtn" href="logout.php">Log Out</a>
        </div>
    </header>
    <body>
        <div class="ticketDetails">
            <div class="ticketDetails__info">
                <div class="ticketDetails__info_text">
                    <h2>Ticket #<?= $ticketId ?> Details</h2>
                    <a class="backBtn" href="<?= $backLocation ?>"><i class="fas fa-caret-left"></i> Back to Tickets</a>
                    <p><b>Created On:</b> <?= $dateCreated ?></p>
                    <p><b>Subject:</b> <?= $subject ?></p>
                </div>
                <div class="ticketDetails__info_button" <?php echo $style;?>>
                    <form method="POST">
                        <div class=formSection  <?php echo $styleCloseBtn;?>>
                            <input type="hidden" name="id" value="<?= $ticketId ?>"/>
                            <input type="submit" class="closeBtn" name="closeTicket" value="Close Ticket" />
                        </div>
                        <div class=formSection  <?php echo $styleOpenBtn ;?>>
                            <input type="hidden" name="id" value="<?= $ticketId ?>"/>
                            <input type="submit" class="openBtn" name="openTicket" value="Re-Open Ticket" />
                        </div>
                    </form>
                </div>
            </div>
            <?php
                echo $rows;
            ?>
            <div class="ticketDetails__inputs">
                <div class="messageForm" <?php echo $styleDisplayForm;?> >
                    <form method="POST" action="">
                            <label for="postMessage">Post a message: </label>
                            <textarea class="messageForm__text" type="textbox" name="postMessage" id="postMessage" placeholder="Reply..."></textarea>
                            <div style="flex-basis: 100%;"><p style="color:red; text-align: center; margin: 1em auto;"><?= $error ?></p></div>
                            <input type="hidden" name="id" value="<?= $ticketId ?>"/>
                            <input type="submit" class="inputBtn" name="submitMessage" value="Send" />
                    </form>
                </div> 
                <div class="messageForm" <?php echo $styleDisplayMsg;?>>
                    <h3>This ticket has been closed</h3>
                </div> 
            </div>
        </div>
    </body>
</html>