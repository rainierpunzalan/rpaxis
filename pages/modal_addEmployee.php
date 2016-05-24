<?php
include '../utils/functions.php';
  $db = new axis_functions();
?>
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Add new Employee</h4>
  </div>
  <div class="modal-body">
    <form role="form" class="form ac-form" action="../gateway/addEmployee.php" method="post">
        <label>First Name</label><input name="first_name" type="text" class="form-control" required/>
        <label>Middle Name</label><input name="middle_name" type="text" class="form-control" />
        <label>Last Name</label><input name="last_name" type="text" class="form-control" required/>
        <label>Birth date</label><input name="bday" type="date" class="form-control"/>
        <label>Email Address</label><input name="emailadd" type="email" class="form-control"/>
        <label>Date Hired</label><input name="date_hired" type="date" class="form-control"/>
        <label>Position</label><select name="position" class="form-control"><option>Select Position</option><?php echo $db->ddl_positions(); ?></select>
        <label>Salary</label><input name="salary" type="text" class="form-control"/>
        <label>Contract Type</label><select name="contract_type" class="form-control"><option>Select Contract Type</option><?php echo $db->ddl_contractType(); ?></select>
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