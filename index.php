<?php 
session_start();

if(isset($_GET['logout'])) $_SESSION = array();

if($_SESSION['is_auth']) {
    header("location: dashboard.php");
    exit();
}

include("header.php") ?>

	<div class="container">

      <div class="row" id="login_div">
        <div class="span12">
          <h2>Login</h2>
           
           <div class="alert alert-error" id="div_error_login" style="display:none"></div>
           
            <form class="well form-inline" id="login_form">
              Login: <input id="txt_login" type="text" class="input-small" placeholder="Login">
              Password: <input id="txt_password" type="password" class="input-small" placeholder="Password">
              <button id="btn_login" type="submit" class="btn">Log in</button>
            </form>
        	<a href="#" onClick="showSignup()">Ask for an account</a> - <a href="#" onClick="showReset()">Forgot your password?</a>

        </div>

      </div>

      <div class="row" id="reset_div" style="display:none">
        <div class="span12">
          <h2>Ask for a new password</h2>
           
           <div class="alert alert-error" id="div_error_reset" style="display:none"></div>
           
            <form class="well form-inline" id="reset_form">
				Enter your email: <input id="txt_email_reset" class="input-xlarge" type="email" required="required">
           	  <button id="btn_reset" class="btn btn-primary" type="submit">Submit</button>
            </form>
        	<a href="#" onClick="showSignup()">Ask for an account</a> - <a href="#" onClick="showLogin()">Login</a>

        </div>
      </div>
      
      <div class="row" id="signup_div" style="display:none">
        <div class="span12">
          <h2>Sign up</h2>
           
            <div class="alert alert-error" id="div_error_signup" style="display:none"></div>
           
            <form class="well form-horizontal" id="signup_form">

          <div class="control-group">
            <label class="control-label" for="txt_firstname">First Name:</label>
            <div class="controls">
              <input id="txt_firstname" class="input-xlarge" type="text" required="required"  >
            </div>
          </div>

          <div class="control-group">
            <label class="control-label" for="txt_lastname">Last Name:</label>
            <div class="controls">
              <input id="txt_lastname" class="input-xlarge" type="text" required="required" >
            </div>
          </div>

          <div class="control-group">
            <label class="control-label" for="txt_email">Email:</label>
            <div class="controls">
              <input id="txt_email" class="input-xlarge" type="email" required="required" >
            </div>
          </div>

          <div class="control-group">
            <label class="control-label" for="txt_structure">Structure:</label>
            <div class="controls">
              <input id="txt_structure" class="input-xlarge" type="text" required="required" >
            </div>
          </div>

          <div class="control-group">
            <label class="control-label" for="txt_city">City:</label>
            <div class="controls">
              <input id="txt_city" class="input-xlarge" type="text" required="required" >
            </div>
          </div>

          <div class="control-group">
            <label class="control-label" for="txt_country">Country:</label>
            <div class="controls">
              <input id="txt_country" class="input-xlarge" type="text" required="required" >
            </div>
          </div>

          <div class="control-group">
            <label class="control-label" for="txt_sshkey">SSH public key :</label>
            <div class="controls">
                 <textarea id="txt_sshkey" class="input-xlarge" id="textarea" rows="3" required="required"></textarea>
            </div>
          </div>

          <div class="control-group">
            <label class="control-label" for="txt_motivation">Motivation :</label>
            <div class="controls">
                <textarea id="txt_motivation" class="input-xlarge" id="textarea" rows="3" required="required"></textarea>
            </div>
          </div>



          <div class="control-group" id="cg_captcha">
            <label class="control-label" for="txt_motivation">Anti-spam :</label>
            <div class="controls">
                <input id="captcha" class="input-xlarge" name="captcha"  />
                <br/>
                <br/>
                    <a href="#" onclick="
                    document.getElementById('captcha-img').src='captcha/captcha.php?'+Math.random();
                    document.getElementById('captcha').focus();"
                    id="change-image" title="Click to change text">
                    <img style="border:solid 1px" src="captcha/captcha.php" id="captcha-img" /></a>
                
            </div>
          </div>


            <button id="btn_signup" class="btn btn-primary" type="submit">Submit</button>

            </form>
        	<a href="#" onClick="showReset()">Forgot your password?</a> - <a href="#" onClick="showLogin()">Login</a>

        </div>
      </div>
      
      
      <hr>

