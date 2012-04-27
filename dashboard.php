<?php 
session_start();
if(!$_SESSION['is_auth']) {
    header("location: .");
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
 
 
            <p><a data-toggle="modal" href="#password_modal">Modify Password</a></p>
            <p><a data-toggle="modal" href="#ssh_modal">Modify SSH Key</a></p>

            <hr/>

            <p><a href="/monika">View nodes status</a></p>
            <p><a href="/drawgantt">View Gantt chart</a></p>
           
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
                    <textarea id="txt_ssh" class="input-xlarge" rows="3" required="required"></textarea>
                </div>
              </div>

                <button id="btn_modify" class="btn btn-primary" type="submit">Modify</button>
                </form>
            </div>
        </div>
        
        
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
        
        
        
        
        <?php include('footer.php') ?>

    <script type="text/javascript">
        
        $(document).ready(function(){
            
            /* Retrieve current sshkey */
            $.ajax({
                url: "/rest/users/"+<?php echo '"'.$_SESSION['login'].'"'?>+"/sshkeys",
                type: "GET",
                //contentType: "application/json",
                //data: JSON.stringify({"login":"<?php echo $_SESSION['login'] ?>"}),
                data: {},
                dataType: "text",
                success:function(data){
                    $("#txt_ssh").val(data);
            },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    alert("error" + textStatus);
                }
            });
            
            $('#ssh_modal').modal('hide');
            $('#password_modal').modal('hide');
        
            /* Modify SSH Key */
            $('#form_modify').bind('submit', function(){
                var user = {
                    "sshkeys":$("#txt_ssh").val(),
                };
                
                $.ajax({
                    url: "/rest/users/"+<?php echo '"'.$_SESSION['login'].'"'?>+"/sshkeys",
                    type: "PUT",
                    dataType: "text",
                    contentType: "application/json; charset=utf-8",
                    data: JSON.stringify(user),
                    success:function(data){
                        $("#div_error").show();
                        $("#div_error").removeClass("alert-error");
                        $("#div_error").addClass("alert-success");
                        $("#div_error").html("Your SSH Key was modify");
                        $("#ssh_modal").modal("hide");
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
            
            
            /* Modify Password Key */
            $('#form_modify_password').bind('submit', function(){
            
                if($("#txt_new_password").val() != $("#txt_cnew_password").val()) 
                {
                    $("#div_error_password").show();
                    $("#div_error_password").removeClass("alert-success");
                    $("#div_error_password").addClass("alert-error");
                    $("#div_error_password").html("Please confirm password");
                    return false;
                }
            
            
                var user = {
                    "newPassword":$("#txt_new_password").val(),
                    "oldPassword":$("#txt_current_password").val(),
                };
                
                $.ajax({
                    url: "/rest/users/"+<?php echo '"'.$_SESSION['login'].'"'?>+"/password",
                    type: "PUT",
                    dataType: "text",
                    contentType: "application/json; charset=utf-8",
                    data: JSON.stringify(user),
                    success:function(data){
                        $("#div_error_password").show();
                        $("#div_error_password").removeClass("alert-error");
                        $("#div_error_password").addClass("alert-success");
                        $("#div_error_password").html("Your Password was modify");
                        $("#password_modal").modal("hide");
                },
                    error:function(XMLHttpRequest, textStatus, errorThrows){
                        $("#div_error_password").show();
                        $("#div_error_password").removeClass("alert-success");
                        $("#div_error_password").addClass("alert-error");
                        $("#div_error_password").html("Error");
                    }
                });
                
            return false;
            
            });
            
        });
    
        
    </script>

  </body>
</html>
