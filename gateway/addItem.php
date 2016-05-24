<?php
  include "../utils/functions.php";
  $db = new axis_functions();
  $item_code = $_POST['item_code'];
  $item_description = $_POST['item_description'];
  $unit = $_POST['unit'];
  $cost = $_POST['cost'];
  $srp = $_POST['srp'];
  $category = $_POST['category'];
  $newsup = $db->addNewItem($item_code,$item_description,$unit,$cost,$srp,$category);
  if($newsup>0){
    //success
    session_start();
    $db->addHistory("",$_SESSION['username']." added item id ".$item_code);
    header("location: ../pages/inventory.php?addItem=1");
  }else{
    header("location: ../pages/inventory.php?addItem=0");
  }
  
?>
