<?php include("header.php") ?>


    <div class="container">

      <!-- Example row of columns -->
      <div class="row">
        <div class="span12">
          <h2>Ask for a new password</h2>
           
           <div class="alert alert-error" id="div_error" style="display:none"></div>
           
            <form class="well form-horizontal" id="reset_form">

          <div class="control-group">
            <label class="control-label" for="txt_firstname">Enter your email:</label>
            <div class="controls">
              <input id="txt_email" class="input-xlarge" type="email" required="required"  >
            </div>
          </div>

            <button id="btn_register" class="btn btn-primary" type="submit">Submit</button>

            </form>

        </div>
      </div>

      <hr>

<?php include('footer.php') ?>



    

    <script type="text/javascript">

$(document).ready(function(){

    $("#txt_email").focus();

    $('#reset_form').bind('submit', function(){
    
        var user = {
        "email":$("#txt_email").val(),
        };
        
        console.log(user);
        
        $.ajax({
            url: "http://devgrenoble.senslab.info/rest/admin/user?resetpassword",
            type: "POST",
            dataType: "text",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(user),
            success:function(data){
                $("#div_error").show();
                $("#div_error").removeClass("alert-error");
                $("#div_error").addClass("alert-success");
                $("#div_error").html(data);
        },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                $("#div_error").show();
                $("#div_error").removeClass("alert-success");
                $("#div_error").addClass("alert-error");
                $("#div_error").html(errorThrows);
            }
        });
        
    return false;
    
    });

    
 });
    </script>

  </body>
</html>
