<?php
session_start();

if (!isset($_SESSION['is_auth']) || !$_SESSION['is_auth'] || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("location: .");
    exit();
}

include("header.php");
?>


<div class="container">

<div class="row">
    <div class="col-md-12">
        <h2>Users</h2>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 text-right pull-right">
        <button type="button" class="btn btn-lg btn-default btn-add" data-toggle="modal" aria-label="Add user(s)">
          <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
          Add user(s)
        </button>
    </div>
    <div class="col-sm-6">
      <span class="lead">Show: </span>
      <div class="radio">
        <label>
          <input type="radio" name="table_content_mode" value="pending" checked>
          Only pending users
        </label>
      </div>
      <div class="radio">
        <label>
          <input type="radio" name="table_content_mode" value="all">
          All users with email including
          <form id="form_email_filter" class="form-inline">
            <fieldset id="fieldset_email_filter"disabled>
              <div class="input-group">
                <input type="text" id="email_filter" class="form-control" placeholder="email pattern">
                <div class="input-group-btn">
                  <button type="submit" class="btn btn-default" aria-label="Search">
                    <span class="glyphicon glyphicon-search"></span>
                  </button>
                </div>
              </div>
            </fieldset>
          </form>
        </label>
      </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="tbl_users_processing" class="dataTables_processing">Processing...</div>
        <div class="alert alert-danger" id="div_msg" style="display:none">Loading ...</div>
        <table id="tbl_users" class="table table-striped table-condensed" style="display:none">
            <thead>
            <tr>
                <th>Login</th>
                <th>FirstName</th>
                <th>LastName</th>
                <th>Email</th>
                <th>Creation date</th>
                <th width='50px'>State</th>
                <th width='50px'>Role</th>
                <th>Options</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

</div>


<!--  MODAL WINDOW FOR ADDING USER(S) -->

<div id="add_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3>Add user(s)</h3>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" id="div_error_add" style="display:none"></div>

                <div class="tabbable">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_SA" data-toggle="tab">Single Account Creation</a></li>
                        <li><a href="#tab_MA" data-toggle="tab">Multiple Accounts Creation</a></li>
                    </ul>

                    <div class="tab-content">

                        <!-- PANEL FOR SINGLE ACCOUNT CREATION FORM -->

                        <div class="tab-pane active" id="tab_SA">
                            <form class="well form-horizontal" id="form_add_SA">


                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="txt_firstname_SA">First Name:</label>

                                    <div class="col-lg-8">
                                        <input id="txt_firstname_SA" type="text" class="form-control"
                                               required="required"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="txt_lastname_SA">Last Name:</label>

                                    <div class="col-lg-8">
                                        <input id="txt_lastname_SA" type="text" class="form-control"
                                               required="required"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="txt_email_SA">Email:</label>

                                    <div class="col-lg-8">
                                        <input id="txt_email_SA" type="email" class="form-control" required="required"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="txt_organization_SA">Organization:</label>

                                    <div class="col-lg-8">
                                        <input id="txt_organization_SA" type="text" class="form-control"
                                               required="required"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="txt_city_SA">City:</label>

                                    <div class="col-lg-8">
                                        <input id="txt_city_SA" type="text" class="form-control" required="required"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="txt_country_SA">Country:</label>

                                    <div class="col-lg-8">
                                        <select id="txt_country_SA" class="form-control"
                                                required="required"><?php include 'scripts/countries.html'; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="txt_sshkey_SA">SSH Key:</label>

                                    <div class="col-lg-8">
                                        <textarea id="txt_sshkey_SA" class="form-control" rows="3"
                                                  required="required"></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="txt_motivation_SA">Motivation:</label>

                                    <div class="col-lg-8">
                                        <textarea id="txt_motivation_SA" class="form-control" rows="3"
                                                  required="required"></textarea>
                                    </div>
                                </div>

                                <button id="btn_add_SA" class="btn btn-primary" type="submit">Add</button>
                            </form>
                        </div>

                        <!-- PANEL FOR MULTIPLE ACCOUNTS CREATION FORM -->

                        <div class="tab-pane" id="tab_MA">
                            <form class="well form-horizontal" id="form_add_MA">

                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="txt_howmany_MA">How many
                                        accounts:</label>

                                    <div class="col-lg-8">
                                        <input id="txt_howmany_MA" type="text" class="form-control" required="required"
                                               value="1"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="txt_login_MA">Login:</label>

                                    <div class="col-lg-8">
                                        <input id="txt_login_MA" type="text" class="form-control" required="required"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="txt_event_MA">Event:</label>

                                    <div class="col-lg-8">
                                        <input id="txt_event_MA" type="text" class="form-control" required="required"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="txt_organization_MA">Organization:</label>

                                    <div class="col-lg-8">
                                        <input id="txt_organization_MA" type="text" class="form-control"
                                               required="required"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="txt_city_MA">City:</label>

                                    <div class="col-lg-8">
                                        <input id="txt_city_MA" type="text" class="form-control" required="required"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="txt_country_MA">Country:</label>

                                    <div class="col-lg-8">
                                        <select id="txt_country_MA" class="form-control"
                                                required="required"><?php include 'scripts/countries.html'; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="txt_sshkey_MA">SSH Key:</label>

                                    <div class="col-lg-8">
                                        <textarea id="txt_sshkey_MA" class="form-control" rows="3"
                                                  required="required"></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="txt_motivation_MA">Motivation:</label>

                                    <div class="col-lg-8">
                                        <textarea id="txt_motivation_MA" class="form-control" rows="3"
                                                  required="required"></textarea>
                                    </div>
                                </div>

                                <button id="btn_add_MA" class="btn btn-primary" type="submit">Add</button>
                            </form>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<!--  MODAL WINDOW FOR EDITING USER -->

