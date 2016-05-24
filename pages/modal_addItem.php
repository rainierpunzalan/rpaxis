<?php
include '../utils/functions.php';
  $db = new axis_functions();
?>
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Add new Item</h4>
  </div>
  <div class="modal-body">
    <form role="form" class="form ac-form" action="../gateway/addItem.php" method="post">
        <label>Item Code</label><input name="item_code" type="text" class="form-control"/>
        <label>Item Description</label><textarea name="item_description" class="form-control" rows="3"></textarea>
        <label>Unit</label><input name="unit" type="text" class="form-control" />
        <label>Cost</label><input name="cost" type="text" class="form-control" />
        <label>SRP</label><input name="srp" type="text" class="form-control" />
        <label>Category</label><select name="category" class="form-control" data-live-search="true"><option>Select Category</option><?php echo $db->ddl_inventoryCategories(); ?></select>
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