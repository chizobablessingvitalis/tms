<?php

session_start();
header("refresh:1; url=../login.php");

echo " <p>Signing out user account. Please wait for some seconds...</p> ";
session_destroy();
