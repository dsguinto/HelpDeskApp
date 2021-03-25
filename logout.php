<?php

//Connects loginData.php to gather session data from login
require_once "library/loginData.php";

//Destroys session data and redirects to login page
session_start();
session_destroy();
header("Location: ./login.php");

?>