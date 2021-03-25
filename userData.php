<?php
//Create DomDocument and loads xml file to be read
$doc = new DOMDocument();

//Formats new XML content
$doc->preserveWhiteSpace = false;
$doc->formatOutput = true;

//Loads XML file
$doc->load("xml/Assignment1_SupportTicket.xml");

//Sets variables
$subject = $doc->getElementsByTagName("subject");
$postMessage = $doc->getElementsByTagName("postMessage");
$date = new DateTime("NOW", new DateTimeZone('America/Toronto'));
$error = "";


//Adds new ticket to xml file when submitted
if (isset($_POST["submitTicket"])){
    $subject = $_POST["subject"];
    $postMessage = $_POST["postMessage"];

    //Checks to see if input fields are empty. If empty, displays error message.
    if (empty($subject) || empty($postMessage)){
        $error = "Please input all information.";
    }

    else{
    $error = "";

    //Creates elements and their corresponding attribute (if applicable) for new ticket
    $newTicket = $doc->createElement("ticket");
    $newTicket->setAttribute("userId", $userId);
    $newTicket->setAttribute("ticketId", rand(300000, 400000));//Make into random number 
    $newTicket->setAttribute("status", "on-going");

    $subject = $doc->createElement("subject", $subject);
    $issueDate = $doc->createElement("issueDate", $date->format('Y-m-d\TH:i:s'));
    $messages = $doc->createElement("messages");

    $message = $doc->createElement("message");
    $message->setAttribute("userId", $userId);

    $messageText = $doc->createElement("messageText", $postMessage);
    $messageDate = $doc->createElement("messageDate", $date->format('Y-m-d\TH:i:s'));

    //Append child elements to parent elements
    $message->appendChild($messageText);
    $message->appendChild($messageDate);

    $messages->appendChild($message);

    $newTicket->appendChild($subject);
    $newTicket->appendChild($issueDate);
    $newTicket->appendChild($messages);

    $doc->documentElement->appendChild($newTicket);

    //Saves updates to xml file
    $doc->save("xml/Assignment1_SupportTicket.xml");

    header( "Location: {$_SERVER['REQUEST_URI']}", true, 303 );
    exit();
    }

}

//Sets up XPath 
$xpath = new DOMXPath($doc);

//Uses XPath to check ticket elements that contain the same userId of the user that is logged in (used in foreach loop)
$userTickets = $xpath->query("//ticket[@userId='$userId']");

//Initialize row variable
$rows = "";

//Loops through each ticket element that matches the logged in users userId (using XPath) and gets values to display
foreach ($userTickets as $ticket){
    $rows .= '<tr>';
    // $rows .= '<td>' . $ticket->attributes->getNamedItem("ticketId")->nodeValue . '</td>';
    $ticketId = $ticket->attributes->getNamedItem("ticketId")->nodeValue;
    $rows .= '<td>' . $ticketId  . '</td>';
    
    $rows .= '<td>' . $ticket->getElementsByTagName("subject")->item(0)->nodeValue . '</td>';
    
    //Gets issuedate value and converts it to DateTime data type and formats date for table
    $date = new DateTime($ticket->getElementsByTagName("issueDate")->item(0)->nodeValue);
    $rows .= '<td>' . date_format($date, 'Y-m-d,  H:i:s') . '</td>';

    $rows .= '<td>' . $ticket->attributes->getNamedItem("status")->nodeValue . '</td>';
    $rows .= '<td>' . $ticket->attributes->getNamedItem("userId")->nodeValue . '</td>';
    $rows .=    '<td>
                    <form action="./ticketDetails.php" method="POST">
                        <input type="hidden" name="id" value="' . $ticketId . '"/>
                        <input type="submit" class="ticketDetailsBtn" name="ticketDetails" value="View Ticket"/>
                    </form>
                </td>';
    $rows .= '</tr>';
}

?>