<div id="edit_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3>Edit user <span id="s_login_e"></span><span id="s_id_e" style="display:none"></span></h3>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" id="div_error_edit" style="display:none"></div>
                <form class="well form-horizontal" id="form_modify_user">


                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="txt_firstname_e">First Name:</label>

                        <div class="col-lg-8">
                            <input id="txt_firstname_e" type="text" class="form-control" required="required"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="txt_lastname_e">Last Name:</label>

                        <div class="col-lg-8">
                            <input id="txt_lastname_e" type="text" class="form-control" required="required"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="txt_login_e">Login:</label>

                        <div class="col-lg-8">
                            <input id="txt_login_e" type="text" class="form-control" required="required"
                                   disabled="disabled"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="txt_email_e">Email:</label>

                        <div class="col-lg-8">
                            <input id="txt_email_e" type="email" class="form-control" required="required"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="txt_organization_e">Organization:</label>

                        <div class="col-lg-8">
                            <input id="txt_organization_e" type="text" class="form-control" required="required"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="txt_city_e">City:</label>

                        <div class="col-lg-8">
                            <input id="txt_city_e" type="text" class="form-control" required="required"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="txt_country_e">Country:</label>

                        <div class="col-lg-8">
                            <input id="txt_country_e" type="text" class="form-control" required="required"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="txt_motivation_e">Motivation:</label>

                        <div class="col-lg-8">
                            <textarea id="txt_motivation_e" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="tabbable">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_SSH1_e" data-toggle="tab">SSH Key 1</a></li>
                            <li><a href="#tab_SSH2_e" data-toggle="tab">SSH Key 2</a></li>
                            <li><a href="#tab_SSH3_e" data-toggle="tab">SSH Key 3</a></li>
                            <li><a href="#tab_SSH4_e" data-toggle="tab">SSH Key 4</a></li>
                            <li><a href="#tab_SSH5_e" data-toggle="tab">SSH Key 5</a></li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_SSH1_e">
                                <div class="form-group">
                                    <textarea id="txt_sshkey_e" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_SSH2_e">
                                <div class="form-group">
                                    <textarea id="txt_sshkey_e2" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_SSH3_e">
                                <div class="form-group">
                                    <textarea id="txt_sshkey_e3" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_SSH4_e">
                                <div class="form-group">
                                    <textarea id="txt_sshkey_e4" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_SSH5_e">
                                <div class="form-group">
                                    <textarea id="txt_sshkey_e5" class="form-control" rows="3"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                    <button id="btn_modify" class="btn btn-primary" type="submit">Modify</button>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<!--  MODAL WINDOW FOR EMAILING USER -->

