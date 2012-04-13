<?php

session_start();
if(!$_SESSION['is_auth'] || !$_SESSION['is_admin'] ) {
    header("location: .");
    exit();
}

?>

<?php include("header.php") ?>

    <div class="container">
        
      <div class="row">
        <div class="span12">
          <h2>Users</h2>
            <p></p>
                <table id="tbl_users" class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th>Login</th>
                        <th>FirstName</th>
                        <th>LastName</th>
                        <th>Email</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                </table>
        </div>

      </div>

        <div id="edit_modal" class="modal hide fade">
            <div class="modal-header">
              <a class="close" data-dismiss="modal">X</a>
              <h3>Edit user <span id="s_login"></span></h3>
              
            </div>
           <div class="modal-body">
               <div class="alert alert-error" id="div_error" style="display:none"></div>
               
                <form class="well form-horizontal" id="form_modify">

              <div class="control-group">
                <label class="control-label" for="txt_firstname">First Name:</label>
                <div class="controls">
                    <input id="txt_firstname" type="text" class="input-xlarge" required="required"/>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_lastname">Last Name:</label>
                <div class="controls">
                    <input id="txt_lastname" type="text" class="input-xlarge" required="required"/>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_login">Login:</label>
                <div class="controls">
                    <input id="txt_login" type="text" class="input-xlarge" required="required" disabled="disabled"/>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_email">Email:</label>
                <div class="controls">
                    <input id="txt_email" type="email" class="input-xlarge" required="required"/>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_structure">Structure:</label>
                <div class="controls">
                    <input id="txt_structure" type="text" class="input-xlarge" required="required" />
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_city">City:</label>
                <div class="controls">
                    <input id="txt_city" type="text" class="input-xlarge" required="required" />
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_country">Country:</label>
                <div class="controls">
                    <input id="txt_country" type="text" class="input-xlarge" required="required" />
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_sshkey">SSH Key:</label>
                <div class="controls">
                    <textarea id="txt_sshkey" class="input-xlarge" rows="3" required="required"></textarea>
                </div>
              </div>
              
               <div class="control-group">
                <label class="control-label" for="txt_motivation">Motivation:</label>
                <div class="controls">
                    <textarea id="txt_motivation" class="input-xlarge" rows="3" required="required"></textarea>
                </div>
              </div>
              
            </div>
            
            <div class="modal-footer">
                   <button id="btn_modify" class="btn btn-primary" type="submit">Modify</button>
                </form>
            </div>
            
        </div>


      <hr>

