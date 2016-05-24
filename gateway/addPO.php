<?php
  include "../utils/functions.php";
  $db = new axis_functions();
  $acct = new accounting_functions();
  $itemCount = $_POST['itemCount'];
  $po_no = $_POST['po_no'];
  $po_date = $_POST['po_date'];
  $payee = $_POST['payee'];
  $totalamount = $_POST['totalamount'];
  $terms = $_POST['terms'];
  if($terms == 1){
    $bankAccount = "";
    $checkNo = "";
    $checkDate = "";
  }else{
    $bankAccount = $_POST['bank'];
    $checkNo = $_POST['check_no'];
    $checkDate = $_POST['check_date'];    
  }
  $balance = $_POST['totalamount'];
  $due_date = $_POST['due_date'];
  $newPO = $db->addPO($po_no,$po_date,$payee,$totalamount,$terms,$bankAccount,$checkNo,$checkDate,1,"",$balance,$due_date);
  print $newPO;
  if($newPO>0){
    //success
    $db->addPayable($po_no,$due_date,$totalamount);
    $acct->debit($totalamount,28,$po_date);
    $acct->credit($totalamount,1,$po_date);
    print $_POST['item_code'][0];
    print $itemCount;
    for($i=0;$i<$itemCount;$i++){
      $db->addPOItem($newPO,$_POST['item_code'][$i],$_POST['qty'][$i],$_POST['cost'][$i]);
    }

    session_start();
    $db->addHistory("",$_SESSION['username']." added PO id ".$newPO);
     header("location: ../pages/purchaseOrders.php?addPO=1");
  }else{
    header("location: ../pages/purchaseOrders.php?addPO=0");
  }
  
?>
