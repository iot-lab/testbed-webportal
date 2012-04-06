<?php 
session_start();


//DEBUG ONLY: force a valid auth
//$_SESSION['is_auth'] = true;

if($_SESSION['is_auth']) {
    header("location: dashboard.php");
    exit();
}

?>


<?php include("header.php") ?>

    <div class="container">

      <!-- Example row of columns -->
      <div class="row">
        <div class="span12">
          <h2>Login</h2>
           
           <div class="alert alert-error" id="div_error" style="display:none"></div>
           
            <form class="well form-inline">
              Login: <input id="txt_login" type="text" class="input-small" placeholder="Login">
              Password: <input id="txt_password" type="password" class="input-small" placeholder="Password">
              <button id="btn_submit" type="submit" class="btn">Log in</button>
            </form>
        <a href="signup.php">Ask for an account</a> - <a href="resetpassword.php">Forgot your password ?</a>

        </div>

      </div>

      <hr>

<?php include('footer.php') ?>


    <script type="text/javascript">

    var userjson = {};


$("#btn_submit").click(function(){
    
        var userlogin = {"login":$("#txt_login").val(),"password":$("#txt_password").val()};
        
        console.log(userlogin);
    
        $.ajax({
            url: "auth.php",
            //url: "http://devgrenoble.senslab.info/rest/admin/user?bind",
            type: "POST",
            //data: JSON.stringify(userlogin),
            data: {"login":$("#txt_login").val(),"password":$("#txt_password").val()},
            success:function(data){
                // similar behavior as an HTTP redirect
                window.location.replace(".");
                // similar behavior as clicking on a link
                //window.location.href = "http://devgrenoble.senslab.info/portal";
                
                /*$("#div_error").show();
                $("#div_error").removeClass("alert-error");
                $("#div_error").addClass("alert-success");
                $("#div_error").html("Ok");*/
        },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                $("#div_error").show();
                $("#div_error").removeClass("alert-success");
                $("#div_error").addClass("alert-error");
                $("#div_error").html("Wrong login or password");
            }
        });
        
    return false;
    
    });

    
 
    </script>

  </body>
</html>
