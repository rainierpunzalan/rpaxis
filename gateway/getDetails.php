<?php
  include "../utils/functions.php";
  $db = new axis_functions();
  if(isset($_GET['getSupplierAddress'])){
    echo json_encode($db->getSupplierDetails($_GET['sid']));
  }
  if(isset($_GET['getSupplierDetails'])){
    echo ($db->getSupplierListAngular($_GET['sid']));
  }
?>
