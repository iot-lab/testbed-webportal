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
            type: "POST",
            data: userlogin,
            success:function(response){
                $("#div_error").show();
                $("#div_error").removeClass("alert-error");
                $("#div_error").addClass("alert-success");
                $("#div_error").html("OK, please wait ...");
                other_function();
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


    function other_function() {

        $.ajax({
            url: "http://"+$("#txt_login").val()+":"+$("#txt_password").val()+"@devgrenoble.senslab.info/rest/users?login",
            type: "GET",
            success:function(response){
                window.location.replace("http://devgrenoble.senslab.info/portal");
            }
        });
        
        return false;
    }   
 
    </script>

  </body>
</html>
