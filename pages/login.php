<?php
if(isset($_SESSION['userid'])){
    header("location:index.php");
  }
  include '../utils/functions.php';


?>
<!DOCTYPE>
<html>
  <head>
    <title>RP Axis Login</title>
    <link rel="stylesheet" href="../assets/plugins/bootstrap/bootstrap.css" />
    <link rel="stylesheet" href="../assets/styles/main.css" />
  </head>
  <body>

    <div class="main_container">
      <div class="login-header col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
      <div class="spacer-40 col-xs-hidden col-sm-hidden col-md-12 col-lg-12"></div>
      <div class="row-grid">
        <div class="col-xs-hidden col-sm-hidden col-md-4 col-lg-4"></div>
        <div class="login-container col-xs-12 col-sm-12 col-md-4 col-lg-4 one-edge-shadow">
          <br />
          <div class="alert alert-danger fade in" style="display:none;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Error!</strong> <span class="error_msg"></span>
          </div>
          <form method="post" action="../gateway/auth.php">
            <div class="form-group">
                <label for="inputEmail">Username</label>
                <input type="text" class="form-control" required name="inputUsername" placeholder="Username">
            </div>
            <div class="form-group">
                <label for="inputPassword">Password</label>
                <input type="password" class="form-control" required name="inputPassword" placeholder="Password">
            </div>
            <!--div class="checkbox">
                <label><input type="checkbox"> Remember me</label>
            </div-->
            <button type="submit" name="loginFlag" class="btn btn-primary">Login</button>

        </form>
        </div>
        <div class="col-xs-hidden col-sm-hidden col-md-4 col-lg-4"></div>
      </div>
    </div>

    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <script src="../assets/plugins/bootstrap/tether.min.js"></script>
    <script src="../assets/plugins/bootstrap/bootstrap.js"></script>
    <script src="../assets/scripts/script.js"></script>
    <script>
      $(document).ready(function(){
      });
    </script>
    <?php
      if(isset($_GET['loginFailed'])){
        if($_GET['loginFailed']=="1"){
          ?>
          <script>
           error_alert("Incorrect Username or Password!");
          </script>
          <?php
        }
      }
    ?>
  </body>
</html>
