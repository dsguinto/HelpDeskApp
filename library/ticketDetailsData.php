<?php

//Create DomDocument and loads xml file to be read
$ticketDoc = new DOMDocument();
$userDoc = new DOMDocument();

//Formats new XML content
$ticketDoc->preserveWhiteSpace = false;
$ticketDoc->formatOutput = true;

//Loads XML file
$ticketDoc->load("xml/Assignment1_SupportTicket.xml");
$userDoc->load("xml/Assignment1_Users.xml");

//Sets variables
$error = "";
$postMessage = $ticketDoc->getElementsByTagName("postMessage");
$date = new DateTime("NOW", new DateTimeZone('America/Toronto'));

//Sets up XPath 
$ticketXPath = new DOMXPath($ticketDoc);
$userXPath = new DOMXPath($userDoc);

//Uses XPath to find the ticket element that matches the ticketId associated with the ticket wanting to be viewed (to be used in foreach loops to gather data within it)
$ticketDetails = $ticketXPath->query("//ticket[@ticketId='$ticketId']");

//Adds message to XML file when submitted
if (isset($_POST["submitMessage"])){
    //Sets variables for POST data from form
    $postMessage = $_POST["postMessage"];
    
    //Saves current ticketId as a session data
    $_SESSION['post-data']['id'] = $_POST["id"];

    //Checks to see if input fields are empty. If empty, displays error message.
    if (empty($postMessage)){
        $error = "Please input a message.";
    }

    else{
        $error = "";
        
        //Creates new message element for respective ticket
        $newMessage = $ticketDoc->createElement("message");
        $newMessage->setAttribute("userId", $userId);

        $messageText = $ticketDoc->createElement("messageText", $postMessage);
        $messageDate = $ticketDoc->createElement("messageDate", $date->format('Y-m-d\TH:i:s'));

        //Append child elements to parent elements
        $newMessage->appendChild($messageText);
        $newMessage->appendChild($messageDate);

        //Loops through each ticket node and appends message to corresponding ticket node
        foreach ($ticketDetails as $ticket){
        $ticket->getElementsByTagName("messages")->item(0)->appendChild($newMessage);
        }

        //Saves updates to xml file
        $ticketDoc->save("xml/Assignment1_SupportTicket.xml");

        //Redirects user to respective page (in this situation, same page but updated)
        header( "Location: {$_SERVER['REQUEST_URI']}", true, 303 );
        exit();
    }
}

//XPath query to check message node of ticket with specified ticketId value
$messageDetails =  $ticketXPath->query("//ticket[@ticketId='$ticketId']//message");

//Initialize variables
$dateCreated = "";
$rows = "";

//Loops through each ticket element in xml file and gets values to display
foreach ($ticketDetails as $ticket){
$id = $ticket->attributes->getNamedItem("ticketId")->nodeValue;
$subject = $ticket->getElementsByTagName("subject")->item(0)->nodeValue;
$date = new DateTime($ticket->getElementsByTagName("issueDate")->item(0)->nodeValue);
$dateCreated = date_format($date, 'Y-m-d,  H:i:s');

$status = $ticket->attributes->getNamedItem("status")->nodeValue;
}

//Loops through message element for corresponding ticket to display message data
foreach ($messageDetails  as $ticket){
    $user = $ticket->attributes->getNamedItem("userId")->nodeValue;
    $userStr = strval($user); //Converts userID (positiveInteger data type) to string, to be used for IF statement below
    $messageText = $ticket->getElementsByTagName("messageText")->item(0)->nodeValue;

    //Gets messageDate value and converts it to DateTime data type and formats date for table
    $messageDate = new DateTime($ticket->getElementsByTagName("messageDate")->item(0)->nodeValue);
    $dateSent = date_format($messageDate , 'Y-m-d,  H:i:s');

    //XPath query to check User xml file to grab data to display
    $userDetails =  $userXPath->query("//user[@userId='$user']");

    //Checks data of user with specified userid to get title and last to display on messages
    foreach ($userDetails as $user){
        $title = $user->attributes->getNamedItem("title")->nodeValue;
        $userType = $user->attributes->getNamedItem("userType")->nodeValue;
        $lastname = $user->getElementsByTagName("last")->item(0)->nodeValue;
    }

    //strcmp Statement to check compare user ids, determines display over message bubble
    if ((strcmp($userStr, $_SESSION['userId'])) !== 0) {
        //Checks usertype to see if it should show "admin" in message
        if ($userType == "client"){
        $userDisplay = $title . ". " . $lastname . " says: ";
        }
        else{
            $userDisplay = $title . ". " . $lastname . " (Admin) says: ";
        }
    }
    else {
       $userDisplay = "You said:";
    }

    //Creates message boxes with all specified details within it
    $rows .= '<div class="messageText">';
    $rows .= '<p class="userId">'  . $userDisplay . '</p>';
    $rows .= '<p class="messageBox">'  . $messageText . '</p>';
    $rows .= '<p class="dateSent">'  . $dateSent . '</p>';
    $rows .= '</div>';
}
    
//If Close Ticket button is clicked, changes the status of the ticket to "Resolved" (Admin only)
if (isset($_POST["closeTicket"])){
     //Saves current ticketId as a session data
    $_SESSION['post-data']['id'] = $_POST["id"];

    //Loops through each ticket node and appends message to corresponding ticket node
    foreach ($ticketDetails as $ticket){
        $ticket->setAttribute('status', 'resolved');
    }

    //Saves updates to xml file
    $ticketDoc->save("xml/Assignment1_SupportTicket.xml");

    //Redirects user to respective page (in this situation, same page but updated)
    header( "Location: {$_SERVER['REQUEST_URI']}", true, 303 );
    exit();
}

//If Open Ticket button is clicked, changes the status of the ticket to "On-going" (Admin only)
if (isset($_POST["openTicket"])){
     //Saves current ticketId as a session data
    $_SESSION['post-data']['id'] = $_POST["id"];

    //Loops through each ticket node and appends message to corresponding ticket node
    foreach ($ticketDetails as $ticket){
        $ticket->setAttribute('status', 'on-going');
    }

    //Saves updates to xml file
    $ticketDoc->save("xml/Assignment1_SupportTicket.xml");

    //Redirects user to respective page (in this situation, same page but updated)
    header( "Location: {$_SERVER['REQUEST_URI']}", true, 303 );
    exit();
}

    //Initializes display of Close/Open ticket feature
    $styleCloseBtn = "style='display:none;'";
    $styleOpenBtn = "style='display:none;'";
    $styleDisplayForm = "style='display:none;'";
    $styleDisplayMsg = "style='display:none;'";

    //Checks status of ticket, displays corresponding button/message depending on status
    if ($status == "resolved"){
        $styleDisplayMsg = "style='display:block;'";
        $styleOpenBtn = "style='display:block;'";
    }
    else if ($status =="on-going"){
        $styleCloseBtn = "style='display:block;'";
        $styleDisplayForm = "style='display:block;'";
    }

?>