<div id="email_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3>Send a mail to user <span id="s_login_email"></span></h3>
            </div>
            <div class="modal-body">
                <form id="sendMail" class="well" id="form_email">
                    <div class="form-group">
                        <input type="hidden" id="to" name="to" value=""/>
                        <label class="control-label">Subject:</label>
                        <input type="text" name="subject" class="form-control" value="IoT-LAB registration rejection"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Message:</label>
                        <textarea rows="7" class="form-control" name="message">
Dear user,

Please consider re-filling the sign-up form, with an academic/professional (non-/*gmail/hotmail/yahoo/personal*/ ) mail address.

For your information, I will reject this first subscription.

Regards,</textarea>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Send Email"/>
                </form>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


</div> <!-- container -->

<link href="css/datatable.css" rel="stylesheet">
<link href="css/datatable-custom.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="js/datatable.js"></script>

<script type="text/javascript">

var oTable;
var users = {};
var selectedUser = {};

var admin_users_url = "/rest/admin/users";
var pending_option = "?validate=0";
var email_option = "?email=";

$(document).ready(function () {

    $("#admin").addClass("active");
    $("#admin_users").addClass("active");

    $('#add_modal').modal('hide');
    $('#edit_modal').modal('hide');
    $("#email_modal").modal('hide');

    buildUsersTable();
});

