<?php

$doc = new DOMDocument();
$doc->load("xml/Assignment1_Users.xml");


$users = $doc->getElementsByTagName("user");
$email = $doc->getElementsByTagName("email");
$username = $doc->getElementsByTagName("username");
$password = $doc->getElementsByTagName("password");

$userType = "";
$userId = "";
$xmlUsername = "";
$xmlPassword = "";
$firstname = "";
$error = "";

if (isset($_POST["submitLogin"])){
    $username = $_POST["username"];
    $password = $_POST["password"];

    foreach ($users as $user){
        $xmlUsername = $user->getElementsByTagName("username")->item(0)->nodeValue;
        $xmlPassword = $user->getElementsByTagName("password")->item(0)->nodeValue;

        $userId = $user->attributes->getNamedItem("userId")->nodeValue;
        $userType = $user->attributes->getNamedItem("userType")->nodeValue;

        $firstname = $user->getElementsByTagName("first")->item(0)->nodeValue;

        
        
        if (($username == $xmlUsername) && ($password == $xmlPassword)){
            echo "Login Success!";
            session_start();
            $_SESSION['status']="Logged In";
            $_SESSION['userId'] = $userId;
            $_SESSION['firstname'] = $firstname;
            $_SESSION['userType'] = $userType;


            if (($userType == "admin")){
                exit(header("Location: ./adminHome.php"));
            }
            else if (($userType == "client")){
                exit(header("Location: ./userHome.php"));
            }
            break;
        }
        else{
            $error = "Invalid username and/or password";
        }
    }

}

?>


