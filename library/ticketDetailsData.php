<?php

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

//Uses XPath to find the ticket element that matches the ticketId associated with the ticket wanting to be viewed (to be used in foreach loops to gather data within it)
$ticketDetails = $xpath->query("//ticket[@ticketId='$ticketId']");

//Adds message to XML file when submitted
if (isset($_POST["submitMessage"])){
    //Sets variables for POST data from form
    $postMessage = $_POST["postMessage"];
    
    //Saves current ticketId as a session data
    $_SESSION['post-data']['id'] = $_POST["id"];

    // ------------------------------- TO BE DELETED ------------------------------
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

        //Redirects user to respective page (in this situation, same page but updated)
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
    $user = $ticket->attributes->getNamedItem("userId")->nodeValue;
    $messageText = $ticket->getElementsByTagName("messageText")->item(0)->nodeValue;
    //Gets messageDate value and converts it to DateTime data type and formats date for table
    $messageDate = new DateTime($ticket->getElementsByTagName("messageDate")->item(0)->nodeValue);
    $dateSent = date_format($messageDate , 'Y-m-d,  H:i:s');
    $rows .= '<div class="messageText">';
    $rows .= '<p class="userId">User ID: '  . $user . '</p>';
    $rows .= '<p class="messageBox">'  . $messageText . '</p>';
    $rows .= '<p class="dateSent">'  . $dateSent . '</p>';
    $rows .= '</div>';
}

?>