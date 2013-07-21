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

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
  <script src="js/html5.js"></script>
<![endif]-->

  <script src="js/jquery.js"></script>
  <script src="js/utils.js"></script>
  <script src="js/bootstrap.js"></script>

<link rel="shortcut icon" href="img/favicon.png" />

<style type="text/css">

	body {
		font-size:13px;
	}

	h2,h3{
		color: #86A219;
		font-size:24px;
	}
    
	a, a:focus {
		text-decoration:none;
		color: #86A219;
	}
    
	a:hover, a:active {
		color: #86A219;
		text-decoration:underline;
	}
    
	input:focus, textarea:focus {
		border-color: rgba(170, 212, 0, 0.8);
		box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(170, 212, 0, 0.6);
		outline: 0 none;
	}

	.progress .bar {
		background-color: #86A219;
		background-image: -moz-linear-gradient(center top , #BEED00, #85A600);
	}

	select {
		background-color: #ffffff;
		-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		-moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		-webkit-transition: border linear 0.2s, box-shadow linear 0.2s;
		-moz-transition: border 0.2s linear 0s, box-shadow 0.2s linear 0;
		-ms-transition: border linear 0.2s, box-shadow linear 0.2s;
		-o-transition: border linear 0.2s, box-shadow linear 0.2s;
		transition: border 0.2s linear 0s, box-shadow 0.2s linear 0s;
		border-radius: 3px 3px 3px 3px;
		width:280px;
	}

	.sl-logo {
		background-color: #f7f7f7;
		height: 112px;
		position:fixed;
		top:0;
		width:100%;
		z-index:1000;
	}

	.sl-logo a {
		background:url('img/banniere2.png') no-repeat left;
		margin-left: auto;
		margin-right: auto;
		display:block;
		width:960px;
		height:100%;
		text-decoration:none;
	}
	
	.navbar-fixed-top {
		top:112px;
	}
	   
	.navbar-inner {
		min-height:31px;
		padding-top:20px;
		background: #454546;
		border-radius:0px;
	}
	
	.navbar-inner .container {
		width:960px;
	}
	
	.navbar .nav > li {
		margin-right: 8px;
	}

	.navbar .nav > li > a {
		-moz-border-bottom-colors: none;
		-moz-border-left-colors: none;
		-moz-border-right-colors: none;
		-moz-border-top-colors: none;
		background: none repeat scroll 0 0 #333333;
		border-color: #555555 #555555 -moz-use-text-color;
		border-image: none;
		border-style: none;
		border-width: 1px 1px medium;
		border-radius: 6px 6px 0 0;
		color: #ABAAAA;
		display: block;
		line-height: 31px;
		padding: 0 10px;
		font-family: Tahoma,Arial,Helvetica,sans-serif;
		text-shadow:none;
	}
	

    .navbar .nav > li > a:hover, .navbar .nav > li > a:focus {
		background: none repeat scroll 0 0 #222222;
		color: #fff;
    }

    .dropdown-menu > li > a:hover {
		background: none repeat scroll 0 0 #82A619;
    }
    
	.dropdown .caret {
		margin-top: 24px;
	}


   .alert-info {
       border-color: #d9edf7;
   }

	.btn-large {
		font-size:14px;
	}

</style>

<link href="css/bootstrap-responsive.css" rel="stylesheet">
<style type="text/css">
	body {
		padding-top: 180px;
		padding-bottom: 40px;
	}
</style>
</head>

<body>

<!-- ---------- -->
<!--  MENU BAR  -->
<!-- ---------- -->
<div class="sl-logo"><a href="."></a></div>
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <div class="nav-collapse">
            <ul class="nav">
                <?php if(isset($_SESSION['is_auth']) && $_SESSION['is_auth']) { ?>
                    <li><a href="."><i class="icon-home icon-white"></i> Home</a></li>  
                    <li><a href="exp_new.php">New Experiment</a></li>
                    <li><a id='profilesModalLink' data-toggle="modal" data-target="#profiles_modal" style="cursor:pointer">Manage Profiles</a></li>
                <?php } else { ?>
                    <li><a href="#login" onClick="showLogin()">Login</a></li>
                    <li><a href="#signup" onClick="showSignup()">Sign up</a></li>
                 <?php } ?>
            </ul> 
            
      <ul class="nav pull-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Testbed activity <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="monika.php" target="_blank">View nodes status</a></li>
                        <li><a href="drawgantt.php" target="_blank">View Gantt chart</a></li>
                    </ul>
                </li>
      
          <li><a href="/wiki">Documentation</a></li>

          <?php if(isset($_SESSION['is_auth']) && $_SESSION['is_auth']) { ?>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo "".$_SESSION['login']."" ?> [logout] <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a data-toggle="modal" href="#ssh_modal" onClick="loadSSHKeys()">Modify SSH keys</a></li>
                    <li><a data-toggle="modal" href="#password_modal">Modify password</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </li>
          <?php } ?>
      
          <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin']) { ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="admin_users.php">Users</a></li>
                <li><a href="admin_exps.php">Experiments</a></li>
                <li><a href="admin_nodes.php">Nodes</a></li>
                <li><a href="admin_stats.php">Statistics</a></li>
              </ul>
            </li>
          <?php } ?>
      </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>