<?php include('footer.php') ?>

    <script type="text/javascript">
    
    $(document).ready(function(){

        <?php if(isset($_GET['logout'])) { ?>
	        $("#div_error_login").removeClass("alert-error");
	        $("#div_error_login").addClass("alert-success");
	        $("#div_error_login").html("You have been successfully logged out.");
	        $("#div_error_login").show();
        <?php } ?>

		// submit login
        $('#login_form').bind('submit', function(){
	  
	    
	        var userlogin = {"login":$("#txt_login").val(),"password":$("#txt_password").val()};
	        
	        console.log(userlogin);
	
	        $.ajax({
	            url: "auth.php",
	            type: "POST",
	            data: userlogin,
	            success:function(response){
	                window.location.href=".";
	            },
	            error:function(XMLHttpRequest, textStatus, errorThrows){
	                $("#div_error_login").addClass("alert-error");
	                $("#div_error_login").html("Wrong login or password");
	                $("#div_error_login").show();
	            }
	        });
	        return false;
	    });

	    // submit reset password 
        $('#reset_form').bind('submit', function(){

	        var user = {
	            "email":$("#txt_email_reset").val(),
	        };
	        
	        console.log(user);
	        
	        $.ajax({
	            url: "/rest/users/"+$("#txt_email_reset").val()+"?resetpassword",
	            type: "PUT",
	            dataType: "text",
	            contentType: "application/json; charset=utf-8",
	            data: JSON.stringify(user),
	            success:function(data){
	                $("#div_error_reset").show();
	                $("#div_error_reset").removeClass("alert-error");
	                $("#div_error_reset").addClass("alert-success");
	                $("#div_error_reset").html("Your password was reset, check your inbox");
	            },
	            error:function(XMLHttpRequest, textStatus, errorThrows){
	                $("#div_error_reset").show();
	                $("#div_error_reset").removeClass("alert-success");
	                $("#div_error_reset").addClass("alert-error");
	                $("#div_error_reset").html("This email was not found");
	            }
	        });
	        
	        return false;
	     });

	    // submit sign up
        $('#signup_form').bind('submit', function(){
        
            var userregister = {
            "firstName":$("#txt_firstname").val(),
            "lastName":$("#txt_lastname").val(),
            "email":$("#txt_email").val(),
            "structure":$("#txt_structure").val(),
            "city":$("#txt_city").val(),
            "country":$("#txt_country").val(),
            "sshPublicKey":$("#txt_sshkey").val(),
            "motivations":$("#txt_motivation").val(),
            "password":$("#txt_password").val(),
            "validate":true,
            "captcha":$("#captcha").val(),
            };
            
            console.log(userregister);
            
            $.ajax({
                url: "captcha.php",
                type: "POST",
                data: userregister,
                success:function(data){
                     //window.location.replace(".");
                    /* TODO */
                    /* clear sign up form
                       print success message */
                    $('#signup_form').each (function(){
                   		this.reset();
                   	});
                    document.getElementById('captcha-img').src='captcha/captcha.php?'+Math.random();
                    $("#div_error_signup").show();
                    $("#div_error_signup").removeClass("alert-error");
                    $("#div_error_signup").addClass("alert-success");
                    $("#div_error_signup").html("Your registration has been saved and submitted to validation by an administrator.");
            	},
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    if(XMLHttpRequest.status == 409)
                    {
                        $("#div_error_signup").show();
                        $("#div_error_signup").removeClass("alert-success");
                        $("#div_error_signup").addClass("alert-error");
                        $("#div_error_signup").html("This user is already registered");
                    }
                    else if(XMLHttpRequest.status == 403)
                    {
                        $("#div_error_signup").show();
                        $("#div_error_signup").removeClass("alert-success");
                        $("#div_error_signup").addClass("alert-error");
                        $("#div_error_signup").html("Error");
                        $("#cg_captcha").addClass("error");
                        $("#captcha").focus();
                    }
                    else {
                        $("#div_error_signup").show();
                        $("#div_error_signup").removeClass("alert-success");
                        $("#div_error_signup").addClass("alert-error");
                        $("#div_error_signup").html(errorThrows); 
                    }  
                }
            });
            
        	return false;
        
        });
        
    });

    function showReset() {
        $('#login_div').hide();
        $('#signup_div').hide();
        $('#reset_div').show();
    }

    function showSignup() {
        $('#login_div').hide();
        $('#reset_div').hide();
        $('#signup_div').show();
    }

    function showLogin() {
        $('#reset_div').hide();
        $('#signup_div').hide();
        $('#login_div').show();
    }

    </script>

  </body>
</html>
