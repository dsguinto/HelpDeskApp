<?php

//Gets values from login data
include "loginData.php";
include "adminData.php";

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

if (empty($_POST)) {
    $ticketId = $_SESSION['post-data']['id'];
}
else{
    $ticketId = $_POST['id'];
}

// var_dump($_SESSION['post-data']);
// var_dump($ticketId);

//Initialize variable
$backLocation = "";

if (($userType == "admin")){
    $backLocation = "adminHome";
}
else if (($userType == "client")){
    $backLocation = "userHome";
}

//Create DomDocument and loads xml file to be read
$doc = new DOMDocument();

//Formats new XML content
$doc->preserveWhiteSpace = false;
$doc->formatOutput = true;

//Loads XML file
$doc->load("xml/Assignment1_SupportTicket.xml");

//Sets variables
$error = "";
$postMessage = $doc->getElementsByTagName("postMessage");
$date = new DateTime("NOW", new DateTimeZone('America/Toronto'));

//Sets up XPath 
$xpath = new DOMXPath($doc);

//Uses XPath to check ticket elements that contain the same userId of the user that is logged in (used in foreach loop)
$ticketDetails = $xpath->query("//ticket[@ticketId='$ticketId']");

//Adds message to XML file when submitted
if (isset($_POST["submitMessage"])){
    $postMessage = $_POST["postMessage"];
    $_SESSION['post-data']['id'] = $_POST["id"];
    // $_SESSION['post-data'] = $_POST;

    // $_SESSION['post-data'] = $_POST;
    // echo $postMessage . "hey";
    // echo $id;
    // echo "</br>";
    // var_dump($_SESSION['post-data']);

    //Checks to see if input fields are empty. If empty, displays error message.
    if (empty($postMessage)){
        $error = "Please input a message.";
    }

    else{
        $error = "";
        
        //Creates new message element for respective ticket
        $newMessage = $doc->createElement("message");
        $newMessage->setAttribute("userId", $userId);

        $messageText = $doc->createElement("messageText", $postMessage);
        $messageDate = $doc->createElement("messageDate", $date->format('Y-m-d\TH:i:s'));

        //Append child elements to parent elements
        $newMessage->appendChild($messageText);
        $newMessage->appendChild($messageDate);

        //Loops through each ticket node and appends message to corresponding ticket node
        foreach ($ticketDetails as $ticket){
        $ticket->getElementsByTagName("messages")->item(0)->appendChild($newMessage);
        }

        //Saves updates to xml file
        $doc->save("xml/Assignment1_SupportTicket.xml");

        header( "Location: {$_SERVER['REQUEST_URI']}", true, 303 );
        exit();
    }
}

//More XPath queries
$messageDetails =  $xpath->query("//ticket[@ticketId='$ticketId']//message");

//Initialize variables
$dateCreated = "";
$rows = "";

//Loops through each ticket element in xml file and gets values to display
foreach ($ticketDetails as $ticket){
$id = $ticket->attributes->getNamedItem("ticketId")->nodeValue;
$subject = $ticket->getElementsByTagName("subject")->item(0)->nodeValue;
$date = new DateTime($ticket->getElementsByTagName("issueDate")->item(0)->nodeValue);
$dateCreated = date_format($date, 'Y-m-d,  H:i:s');
}

//Loops through message element for corresponding ticket to display message data
foreach ($messageDetails  as $ticket){
    $client = $ticket->attributes->getNamedItem("userId")->nodeValue;

    $messageText = $ticket->getElementsByTagName("messageText")->item(0)->nodeValue;
    $messageDate = new DateTime($ticket->getElementsByTagName("messageDate")->item(0)->nodeValue);
    $dateSent = date_format($messageDate , 'Y-m-d,  H:i:s');

    $rows .= '<div class="messageText">';
    $rows .= '<p class="userId">User ID: '  . $client . '</p>';
    $rows .= '<p class="messageBox">'  . $messageText . '</p>';
    $rows .= '<p class="dateSent">'  . $dateSent . '</p>';
    $rows .= '</div>';
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>HelpDesk: Online Support Ticket System</title>
        <script src="https://kit.fontawesome.com/2956115494.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="css/global.css" />
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
                <h2>Ticket #<?= $ticketId ?> Details</h2>
                <a class="backBtn" href="<?= $backLocation ?>.php"><i class="fas fa-caret-left"></i> Back to Tickets</a>
                <p><b>Created On:</b> <?= $dateCreated ?></p>
                <p><b>Subject:</b> <?= $subject ?></p>
            </div>
            <?php
                echo $rows;
            ?>
            <div class="ticketDetails__inputs">
                <form method="POST" action="">
                    <div class="messageForm">
                        <label for="postMessage">Post a message: </label>
                        <textarea class="messageForm__text" type="textbox" name="postMessage" id="postMessage" placeholder="Reply..."></textarea>
                        <div style="flex-basis: 100%;"><p style="color:red; text-align: center; margin: 1em auto;"><?= $error ?></p></div>
                        <input type="hidden" name="id" value="<?= $ticketId ?>"/>
                        <input type="submit" class="inputBtn" name="submitMessage" value="Send" />
                    </div> 
                </form>
                <!-- Look into implementing "Close Ticket" button that hides text box. -->
                <!-- <div class="close">
                    <a>Close Ticket</a>
                </div> -->
            </div>
        </div>
    </body>
</html>