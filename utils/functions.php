<?php
require_once('../inc/dbinfo.inc');
require_once('../inc/constants.inc');
class accounting_functions{

  
  private function connect() {
    $link = mysqli_connect ( HOSTNAME, USERNAME, PASSWORD, DATABASE ) or die ( 'Could not connect: ' . mysqli_error () );
    mysqli_select_db ( $link, DATABASE ) or die ( 'Could not select database' . mysql_error () );
    return $link;
  }

  public function debit($amount,$segment,$transaction_date){
    if($segment == "1"){
      //assets
    }else if($segment == "2"){
      //liabilities
      $amount = $amount * -1;
    }else if($segment == "3"){
      //equity
    }
    $link = $this->connect();
    $query=sprintf("INSERT INTO balance_sheet(amount,acct_title,transaction_date)
                        VALUES('".mysqli_escape_string($link,$amount)."','".mysqli_escape_string($link,$segment)."','".mysqli_escape_string($link,$transaction_date)."')");
    $result = mysqli_query( $link, $query);
  }
  public function credit($amount,$segment,$transaction_date){
    if($segment == "1"){
      //assets
      $amount = $amount * -1;
    }else if($segment == "2"){
      //liabilities
    }else if($segment == "3"){
      //equity
      $amount = $amount * -1;
    }
    $link = $this->connect();
    $query=sprintf("INSERT INTO balance_sheet(amount,acct_title,transaction_date)
                        VALUES('".mysqli_escape_string($link,$amount)."','".mysqli_escape_string($link,$segment)."','".mysqli_escape_string($link,$transaction_date)."')");
    $result = mysqli_query( $link, $query);
  }

}
class axis_functions{

  private function connect() {
		$link = mysqli_connect ( HOSTNAME, USERNAME, PASSWORD, DATABASE ) or die ( 'Could not connect: ' . mysqli_error () );
		mysqli_select_db ( $link, DATABASE ) or die ( 'Could not select database' . mysql_error () );
		return $link;
	}

  public function getEmployeeDetails($id){
    $link = $this->connect();
    //$login = mysqli_real_escape_string($link,$username);
    $query = "SELECT e.id,
                      e.first_name,
                      e.middle_name,
                      e.last_name,
                      e.bday,
                      e.email_address,
                      e.date_hired,
                      ep.position,
                      e.salary,
                      c.type
              FROM employees e
              INNER JOIN employee_positions ep
              ON ep.id = e.position
              INNER JOIN contract_type c
              ON c.id = e.contract_type
              WHERE e.id = '".$id."'
              ORDER BY e.last_name";
    $result = mysqli_query ( $link, $query );
    $data = array();
    while($row =mysqli_fetch_assoc($result))
    {
        $data[] = $row;
    }
    return $data;
  }

  public function getEmployeeList(){
    $link = $this->connect();
    //$login = mysqli_real_escape_string($link,$username);
    $query = "SELECT e.id,
                      e.first_name,
                      e.middle_name,
                      e.last_name,
                      e.bday,
                      e.email_address,
                      e.date_hired,
                      ep.position,
                      e.salary,
                      c.type
              FROM employees e
              INNER JOIN employee_positions ep
              ON ep.id = e.position
              INNER JOIN contract_type c
              ON c.id = e.contract_type
              ORDER BY e.last_name";
    $result = mysqli_query ( $link, $query );
    $data = array();
    while($row =mysqli_fetch_assoc($result))
    {
        $data[] = $row;
    }
    return $data;
  }

  public function getNextPO(){
    $link = $this->connect();
    //$login = mysqli_real_escape_string($link,$username);
    $query = "SELECT max(po_no) as 'next'
              FROM purchase_order LIMIT 1";
    $result = mysqli_query ( $link, $query );
    while($row =mysqli_fetch_assoc($result))
    {
        return $row['next']+1;
    }
  }
  public function getPODetails($po_no){
    $link = $this->connect();
    $po_no = mysqli_real_escape_string($link,$po_no);
    $query = "SELECT *
              FROM purchase_order
              WHERE po_no = '".$po_no."' LIMIT 1";
    $result = mysqli_query ( $link, $query );
    $data = array();
    while($row =mysqli_fetch_assoc($result))
    {
        $data[] = $row;
    }
    return $data;
  }
  public function getPOItems($po_no){
    $link = $this->connect();
    $po_no = mysqli_real_escape_string($link,$po_no);
    $query = "SELECT *
              FROM po_item 
              WHERE po_id = '".$po_no."'";
    $result = mysqli_query ( $link, $query );
    $data = array();
    while($row =mysqli_fetch_assoc($result))
    {
        $data[] = $row;
    }
    return $data;
  }
  public function getVoucherList(){
    $link = $this->connect();
    //$login = mysqli_real_escape_string($link,$username);
    $query = "SELECT v.voucher_no,
                    v.voucher_date,
                    s.supplier_name,
                    v.total_amount,
                    v.status,
                    v.due_date
              FROM voucher v
              INNER JOIN suppliers s
              ON s.supplier_id = v.payee
              GROUP BY v.voucher_no
              ORDER BY v.voucher_date";
    $result = mysqli_query ( $link, $query );
    $data = array();
    while($row =mysqli_fetch_assoc($result))
    {
        $data[] = $row;
    }
    return $data;
  }
  public function getPOList(){
    $link = $this->connect();
    //$login = mysqli_real_escape_string($link,$username);
    $query = "SELECT po.po_no,
                    s.supplier_name,
                    po.total_amount,
                    po.status,
                    po.due_date
              FROM purchase_order po
              INNER JOIN po_item pi
              ON pi.po_id = po.po_no
              INNER JOIN suppliers s
              ON s.supplier_id = po.payee
              GROUP BY po.po_no
              ORDER BY po.po_date";
    $result = mysqli_query ( $link, $query );
    $data = array();
    while($row =mysqli_fetch_assoc($result))
    {
        $data[] = $row;
    }
    return $data;
  }

  public function getInventoryList(){
    $link = $this->connect();
    //$login = mysqli_real_escape_string($link,$username);
    $query = "SELECT i.item_description AS 'desc',
                     SUM(inv.quantity) AS 'qty',
                     i.srp AS 'srp'
              FROM inventory inv
              INNER JOIN item i
              ON i.item_id = inv.item_id
              INNER JOIN inventory_category c
              ON c.id = inv.inventory_category
              GROUP BY inv.item_id
              ORDER BY i.item_description";
    $result = mysqli_query ( $link, $query );
    $data = array();
    while($row =mysqli_fetch_assoc($result))
    {
        $data[] = $row;
    }
    return $data;
  }

  public function getCustomerList(){
    $link = $this->connect();
    //$login = mysqli_real_escape_string($link,$username);
    $query = "SELECT * FROM customers ORDER BY customer_name";
    $result = mysqli_query ( $link, $query );
    $data = array();
    while($row =mysqli_fetch_assoc($result))
    {
        $data[] = $row;
    }
    return $data;
  }

  public function getSupplierList(){
    $link = $this->connect();
    //$login = mysqli_real_escape_string($link,$username);
    $query = "SELECT * FROM suppliers ORDER BY supplier_name";
    $result = mysqli_query ( $link, $query );
    $data = array();
    while($row =mysqli_fetch_assoc($result))
    {
        $data[] = $row;
    }
    return $data;
  }

  public function getSupplierListAngular(){
    $link = $this->connect();
    //$login = mysqli_real_escape_string($link,$username);
    $query = "SELECT * FROM suppliers ORDER BY supplier_name";
    $result = mysqli_query ( $link, $query );

    $outp = "";
    while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
        if ($outp != "") {$outp .= ",";}
        $outp .= '{"supplier_id":"'  . $rs["SUPPLIER_ID"] . '",';
        $outp .= '"supplier_name":"'   . $rs["SUPPLIER_NAME"]        . '",';
        $outp .= '"supplier_address":"'   . $rs["SUPPLIER_ADDRESS"]        . '",';
        $outp .= '"contact_number":"'. $rs["CONTACT_NUMBER"]     . '"}'; 
    }
    $outp ='{"records":['.$outp.']}';
    return $outp;
  }

  public function changePOStatus($po_no,$status){
    $link = $this->connect();
    $query = "UPDATE purchase_order
              SET status = '".mysqli_escape_string($link,$status)."'
              WHERE po_no = '".mysqli_escape_string($link,$po_no)."'";
    if ($result = mysqli_query( $link, $query )) {
      return 1;
    }else {
      return 0;
    }
    
  }
  public function addPO($po_no,$po_date,$payee,$total_amount,$terms,$bank_account,$check_no,$check_date,$status,$or_no,$balance,$due_date){
    $link = $this->connect();
    $query=sprintf("INSERT INTO purchase_order(po_no,po_date,payee,total_amount,terms,bank_account,check_no,check_date,status,or_no,balance,due_date)
                        VALUES('".mysqli_escape_string($link,$po_no)."','".mysqli_escape_string($link,$po_date)."','".mysqli_escape_string($link,$payee)."','".mysqli_escape_string($link,$total_amount)."','".mysqli_escape_string($link,$terms)."','".mysqli_escape_string($link,$bank_account)."','".mysqli_escape_string($link,$check_no)."','".mysqli_escape_string($link,$check_date)."','".mysqli_escape_string($link,$status)."','".mysqli_escape_string($link,$or_no)."','".mysqli_escape_string($link,$balance)."','".mysqli_escape_string($link,$due_date)."')");
    if ($result = mysqli_query( $link, $query )) {
      return $po_no;
    }else {
      return 0;
    }
    
  }

  public function addPOItem($po_id,$item_id,$quantity,$amount){
    $link = $this->connect();
    $query=sprintf("INSERT INTO po_item(po_id,item_id,quantity,amount)
                        VALUES('".mysqli_escape_string($link,$po_id)."','".mysqli_escape_string($link,$item_id)."','".mysqli_escape_string($link,$quantity)."','".mysqli_escape_string($link,$amount)."')");
    if ($result = mysqli_query( $link, $query )) {
      return $po_id;
    }else {
      return 0;
    }
    
  }
  public function addPayable($voucher_id,$due_date,$amount){
    $link = $this->connect();
    $query=sprintf("INSERT INTO payables(voucher_id,due_date,amount)
                        VALUES('".mysqli_escape_string($link,$voucher_id)."','".mysqli_escape_string($link,$due_date)."','".mysqli_escape_string($link,$amount)."')");
    if ($result = mysqli_query( $link, $query )) {
      return $link->insert_id;
    }else {
      return 0;
    }
    
  }
  public function receiveItem($item_id,$quantity,$amount,$inventory_category,$date_received){
    $link = $this->connect();
    $query=sprintf("INSERT INTO inventory(item_id,quantity,date_received,inventory_category,amount)
                        VALUES('".mysqli_escape_string($link,$item_id)."','".mysqli_escape_string($link,$quantity)."','".mysqli_escape_string($link,$date_received)."','".mysqli_escape_string($link,$inventory_category)."','".mysqli_escape_string($link,$amount)."')");
    if ($result = mysqli_query( $link, $query )) {
      return $link->insert_id;
    }else {
      return 0;
    }
    
  }
  public function addNewItem($item_code,$item_description,$unit,$cost,$srp,$category){
    $link = $this->connect();
    $query=sprintf("INSERT INTO item(item_id,item_description,unit,cost,srp,category)
                        VALUES('".mysqli_escape_string($link,$item_code)."','".mysqli_escape_string($link,$item_description)."','".mysqli_escape_string($link,$unit)."','".mysqli_escape_string($link,$cost)."','".mysqli_escape_string($link,$srp)."','".mysqli_escape_string($link,$category)."')");
    if ($result = mysqli_query( $link, $query )) {
      return $link->insert_id;
    }else {
      return 0;
    }
    
  }
  public function addNewCustomer($customer_name,$address,$contact_number){
    $link = $this->connect();
    $query=sprintf("INSERT INTO customers(customer_name,contact_number,address)
                        VALUES('".mysqli_escape_string($link,$customer_name)."','".mysqli_escape_string($link,$contact_number)."','".mysqli_escape_string($link,$address)."')");
    if ($result = mysqli_query( $link, $query )) {
      return $link->insert_id;
    }else {
      return 0;
    }
    
  }
  public function addNewSupplier($supplier_name,$address,$contact_number){
    $link = $this->connect();
    $query=sprintf("INSERT INTO suppliers(supplier_name,supplier_address,contact_number)
                        VALUES('".mysqli_escape_string($link,$supplier_name)."','".mysqli_escape_string($link,$address)."','".mysqli_escape_string($link,$contact_number)."')");
    if ($result = mysqli_query( $link, $query )) {

      return $link->insert_id;
    }else {
      return 0;
    }
    
  }
  public function addNewEmployee($f,$m,$l,$b,$e,$d,$p,$s,$c){
    $link = $this->connect();
    $query=sprintf("INSERT INTO employees(first_name,middle_name,last_name,bday,email_address,date_hired,position,salary,contract_type)
                        VALUES('".mysqli_escape_string($link,$f)."','".mysqli_escape_string($link,$m)."','".mysqli_escape_string($link,$l)."','".mysqli_escape_string($link,$b)."','".mysqli_escape_string($link,$e)."','".mysqli_escape_string($link,$d)."','".mysqli_escape_string($link,$p)."','".mysqli_escape_string($link,$s)."','".mysqli_escape_string($link,$c)."')");
    if ($result = mysqli_query( $link, $query )) {
      //return $link->insert_id;
    }else {
      echo $query;
      //return 0;
    }
  }
  public function getItemDetails($itemId){
    $link = $this->connect();
    $itemId = mysqli_real_escape_string($link,$itemId);
    $query = "SELECT * FROM item where item_id = '".$itemId."'";
    $result = mysqli_query ( $link, $query );
    while($row =mysqli_fetch_assoc($result))
    {
      $arr = array(
        "item_description" => $row['item_description'],
        "unit"=> $row['unit'],
        "cost"=>  $row['cost'],
        "srp"=>  $row['srp'],
        "category"=>  $row['category']
      );
    }
    return $arr;
  }
  public function getCustomerDetails($customerId){
    $link = $this->connect();
    $customer_id = mysqli_real_escape_string($link,$customerId);
    $query = "SELECT * FROM customers where customer_id = '".$customer_id."'";
    $result = mysqli_query ( $link, $query );
    while($row =mysqli_fetch_assoc($result))
    {
      return $row;
    }
  }
  public function getSupplierDetails($sid){
    $link = $this->connect();
    $sid = mysqli_real_escape_string($link,$sid);
    $query = "SELECT * FROM suppliers where supplier_id = '".$sid."' LIMIT 1";
    $result = mysqli_query ( $link, $query );

    while($row =mysqli_fetch_assoc($result))
    {
      $arr = array(
        "supplier_id" => $row['SUPPLIER_ID'],
        "supplier_name"=> $row['SUPPLIER_NAME'],
        "supplier_address"=>  $row['SUPPLIER_ADDRESS'],
        "contact_number"=>  $row['CONTACT_NUMBER']
      );
    }
    return $arr;
  }

  public function getSupplierPendingVoucherCount($supplier){
    $link = $this->connect();
    $supplier = mysqli_real_escape_string($link,$supplier);
    $query = "SELECT COUNT(total_amount) FROM voucher where payee = '".$supplier."'";
    $result = mysqli_query ( $link, $query );
    while($row =mysqli_fetch_row($result))
    {
      if($row[0]==0 || $row[0]==""){
        return "-";
      }
      return $row[0];
    }
  }
  public function getSupplierPendingVoucherAmount($supplier){
    $link = $this->connect();
    $supplier = mysqli_real_escape_string($link,$supplier);
    $query = "SELECT SUM(total_amount) FROM voucher where payee = '".$supplier."'";
    $result = mysqli_query ( $link, $query );
    while($row =mysqli_fetch_row($result))
    {
      if($row[0]==0 || $row[0]==""){
        return "-";
      }
      return $row[0];
    }
  }


  public function getCustomerPendingInvoicesCount($customerId){
    $link = $this->connect();
    $customer_id = mysqli_real_escape_string($link,$customerId);
    $query = "SELECT COUNT(total_amount) FROM sales_invoice where customer_id = '".$customer_id."'";
    $result = mysqli_query ( $link, $query );
    while($row =mysqli_fetch_row($result))
    {
      if($row[0]==0 || $row[0]==""){
        return "-";
      }
      return $row[0];
    }
  }
  public function getCustomerPendingInvoicesAmount($customerId){
    $link = $this->connect();
    $customer_id = mysqli_real_escape_string($link,$customerId);
    $query = "SELECT SUM(total_amount) FROM sales_invoice where customer_id = '".$customer_id."'";
		$result = mysqli_query ( $link, $query );
    while($row =mysqli_fetch_row($result))
    {
      if($row[0]==0 || $row[0]==""){
        return "-";
      }
      return $row[0];
    }
  }

  public function login($username,$password){
    $link = $this->connect();
    $login = mysqli_real_escape_string($link,$username);
    $pword = mysqli_real_escape_string($link,$password);
    $query = "SELECT * FROM user_accounts WHERE username = '$login' AND password = '".md5($pword)."' LIMIT 1";
		$result = mysqli_query ( $link, $query );
	print $query;
    if(mysqli_num_rows($result) == 1){
      while ( $row = mysqli_fetch_row ( $result ) ) {
        $lid = $row[0];

        session_start();
        session_regenerate_id(TRUE);
  			$_SESSION['userid'] = $row[0];
    		$_SESSION['username'] = $row[1];
        $_SESSION['access_level'] = $row[3];
  		}
     
      return true;
    }else{
      return false;
    }
  }

  public function addHistory($reference_id,$remarks_id){
    $link = $this->connect();
    $query=sprintf("INSERT INTO history(user_id,reference_id,remarks_id)
                        VALUES('".mysqli_real_escape_string($link,$_SESSION['userid'])."','".mysqli_real_escape_string($link,$reference_id)."','".mysqli_real_escape_string($link,$remarks_id)."')");
    $result = mysqli_query( $link, $query);
    
  }
  public function ddl_bankAccounts(){
    $link = $this->connect();
    $query = "SELECT account_no,account_name FROM bank_accounts ORDER BY account_name";
    $result = mysqli_query ( $link, $query );
    $ret = "";
    while($row =mysqli_fetch_row($result))
    {
      $ret = $ret. "<option value='".$row[0]."'>".$row[1]."</option>";
    }
    return $ret;
  }
  public function ddl_banklist(){
    $link = $this->connect();
    $query = "SELECT bank_no,bank_name FROM banklist ORDER BY bank_name";
    $result = mysqli_query ( $link, $query );
    $ret = "";
    while($row =mysqli_fetch_row($result))
    {
      $ret = $ret. "<option value='".$row[0]."'>".$row[1]."</option>";
    }
    return $ret;
  }
  public function ddl_allItems(){
    $link = $this->connect();
    $query = "SELECT item_id,item_description FROM item ORDER BY item_description";
    $result = mysqli_query ( $link, $query );
    $ret = "";
    while($row =mysqli_fetch_row($result))
    {
      $ret = $ret. "<option value='".$row[0]."'>".$row[1]."</option>";
    }
    return $ret;
  }
  public function ddl_allItemCodes(){
    $link = $this->connect();
    $query = "SELECT item_id FROM item ORDER BY item_id";
    $result = mysqli_query ( $link, $query );
    $ret = "";
    while($row =mysqli_fetch_row($result))
    {
      $ret = $ret. "<option value='".$row[0]."'>".$row[0]."</option>";
    }
    return $ret;
  }
  public function ddl_inventoryCategories(){
    $link = $this->connect();
    $query = "SELECT id,inventory_category FROM inventory_category ORDER BY inventory_category";
    $result = mysqli_query ( $link, $query );
    $ret = "";
    while($row =mysqli_fetch_row($result))
    {
      $ret = $ret. "<option value='".$row[0]."'>".$row[1]."</option>";
    }
    return $ret;
  }
  public function ddl_positions(){
    $link = $this->connect();
    $query = "SELECT id,position FROM employee_positions ORDER BY position";
    $result = mysqli_query ( $link, $query );
    $ret = "";
    while($row =mysqli_fetch_row($result))
    {
      $ret = $ret. "<option value='".$row[0]."'>".$row[1]."</option>";
    }
    return $ret;
  }
  public function ddl_contractType(){
    $link = $this->connect();
    $query = "SELECT id,type FROM contract_type ORDER BY type";
    $result = mysqli_query ( $link, $query );
    $ret = "";
    while($row =mysqli_fetch_row($result))
    {
      $ret = $ret. "<option value='".$row[0]."'>".$row[1]."</option>";
    }
    return $ret;
  }
  public function ddl_suppliers(){
    $link = $this->connect();
    $query = "SELECT supplier_id,supplier_name FROM suppliers order by supplier_name";
    $result = mysqli_query ( $link, $query );
    $ret = "";
    while($row =mysqli_fetch_row($result))
    {
      $ret = $ret. "<option value='".$row[0]."'>".$row[1]."</option>";
    }
    return $ret;
  }

}

class ui_functions{
  public function showCompanyHeader(){
    print '
    <div class="col-xs-hidden col-sm-hidden col-lg-12 company-header">
        <div class="col-lg-1">
          <div class="company-logo"></div>
        </div>

        <div class="col-lg-11">
          <div class="col-lg-12">
            <div class="company-name">'.COMPANY_NAME.'</div>
          </div>
          <div class="col-lg-12">
            <div class="time-status">'.date('l\, F d Y').'</div>
          </div>
        </div>


    </div>
    <!-- /.col-lg-12 -->
    ';
  }
  public function showNav(){
    print '
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">'.SITE_TITLE.'</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">

                <li class="dropdown"  style="float:right;">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="../gateway/logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">

                <li>
                    <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                </li>
                <li>
                    <a href="customers.php"><i class="fa fa-users fa-fw"></i> Customers</a>
                </li>
                <li>
                    <a href="suppliers.php"><i class="fa fa-briefcase fa-fw"></i> Suppliers</a>
                </li>
                <li>
                    <a href="employees.php"><i class="fa fa-user fa-fw"></i> Employees</a>
                </li>
                <li>
                    <a href="inventory.php"><i class="fa fa-align-justify fa-fw"></i> Inventory</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-weixin fa-fw"></i> Purchases<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <!--li>
                            <a href="purchaseOrders.php">Purchase Orders</a>
                        </li-->
                        <li>
                            <a href="purchaseOrders.php">Vouchers</a>
                        </li>
                        <li>
                            <a href="payables.php">Payables</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#"><i class="fa fa-weixin fa-fw"></i> Sales<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="invoices.php">Sales Invoice</a>
                        </li>
                        <li>
                            <a href="receivables.php">Receivables</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#"><i class="fa fa-file fa-fw"></i> Reports</a>

                </li>
                <li>
                    <a href="#"><i class="fa fa-user-secret fa-fw"></i> Admin<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="blank.html">Blank Page</a>
                        </li>
                        <li>
                            <a href="../gateway/logout.php">Login Page</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    </nav>
    ';
  }

  public function showHeadHTML(){
    print '
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="">
      <meta name="author" content="">

      <title>'.SITE_TITLE.'</title>

      <!-- Bootstrap Core CSS -->
      <link href="../assets/plugins/sbadmin/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

      <!-- MetisMenu CSS -->
      <link href="../assets/plugins/sbadmin/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

      <!-- Timeline CSS -->
      <link href="../assets/plugins/sbadmin/dist/css/timeline.css" rel="stylesheet">

      <!-- Custom CSS -->
      <link href="../assets/plugins/sbadmin/dist/css/sb-admin-2.css" rel="stylesheet">

      <!-- Morris Charts CSS -->
      <link href="../assets/plugins/sbadmin/bower_components/morrisjs/morris.css" rel="stylesheet">

      <!-- Custom Fonts -->
      <link href="../assets/plugins/sbadmin/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

      <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn\'t work if you view the page via file:// -->
      <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
      <link href="../assets/plugins/fontawesome/css/font-awesome.css" rel="stylesheet" type="text/css">

      <link href="../assets/styles/main.css" rel="stylesheet">
      <link rel="stylesheet" href="../assets/plugins/bootstrap-select-1.10.0/dist/css/bootstrap-select.css">
      <link rel="stylesheet" href="../assets/plugins/impromptu/dist/jquery-impromptu.min.css" />
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
      <script src="../assets/plugins/bootstrap-select-1.10.0/dist/js/bootstrap-select.js"></script>
      <script src="../assets/plugins/impromptu/dist/jquery-impromptu.min.js"></script>
    ';
  }

}