/* Load data in the table */
function buildUsersTable() {
  $('#tbl_users').hide();
  $('#tbl_users_processing').show();

  var url = admin_users_url;
  if($('input[type=radio][name=table_content_mode]:checked').val() == "pending")
    url += pending_option;
  else {
    //alert($("#email_filter").val());
    url += email_option + $("#email_filter").val();
  }
  $.ajax({
    url: url,
    type: "GET",
    dataType: "json",
    success: function (data) {
        users = data;
        if(oTable && (typeof oTable != "undefined"))
            oTable.fnClearTable();
        var i = 0;
        $.each(data, function (key, val) {
            var btnValidClass = "btn-primary";
            var btnValidValue = "Pending";
            if (val.validate) {
                btnValidClass = "btn-default";
                btnValidValue = "Valid";
            }

            btnAdminClass = 'btn-default';
            btnAdminValue = "User";
            if (val.admin) {
                btnAdminClass = 'btn-warning';
                btnAdminValue = "Admin";
            }

            //user row
            $("#tbl_users tbody").append(
                '<tr id=' + i + ' data=' + i + '>' +
                    '<td>' + val.login + '</td>' +
                    '<td class="firstName">' + val.firstName + '</td>' +
                    '<td class="lastName">' + val.lastName + '</td>' +
                    '<td><a href="mailto:' + val.email + '" class="email">' + val.email + '</a></td>' +
                    '<td>' + formatCreateTimeStamp(val.createTimeStamp) + '</td>' +
                    '<td><a href="#" class="btn btn-valid ' + btnValidClass + '" data="' + i + '" data-state="' + val.validate + '" onClick="validateUser(' + i + ')">' + btnValidValue + '</a></td>' +
                    '<td><a href="#" class="btn btn-admin ' + btnAdminClass + '" data="' + i + '" data-state="' + val.admin + '" onClick="setAdmin(' + i + ')">' + btnAdminValue + '</a></td>' +
                    '<td><a href="admin_exps.php?user=' + val.login + '" class="btn btn-default btn-view" title="Experiments"><span class="glyphicon glyphicon-list"></span></a> ' +
                    '<a href="#" class="btn btn-default btn-edit" data-toggle="modal" data="' + i + '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a> ' +
                    '<a href="#" class="btn btn-default btn-email" data-toggle="modal" data="' + i + '" title="Email"><span class="glyphicon glyphicon-envelope"></span></a> ' +
                    '<a href="#" class="btn btn-danger btn-passwd" data="' + i + '" onClick="resetPasswd(' + i + ')" title="Reset password"><span class="glyphicon glyphicon-lock"></span></a> ' +
                    '<a href="#" class="btn btn-danger btn-del" data="' + i + '" onClick="deleteUser(' + i + ')" title="Delete"><span class="glyphicon glyphicon-remove"></span></a></td>'
                    + '</tr>');
            $("tr[data=" + i + "] .btn-valid").width(50);
            $("tr[data=" + i + "] .btn-admin").width(50);
            i++;
        });

        //action on edit button: load data on modal form
        $(".btn-edit").click(function () {
            var userId = $(this).attr("data");
            selectedUser = users[userId];
            $('#s_login_e').html(selectedUser.login);
            $('#s_id_e').html(userId);
            $('#txt_sshkey_e').val(selectedUser.sshPublicKeys[0]);
            $('#txt_sshkey_e2').val(selectedUser.sshPublicKeys[1]);
            $('#txt_sshkey_e3').val(selectedUser.sshPublicKeys[2]);
            $('#txt_sshkey_e4').val(selectedUser.sshPublicKeys[3]);
            $('#txt_sshkey_e5').val(selectedUser.sshPublicKeys[4]);
            $('#txt_firstname_e').val(selectedUser.firstName);
            $('#txt_lastname_e').val(selectedUser.lastName);
            $('#txt_login_e').val(selectedUser.login);
            $('#txt_email_e').val(selectedUser.email);
            $('#txt_organization_e').val(selectedUser.structure);
            $('#txt_city_e').val(selectedUser.city);
            $('#txt_country_e').val(selectedUser.country);
            $('#txt_motivation_e').val(selectedUser.motivations);
            $("#edit_modal").modal('show');
        });

        //action on email button: load data on modal form
        $(".btn-email").click(function () {
            var userId = $(this).attr("data");
            useremail = users[userId];
            $("#to").val(useremail.email);
            $('#s_login_email').html(useremail.login);
            $("#email_modal").modal('show');
        });

        oTable = $('#tbl_users').dataTable({
            "bDestroy": true,
            "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
            "sPaginationType": "bootstrap",
            "bAutoWidth": false
        });
        $('#tbl_users').show();
        $('#tbl_users_processing').hide();
        table_content_mode = $('input[type=radio][name=table_content_mode]:checked').val();

    },
    error: function (XMLHttpRequest, textStatus, errorThrows) {
        $('#tbl_users_processing').hide();
        $("#div_msg").show();
        $("#div_msg").html("An error occurred while retrieving user list");
    }
  });
}

/* Delete a user */
function deleteUser(id) {
    var userdelete = users[id];
    if (confirm("Delete user " + userdelete.login + "?")) {
        $.ajax({
            url: "/rest/admin/users/" + userdelete.login,
            type: "DELETE",
            contentType: "application/json",
            dataType: "text",

            success: function (data) {
                //$("tr[data="+id+"]").remove()
                oTable.fnDeleteRow(document.getElementById(id), true);
            },
            error: function (XMLHttpRequest, textStatus, errorThrows) {
                alert("error: " + errorThrows);
            }
        });
    }
}
;


/* Reset a user's password */
function resetPasswd(id) {
    var userreset = users[id];
    if (confirm("Reset password for user " + userreset.login + "?")) {
        $.ajax({
            url: "/rest/admin/users/" + userreset.login + "?resetpassword",
            type: "PUT",
            contentType: "application/json",
            dataType: "text",

            success: function (data) {
                alert(userreset.login + "'s password has been reset");
            },
            error: function (XMLHttpRequest, textStatus, errorThrows) {
                alert("error: " + errorThrows);
            }
        });
    }
}
;

