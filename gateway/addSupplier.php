<?php
  include "../utils/functions.php";
  $db = new axis_functions();
  $n = $_POST['supplier_name'];
  $a = $_POST['supplier_address'];
  $num = $_POST['contact_number'];
  $newsup = $db->addNewSupplier($n,$a,$num);
  if($newsup>0){
    //success
    session_start();
    $db->addHistory("",$_SESSION['username']." added supplier id ".$newsup);
    echo "Success";
    
    //header("location: ../pages/suppliers.php?addSupplier=1");
  }else{
    //header("location: ../pages/suppliers.php?addSupplier=0");
  }
  
?>
