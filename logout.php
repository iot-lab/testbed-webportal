<?php 
session_start();

$_SESSION = array();

include("header.php");

?>
	<div class="container">

      <div class="row" id="login_div">
        <div class="span12">
          <h2>Logout</h2>
           
           <div class="alert alert-success">You have been logged out. Click <a href=".">here</a> to login.</div>
        
        </div>

      </div>
      
      
      <hr>

<?php include('footer.php') ?>

  </body>
</html>
