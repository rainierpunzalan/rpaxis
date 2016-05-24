<?php
  include '../utils/functions.php';
  require_once '../inc/constants.inc';
  $db = new axis_functions();
  $ui = new ui_functions();
  $poDet = $db->getPODetails($_GET['po_no']);
  $poitems = $db->getPOItems($_GET['po_no']);
  // print_r($poDet);
  //print_r($poitems);
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <?php
    $ui->showHeadHTML();
  ?>
</head>

<body>
    
    <div id="wrapper">
        <div id="myModal" class="modal fade addSupplierModal" role="dialog">
          <div class="modal-dialog">

          </div>
        </div>
        <!-- Navigation -->
        
            <!-- /.navbar-top-links -->

              <?php $ui->showNav(); ?>

        <div id="page-wrapper">
            <div class="row">
                <?php $ui->showCompanyHeader(); ?>
            </div>

            
            <!-- /.row -->
            <div class="row">
                
                
                <form role="form" class="form ac-form" id="po-form" action="../gateway/addPO.php" method="post">
                  <input type="hidden" name="itemCount" id="itemCount" />
                  <div class="col-md-8">
                    <table class="po-table">
                      <tr>
                        <td style="width:10%;"><label>Payee</label></td>
                        <td><input name="address" id="address" type="text" class="form-underline" readonly value="<?php echo $db->getSupplierDetails($poDet[0]['payee'])['supplier_name']; ?>" /></td>
                      </tr>
                      <tr>
                        <td style="width:10%;"><label>Address</label></td>
                        <td><input name="address" id="address" type="text" class="form-underline" readonly value="<?php echo $db->getSupplierDetails($poDet[0]['payee'])['supplier_address']; ?>" /></td>
                      </tr>
                    </table>
                    
                    
                  </div>
                  <div class="col-md-4">

                    <table class="po-table">
                      <tr>
                        <td><label>PO No</label></td>
                        <td><input name="address" id="address" type="text" class="form-underline" readonly value="<?php echo $poDet[0]['po_no']; ?>" /></td>
                      </tr>
                      <tr>
                        <td><label>Date</label></td>
                        <td><input name="address" id="address" type="text" class="form-underline" readonly value="<?php echo $poDet[0]['po_date']; ?>" /></td>
                      </tr>
                    </table>
                  </div>
                  <div class="col-md-12">
                    <br />
                  </div>
                  <div class="col-md-12">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer lister-table" id="poItems" data-sortable>
                      <thead>
                        <th width="80%">Particulars</th>
                        <th>Cost</th>
                        <th>Amount</th>
                      </thead>
                      <tbody class="searchable">
                        <?php
                          for($i=0;$i<sizeOf($poitems);$i++){
                            print "<tr>
                                      <td>".$poitems[$i]['quantity'].$db->getItemDetails($poitems[$i]['item_id'])['unit']." - ".$db->getItemDetails($poitems[$i]['item_id'])['item_description']."(".$poitems[$i]['item_id'].")</td>
                                      <td>".$poitems[$i]['amount']."</td>
                                      <td>".number_format($poitems[$i]['quantity']*$poitems[$i]['amount'],2)."</td>
                                  </tr>";
                          }
                        ?>
                      </tbody>
                    </table>
                  </div>
                  <div class="col-md-12">
                    
                    <table class="po-table">
                      <tr>
                        <td style="width:15%;"><label>Term</label></td>
                        <?php
                          if($poDet[0]['terms']==1){
                            $trm = "CASH";
                            $dets = "";
                          }else{
                            $trm = "CHECK";
                            $dets = '
                                    <tr>
                                      <td style="width:10%;"><label>Bank Account</label></td>
                                      <td><input name="address" id="address" type="text" class="form-underline" readonly value="'. $poDet[0]['bank_account'].'" /></td>
                                    </tr>
                                    <tr>
                                      <td style="width:10%;"><label>Check No</label></td>
                                      <td><input name="address" id="address" type="text" class="form-underline" readonly value="'. $poDet[0]['check_no'].'" /></td>
                                    </tr>
                                    <tr>
                                      <td style="width:10%;"><label>Check Date</label></td>
                                      <td><input name="address" id="address" type="text" class="form-underline" readonly value="'. $poDet[0]['check_date'].'" /></td>
                                    </tr>
                                    ';
                          }
                        ?>
                        <td><input name="address" id="address" type="text" class="form-underline" readonly value="<?php echo $trm; ?>" /></td>
                      </tr>
                      <?php
                        print $dets;
                      ?>
                    </table>
                  </div>
                </form>
                <div class="col-md-12">
                  <br />
                  <?php
                    switch($poDet[0]['status']){
                      case '1':
                      print '<button id="receiveItems" class="form-control">Receive Items</button>
                              <button id="payVoucher" class="form-control">Pay Voucher</button>';
                      break;
                      case '2':
                      print '<button id="receiveItems" class="form-control">Receive Items</button>';
                      break;
                      case '3':
                      print '<button id="payVoucher" class="form-control">Pay Voucher</button>';
                      break;
                      default:
                      break;
                    }
                  ?>
                </div>
                <!-- /.col-lg-4 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    <script>
    $(document).ready(function () {
         
         $('#receiveItems').on("click",function(){
          
          var txt = 'Date: <input id="datepicker" name="date_picker" type="date" value="<?php echo date("Y-m-d"); ?>" />';
          var myPrompt = $.prompt(txt);
          myPrompt.on('impromptu:submit', function(e,v,m,f){
            var dateReceived = $('#datepicker').val();
            <?php
              for($i=0;$i<sizeOf($poitems);$i++){
            ?>
            if(dateReceived != null){
              $.ajax({
                url: '../gateway/poActions.php?receiveItems=1',
                type: 'post',
                data: {'items[]':<?php echo json_encode($poitems[$i]); ?>,
                        'date_received': dateReceived},
                success: function(data){
                  if(data == "success"){
                    location.reload();
                  }
                }
              });
            }
            
            <?php
              }
            ?>
          });
          
         });
        
      });
    </script>
</body>

</html>
