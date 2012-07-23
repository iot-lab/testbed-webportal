<?php
session_start();

if(!$_SESSION['is_auth'] || !$_SESSION['is_admin'] ) {
    header("location: .");
    exit();
}


include("header.php") ?>


    <div class="container">
      
    <div class="row">
        <div class="span12">
          <h2>Users</h2>
        </div>
    </div>
  
    <div class="row">
        <div class="span2" style="text-align:left;padding-bottom:5px;padding-left:5px;">
          <a href="#" class="btn btn-add" data-toggle="modal">Add user(s)</a>
        </div>
    </div>
      
      <div class="row">
        <div class="span12">
        		<div id="tbl_exps_processing" class="dataTables_processing">Processing...</div>
                <div class="alert alert-error" id="div_msg" style="display:none">Loading ...</div>
                <table id="tbl_users" class="table table-bordered table-striped table-condensed" style="display:none">
                    <thead>
                        <tr>
                            <th>Login</th>
                            <th>FirstName</th>
                            <th>LastName</th>
                            <th>Email</th>
                            <th>Creation date</th>
                            <th width='50px'>isValid</th>
                            <th width='50px'>isAdmin</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
        </div>

      </div>

        <div id="add_modal" class="modal hide fade">
            <div class="modal-header">
              <a class="close" data-dismiss="modal">X</a>
              <h3>Add user(s)</h3>
              
            </div>
           <div class="modal-body">
               <div class="alert alert-error" id="div_error_add" style="display:none"></div>
               
                <form class="well form-horizontal" id="form_add">

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
                <label class="control-label" for="txt_howmany">How many user:</label>
                <div class="controls">
                    <input id="txt_howmany" type="text" class="input-xlarge" required="required" value="1"/>
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
                   <button id="btn_add" class="btn btn-primary" type="submit">Add</button>
                </form>
            </div>
            
        </div>

        <div id="edit_modal" class="modal hide fade">
            <div class="modal-header">
              <a class="close" data-dismiss="modal">X</a>
              <h3>Edit user <span id="s_login_e"></span><span id="s_id_e" style="display:none"></span></h3>
              
            </div>
           <div class="modal-body">
               <div class="alert alert-error" id="div_error_edit" style="display:none"></div>
               
                <form class="well form-horizontal" id="form_modify_user">

              <div class="control-group">
                <label class="control-label" for="txt_firstname_e">First Name:</label>
                <div class="controls">
                    <input id="txt_firstname_e" type="text" class="input-xlarge" required="required"/>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_lastname_e">Last Name:</label>
                <div class="controls">
                    <input id="txt_lastname_e" type="text" class="input-xlarge" required="required"/>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_login_e">Login:</label>
                <div class="controls">
                    <input id="txt_login_e" type="text" class="input-xlarge" required="required" disabled="disabled"/>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_email_e">Email:</label>
                <div class="controls">
                    <input id="txt_email_e" type="email" class="input-xlarge" required="required"/>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_structure_e">Structure:</label>
                <div class="controls">
                    <input id="txt_structure_e" type="text" class="input-xlarge" required="required" />
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_city_e">City:</label>
                <div class="controls">
                    <input id="txt_city_e" type="text" class="input-xlarge" required="required" />
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_country_e">Country:</label>
                <div class="controls">
                    <input id="txt_country_e" type="text" class="input-xlarge" required="required" />
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_sshkey_e">SSH Key:</label>
                <div class="controls">
                    <textarea id="txt_sshkey_e" class="input-xlarge" rows="3" required="required"></textarea>
                </div>
              </div>
              
               <div class="control-group">
                <label class="control-label" for="txt_motivation_e">Motivation:</label>
                <div class="controls">
                    <textarea id="txt_motivation_e" class="input-xlarge" rows="3" required="required"></textarea>
                </div>
              </div>
              
            </div>
            
            <div class="modal-footer">
                   <button id="btn_modify" class="btn btn-primary" type="submit">Modify</button>
                </form>
            </div>
            
        </div>
        

<?php include('footer.php') ?>

