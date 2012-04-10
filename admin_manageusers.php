<?php

session_start();
if(!$_SESSION['is_auth'] || !$_SESSION['is_admin'] ) {
    header("location: .");
    exit();
}

?>

<?php include("header.php") ?>

    <div class="container">

      <!-- Example row of columns -->
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
                <label class="control-label" for="txt_sshkey">SSH Key:</label>
                <div class="controls">
                    <textarea id="txt_sshkey" class="input-xlarge" rows="3" required="required"></textarea>
                </div>
              </div>

                <button id="btn_modify" class="btn btn-primary" type="submit">Modify</button>
                </form>
            </div>
        </div>


      <hr>

<?php include('footer.php') ?>


    <script type="text/javascript">

    var userjson = {};
    var useredit = {};

    $(document).ready(function(){
            
        $('#edit_modal').modal('hide');

        //load data
        $.ajax({
            url: "http://devgrenoble.senslab.info/rest/admin/users",
            type: "GET",
            dataType: "json",
            success:function(data){
                userjson = data;
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
                   

                    $("#tbl_users tbody").append(
                    '<tr data=' + i + '>'+
                    '<td>' + val.login + '</td>'+
                    '<td>' + val.firstName + '</td>'+
                    '<td>'+ val.lastName +'</td>'+
                    '<td><a href="mailto:' + val.email + '">' + val.email + '</a></td>'+
                    '<td><a href="#"><button class="btn ' + btnClass + ' validate "' + btnState + 'onClick="validateUser('+i+')">Validate</button></a> ' +
                    '<a href="#" class="btn btn-edit" data-toggle="modal" data="'+i+'">Edit</a> ' +
                    '<a href="#" class="btn btn-admin '+btnIsAdminState+'" data="'+i+'" onClick="setAdmin('+i+','+val.admin+')">Admin</a> ' +
                    '<a href="#"><button class="btn btn-danger" onClick="deleteUser('+i+')">Delete</button></a></td>'
                    +'</tr>');
                    i++;
                });

                //action on Edit click button
                $(".btn-edit").click(function(){
                    var userid = $(this).attr("data");
                    useredit = userjson[userid];
                    $('#s_login').html(useredit.login);
                    $('#txt_sshkey').html(useredit.sshPublicKey);
                    $("#edit_modal").modal('show');
                });

            },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                alert("error: " + errorThrows)
            }
        });
    });
    
    //delete a user
    function deleteUser(id) {
        
        if(confirm("Delete user?"))
        {
            var userdelete = userjson[id];
            $.ajax({
            url: "http://devgrenoble.senslab.info/rest/admin/users",
                type: "DELETE",
                contentType: "application/json",
                data: JSON.stringify(userdelete),
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
    
    
    //validate a user
    function validateUser(id) {
        
        if(confirm("Validate user?"))
        {
            var uservalidate = userjson[id];
            $.ajax({
                url: "http://devgrenoble.senslab.info/rest/admin/users?validate",
                type: "POST",
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
    
    
    //save edit modal modifications
    $('#form_modify').bind('submit', function(){
    
        var usermodify = {
        "login":useredit.login,
        "sshPublicKey":$("#txt_sshkey").val(),
        };
        
        console.log(usermodify);
        
        $.ajax({
            url: "http://devgrenoble.senslab.info/rest/users",
            type: "POST",
            dataType: "text",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(usermodify),
            success:function(data){
                alert("Ok")
            },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                alert("Error")
            }
        });
        
    return false;
    
    });
    
    
    //validate a user
    function setAdmin(id,state) {
        
        if(state)
            url = "http://devgrenoble.senslab.info/rest/admin/users?deladmin";
        else
            url = "http://devgrenoble.senslab.info/rest/admin/users?addadmin";
    
        if(confirm("Change Admin state?"))
        {
            var uservalidate = userjson[id];
            $.ajax({
                url: url,
                type: "POST",
                dataType: "text",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify(uservalidate),
                success:function(data){
                        alert("ok");
                        //$("tr[data="+id+"] .validate").attr("disabled","disabled")
                        //$("tr[data="+id+"] .validate").removeClass("btn-primary");
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
