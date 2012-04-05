<?php include("header.php") ?>


    <div class="container">

      <!-- Example row of columns -->
      <div class="row">
        <div class="span12">
          <h2>Sign up</h2>
           
            <div class="alert alert-error" id="div_error" style="display:none"></div>
           
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

            <button id="btn_register" class="btn btn-primary" type="submit">Submit</button>

            </form>

        </div>
      </div>

      <hr>

<?php include('footer.php') ?>



    

    <script type="text/javascript">

$(document).ready(function(){

    $("#txt_firstname").focus();

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
        "validate":true
        };
        
        console.log(userregister);
        
        $.ajax({
            url: "http://devgrenoble.senslab.info/rest/users",
            type: "POST",
            dataType: "text",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(userregister),
            success:function(data){
                 window.location.replace("http://devgrenoble.senslab.info/portal");
        },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                if(XMLHttpRequest.status == 409)
                {
                    $("#div_error").show();
                    $("#div_error").removeClass("alert-success");
                    $("#div_error").addClass("alert-error");
                    $("#div_error").html("This user is already registered");
                }
                else {
                    $("#div_error").show();
                    $("#div_error").removeClass("alert-success");
                    $("#div_error").addClass("alert-error");
                    $("#div_error").html(errorThrows); 
                }  
            }
        });
        
    return false;
    
    });

    
 });
    </script>

  </body>
</html>
