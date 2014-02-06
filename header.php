<?php
/* are we showing activity or testbed? */
/* check the name of the file: stats, monika or drawgantt are for activity */
$is_activity = ((strpos($_SERVER['PHP_SELF'],"/stats.php")!==FALSE) || (strpos($_SERVER['PHP_SELF'],"/drawgantt.php")!==FALSE) || (strpos($_SERVER['PHP_SELF'],"/monika.php")!==FALSE));

$body_padding_top=51;
if(isset($_SESSION['is_auth']) && $_SESSION['is_auth'] && !$is_activity) $body_padding_top=102;


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>FIT/IoT-LAB &#8226; Very large scale open wireless sensor network testbed</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	
	<!-- Le styles -->
	<link href="/wp-content/themes/alienship-1.2.2/css/bootstrap.min.css?ver=0.0.1" rel="stylesheet">
	<link href="/wp-content/themes/alienship-1.2.2-child/style.css" rel="stylesheet">
	
	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  <script src="js/html5.js"></script>
	<![endif]-->
	
	<link rel="shortcut icon" href="img/favicon.png" />
	
	<style type="text/css">
	
		body {
			padding-top:<?php echo $body_padding_top; ?>px;
		}	

		.badge-success {
			background-color: #468847;
		}
		
		.badge-warning {
			background-color: #F89406;
		}
		
		.badge-info {
			background-color: #3A87AD;
		}
		

		.navbar-grey {
			top:51px;
			z-index:1000;
			background-color: #E7E7E7;
			border-bottom:1px solid #eee;
		}

		.navbar-grey .navbar-nav > li > a:hover,
		.navbar-grey .navbar-nav > li > a:focus,
		.navbar-grey .navbar-nav > li.dropdown > a:hover,
		.navbar-grey .navbar-nav > li.dropdown > a:focus,
		.navbar-grey .navbar-nav > li.active > a,
		.navbar-grey .navbar-nav > li.active > a:hover,
		.navbar-grey .navbar-nav > li.active > a:focus {
		    background-color: #ddd;
		    color: #555555;
		}

		label {
			font-weight:normal;
		}


	</style>
	
	<!--<script src='/wp-includes/js/jquery/jquery.js'></script>-->
	<script src='js/jquery-1.10.2.min.js'></script>
	<script src="js/utils.js"></script>
	<script type='text/javascript' src='/wp-content/themes/alienship-1.2.2/js/bootstrap.min.js?ver=3.0.0'></script>
	<script type='text/javascript' src='/wp-content/themes/alienship-1.2.2-child/js/twitter-bootstrap-hover-dropdown.min.js?ver=1.0.0'></script>

</head>

<body>

	<!--  NAV BAR  -->
	
	<header class="navbar navbar-default navbar-fixed-top" role="banner">
	  <div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a href="/" class="navbar-brand"><img src="/wp-content/themes/alienship-1.2.2-child/templates/parts/FIT-IoT-Lab-Logo2.png" height="20"/></a>
		</div>
	  
	  
		<nav class="navbar-collapse collapse navbar-ex1-collapse" role="navigation">

			<?php include('./wp-menu/wp-menu.php'); ?>
	
			<ul class="nav navbar-nav navbar-right testbed">
				<li class="dropdown <?php echo ($is_activity?'active':''); ?>">
					<a href="./stats.php" class="testbed" title="Testbed activity" data-hover="dropdown"><span class="glyphicon glyphicon-info"></span> Activity <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li id="stats"><a href="./stats.php">Statistics</a></li>
						<li id="monika"><a href="./monika.php">View nodes status</a></li>
						<li id="drawgantt"><a href="./drawgantt.php">View gantt chart</a></li>
					</ul>
				</li>
<?php if(isset($_SESSION['is_auth']) && $_SESSION['is_auth']) { ?>
				<li class="dropdown <?php echo ($is_activity?'':'active'); ?>">
					<a href="./" class="testbed" title="Testbed" data-hover="dropdown"><span class="glyphicon glyphicon-wrench"></span> Testbed <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li id="dashboard"><a href="./dashboard.php"><span class="glyphicon glyphicon-list"></span> Dashboard</a></li>
						<li id="exp_new"><a href="./exp_new.php"><span class="glyphicon glyphicon-file"></span> New Experiment</a></li>
						<li id="profiles"><a href="./profiles.php"><span class="glyphicon glyphicon-cog"></span> Manage Profiles</a></li>
						<li class="divider"></li>
						<li class="dropdown-header"><span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION['login']; ?></li>
						<li id="user_profile"><a href="./user_profile.php">Edit My Profile</a></li>
						<li><a href="./logout.php">Logout</a></li>
					</ul>
				</li>
<?php } else { ?>
				<li id="login"><a href="./">Login</a></li>
<?php } ?>
			</ul>
		</nav><!--/.nav-collapse -->
	  </div><!--/.container -->
	</header>

	<!--  END NAV BAR  -->

	<!--  LOGGED IN NAV BAR  -->
	
<?php if(isset($_SESSION['is_auth']) && $_SESSION['is_auth'] && !$is_activity) { ?>
	<div class="navbar navbar-default navbar-fixed-top navbar-grey" role="banner">
		<div class="container">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex2-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
		
			<nav class="navbar-collapse collapse navbar-ex2-collapse" role="navigation">
				<ul class="nav navbar-nav">
					<li class="divider-vertical"></li>
                	<li id="dashboard2"><a href="./dashboard.php">Dashboard</a></li>
                	<li id="exp_new2"><a href="./exp_new.php">New Experiment</a></li>
                	<!--<li><a id='profilesModalLink' data-toggle="modal" data-target="#profiles_modal" style="cursor:pointer">Manage Profiles</a></li>-->
			<li id="profiles2"><a href="./profiles.php">Manage Profiles</a></li>
                	<li class="divider-vertical"></li>
            	</ul>
<?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin']) { ?>
      	    	<ul class="nav navbar-nav pull-right">
	                <li class="dropdown" id="admin">
                    	<a href="#" data-hover="dropdown">Admin <b class="caret"></b></a>
                    	<ul class="dropdown-menu">
	                        <li id="admin_users"><a href="./admin_users.php">Users</a></li>
                        	<li id="admin_exps"><a href="./admin_exps.php">Experiments</a></li>
                        	<li id="admin_nodes"><a href="./admin_nodes.php">Nodes</a></li>
                		<li id="admin_stats"><a href="./admin_stats.php">Statistics</a></li>
                    	</ul>
                	</li>
		</ul>
<?php } ?>
			</nav><!--/.nav-collapse -->
		</div><!--/.container -->
	</div><!--/.navbar -->
<?php } ?>

	<!--  END LOGGED IN NAV BAR  -->

<!-- ------------------------------------- -->
<!--            END HEADER                 -->
<!-- ------------------------------------- -->


