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

//Checks if login button is clicked
if (isset($_POST["submitLogin"])){
    //Saves POST data as variables
    $username = $_POST["username"];
    $password = $_POST["password"];

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

?>


