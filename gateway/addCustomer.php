<?php
  include "../utils/functions.php";
  $db = new axis_functions();
  $n = $_POST['customer_name'];
  $a = $_POST['customer_address'];
  $num = $_POST['contact_number'];
  $newcust = $db->addNewCustomer($n,$a,$num);
  if($newcust>0){
    //success
    session_start();
    $db->addHistory("",$_SESSION['username']." added customer id ".$newcust);
    header("location: ../pages/customers.php?addCustomer=1");
  }else{
    header("location: ../pages/customers.php?addCustomer=0");
  }
  
?>
