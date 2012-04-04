<?php 
session_start();
if(!$_SESSION['is_auth']) {
    header("location: http://devgrenoble.senslab.info/portal/");
    exit();
}

?>

<?php include("header.php") ?>

    <div class="container">
        
    <div class="row">
        <div class="span8">
          <h2>New Experiment</h2>
           <p>
           
           </p>
        </div>
        <div class="span4">
          <h2>Personal dashboard</h2>
          <p><i class="icon-cog"></i> Experiments:</p>
            <ul>
                <li><span class="badge badge-success">1</span> running</li>
                <li><span class="badge badge-info">2</span> upcoming</li>
                <li><span class="badge">851</span> done</li>
            </ul>
          <p><i class="icon-th"></i> Profiles: 2 <p>
          <p><i class="icon-home"></i> Home's quota: 60% (600/1000Mo)
            <div class="progress" style="width:200px">
              <div class="bar"
                   style="width: 60%;"></div>
            </div>
            </p>
            <!-- <p><i class="icon-user"></i> VM's Status: <button class="btn btn-success">ON</button></p> -->

            <hr/>
 
            <p><a href="#"><button class="btn btn-danger" onClick="new_password()">Get new password</button></a></p>
            <p><a data-toggle="modal" href="#ssh_modal">Modify SSH Key</a></p>

            <hr/>

            <p><a href="https://strasbourg.senslab.info/monika">View nodes status</a></p>
            <p><a href="https://strasbourg.senslab.info/drawgantt">View Gantt chart</a></p>
           
          </div>
      </div>
        
        
        <div id="ssh_modal" class="modal hide fade">
            <div class="modal-header">
              <a class="close" data-dismiss="modal">×</a>
              <h3>Modify SSH Key</h3>
            </div>
           <div class="modal-body">
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
        
        <?php include('footer.php') ?>

    <script type="text/javascript">
        
        $(document).ready(function(){
            
            $('#ssh_modal').modal('hide');
            
            function new_password() {
                var user = {
                    "login": "<?php echo $_SESSION["login"] ?>",
                };
                
                $.ajax({
                url: "http://devgrenoble.senslab.info/rest/admin/user?modpassword",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(user),
                    dataType: "text",
                
                    success:function(data){
                        alert("ok");
                    },
                    error:function(XMLHttpRequest, textStatus, errorThrows){
                        alert("error: " + errorThrows);
                    }
                });
            }

            

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
