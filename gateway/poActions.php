<?php
  include "../utils/functions.php";
  $db = new axis_functions();

  if(isset($_GET['receiveItems'])){
  	//print_r($_POST['items']);
  	$itemDet = $db->getItemDetails($_POST['items'][2]['item_id']);
  	$category = $itemDet['category'];
  	$item_id = $_POST['items'][2]['item_id'];
  	$amount = $_POST['items'][4]['amount'];
  	$quantity = $_POST['items'][3]['quantity'];
  	$date_received = $_POST['date_received'];

  	$received = $db->receiveItem($item_id,$quantity,$amount,$category,$date_received);
  	 if($received>0){
	    //success
	    session_start();
	    $db->addHistory("",$_SESSION['username']." received item id ".$received);
	    $po_det = $db->getPODetails($_POST['items'][1]['po_id']);
	    switch($po_det[0]['status']){
	      case 1:
	      $db->changePOStatus($_POST['items'][1]['po_id'],3);
	      break;
	      case 2:
	      $db->changePOStatus($_POST['items'][1]['po_id'],4);
	      break;
	      default:
	      break;
	    }
	    echo "Success";
	    //header("location: ../pages/suppliers.php?addSupplier=1");
	  }else{
	    echo "Failed";
	    //header("location: ../pages/suppliers.php?addSupplier=0");
	  }
  
  }
?>
