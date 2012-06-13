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

<link rel="shortcut icon" href="img/favicon.png" />

<style type="text/css">
    h2,h3{
        color: #577996;
    }
    
    .navbar .nav > li > a {
        color: #fff;
    }

    .navbar .nav > li > a:hover {
        color: #999;
    }


</style>

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
      <a class="brand" href="." style="color:#AAD400"><img src="img/banniere.png" style="width:110px"></a>
      <div class="nav-collapse">
            <ul class="nav">
                <li class="divider-vertical"></li>
                <?php if($_SESSION['is_auth']) { ?>
                    <li><a href="."><i class="icon-home icon-white"></i> Home</a></li>  
                    <li class="divider-vertical"></li>
                    <li><a href="new_experiment.php">New Experiment</a></li>
                    <li class="divider-vertical"></li>
                    <li><a href="new_profile.php">Manage Profiles</a></li> 
                <?php } else { ?>
                    <li><a href="#" onClick="showLogin()">Login</a></li>
                    <li class="divider-vertical"></li>
                    <li><a href="#" onClick="showSignup()">Sign up</a></li>
                 <?php } ?>
                <li class="divider-vertical"></li>
            </ul> 
      <ul class="nav pull-right">
      
          <?php if($_SESSION['is_auth']) { ?>
            <li class="divider-vertical"></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">User <?php echo "[".$_SESSION['login']."]" ?> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a data-toggle="modal" href="#ssh_modal" onClick="loadSSHKeys()">Modify SSH keys</a></li>
                    <li><a data-toggle="modal" href="#password_modal">Modify password</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </li>
          <?php } ?>
          
            <li class="divider-vertical"></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Testbed activity <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="monika.php" target="_blank">View nodes status</a></li>
                    <li><a href="drawgantt.php" target="_blank">View Gantt chart</a></li>
                </ul>
            </li>
      
          <?php if($_SESSION['is_admin']) { ?>
            <li class="divider-vertical"></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="admin_manageusers.php">Users</a></li>
                <li><a href="admin_manageexps.php">Experiments</a></li>
              </ul>
            </li>
          <?php } ?>
      </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>

<?php if($_SESSION['is_auth']) { ?>   
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
                <input id="txt_current_password" class="input-xlarge" type="password" required="required"/>
            </div>
          </div>

          <div class="control-group">
            <label class="control-label" for="txt_new_password">New password:</label>
            <div class="controls">
                <input id="txt_new_password" class="input-xlarge" type="password" required="required"/>
            </div>
          </div>

          <div class="control-group">
            <label class="control-label" for="txt_cnew_password">Confirm New password:</label>
            <div class="controls">
                <input id="txt_cnew_password" class="input-xlarge" type="password" required="required"/>
            </div>
          </div>
          
            <button id="btn_modify_password" class="btn btn-primary" type="submit">Modify</button>
            </form>
        </div>
    </div>
    
    <div id="ssh_modal" class="modal hide fade">
        <div class="modal-header">
          <a class="close" data-dismiss="modal">×</a>
          <h3>Modify SSH Key</h3>
        </div>
       <div class="modal-body">
           <div class="alert alert-error" id="div_error_ssh" style="display:none"></div>
           
            <form class="well form-horizontal" id="form_modify">

          <div class="control-group">
            <label class="control-label" for="txt_ssh">SSH Key:</label>
            <div class="controls">
                <textarea id="txt_ssh" class="input-xlarge" rows="3" required="required"></textarea>
            </div>
          </div>

            <button id="btn_modify" class="btn btn-primary" type="submit">Modify</button>
            </form>
        </div>
    </div>
    

<script type="text/javascript">


var SSHKeysLoaded = false;

    $(document).ready(function(){

        // Hide modal windows
        $('#ssh_modal').modal('hide');
        $('#password_modal').modal('hide');
    
        // Modify SSH Key
        $('#form_modify').bind('submit', function(){
            var user = {
                "sshkeys":$("#txt_ssh").val()
            };
            
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
        
        // Modify Password Key
        $('#form_modify_password').bind('submit', function(){
        
            if($("#txt_new_password").val() != $("#txt_cnew_password").val()) {
                $("#div_error_password").removeClass("alert-success");
                $("#div_error_password").addClass("alert-error");
                $("#div_error_password").html("Please confirm password");
                $("#div_error_password").show();
                return false;
            }
        
        
            var user = {
                "newPassword":$("#txt_new_password").val(),
                "oldPassword":$("#txt_current_password").val()
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
                    $("#div_error_password").html("Your password has been changed successfully. Please login again.");
                    $("#div_error_password").show();
                    setTimeout( "$('#div_error_password').hide()", 2000); 
                    setTimeout( "window.location.href = 'index.php?logout'", 3000); 
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
                    $("#txt_ssh").val(data);
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
