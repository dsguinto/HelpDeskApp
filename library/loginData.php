<?php

//Create DomDocument and loads xml file to be read
$doc = new DOMDocument();

//Loads XML file
$doc->load("xml/Assignment1_Users.xml");

//Gathers/Sets variables from XML elements
$users = $doc->getElementsByTagName("user");
$email = $doc->getElementsByTagName("email");
$username = $doc->getElementsByTagName("username");
$password = $doc->getElementsByTagName("password");

//Initialize variables
$userType = "";
$userId = "";
$xmlUsername = "";
$xmlPassword = "";
$firstname = "";
$error = "";
$error2 = "";


//Checks if login button is clicked
if (isset($_POST["submitLogin"])){
    //Saves POST data as variables
    $username = $_POST["username"];
    $password = md5($_POST["password"]);

    //Loops through each user element in XML file to check/gather necessary data
    foreach ($users as $user){
        $xmlUsername = $user->getElementsByTagName("username")->item(0)->nodeValue;
        $xmlPassword = $user->getElementsByTagName("password")->item(0)->nodeValue;

        $userId = $user->attributes->getNamedItem("userId")->nodeValue;
        $userType = $user->attributes->getNamedItem("userType")->nodeValue;

        $firstname = $user->getElementsByTagName("first")->item(0)->nodeValue;

        //Checks if login credentials are valid
        if (($username == $xmlUsername) && ($password == $xmlPassword)){

            //Starts session and saves necessary login data as session data
            session_start();
            $_SESSION['status']="Logged In";
            $_SESSION['userId'] = $userId;
            $_SESSION['firstname'] = $firstname;
            $_SESSION['userType'] = $userType;

            //Redirects login user to page depenedant on userType
            if (($userType == "admin")){
                exit(header("Location: ./adminHome.php"));
            }
            else if (($userType == "client")){
                exit(header("Location: ./userHome.php"));
            }
            break;
        }
        //Error message for invalid login credentials
        else{
            $error = "Invalid username and/or password";
        }
    }

}

$hide = "";
$signUpMsg = "";

    //Checks if Sign Up button is clicked
    if (isset($_POST["submitSignUp"])){
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
            $error2 = "Please input all information.";
        }

        else{
            //Create DomDocument
            $doc = new DOMDocument();

            //Formats new XML content
            $doc->preserveWhiteSpace = false;
            $doc->formatOutput = true;

            //Loads XML file
            $doc->load("xml/Assignment1_Users.xml");

            //Creates elements and their corresponding attribute (if applicable) for new ticket
            $newUser = $doc->createElement("user");
            $newUser->setAttribute("userType", "client");//Make into random number 
            $newUser->setAttribute("userId", rand(40000, 90000));//Make into random number 
            $newUser->setAttribute("title", $title);

            $newName = $doc->createElement("name");
            $newFirst = $doc->createElement("first", $firstname);
            $newLast = $doc->createElement("last", $lastname);

            $newEmail = $doc->createElement("email", $email);

            $newUsername = $doc->createElement("username", $username);
            $newPassword = $doc->createElement("password", $password);

            $newLocation = $doc->createElement("location");
            $newCountry = $doc->createElement("country", $country);
            $newCity = $doc->createElement("city", $city);

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

            $doc->documentElement->appendChild($newUser);

            //Saves updates to xml file
            $doc->save("xml/Assignment1_Users.xml");

            //Displays respective messages
            $hide = "style='display: none;'";
            $signUpMsg = "<h2 style='text-align: center'>Your sign up was successful! </br> Please proceed with signing in with your credentials.";

        }
    }
?>


