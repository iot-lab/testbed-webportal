<?php
session_start();

if (!isset($_SESSION['is_auth']) || !$_SESSION['is_auth']) {
    header("location: .");
    exit();
}

include("header.php");
?>

<style type="text/css">
    textarea.form-control {
        border-top: none;
        border-radius: 0px 0px 4px 4px;
        box-shadow: none;
    }
</style>

<div class="container" text-align="top">

    <h2>Edit My Profile</h2>

    <div class="row" style="margin-top: 50px;">
        <div class="alert alert-danger" id="div_user_profile_error" style="display:none"></div>
        <div class="col-md-6 well">

            <h4>Modify your SSH keys:</h4>

            <form class="form-horizontal" id="modify_ssh_form">
                <div class="tabbable">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_SSH1" data-toggle="tab">SSH Key 1</a></li>
                        <li><a href="#tab_SSH2" data-toggle="tab">SSH Key 2</a></li>
                        <li><a href="#tab_SSH3" data-toggle="tab">SSH Key 3</a></li>
                        <li><a href="#tab_SSH4" data-toggle="tab">SSH Key 4</a></li>
                        <li><a href="#tab_SSH5" data-toggle="tab">SSH Key 5</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_SSH1">
                            <div class="control-group">
                                <div class="controls">
                                    <textarea id="txt_ssh1_modify_ssh_form" class="form-control" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_SSH2">
                            <div class="control-group">
                                <div class="controls">
                                    <textarea id="txt_ssh2_modify_ssh_form" class="form-control" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_SSH3">
                            <div class="control-group">
                                <div class="controls">
                                    <textarea id="txt_ssh3_modify_ssh_form" class="form-control" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_SSH4">
                            <div class="control-group">
                                <div class="controls">
                                    <textarea id="txt_ssh4_modify_ssh_form" class="form-control" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_SSH5">
                            <div class="control-group">
                                <div class="controls">
                                    <textarea id="txt_ssh5_modify_ssh_form" class="form-control" rows="5"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <button id="btn_modify_ssh_form" class="btn btn-primary" type="submit" style="margin-top: 6px;">Modify
                    SSH keys
                </button>

            </form>
        </div>


        <div class="col-md-5 col-md-offset-1 well">

            <h4>Modify your password:</h4>

            <form id="modify_password_form" class="form-horizontal">
                <input id="txt_current_password_modify_password_form" placeholder="Current password"
                       class="form-control" type="password" required="required" style="margin-bottom:5px;"/>
                <input id="txt_new_password_modify_password_form" placeholder="New password" class="form-control"
                       type="password" required="required" style="margin-bottom:5px;"/>
                <input id="txt_cnew_password_modify_password_form" placeholder="Confirm new password"
                       class="form-control" type="password" required="required" style="margin-bottom:5px;"/>
                <button id="btn_modify_password_form" class="btn btn-primary" type="submit" style="margin-bottom:5px;">
                    Modify password
                </button>
            </form>
        </div>


    </div>

