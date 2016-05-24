<?php
  include "../utils/functions.php";
  $db = new axis_functions();
  $f = $_POST['first_name'];
  $m = $_POST['middle_name'];
  $l = $_POST['last_name'];
  $b = $_POST['bday'];
  $e = $_POST['emailadd'];
  $d = $_POST['date_hired'];
  $p = $_POST['position'];
  $s = $_POST['salary'];
  $c = $_POST['contract_type'];
  $newcust = $db->addNewEmployee($f,$m,$l,$b,$e,$d,$p,$s,$c);
  if($newcust>0){
    //success
    session_start();
    $db->addHistory("",$_SESSION['username']." added employee id ".$newcust);
    header("location: ../pages/employees.php?addEmployee=1");
  }else{
    header("location: ../pages/employees.php?addEmployee=0");
  }
  
?>
