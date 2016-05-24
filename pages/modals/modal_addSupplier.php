
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Add new Supplier</h4>
  </div>
  <div class="modal-body">
    <form role="form" class="form ac-form" action="#" method="post">
        <label>Name *</label><input name="supplier_name" id="supplier_name" type="text" class="form-control" required/>
        <label>Address</label><textarea name="supplier_address" id="supplier_address" class="form-control" rows="3"></textarea>
        <label>Contact Number</label><input name="contact_number" id="contact_number" type="text" class="form-control" />
        <input type="hidden" name="addSupplierFlag" />
    </form>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-primary btn-addCust" >Add</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
  </div>
</div>

<script>
 $('.btn-addCust').click(function(){
    //$('.ac-form').submit();

    $.ajax({
      url: '../gateway/addSupplier.php',
      type: 'post',
      data : {
        supplier_name: $('#supplier_name').val(),
        supplier_address: $('#supplier_address').val(),
        contact_number: $('#contact_number').val()
      },
      success: function(data){
        $('.close').click();
      }
    });
 });
</script>