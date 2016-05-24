<?php
  include '../utils/functions.php';
  require_once '../inc/constants.inc';
  $db = new axis_functions();
  $ui = new ui_functions();
  $poList = $db->getPOList();
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
        <div id="myModal" class="modal fade addCustomerModal" role="dialog">
          <div class="modal-dialog">

          </div>
        </div>
          <?php $ui->showNav(); ?>
        

        <div id="page-wrapper">
            <div class="row">
                <?php $ui->showCompanyHeader(); ?>
            </div>

            
            <!-- /.row -->
            <div class="row">
                <div class="alert alert-danger fade in" style="display:none;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Error!</strong> <span class="error_msg"></span>
                  </div>
                <div class="col-lg-12">
                <div class="alert alert-success fade in" style="display:none;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Success!</strong> <span class="success_msg"></span>
                  </div>
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-users fa-fw"></i> Vouchers
                            <div class="pull-right">
                                <form class="form-inline" style="display:inline-flex !important;  margin-top: -5px !important;">
                                  <a href="purchaseOrder.php"><button type="button" class="add-toolbar"><i class="fa fa-plus"></i></button></a>
                                  <input id="filter" type="text" class="form-control"placeholder="Search... "  />
                                </form>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">

                          <table class="table table-striped table-bordered table-hover dataTable no-footer lister-table" data-sortable>
                            <thead>
                              <th>PO Number</th>
                              <th>Supplier</th>
                              <th>Amount</th>
                              <th>Due Date</th>
                              <th>Status</th>
                            </thead>
                            <tbody class="searchable">
                            <?php
                              for($i=0;$i<sizeof($poList);$i++){
                               

                                  switch ($poList[$i]['status']) {
                                    case '1':
                                      # code...
                                      $status = "Unpaid and not Received";
                                      break;
                                    case '2':
                                      # code...
                                      $status = "Paid but not yet Received";
                                      break;
                                    case '3':
                                      # code...
                                      $status = "Unpaid and Received";
                                      break;
                                    case '4':
                                      # code...
                                      $status = "Paid and Received";
                                      break;
                                    
                                    default:
                                      # code...
                                      break;
                                  }
                                  
                                print "
                                  <tr>
                                    <td>
                                        <div class='cust-table-name'><a href=poDetails.php?po_no=".$poList[$i]['po_no'].">".$poList[$i]['po_no']."</a></div>
                                    </td>
                                    <td>".$poList[$i]['supplier_name']."</td>
                                    <td>".$poList[$i]['total_amount']."</td>
                                    <td>".$poList[$i]['due_date']."</td>
                                    <td>".$status."</td>
                                  </tr>
                                ";
                              }
                            ?>
                            </tbody>
                          </table>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->

                </div>
                <!-- /.col-lg-4 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../assets/plugins/sbadmin/bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../assets/plugins/sbadmin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../assets/plugins/sbadmin/bower_components/metisMenu/dist/metisMenu.min.js"></script>


    <!-- Custom Theme JavaScript -->
    <script src="../assets/plugins/sbadmin/dist/js/sb-admin-2.js"></script>
    <script src="../assets/plugins/sortable/js/sortable.min.js"></script>
    <script src="../assets/scripts/script.js"></script>
    <script>
    $(document).ready(function () {
       
        (function ($) {

            $('#filter').keyup(function () {

                    var rex = new RegExp($(this).val(), 'i');
                    $('.searchable tr').hide();
                    $('.searchable tr').filter(function () {
                        return rex.test($(this).text());
                    }).show();

                })

        }(jQuery));

        $.get('modal_addEmployee.php', function(result) {
            $('.addCustomerModal').html(result);
        });

        
      });
    </script>
    <?php
      if(isset($_GET['addPO'])){
        if($_GET['addPO']=="0"){
          ?>
          <script>
           error_alert("Failed to create new PO!");
          </script>
          <?php
        }else if($_GET['addPO']=="1"){
          ?>
          <script>
           success_alert("Creating new PO successful!");
          </script>
          <?php
        }
      }
    ?>

</body>

</html>
