<?php
  include '../utils/functions.php';
  require_once '../inc/constants.inc';
  $db = new axis_functions();
  $ui = new ui_functions();
  if(isset($_GET['customer'])){
    $cid = $_GET['customer'];
    $cust = $db->getCustomerDetails($cid);
  }
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
        <!-- Navigation -->
        
            <!-- /.navbar-top-links -->

              <?php $ui->showNav(); ?>

        <div id="page-wrapper">
            <!--div class="row">
                <?php $ui->showCompanyHeader(); ?>
            </div-->

            
            <!-- /.row -->
            <div class="row">
                
                <div class="col-lg-12">
                   
                   <h1><?php echo $cust['CUSTOMER_NAME']; ?></h1>
                   <hr />
                   
                   <b>Address: </b> <?php echo $cust['ADDRESS']; ?> <br />
                   <b>Contact Number: </b> <?php echo $cust['CONTACT_NUMBER']; ?> 
                   <hr />

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

        $.get('modals/modal_addCustomer.php', function(result) {
            $('.addCustomerModal').html(result);
        });

        
      });
    </script>
    <?php
      if(isset($_GET['addCustomer'])){
        if($_GET['addCustomer']=="0"){
          ?>
          <script>
           error_alert("Failed to create new Customer!");
          </script>
          <?php
        }else if($_GET['addCustomer']=="1"){
          ?>
          <script>
           success_alert("Creating new Customer successful!");
          </script>
          <?php
        }
      }
    ?>

</body>

</html>
