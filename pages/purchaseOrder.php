<?php
  include '../utils/functions.php';
  require_once '../inc/constants.inc';
  $db = new axis_functions();
  $ui = new ui_functions();
  if(isset($_GET['customer'])){
    $cid = $_GET['customer'];
    $cust = $db->getCustomerDetails($cid);
  }
  if(isset($_POST['addSupplierFlag'])){
    $n = $_POST['supplier_name'];
    $a = $_POST['supplier_address'];
    $num = $_POST['contact_number'];
    $newsup = $db->addNewSupplier($n,$a,$num);
    if($newsup>0){
      //success
      session_start();
      $db->addHistory("",$_SESSION['username']." added supplier id ".$newsup);
      print "YES";
      //header("location: ../pages/suppliers.php?addSupplier=1");
    }else{
      //header("location: ../pages/suppliers.php?addSupplier=0");
      print "A";
    }
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
                  <div class="col-md-9">
                    <label>Payee</label>
                    <select  data-toggle="modal" data-target="#myModal" class="supplier selectpicker show-tick form-control" id="supplier_list" name="payee" data-live-search="true">
                      <option value="-1">Select</option>
                      <option value="-10">Add New Supplier</option>
                      <?php echo $db->ddl_suppliers(); ?>
                    </select>
                    <label>Address</label><input name="address" id="address" type="text" class="form-control" readonly />
                  </div>
                  <div class="col-md-3">
                    <label>PO No.</label><input name="po_no" type="text" class="form-control" value="<?php echo $db->getNextPO(); ?>" />
                    <label>Date</label><input name="po_date" type="date" class="form-control" value="<?php echo date("Y-m-d"); ?>" />
                  </div>
                  <div class="col-md-12">
                    <br />
                  </div>
                  <div class="col-md-12">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer lister-table" id="poItems" data-sortable>
                      <thead>
                        <th>Item Code</th>
                        <th>Item Description</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                        <th>Cost</th>
                        <th>Amount</th>
                      </thead>
                      <tbody class="searchable">
                        <?php for($i=0; $i<10; $i++){ ?>
                        <tr>
                          <td width="100px">
                            <select class="itemcodes selectpicker show-tick form-control" name="item_code[]" data-live-search="true">
                              <option>Select</option>
                              <?php echo $db->ddl_allItemCodes(); ?>
                            </select>
                          </td>
                          <td width="400px">
                            <select class="itemdescriptions selectpicker show-tick form-control" name="item_description[]" data-live-search="true">
                              <option>Select</option>
                              <?php echo $db->ddl_allItems(); ?>
                            </select>
                          </td>
                          <td width="50px">
                            <input name="unit[]" type="text" class="form-control" readonly/>
                          </td>
                          <td width="50px">
                            <input name="qty[]" type="number" class="quantities form-control" />
                          </td>
                          <td width="100px">
                            <input name="cost[]" type="number" class="costs form-control" />
                          </td>
                          <td width="100px">
                            <input name="amount[]" type="number" class="amounts form-control" readonly/>
                          </td>
                        </tr>
                        <?php }?>
                      </tbody>
                    </table>
                  </div>

                  <div class="col-md-12">
                    <div class="col-md-3">
                      <label>Terms</label>
                      <select class="terms selectpicker show-tick form-control" name="terms">
                        <option value="1">CASH</option>
                        <option value="2">CHECK</option>
                      </select>
                      <br />
                      <label>Bank Account</label>
                      <select class="bank form-control" name="bank" disabled>
                        <option>Select</option>
                        <?php echo $db->ddl_bankAccounts(); ?>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label>Check No</label><input name="check_no" type="text" class="check_no form-control" disabled/>
                      <label>Check Date</label><input name="check_date" type="date" class="check_date form-control" disabled/>
                    </div>
                    <div class="col-md-3">
                      <label>Total Amount</label><input name="totalamount" id="totalamount" type="text" class="totalamount form-control" readonly/>
                      <label>Due Date</label><input name="due_date" type="date" class="due_date form-control"/>
                    </div>
                    <div class="col-md-3">
                      <br /><br />
                      <input type="submit" value="Process PO" class="form-control" />
                    </div>
                  </div>
                </form>
                <!-- /.col-lg-4 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    <script>
    $(document).ready(function () {
         $('.itemcodes').selectpicker({
          liveSearch: true,
          maxOptions: 1
        });
         $('.itemcodes').on("change",function(){
          var itemid = $('.itemcodes option:selected').val();
          $('.itemdescriptions')[$(this).closest('.itemcodes').index('.itemcodes')].value = $(this).val(); 
          $('.itemdescriptions').selectpicker('refresh');
            //$('.itemdescriptions')[$(this).closest('.itemcodes').index('.itemcodes')].val = $(this).val();
         });
         $('.itemdescriptions').on("change",function(){
          var itemid = $('.itemdescriptions option:selected').val();
          $('.itemcodes')[$(this).closest('.itemdescriptions').index('.itemdescriptions')].value = $(this).val(); 
          $('.itemcodes').selectpicker('refresh');
         });
         $('.quantities').on("keyup",function(){
          var ind = $(this).closest('.quantities').index('.quantities');
          $('.amounts')[ind].value = ($(this).val() * $('.costs')[ind].value).toFixed(2); 
          totalamountcounter();
         });
         $('.costs').on("keyup",function(){
          var ind = $(this).closest('.costs').index('.costs');
          $('.amounts')[ind].value = ($(this).val() *  $('.quantities')[ind].value).toFixed(2); 
          totalamountcounter();
         });
         $('.terms').on("change",function(){
          if(this.value=="CASH"){
            $('.bank').attr("disabled",true);
            $('.check_no').attr("disabled",true);
            $('.check_date').attr("disabled",true);
          }else{
            $('.bank').attr("disabled",false);
            $('.check_no').attr("disabled",false);
            $('.check_date').attr("disabled",false);
          }
         });
         function countItems(){
          var count = 0;
          for(i=0;i<20;i++){
            if($('.itemcodes')[i].value!="Select")
              count++;
          }
            return count-10;
         };

         function totalamountcounter(){
          count = 0;
          for(i=0;i<10;i++){
            if($('.amounts')[i].value!=""){
              count = parseFloat(count) + parseFloat($('.amounts')[i].value);
            }
          }
          $('#totalamount').val(count);

         }

        $('#po-form').submit(function() {
          $('#itemCount').val(countItems);
          return true;
        });
        (function ($) {

            $('#filter').keyup(function () {

                var rex = new RegExp($(this).val(), 'i');
                $('.searchable tr').hide();
                $('.searchable tr').filter(function () {
                    return rex.test($(this).text());
                }).show();

            })

        }(jQuery));

        $('.supplier').on('changed.bs.select', function (e) {
          var suppid = $('.supplier option:selected').val();
          if(suppid==-10){
            $.get('modals/modal_addSupplier.php?po=1', function(result) {
              $('.addSupplierModal').html(result);
            });
            $('.addSupplierModal').modal({
                show: true
            });
             $('.supplier').selectpicker('refresh');
          }else{
            $.ajax({
            url:'../gateway/getDetails.php?getSupplierAddress=1&sid='+suppid,
            dataType: "json",
            success: function(data){
              $('#address').val(data.supplier_address);
            }
          });
          }
          
        });

        
      });

    /*function addNewPOItem(){

      var it = '<tr>'+
                                '<td width="100px">'+
                                  '<select class="selectpicker show-tick form-control" name="item_code[]" data-live-search="true">'+
                                  '<option>H</option>'+
                                  '</select>'+
                                '</td>'+
                                '<td width="400px">'+
                                 ' <input name="item_description[]" type="text" class="form-control" required/>'+
                                '</td>'+
                                '<td width="50px">'+
                                  '<input name="unit[]" type="text" class="form-control" required/>'+
                                '</td>'+
                                '<td width="50px">'+
                                  '<input name="qty[]" type="text" class="form-control" required/>'+
                                '</td>'+
                                '<td width="100px">'+
                                  '<input name="cost[]" type="text" class="form-control" required/>'+
                                '</td>'+
                                '<td width="100px">'+
                                  '<input name="amount[]" type="text" class="form-control" required/>'+
                                '</td>'+
                              '</tr>';
      $('#poItems').append(it);
    }*/
    </script>
</body>

</html>
