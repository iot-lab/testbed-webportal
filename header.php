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
  <script src="js/html5.js"></script>
<![endif]-->

  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.js"></script>

<link rel="shortcut icon" href="img/favicon.png" />

<style type="text/css">
    h2,h3{
        color: #AAD400;
    }
    
    .navbar .nav > li > a {
        color: #fff;
    }

    .navbar .nav > li > a:hover {
        color: #999;
    }

    .dropdown-menu > li > a:hover {
        background-color: #AAD400;    
    }
    
    a {
        color: #AAD400;
    }
    
    a:hover {
        color: #AAD400;
        text-decoration:underline;
    }
input:focus, textarea:focus {
    border-color: rgba(170, 212, 0, 0.8);
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(170, 212, 0, 0.6);
    outline: 0 none;
}

.progress .bar {
    background-color: #AAD400;
    background-image: -moz-linear-gradient(center top , #BEED00, #85A600);

</style>

</head>

<body>

<!-- ---------- -->
<!--  MENU BAR  -->
<!-- ---------- -->

<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a class="brand" href="." style="color:#AAD400"><img src="img/banniere.png"></a>
      <div class="nav-collapse">
            <ul class="nav">
                <li class="divider-vertical"></li>
                <?php if(isset($_SESSION['is_auth']) && $_SESSION['is_auth']) { ?>
                    <li><a href="."><i class="icon-home icon-white"></i> Home</a></li>  
                    <li class="divider-vertical"></li>
                    <li><a href="exp_new.php">New Experiment</a></li>
                    <li class="divider-vertical"></li>
                    <li><a data-toggle="modal" href="#profiles_modal" onClick="loadProfiles()">Manage Profiles</a></li>
                <?php } else { ?>
                    <li><a href="#" onClick="showLogin()">Login</a></li>
                    <li class="divider-vertical"></li>
                    <li><a href="#" onClick="showSignup()">Sign up</a></li>
                 <?php } ?>
                <li class="divider-vertical"></li>
            </ul> 
            
      <ul class="nav pull-right">
            	<li class="divider-vertical"></li>
	            <li class="dropdown">
	                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Testbed activity <b class="caret"></b></a>
	                <ul class="dropdown-menu">
	                    <li><a href="monika.php" target="_blank">View nodes status</a></li>
	                    <li><a href="drawgantt.php" target="_blank">View Gantt chart</a></li>
	                </ul>
	            </li>
      
          <?php if(isset($_SESSION['is_auth']) && $_SESSION['is_auth']) { ?>
            <li class="divider-vertical"></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo "".$_SESSION['login']."" ?> [logout] <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a data-toggle="modal" href="#ssh_modal" onClick="loadSSHKeys()">Modify SSH keys</a></li>
                    <li><a data-toggle="modal" href="#password_modal">Modify password</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </li>
            <li class="divider-vertical"></li>
          <?php } ?>
      
          <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin']) { ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="admin_users.php">Users</a></li>
                <li><a href="admin_exps.php">Experiments</a></li>
              </ul>
            </li>
            <li class="divider-vertical"></li>
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
            <label class="control-label" for="txt_cnew_password">Confirm New password:</label>
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

          <div class="control-group">
            <label class="control-label" for="txt_ssh">SSH Key:</label>
            <div class="controls">
                <textarea id="form_modify_ssh_txt_ssh" class="input-xlarge" rows="3" required="required"></textarea>
            </div>
          </div>

            <button id="form_modify_ssh_btn_modify" class="btn btn-primary" type="submit">Modify</button>
            </form>
        </div>
    </div>
    
	<!--  MODAL WINDOW FOR MANAGING PROFILES -->
	
    <div id="profiles_modal" class="modal hide fade" style="width:920px;margin:-290px 0 0 -460px;">
        <div class="modal-header">
          <a class="close" data-dismiss="modal">×</a>
          <h3>Manage Profiles</h3>
        </div>
       <div class="modal-body" style="max-height:580px;">
           <div class="alert alert-error" id="div_error_profiles" style="display:none"></div>            
            
            <div class="row">
                <div class="span3">
                    <div class="alert alert-info">
                        <img src="img/help.png"> To <b>create</b> an new profile click the <b>New</b> button, fill the form and click <b>Save</b>. 
                        <br/><br/>To <b>edit</b> a profile, click on the profile name on the list, edit settings, and click <b>Save</b>.
                        <br/><br/>When finished, close the window by clicking <b>X</b>.
                    </div>
                    <select id="my_profiles_modal" size="15"></select>
                    <p>
                    <button class="btn btn" id="btn_new">New</button>
                    <button class="btn btn-danger" id="btn_delete">Delete</button>
                    </p>
                </div>
            
                <div class="span6">
             
                    <form class="well form-horizontal" id="form_part">

                        <div class="control-group">
                            <label class="control-label" for="txt_name">Name:</label>
                            <div class="controls">
                                <input id="profiles_txt_name" type="text" class="input-small" required="required">
                            </div>
                        </div>
                   
                <div style="border: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;padding:5px 0px 0px 0px;margin:5px;">
                    <div class="control-group">
                        <label class="control-label">Power mode</label>
                        <div class="controls">
                          <label class="radio">
                            <input type="radio" name="or_power" id="or_power_dc" value="dc" checked>
                            dc
                          </label>
                          <label class="radio">
                            <input type="radio" name="or_power" id="or_power_battery" value="battery">
                            battery
                          </label>
                        </div>
                      </div>
                </div>

                <div style="border: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;padding:5px 0px 0px 0px;margin:5px;">
                <div class="control-group">
                    <label class="control-label" for="inlineCheckboxes">Consumption</label>
                    <div class="controls">
                      <label class="checkbox inline">
                        <input type="checkbox" id="cb_current" value="current"> current
                      </label>
                      <label class="checkbox inline">
                        <input type="checkbox" id="cb_voltage" value="voltage"> voltage
                      </label>
                      <label class="checkbox inline">
                        <input type="checkbox" id="cb_power" value="power"> power
                      </label>
                    </div>
                  </div>
                 
                <div class="control-group">
                    <label class="control-label" for="consumption_frequency">Frequency (ms)</label>
                    <div class="controls">
                      <select id="consumption_frequency" class="input-small">
                        <option value="70">70</option>
                        <option value="100">100</option>
                        <option value="500">500</option>
                        <option value="1000">1000</option>
                        <option value="5000">5000</option>
                      </select>
                    </div>
                  </div>
                </div>


                <div style="border: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;padding:5px 0px 0px 0px;margin:5px;">
                <div class="control-group">
                    <label class="control-label" for="inlineCheckboxes">Sensors</label>
                    <div class="controls">
                      <label class="checkbox inline">
                        <input type="checkbox" id="cb_luminosity" value="luminosity"> luminosity
                      </label>
                      <label class="checkbox inline">
                        <input type="checkbox" id="cb_temperature" value="temperature"> temperature
                      </label>
                    </div>
                  </div>

                <div class="control-group">
                    <label class="control-label" for="sensor_frequency">Frequency (ms)</label>
                    <div class="controls">
                      <select id="sensor_frequency" class="input-small">
                        <option value="5000">5000</option>
                        <option value="10000">10000</option>
                        <option value="30000">30000</option>
                      </select>
                    </div>
                  </div>
                </div> 

                <div style="border: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;padding:5px 0px 0px 0px;margin:5px;">
                <div class="control-group">
                    <label class="control-label" for="inlineCheckboxes">Radio</label>
                    <div class="controls">
                      <label class="checkbox inline">
                        <input type="checkbox" id="cb_rssi" value="luminosity"> rssi
                      </label>
                    </div>
                  </div>

                <div class="control-group">
                    <label class="control-label" for="radio_frequency">Frequency (ms)</label>
                    <div class="controls">
                      <select id="radio_frequency" class="input-small">
                        <option value="500">500</option>
                        <option value="1000">1000</option>
                        <option value="5000">5000</option>
                        <option value="10000">10000</option>
                      </select>
                    </div>
                  </div>
                </div>

                   <button id="btn_submit" class="btn btn-primary" type="submit">Save</button>

                    </form>
                </div> 
                
            </div>
       </div>
    </div>

<script type="text/javascript">


var my_profiles = [];
var profilesLoaded = false;
var SSHKeysLoaded = false;



	/* ************ */
	/*   on ready   */
	/* ************ */
    $(document).ready(function(){

        // Hide modal windows
        $('#ssh_modal').modal('hide');
        $('#password_modal').modal('hide');
        $('#profiles_modal').modal('hide');
    
        // Modify SSH Key
        $('#form_modify_ssh').bind('submit', function(){
            var user = {
                "sshkeys":$("#form_modify_ssh_txt_ssh").val()
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
        
            if($("#form_modify_password_txt_new_password").val() != $("#form_modify_password_txt_cnew_password").val()) {
                $("#div_error_password").removeClass("alert-success");
                $("#div_error_password").addClass("alert-error");
                $("#div_error_password").html("Please confirm password");
                $("#div_error_password").show();
                return false;
            }
        
        
            var user = {
                "newPassword":$("#form_modify_password_txt_new_password").val(),
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


        //delete selected profile
        $("#btn_delete").click(function(){
            var profile_name = $("#my_profiles_modal").val();
            $.ajax({
                type: "DELETE",
                dataType: "text",
                contentType: "application/json; charset=utf-8",
                url: "/rest/profile/"+profile_name,
                success: function (data_server) {
                    
                    $("#my_profiles_modal option:selected").remove();
                    
                    $("#div_error_profiles").html("Delete ok");
                    $("#div_error_profiles").show();
                    $("#div_error_profiles").removeClass("alert-error");
                    $("#div_error_profiles").addClass("alert-success");
                    setTimeout( "$('#div_error_profiles').hide()", 2000); 
                },
                error: function (XMLHttpRequest, textStatus, errorThrows) {
                    $("#div_error_profiles").html(errorThrows);
                    $("#div_error_profiles").show();
                    $("#div_error_profiles").removeClass("alert-success");
                    $("#div_error_profiles").addClass("alert-error");
                }
            });
            
        });
        
        
        //prepare form for the new profile
        $("#btn_new").click(function(){
            $("#txt_name").val("new_profile");
            
            $("#my_profiles_modal option:selected").removeAttr("selected");
            
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
                    $("#form_modify_ssh_txt_ssh").val(data);
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



    /* ************************* */
    /* fill select with profiles */
    /* ************************* */
    function loadProfiles() {

        if (!profilesLoaded) {
            $("#div_error_profiles").addClass("alert-success");
            $("#div_error_profiles").removeClass("alert-error");
            $("#div_error_profiles").html("Loading your profiles ...");
            $("#div_error_profiles").show();
		    $.ajax({
		        type: "GET",
		        cache: false,
		        dataType: "text",
		        contentType: "application/json; charset=utf-8",
		        url: "/rest/profiles",
		        success: function (data_server) {
		            
		            if(data_server == "") {
		                //no profile
		                return false;
		            }
		            
		            my_profiles = JSON.parse(data_server);
		            
		            //fill profiles list
		            for(var i = 0; i < my_profiles.length; i++) {
		                $("#my_profiles_modal").append(new Option(my_profiles[i].profilename,my_profiles[i].profilename));
		            }
		            
		            //bind onClick event
		            $("#my_profiles_modal").change(loadProfile);
                    $("#div_error_profiles").hide();
		            profilesLoaded=true;
		        },
		        error: function (XMLHttpRequest, textStatus, errorThrows) {
		            $("#div_error_profiles").html(errorThrows);
		            $("#div_error_profiles").show();
		            $("#div_error_profiles").removeClass("alert-success");
		            $("#div_error_profiles").addClass("alert-error");
		        }
		    });
        }
    }

    /* *************** */
    /* submit profiles */
    /* *************** */
    $("#form_part").bind("submit", function () {

        var regExp = /^[0-9A-z]*$/; ;
        if(regExp.test($("#profiles_txt_name").val()) == false){
           alert("You must set a profile name with only word characters [0-9A-Za-z_]");
           return false;
        }

        consemptium = {
            "current":$('#cb_current').is(':checked'),
            "voltage":$('#cb_voltage').is(':checked'),
            "power":$('#cb_power').is(':checked'),
            "frequency":$('#consumption_frequency').val()
        };

        sensor = {
            "luminosity":$('#cb_luminosity').is(':checked'),
            "temperature":$('#cb_temperature').is(':checked'),
            "frequency":$('#sensor_frequency').val()
        };

        radio = {
            "rssi":$('#cb_rssi').is(':checked'),
            "frequency":$('#radio_frequency').val()
        };

        var profile_json = {
            "profilename":$("#profiles_txt_name").val(),
            "power":$("input[name=or_power]:checked").val(),
            "consemptium":consemptium,
            "sensor":sensor,
            "radio":radio
        };
        

        //send edit or create request
        $.ajax({
            type: "POST",
            dataType: "text",
            data: JSON.stringify(profile_json),
            contentType: "application/json; charset=utf-8",
            url: "/rest/profile",
            success: function (data_server) {

                var result_msg="";
                var edit = false;
                var index = -1;
                //check if profile already exist
                for(var i = 0; i < $("#my_profiles_modal option").length; i++) {
                    if($($("#my_profiles_modal option")[i]).val() == profile_json.profilename) {
                        edit = true;
                        index = i;
                        break;
                    }
                }
                
                if(!edit) {
                    
                	result_msg="Profile created.";
                    $("#my_profiles_modal").append(new Option(profile_json.profilename,profile_json.profilename));
                    
                    my_profiles.push(profile_json);
                }
                else {
                	result_msg="Profile edited.";
                    my_profiles[index] = profile_json;
                     
                }
                $("#div_error_profiles").removeClass("alert-error");
                $("#div_error_profiles").addClass("alert-success");
                $("#div_error_profiles").html(result_msg);
                $("#div_error_profiles").show();
                setTimeout( "$('#div_error_profiles').hide()", 2000); 
            },
            error: function (XMLHttpRequest, textStatus, errorThrows) {
                $("#div_error_profiles").removeClass("alert-success");
                $("#div_error_profiles").addClass("alert-error");
                $("#div_error_profiles").html(errorThrows);
                $("#div_error_profiles").show();
            }
        });
    
        return false;
    });
    
    
    /* ********************** */
    /* fill form with profile */
    /* ********************** */
    function loadProfile() {
        
        var profilename = $(this).val();

        var index = -1;
        
        for(var i = 0; i < my_profiles.length && index == -1; i++) {
            if(my_profiles[i].profilename == profilename) {
                index = i;
                break;
            }
        }
        
        if(index != -1) {
            $("#txt_name").val(my_profiles[index].profilename);
            $('#consumption_frequency').val(my_profiles[i].consemptium.frequency);
            $('#sensor_frequency').val(my_profiles[i].sensor.frequency);
            $('#radio_frequency').val(my_profiles[i].radio.frequency);
            
            $("input[name='or_power']").val([my_profiles[i].power]);
            $("#cb_current").attr("checked",my_profiles[i].consemptium.current);
            $("#cb_voltage").attr("checked",my_profiles[i].consemptium.votage);
            $("#cb_power").attr("checked",my_profiles[i].consemptium.power);
            
            $("#cb_temperature").attr("checked",my_profiles[i].sensor.temperature);
            $("#cb_luminosity").attr("checked",my_profiles[i].sensor.luminosity);
            
            $("#cb_rssi").attr("checked",my_profiles[i].radio.rssi);
        }
        
    }
    
</script>

<?php } ?>       