/* Toggle Valid state */
function validateUser(id) {
    var state = $("tr[data=" + id + "] .btn-valid").attr("data-state");
    var confirmText = "Validate user?";
    if (state == "true") confirmText = "Unvalidate user?";

    if (confirm(confirmText)) {
        //toggle validate flag
        var user = users[id];
        user.validate = !user.validate;

        $.ajax({
            url: "/rest/admin/users/" + user.login,
            type: "PUT",
            dataType: "text",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(user),
            success: function (data) {
                if (state == "false") {
                    oTable.fnUpdate('<a href="#" class="btn btn-default btn-valid" data="' + id + '" data-state="true" onClick="validateUser(' + id + ')">Valid</a>', id, 5, true);
                } else {
                    oTable.fnUpdate('<a href="#" class="btn btn-primary btn-valid" data="' + id + '" data-state="false" onClick="validateUser(' + id + ')">Pending</a>', id, 5, true);
                }
                $("tr[data=" + id + "] .btn-valid").width(50);
            },
            error: function (XMLHttpRequest, textStatus, errorThrows) {
                alert("error:" + errorThrows);
            }
        });
    }
    ;
}
;


/* Toggle Admin state */
function setAdmin(id) {
    var state = $("tr[data=" + id + "] .btn-admin").attr("data-state");
    var confirmText = "Set Admin state?";
    if (state == "true") confirmText = "Unset Admin state?";


    if (confirm(confirmText)) {
        //toggle admin flag
        var user = users[id];
        user.admin = !user.admin;

        $.ajax({
            url: "/rest/admin/users/" + user.login,
            type: "PUT",
            dataType: "text",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(user),
            success: function (data) {
                if (state == "false") {
                    oTable.fnUpdate('<a href="#" class="btn btn-warning btn-admin" data="' + id + '" data-state="true" onClick="setAdmin(' + id + ')">Admin</a>', id, 6, true);
                } else {
                    oTable.fnUpdate('<a href="#" class="btn btn-default btn-admin" data="' + id + '" data-state="false" onClick="setAdmin(' + id + ')">User</a>', id, 6, true);
                }
                $("tr[data=" + id + "] .btn-admin").width(50);
            },
            error: function (XMLHttpRequest, textStatus, errorThrows) {
                alert("error:" + errorThrows);
            }
        });
    }
    ;
}
;

/* Change table mode */
$('input[type=radio][name=table_content_mode]').change(function() {
    if(this.value == "pending") {
      $("#fieldset_email_filter").prop('disabled', true);
      if(this.value != table_content_mode) {
        //alert("pending request");
        buildUsersTable();
        // table_content_mode = "pending";
      }
    }
    if(this.value == "all") {
      $("#fieldset_email_filter").prop('disabled', false);
      $("#email_filter").select();
    }
});

/* Submit email filter request */
$('#form_email_filter').bind('submit', function (e) {
    e.preventDefault();

    //alert("email filter request w/ " + $("#email_filter").val());
    buildUsersTable();
    // table_content_mode = "all";
})

/* Edit a user */
$('#form_modify_user').bind('submit', function (e) {
    e.preventDefault();

    //change all value
    id = $('#s_id_e').html();
    selectedUser.firstName = $("#txt_firstname_e").val();
    selectedUser.lastName = $("#txt_lastname_e").val();
    selectedUser.login = $("#txt_login_e").val();
    selectedUser.email = $("#txt_email_e").val();

    var sshKeys = [];
    sshKeys.push($("#txt_sshkey_e").val());
    sshKeys.push($("#txt_sshkey_e2").val());
    sshKeys.push($("#txt_sshkey_e3").val());
    sshKeys.push($("#txt_sshkey_e4").val());
    sshKeys.push($("#txt_sshkey_e5").val());
    selectedUser.sshPublicKeys = sshKeys;


    selectedUser.motivations = $("#txt_motivation_e").val();
    selectedUser.structure = $("#txt_organization_e").val();
    selectedUser.city = $("#txt_city_e").val();
    selectedUser.country = $("#txt_country_e").val();

    $.ajax({
        url: "/rest/admin/users/" + selectedUser.login,
        type: "PUT",
        dataType: "text",
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(selectedUser),
        success: function (data) {
            $("#edit_modal").modal('hide');
            $("#div_error_edit").html("");
            oTable.fnUpdate(selectedUser.firstName, document.getElementById(id), 1, false);
            oTable.fnUpdate(selectedUser.lastName, document.getElementById(id), 2, false);
            oTable.fnUpdate('<a href="mailto:' + selectedUser.email + '" class="email">' + selectedUser.email + '</a>', document.getElementById(id), 3, true);
            //oTable.fnUpdate (selectedUser.email, document.getElementById(id),3, true);
        },
        error: function (XMLHttpRequest, textStatus, errorThrows) {
            $("#div_error_edit").html("An error occurred while saving user modifications");
            $("#div_error_edit").show();
        }
    });

    return false;

});

