<?php session_start(); ?>

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
          <a class="brand" href="/portal">Senslab</a>
          <div class="nav-collapse">
              

            <?php if(!$_SESSION['is_auth']) { ?>
            <ul class="nav">
            <li><a href="/portal/">Login</a></li>
            </ul> 
            <?php } ?> 
            
            <?php if($_SESSION['is_auth']) { ?>
            <ul class="nav">
            <li><a href="/portal/logout.php">Logout</a></li>
            </ul> 
            <?php } ?>
            
            
            <?php if(!$_SESSION['is_auth']) { ?>
            <ul class="nav">
            <li><a href="/portal/signup.php">Sign up</a></li>
            </ul> 
            <?php } ?>  
            
          </div><!--/.nav-collapse -->
          
          <?php if($_SESSION['is_auth'] && $_SESSION['is_admin']) { ?>
          <ul class="nav pull-right">
            <li class="divider-vertical"></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="/portal/admin_manageusers.php">Users</a></li>
              </ul>
            </li>
          </ul>
          <?php } ?>
          
        </div>
      </div>
    </div>
