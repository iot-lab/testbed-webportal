<?php 
session_start();

$_SESSION = array();

include("header.php");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Senslab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
      <script src="js/jquery.js"></script>
      <script src="js/bootstrap.js"></script>
    
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href=".">Senslab</a>
          <div class="nav-collapse">
	            <ul class="nav">
	            	<li class="divider-vertical"></li>  
						<li><a href=".">Login</a></li>
	            	<li class="divider-vertical"></li>
	            </ul> 
          </div><!--/.nav-collapse -->
          <ul class="nav pull-right">
	            <li class="divider-vertical"></li>
		        <li class="dropdown">
		            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Testbed activity <b class="caret"></b></a>
		            <ul class="dropdown-menu">
		        		<li><a href="/monika" target="_blank">View nodes status</a></li>
		                <li><a href="/drawgantt" target="_blank">View Gantt chart</a></li>
		            </ul>
		        </li>
          </ul>
        </div>
      </div>
    </div>
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