<!-- -------------- -->
<!--  END MENU BAR  -->
<!-- -------------- -->


<?php if(isset($_SESSION['is_auth']) && $_SESSION['is_auth']) { ?>   

    <!--  MODAL WINDOW FOR PASSWORD MODIFICATION -->

    <div id="password_modal" class="modal hide fade">
        <div class="modal-header">
          <a class="close" data-dismiss="modal">×</a>
          <h3>Modify Password</h3>
        </div>
       <div class="modal-body">
           <div class="alert alert-error" id="div_error_password" style="display:none"></div>
           
            <form class="well form-horizontal" id="form_modify_password">

          <div class="control-group">
            <label class="control-label" for="txt_current_password">Current password:</label>
            <div class="controls">
                <input id="form_modify_password_txt_current_password" class="input-xlarge" type="password" required="required"/>
            </div>
          </div>

          <div class="control-group">
            <label class="control-label" for="txt_new_password">New password:</label>
            <div class="controls">
                <input id="form_modify_password_txt_new_password" class="input-xlarge" type="password" required="required"/>
            </div>
          </div>

          <div class="control-group">
            <label class="control-label" for="txt_cnew_password">Confirm new password:</label>
            <div class="controls">
                <input id="form_modify_password_txt_cnew_password" class="input-xlarge" type="password" required="required"/>
            </div>
          </div>
          
            <button id="form_modify_password_btn_modify" class="btn btn-primary" type="submit">Modify</button>
            </form>
        </div>
    </div>
    
    <!--  MODAL WINDOW FOR SSH KEYS MODIFICATION -->

    <div id="ssh_modal" class="modal hide fade">
        <div class="modal-header">
          <a class="close" data-dismiss="modal">×</a>
          <h3>Modify SSH Key</h3>
        </div>
       <div class="modal-body">
           <div class="alert alert-error" id="div_error_ssh" style="display:none"></div>
           
            <form class="well form-horizontal" id="form_modify_ssh">
				<div class="tabbable">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_SSH1" data-toggle="tab">SSH Key 1</a></li>
						<li><a href="#tab_SSH2" data-toggle="tab">SSH Key 2</a></li>
						<li><a href="#tab_SSH3" data-toggle="tab">SSH Key 3</a></li>
						<li><a href="#tab_SSH4" data-toggle="tab">SSH Key 4</a></li>
						<li><a href="#tab_SSH5" data-toggle="tab">SSH Key 5</a></li>
					</ul>

					<div class="tab-content">
						<div class="tab-pane active" id="tab_SSH1">
							<div class="control-group">
								<label class="control-label" for="form_modify_ssh_txt_ssh1">SSH Key 1:</label>
									<div class="controls">
										<textarea id="form_modify_ssh_txt_ssh" class="input-xlarge" rows="3"></textarea>
									</div>
							</div>
						</div>
						<div class="tab-pane" id="tab_SSH2">
							<div class="control-group">
								<label class="control-label" for="form_modify_ssh_txt_ssh2">SSH Key 2:</label>
								<div class="controls">
									<textarea id="form_modify_ssh_txt_ssh2" class="input-xlarge" rows="3"></textarea>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab_SSH3">
							<div class="control-group">
								<label class="control-label" for="form_modify_ssh_txt_ssh3">SSH Key 3:</label>
								<div class="controls">
									<textarea id="form_modify_ssh_txt_ssh3" class="input-xlarge" rows="3"></textarea>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab_SSH4">
							<div class="control-group">
								<label class="control-label" for="form_modify_ssh_txt_ssh4">SSH Key 4:</label>
								<div class="controls">
									<textarea id="form_modify_ssh_txt_ssh4" class="input-xlarge" rows="3"></textarea>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab_SSH5">
							<div class="control-group">
								<label class="control-label" for="form_modify_ssh_txt_ssh5">SSH Key 5:</label>
								<div class="controls">
									<textarea id="form_modify_ssh_txt_ssh5" class="input-xlarge" rows="3"></textarea>
								</div>
							</div>
						</div>

					</div>
				</div>

				<button id="form_modify_ssh_btn_modify" class="btn btn-primary" type="submit">Modify</button>

            </form>
        </div>
    </div>
    
    <!--  MODAL WINDOW FOR MANAGING PROFILES -->

    <div id="profiles_modal" class="modal hide fade" style="width:920px;margin-left:-460px;">
        <div class="modal-header">
          <a class="close" data-dismiss="modal">×</a>
          <h3>Manage Profiles</h3>
        </div>
       <div class="modal-body" style="max-height:610px;">
			<div class="alert alert-error" id="div_error_profiles" style="display:none"></div>
			<div class="row" id="profiles_modal-body"></div>
       </div>
    </div>

<script type="text/javascript">


var SSHKeysLoaded = false;



    /* ************ */
    /*   on ready   */
    /* ************ */
    $(document).ready(function(){

        // Hide modal windows 
        $('#ssh_modal').modal('hide');
        $('#password_modal').modal('hide');
        $('#profiles_modal').modal('hide');


        // load profiles info in profiles modal div when click on "Manage Profiles" link 
        $('#profilesModalLink').click(function(){           
        	    $.ajax({
        	      type: "GET",
        	      url: "scripts/profiles.php",
        	      success: function(html){
        	          $("#profiles_modal-body").html(html);
        	      },
        	      error:function(XMLHttpRequest, textStatus, errorThrows){
        	          $("#div_error_ssh").removeClass("alert-success");
        	          $("#div_error_ssh").addClass("alert-error");
        	          $("#div_error_ssh").html("An error occurred while loading profiles.");
        	          $("#div_error_ssh").show();
        	      }
        	    });
        	});
        
    
        // Modify SSH Key 
        $('#form_modify_ssh').bind('submit', function(e){
            
            e.preventDefault();
            

            
            var sshKeys = [];
    
            sshKeys.push($("#form_modify_ssh_txt_ssh").val());
            sshKeys.push($("#form_modify_ssh_txt_ssh2").val());
            sshKeys.push($("#form_modify_ssh_txt_ssh3").val());
            sshKeys.push($("#form_modify_ssh_txt_ssh4").val());
            sshKeys.push($("#form_modify_ssh_txt_ssh5").val());
            
            var user = {};
            user.sshkeys = sshKeys;
            
            
            $.ajax({
                url: "/rest/users/"+<?php echo '"'.$_SESSION['login'].'"'?>+"/sshkeys",
                type: "PUT",
                dataType: "text",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify(user),
                success:function(data){
                    $("#div_error_ssh").removeClass("alert-error");
                    $("#div_error_ssh").addClass("alert-success");
                    $("#div_error_ssh").html("Your SSH keys had been changed successfully.");
                    $("#div_error_ssh").show();
                    setTimeout( "$('#div_error_ssh').hide()", 2000); 
                    setTimeout( "$('#ssh_modal').modal('hide')", 3000); 
            },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    $("#div_error_ssh").removeClass("alert-success");
                    $("#div_error_ssh").addClass("alert-error");
                    $("#div_error_ssh").html("An error occurred while saving your ssh keys");
                    $("#div_error_ssh").show();
                }
            });
            
            return false;
        
        });
        
        // Modify Password 
        $('#form_modify_password').bind('submit', function(e){
            
            e.preventDefault();
        
            if($("#form_modify_password_txt_new_password").val() != $("#form_modify_password_txt_cnew_password").val()) {
                $("#div_error_password").removeClass("alert-success");
                $("#div_error_password").addClass("alert-error");
                $("#div_error_password").html("Please confirm password");
                $("#div_error_password").show();
                return false;
            }
        
        
            var user = {
                "newPassword":$("#form_modify_password_txt_new_password").val(),
                "confirmNewPassword":$("#form_modify_password_txt_cnew_password").val(),
                "oldPassword":$("#form_modify_password_txt_current_password").val()
            };
            
            $.ajax({
                url: "/rest/users/"+<?php echo '"'.$_SESSION['login'].'"'?>+"/password",
                type: "PUT",
                dataType: "text",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify(user),
                success:function(data){
                    $("#div_error_password").removeClass("alert-error");
                    $("#div_error_password").addClass("alert-success");
                    $("#div_error_password").html("Your password has been changed successfully. Please wait...");
                    $("#div_error_password").show();
                    //setTimeout( "$('#div_error_password').hide()", 2000); 
                    setTimeout( "window.location.href = 'logout.php'", 2000); 
                },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    $("#div_error_password").removeClass("alert-success");
                    $("#div_error_password").addClass("alert-error");
                    $("#div_error_password").html("An error occurred while saving your password");
                    $("#div_error_password").show();
                }
            });
            
            return false;
        
        });        

    });
    
    
    /* ********************** */
    /* fill form with sshkeys */
    /* ********************** */
    function loadSSHKeys() {

        if (!SSHKeysLoaded) {
            $("#div_error_ssh").addClass("alert-success");
            $("#div_error_ssh").removeClass("alert-error");
            $("#div_error_ssh").html("Loading your ssh keys ...");
            $("#div_error_ssh").show();
            // Retrieve current sshkey
            $.ajax({
                url: "/rest/users/"+<?php echo '"'.$_SESSION['login'].'"'?>+"/sshkeys",
                type: "GET",
                data: {},
                dataType: "text",
                success:function(data){
                    $("#div_error_ssh").hide();
                    var sshkeys=JSON.parse(data);
                    $("#form_modify_ssh_txt_ssh").val(sshkeys.sshkeys[0]);
                    if(sshkeys.sshkeys.length>1) $("#form_modify_ssh_txt_ssh2").val(sshkeys.sshkeys[1]);
                    if(sshkeys.sshkeys.length>2) $("#form_modify_ssh_txt_ssh3").val(sshkeys.sshkeys[2]);
                    if(sshkeys.sshkeys.length>3) $("#form_modify_ssh_txt_ssh4").val(sshkeys.sshkeys[3]);
                    if(sshkeys.sshkeys.length>4) $("#form_modify_ssh_txt_ssh5").val(sshkeys.sshkeys[4]);
                    SSHKeysLoaded=true;
                },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    $("#div_error_ssh").removeClass("alert-success");
                    $("#div_error_ssh").addClass("alert-error");
                    $("#div_error_ssh").html("An error occurred while retrieving your ssh keys");
                    $("#div_error_ssh").show();
                }
            });
        }
    }
    
</script>

<?php } ?>       
