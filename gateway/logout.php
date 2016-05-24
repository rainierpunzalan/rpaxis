<?php

  session_start();
  include "../utils/functions.php";
  $db = new axis_functions();
  $db->addHistory("",$_SESSION['username'] . " Logged out");
$_SESSION = array();

header("location: ../pages/index.php");
?>