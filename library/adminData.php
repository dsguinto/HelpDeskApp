<?php
//Create DomDocument and loads xml file to be read
$doc = new DOMDocument();

//Loads XML file
$doc->load("xml/Assignment1_SupportTicket.xml");

//Gathers/Sets variables from XML elements
$tickets = $doc->getElementsByTagName("ticket");

//Initialize variables
$ticketId = "";
$rows = "";

//Loops through each ticket element in xml file and gets values to display
foreach ($tickets as $ticket){
    $rows .= '<tr>';
    //Gets TicketId associated with each ticket
    $ticketId = $ticket->attributes->getNamedItem("ticketId")->nodeValue;
    $rows .= '<td>' . $ticketId  . '</td>';
    $rows .= '<td>' . $ticket->getElementsByTagName("subject")->item(0)->nodeValue . '</td>';
    //Gets issueDate value and converts it to DateTime data type and formats date for table
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