</div>
<script type="text/javascript">

    var sshKeys = [];

    /* ************************** */
    /*   on ready when logged in  */
    /* ************************** */
    $(document).ready(function () {

        $("#user_profile").addClass('active');

        // Modify Password
        $('#modify_password_form').bind('submit', function (e) {

            e.preventDefault();

            if ($("#txt_new_password_modify_password_form").val() != $("#txt_cnew_password_modify_password_form").val()) {
                $("#div_user_profile_error").removeClass("alert-success");
                $("#div_user_profile_error").addClass("alert-danger");
                $("#div_user_profile_error").html("Please confirm password");
                $("#div_user_profile_error").show();
                return false;
            }

            var user = {
                "newPassword": $("#txt_new_password_modify_password_form").val(),
                "confirmNewPassword": $("#txt_cnew_password_modify_password_form").val(),
                "oldPassword": $("#txt_current_password_modify_password_form").val()
            };

            $.ajax({
                url: "/rest/users/"+<?php echo '"'.$_SESSION['login'].'"'?>+"/password",
                type: "PUT",
                dataType: "text",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify(user),
                success: function (data) {
                    $("#div_user_profile_error").removeClass("alert-danger");
                    $("#div_user_profile_error").addClass("alert-success");
                    $("#div_user_profile_error").html("Your password has been changed successfully. Please wait...");
                    $("#div_user_profile_error").show();
                    //setTimeout( "$('#div_error_password').hide()", 2000);
                    setTimeout("window.location.href = './logout.php'", 2000);
                },
                error: function (XMLHttpRequest, textStatus, errorThrows) {
                    $("#div_user_profile_error").removeClass("alert-success");
                    $("#div_user_profile_error").addClass("alert-danger");
                    $("#div_user_profile_error").html("An error occurred while saving your password");
                    $("#div_user_profile_error").show();
                }
            });
            return false;
        });

        // Load SSH keys
        loadSSHKeys();

        // Modify SSH keys
        $('#modify_ssh_form').bind('submit', function (e) {

            e.preventDefault();

            sshKeys = [];
            if ($("#txt_ssh1_modify_ssh_form").val() != "") sshKeys.push($("#txt_ssh1_modify_ssh_form").val());
            if ($("#txt_ssh2_modify_ssh_form").val() != "") sshKeys.push($("#txt_ssh2_modify_ssh_form").val());
            if ($("#txt_ssh3_modify_ssh_form").val() != "") sshKeys.push($("#txt_ssh3_modify_ssh_form").val());
            if ($("#txt_ssh4_modify_ssh_form").val() != "") sshKeys.push($("#txt_ssh4_modify_ssh_form").val());
            if ($("#txt_ssh5_modify_ssh_form").val() != "") sshKeys.push($("#txt_ssh5_modify_ssh_form").val());
            var user = {};
            user.sshkeys = sshKeys;


            $.ajax({
                url: "/rest/users/"+<?php echo '"'.$_SESSION['login'].'"'?>+"/sshkeys",
                type: "PUT",
                dataType: "text",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify(user),
                success: function (data) {
                    $("#div_user_profile_error").removeClass("alert-danger");
                    $("#div_user_profile_error").addClass("alert-success");
                    $("#div_user_profile_error").html("Your SSH keys had been changed successfully.");
                    $("#div_user_profile_error").show();

                    refreshSSHKeys();

                    setTimeout("$('#div_user_profile_error').hide()", 2000);
                },
                error: function (XMLHttpRequest, textStatus, errorThrows) {
                    $("#div_user_profile_error").removeClass("alert-success");
                    $("#div_user_profile_error").addClass("alert-danger");
                    $("#div_user_profile_error").html("An error occurred while saving your ssh keys");
                    $("#div_user_profile_error").show();
                }
            });
            return false;
        });
    });


    /* **************** */
    /* retrieve sshkeys */
    /* **************** */
    function loadSSHKeys() {

        $("#div_user_profile_error").addClass("alert-success");
        $("#div_user_profile_error").removeClass("alert-danger");
        $("#div_user_profile_error").html("Loading your ssh keys ...");
        $("#div_user_profile_error").show();
        $("#txt_ssh1_modify_ssh_form").val("");
        $("#txt_ssh2_modify_ssh_form").val("");
        $("#txt_ssh3_modify_ssh_form").val("");
        $("#txt_ssh4_modify_ssh_form").val("");
        $("#txt_ssh5_modify_ssh_form").val("");
        // Retrieve current sshkey
        $.ajax({
            url: "/rest/users/"+<?php echo '"'.$_SESSION['login'].'"'?>+"/sshkeys",
            type: "GET",
            data: {},
            dataType: "text",
            success: function (data) {
                $("#div_user_profile_error").hide();
                var sshkeys = JSON.parse(data);
                sshKeys = sshkeys.sshkeys;
                refreshSSHKeys();
            },
            error: function (XMLHttpRequest, textStatus, errorThrows) {
                $("#div_user_profile_error").removeClass("alert-success");
                $("#div_user_profile_error").addClass("alert-danger");
                $("#div_user_profile_error").html("An error occurred while retrieving your ssh keys");
                $("#div_user_profile_error").show();
            }
        });
    }

    /* ********************** */
    /* fill form with sshkeys */
    /* ********************** */
    function refreshSSHKeys() {
        $("#txt_ssh1_modify_ssh_form").val("");
        $("#txt_ssh2_modify_ssh_form").val("");
        $("#txt_ssh3_modify_ssh_form").val("");
        $("#txt_ssh4_modify_ssh_form").val("");
        $("#txt_ssh5_modify_ssh_form").val("");

        $("#txt_ssh1_modify_ssh_form").val(sshKeys[0]);
        if (sshKeys.length > 1) $("#txt_ssh2_modify_ssh_form").val(sshKeys[1]);
        if (sshKeys.length > 2) $("#txt_ssh3_modify_ssh_form").val(sshKeys[2]);
        if (sshKeys.length > 3) $("#txt_ssh4_modify_ssh_form").val(sshKeys[3]);
        if (sshKeys.length > 4) $("#txt_ssh5_modify_ssh_form").val(sshKeys[4]);
    }

</script>

<?php include('footer.php') ?>