<?php include('footer.php') ?>


    <script type="text/javascript">

    var users = {};
    var selectedUser = {};

    $(document).ready(function()
    {
        $('#edit_modal').modal('hide');

        /* Load data in the table */
        $.ajax({
            url: "http://devgrenoble.senslab.info/rest/admin/users",
            type: "GET",
            dataType: "json",
            success:function(data){
                users = data;
                var i = 0;
                $.each(data, function(key,val) {
                    var btnState = "";
                    var btnClass = "";
                    if(val.validate) { btnState = 'disabled="true"' }
                    else { btnClass="btn-primary" };
                    
                    if(val.admin) {
                        btnIsAdminState = 'btn-warning';
                    }
                    else { 
                        btnIsAdminState = '';
                    }

                    //user row
                    $("#tbl_users tbody").append(
                    '<tr data=' + i + '>'+
                    '<td>' + val.login + '</td>'+
                    '<td>' + val.firstName + '</td>'+
                    '<td>'+ val.lastName +'</td>'+
                    '<td><a href="mailto:' + val.email + '">' + val.email + '</a></td>'+
                    '<td><a href="#"><button class="btn ' + btnClass + ' validate " data="'+i+'" ' + btnState + 'onClick="validateUser('+i+')">Validate</button></a> ' +
                    '<a href="#" class="btn btn-edit" data-toggle="modal" data="'+i+'">Edit</a> ' +
                    '<a href="#" class="btn btn-admin '+btnIsAdminState+'" data="'+i+'" data-state="'+val.admin+'" onClick="setAdmin('+i+')">Admin</a> ' +
                    '<a href="#"><button class="btn btn-danger" data="'+i+'" onClick="deleteUser('+i+')">Delete</button></a></td>'
                    +'</tr>');
                    i++;
                });

                //action on edit button: load data on modal form
                $(".btn-edit").click(function(){
                    var userId = $(this).attr("data");
                    selectedUser = users[userId];
                    $('#s_login').html(selectedUser.login);
                    $('#txt_sshkey').val(selectedUser.sshPublicKey);
                    $('#txt_firstname').val(selectedUser.firstName);
                    $('#txt_lastname').val(selectedUser.lastName);
                    $('#txt_login').val(selectedUser.login);
                    $('#txt_email').val(selectedUser.email);
                    $('#txt_structure').val(selectedUser.structure);
                    $('#txt_city').val(selectedUser.city);
                    $('#txt_country').val(selectedUser.country);
                    $('#txt_motivation').val(selectedUser.motivations);
                    $("#edit_modal").modal('show');
                });
            },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                alert("error: " + errorThrows)
            }
        });
    });
    
    /* Delete a user */
    function deleteUser(id) 
    {
        if(confirm("Delete user?"))
        {
            var userdelete = users[id];
            $.ajax({
            url: "http://devgrenoble.senslab.info/rest/admin/users/"+userdelete.login,
                type: "DELETE",
                contentType: "application/json",
                dataType: "text",
            
                success:function(data){
                    $("tr[data="+id+"]").remove()    
                },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    alert("error: " + errorThrows)
                }
            });
        }
    };
    
    
    /* Validate a user */
    function validateUser(id) 
    {
        if(confirm("Validate user?"))
        {
            //validate field
            var uservalidate = users[id];
            uservalidate.validate = true;
            
            $.ajax({
                url: "http://devgrenoble.senslab.info/rest/admin/users/"+uservalidate.login,
                type: "PUT",
                dataType: "text",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify(uservalidate),
                success:function(data){
                    $("tr[data="+id+"] .validate").attr("disabled","disabled")
                    $("tr[data="+id+"] .validate").removeClass("btn-primary");
                },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    alert("error:" + errorThrows)
                }
            })
        };
    };
    
    
    /* Save values from edit modal */
    $('#form_modify').bind('submit', function()
    {
        //change all value
        selectedUser.firstName = $("#txt_firstname").val();
        selectedUser.lastName = $("#txt_lastname").val();
        selectedUser.login = $("#txt_login").val();
        selectedUser.email = $("#txt_email").val();
        selectedUser.sshPublicKey = $("#txt_sshkey").val();
        selectedUser.motivations = $("#txt_motivation").val();
        selectedUser.structure = $("#txt_structure").val();
        selectedUser.city = $("#txt_city").val();
        selectedUser.country = $("#txt_country").val();
        
        $.ajax({
            url: "http://devgrenoble.senslab.info/rest/admin/users/"+selectedUser.login,
            type: "PUT",
            dataType: "text",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(selectedUser),
            success:function(data){
                $("#edit_modal").modal('hide');
                $("#div_error").html("");
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
    
    
    /* Validate a user */
    function setAdmin(id) 
    {
        var state = $("tr[data="+id+"] .btn-admin").attr("data-state");

        if(confirm("Change Admin state?"))
        {
            var user = users[id];
            
            //toggle admin value
            user.admin = !user.admin;
            
            $.ajax({
                url: "http://devgrenoble.senslab.info/rest/admin/users/"+user.login,
                type: "PUT",
                dataType: "text",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify(user),
                success:function(data){
                    if(state == "true") {
                        $("tr[data="+id+"] .btn-admin").removeClass("btn-warning");
                        $("tr[data="+id+"] .btn-admin").attr("data-state","false");
                    }
                    else {
                        $("tr[data="+id+"] .btn-admin").addClass("btn-warning");
                        $("tr[data="+id+"] .btn-admin").attr("data-state","true");
                    }
                },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    alert("error:" + errorThrows)
                }
            })
        };
    };    
    
    
    </script>

  </body>
</html>
