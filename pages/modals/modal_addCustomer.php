
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Add new Customer</h4>
  </div>
  <div class="modal-body">
    <form role="form" class="form ac-form" action="../gateway/addCustomer.php" method="post">
        <label>Name *</label><input name="customer_name" type="text" class="form-control" required/>
        <label>Address</label><textarea name="customer_address" class="form-control" rows="3"></textarea>
        <label>Contact Number</label><input name="contact_number" type="text" class="form-control" />
    </form>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-primary btn-addCust" >Add</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
  </div>
</div>

<script>
$('.btn-addCust').click(function(){
            $('.ac-form').submit();
         });
</script>