<link href="css/datatable.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="js/datatable.js"></script>
 
    <script type="text/javascript">

    var oTable;
    var users = {};
    var selectedUser = {};

    $(document).ready(function()
    {
        $('#add_modal').modal('hide');
        $('#edit_modal').modal('hide');

        /* Load data in the table */
        $.ajax({
            url: "/rest/admin/users",
            type: "GET",
            dataType: "json",
            success:function(data){
                users = data;
                var i = 0;
                $.each(data, function(key,val) {
                    var btnValidClass = "btn-primary";
                    var btnValidValue="Pending";
                    if(val.validate) {
                         btnValidClass="";
                         btnValidValue="Valid";
                    }

                    btnAdminClass = '';
                    btnAdminValue = "User";
                    if(val.admin) {
                        btnAdminClass = 'btn-warning';
                        btnAdminValue = "Admin";
                    }
 
                     //user row
                    $("#tbl_users tbody").append(
                    '<tr id=' + i + ' data=' + i + '>'+
                    '<td>' + val.login + '</td>'+
                    '<td class="firstName">' + val.firstName + '</td>'+
                    '<td class="lastName">'+ val.lastName +'</td>'+
                    '<td><a href="mailto:' + val.email + '" class="email">' + val.email + '</a></td>'+
                    '<td>'+ formatCreateTimeStamp(val.createTimeStamp) +'</td>'+
                    '<td><a href="#" class="btn btn-valid '+btnValidClass+'" data="'+i+'" data-state="'+val.validate+'" onClick="validateUser('+i+')">'+btnValidValue+'</a></td>' +
                    '<td><a href="#" class="btn btn-admin '+btnAdminClass+'" data="'+i+'" data-state="'+val.admin+'" onClick="setAdmin('+i+')">'+btnAdminValue+'</a></td>' +
                    '<td><a href="admin_manageexps.php?user='+val.login+'" class="btn btn-view">Exp</a> ' +
                        '<a href="#" class="btn btn-edit" data-toggle="modal" data="'+i+'">Edit</a> ' +
                        '<a href="#" class="btn btn-del btn-danger" data="'+i+'" onClick="deleteUser('+i+')">Delete</a></td>'
                    +'</tr>');
                    $("tr[data="+i+"] .btn-valid").width(50);
                    $("tr[data="+i+"] .btn-admin").width(50);
                    i++;
                });

                //action on edit button: load data on modal form
                $(".btn-edit").click(function(){
                    var userId = $(this).attr("data");
                    selectedUser = users[userId];
                    $('#s_login_e').html(selectedUser.login);
                    $('#s_id_e').html(userId);
                    $('#txt_sshkey_e').val(selectedUser.sshPublicKey);
                    $('#txt_firstname_e').val(selectedUser.firstName);
                    $('#txt_lastname_e').val(selectedUser.lastName);
                    $('#txt_login_e').val(selectedUser.login);
                    $('#txt_email_e').val(selectedUser.email);
                    $('#txt_structure_e').val(selectedUser.structure);
                    $('#txt_city_e').val(selectedUser.city);
                    $('#txt_country_e').val(selectedUser.country);
                    $('#txt_motivation_e').val(selectedUser.motivations);
                    $("#edit_modal").modal('show');
                });
                oTable = $('#tbl_users').dataTable({
                    "sDom": "<'row'<'span8'l><'span8'f>r>t<'row'<'span8'i><'span8'p>>",
                        "bPaginate": true,
                        "sPaginationType": "bootstrap",
                        "bLengthChange": true,
                        "bFilter": true,
                        "bSort": true,
                        "bInfo": true,
                        "bAutoWidth": false
                } );
                $('#tbl_users').show();
                $('#tbl_exps_processing').hide();
                
            },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                $('#tbl_exps_processing').hide();
                $("#div_msg").show();
                $("#div_msg").html("An error occurred while retrieving user list");
            }
        });
    });
    
    /* Delete a user */
    function deleteUser(id) {
        if(confirm("Delete user?")) {
            var userdelete = users[id];
            $.ajax({
                url: "/rest/admin/users/"+userdelete.login,
                type: "DELETE",
                contentType: "application/json",
                dataType: "text",
            
                success:function(data){
                    //$("tr[data="+id+"]").remove()
                    oTable.fnDeleteRow(document.getElementById(id),true);
                },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    alert("error: " + errorThrows)
                }
            });
        }
    };
    
    
    /* Toggle Valid state */
    function validateUser(id) {
        var state = $("tr[data="+id+"] .btn-valid").attr("data-state");
        var confirmText = "Validate user?";
        if(state=="true") confirmText="Unvalidate user?";

        if(confirm(confirmText)) {
            //toggle validate flag
            var user = users[id];
            user.validate = !user.validate;
            
            $.ajax({
                url: "/rest/admin/users/"+user.login,
                type: "PUT",
                dataType: "text",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify(user),
                success:function(data){
                    if(state == "false") {
                        oTable.fnUpdate('<a href="#" class="btn btn-valid" data="'+id+'" data-state="true" onClick="validateUser('+id+')">Valid</a>',id,5,true);
                    } else {
                        oTable.fnUpdate('<a href="#" class="btn btn-valid btn-primary" data="'+id+'" data-state="false" onClick="validateUser('+id+')">Pending</a>',id,5,true);
                    }
                    $("tr[data="+id+"] .btn-valid").width(50);
                },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    alert("error:" + errorThrows)
                }
            })
        };
    };
    
    
    /* Toggle Admin state */
    function setAdmin(id) {
        var state = $("tr[data="+id+"] .btn-admin").attr("data-state");
        var confirmText = "Set Admin state?";
        if(state=="true") confirmText="Unset Admin state?";


        if(confirm(confirmText)) {
            //toggle admin flag
            var user = users[id];
            user.admin = !user.admin;
            
            $.ajax({
                url: "/rest/admin/users/"+user.login,
                type: "PUT",
                dataType: "text",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify(user),
                success:function(data){
                    if(state == "false") {
                        oTable.fnUpdate('<a href="#" class="btn btn-admin btn-warning" data="'+id+'" data-state="true" onClick="setAdmin('+id+')">Admin</a>',id,6,true);
                    } else {
                        oTable.fnUpdate('<a href="#" class="btn btn-admin" data="'+id+'" data-state="false" onClick="setAdmin('+id+')">User</a>',id,6,true);
                    }
                    $("tr[data="+id+"] .btn-admin").width(50);
                },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    alert("error:" + errorThrows)
                }
            })
        };
    };   
    
    
    /* Save values from edit modal */
    $('#form_modify_user').bind('submit', function()
    {
        //change all value
        id = $('#s_id_e').html();
        selectedUser.firstName = $("#txt_firstname_e").val();
        selectedUser.lastName = $("#txt_lastname_e").val();
        selectedUser.login = $("#txt_login_e").val();
        selectedUser.email = $("#txt_email_e").val();
        selectedUser.sshPublicKey = $("#txt_sshkey_e").val();
        selectedUser.motivations = $("#txt_motivation_e").val();
        selectedUser.structure = $("#txt_structure_e").val();
        selectedUser.city = $("#txt_city_e").val();
        selectedUser.country = $("#txt_country_e").val();
        
        $.ajax({
            url: "/rest/admin/users/"+selectedUser.login,
            type: "PUT",
            dataType: "text",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(selectedUser),
            success:function(data){
                $("#edit_modal").modal('hide');
                $("#div_error_edit").html("");
                oTable.fnUpdate (selectedUser.firstName, document.getElementById(id), 1, false);
                oTable.fnUpdate (selectedUser.lastName, document.getElementById(id), 2, false);
                oTable.fnUpdate (selectedUser.email, document.getElementById(id), 3, true);
                //oTable.fnUpdate (selectedUser.email, document.getElementById(id),3, true);
            },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                $("#div_error_edit").html("An error occurred while saving user modifications");
                $("#div_error_edit").show();
            }
        });
        
    return false;
    
    });
    
    /* Add user(s) */
    $(".btn-add").click(function(){
        $("#add_modal").modal('show');
    });
    $('#form_add').bind('submit', function(){
    
        var userregister = {
        "firstName":$("#txt_firstname").val(),
        "lastName":$("#txt_lastname").val(),
        "email":$("#txt_email").val(),
        "structure":$("#txt_structure").val(),
        "city":$("#txt_city").val(),
        "country":$("#txt_country").val(),
        "sshPublicKey":$("#txt_sshkey").val(),
        "motivations":$("#txt_motivation").val(),
        "nbUsersToAdd":$("#txt_howmany").val()
        };
        
        console.log(userregister);
        
        $.ajax({
            url: "/rest/admin/users",
            type: "POST",
            dataType: "text",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(userregister),
            success:function(data){
                 window.location.reload();
            },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                var errorMsg="Error";
                if(XMLHttpRequest.status == 409) errorMsg="This user is already registered";
                else if(XMLHttpRequest.status == 403) errorMsg="Error";
                else errorMsg=errorThrows; 
                $("#div_error_add").html(errorMsg); 
                $("#div_error_add").show();
            }
        });
        
        return false;
    
    });

    function formatCreateTimeStamp(createTimeStamp) {
        /* "yyyy/mm/dd" */
        return createTimeStamp.substring(0,4)+"/"+createTimeStamp.substring(4,6)+"/"+createTimeStamp.substring(6,8);
    }
    
    
    </script>

  </body>
</html>
