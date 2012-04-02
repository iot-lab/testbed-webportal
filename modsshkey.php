<?php include("header.php") ?>


    <div class="container">

      <!-- Example row of columns -->
      <div class="row">
        <div class="span12">
          <h2>Modify SSH Key</h2>
           
           <div class="alert alert-error" id="div_error" style="display:none"></div>
           
            <form class="well form-horizontal" id="form_modify">

          <div class="control-group">
            <label class="control-label" for="txt_ssh">SSH Key:</label>
            <div class="controls">
                <textarea id="txt_ssh" class="input-xlarge" id="textarea" rows="3" required="required"></textarea>
            </div>
          </div>

            <button id="btn_modify" class="btn btn-primary" type="submit">Modify</button>

            </form>

        </div>
      </div>

      <hr>

<?php include('footer.php') ?>


    <script type="text/javascript">

$(document).ready(function(){

    $("#txt_ssh").focus();

    $('#form_modify').bind('submit', function(){
    
        var user = {
        "login":"<?php echo $_SESSION['login']?>",
        "sshPublicKey":$("#txt_ssh").val(),
        };
        
        $.ajax({
            url: "http://devgrenoble.senslab.info/rest/admin/user?modsshkey",
            type: "POST",
            dataType: "text",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(user),
            success:function(data){
                $("#div_error").show();
                $("#div_error").removeClass("alert-error");
                $("#div_error").addClass("alert-success");
                $("#div_error").html("Your SSH Key was modify");
        },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                $("#div_error").show();
                $("#div_error").removeClass("alert-success");
                $("#div_error").addClass("alert-error");
                $("#div_error").html("Error");
            }
        });
        
    return false;
    
    });

    
 });
    </script>

  </body>
</html>
