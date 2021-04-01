<?php
//Create DomDocument and loads xml file to be read
$ticketDoc = new DOMDocument();
$userDoc = new DOMDocument();

//Formats new XML content
$userDoc->preserveWhiteSpace = false;
$userDoc->formatOutput = true;

//Loads XML file
$ticketDoc->load("xml/Assignment1_SupportTicket.xml");
$userDoc->load("xml/Assignment1_Users.xml");

//Gathers/Sets variables from XML elements
$tickets = $ticketDoc->getElementsByTagName("ticket");

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

//Initialize variables
$error = "";
$hide = "";
$signUpMsg = "";

    //Checks if Create Admin button is clicked
    if (isset($_POST["submitAdmin"])){
        //Saves POST data as variables
        $title = $_POST['title'];
        $firstname = $_POST["firstname"];
        $lastname = $_POST["lastname"];
        $email = $_POST["email"];
        $username = $_POST["username"];
        $password = md5($_POST["password"]);
        $country = $_POST["country"];
        $city = $_POST["city"];

        //Checks for empty fields, returns error is missing data
        if (empty($firstname) || empty($lastname) || empty($email) || empty($username) || empty($password) || empty($country) || empty($city)){
            $error = "Please input all information.";
            $userId = $_SESSION['userId'];
            $firstnameDisplay = $_SESSION['firstname'];
        }

        else{ 
            //Keeps current login info displayed
            $userId = $_SESSION['userId'];
            $firstnameDisplay = $_SESSION["firstname"];

            //Creates elements and their corresponding attribute (if applicable) for new ticket
            $newUser = $userDoc->createElement("user");
            $newUser->setAttribute("userType", "admin");//Make into random number 
            $newUser->setAttribute("userId", rand(40000, 90000));//Make into random number 
            $newUser->setAttribute("title", $title);

            $newName = $userDoc->createElement("name");
            $newFirst = $userDoc->createElement("first", $firstname);
            $newLast = $userDoc->createElement("last", $lastname);

            $newEmail = $userDoc->createElement("email", $email);

            $newUsername = $userDoc->createElement("username", $username);
            $newPassword = $userDoc->createElement("password", $password);

            $newLocation = $userDoc->createElement("location");
            $newCountry = $userDoc->createElement("country", $country);
            $newCity = $userDoc->createElement("city", $city);

            //Append child elements to parent elements
            $newName->appendChild($newFirst);
            $newName->appendChild($newLast);

            $newLocation->appendChild($newCountry);
            $newLocation->appendChild($newCity);

            $newUser->appendChild($newName);
            $newUser->appendChild($newEmail);
            $newUser->appendChild($newUsername);
            $newUser->appendChild($newPassword);
            $newUser->appendChild($newLocation);

            $userDoc->documentElement->appendChild($newUser);

            //Saves updates to xml file
            $userDoc->save("xml/Assignment1_Users.xml");

            //Displays respective messages
            $hide = "style='display: none;'";
            $signUpMsg = "<h2 style='text-align: center'>The admin account was successfully made! </br> You may use the Login page to access the account.";

        }
    }
?>