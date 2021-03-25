<?php

include "loginData.php";

session_start();
session_destroy();
header("Location: ./login.php");
?>