/* Send email to a user */
$("#sendMail").bind('submit', function (e) {

    e.preventDefault();

    $.ajax({
        url: "scripts/send_mail.php",
        type: "POST",
        dataType: "text",
        data: $(this).serialize(),
        success: function (data) {
            alert("Mail sent");
            $("#email_modal").modal('hide');
        },
        error: function (XMLHttpRequest, textStatus, errorThrows) {
            alert("error");
        }
    });
});


/* Add user(s) */
$(".btn-add").click(function () {
    $("#add_modal").modal('show');
});
$('#form_add_SA').bind('submit', function (e) {

    e.preventDefault();

    var userregister = {
        "firstName": $("#txt_firstname_SA").val(),
        "lastName": $("#txt_lastname_SA").val(),
        "email": $("#txt_email_SA").val(),
        "structure": $("#txt_organization_SA").val(),
        "city": $("#txt_city_SA").val(),
        "country": $("#txt_country_SA").val(),
        "sshPublicKey": $("#txt_sshkey_SA").val(),
        "motivations": $("#txt_motivation_SA").val(),
        "type": "SA"
    };


    $.ajax({
        url: "/rest/admin/users",
        type: "POST",
        dataType: "text",
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(userregister),
        success: function (data) {
            window.location.reload();
        },
        error: function (XMLHttpRequest, textStatus, errorThrows) {
            var errorMsg = "Error";
            if (XMLHttpRequest.status == 409) errorMsg = "This user is already registered";
            else if (XMLHttpRequest.status == 403) errorMsg = "Error";
            else errorMsg = errorThrows;
            $("#div_error_add").html(errorMsg);
            $("#div_error_add").show();
        }
    });

    return false;

});
$('#form_add_MA').bind('submit', function (e) {

    e.preventDefault();

    var userregister = {
        "login": $("#txt_login_MA").val(),
        "event": $("#txt_event_MA").val(),
        "structure": $("#txt_organization_MA").val(),
        "city": $("#txt_city_MA").val(),
        "country": $("#txt_country_MA").val(),
        "sshPublicKey": $("#txt_sshkey_MA").val(),
        "motivations": $("#txt_motivation_MA").val(),
        "nbUsersToAdd": $("#txt_howmany_MA").val(),
        "type": "MA"
    };


    $.ajax({
        url: "/rest/admin/users",
        type: "POST",
        dataType: "text",
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(userregister),
        success: function (data) {
            window.location.reload();
        },
        error: function (XMLHttpRequest, textStatus, errorThrows) {
            var errorMsg = "Error";
            if (XMLHttpRequest.status == 409) errorMsg = "This user is already registered";
            else if (XMLHttpRequest.status == 403) errorMsg = "Error";
            else errorMsg = errorThrows;
            $("#div_error_add").html(errorMsg);
            $("#div_error_add").show();
        }
    });

    return false;

});

function formatCreateTimeStamp(createTimeStamp) {
    /* "yyyy/mm/dd" */
    return createTimeStamp.substring(0, 4) + "/" + createTimeStamp.substring(4, 6) + "/" + createTimeStamp.substring(6, 8);
}


</script>

<?php include('footer.php') ?>
