<?php
/* LOGIN AND RESET PASSWORD PAGE */
session_start();
if (isset($_SESSION['is_auth']) && $_SESSION['is_auth']) {
    header("location: dashboard.php");
    exit();
}
include('header.php');
?>


<div class="container" style="margin-top:50px;">


    <div class="row">
        <div class="alert alert-error" id="div_error_login_and_reset_forms" style="display:none"></div>
        <div class="col-md-4 col-md-offset-4 well" id="login_div">
            <h2>Please Log in</h2>


            <form id="login_form">
                <input id="txt_login_login_form" type="text" class="form-control" placeholder="Username"
                       style="margin-bottom:5px;">
                <input id="txt_password_login_form" type="password" class="form-control" placeholder="Password"
                       style="margin-bottom:5px;">
                <button id="btn_login" type="submit" class="btn btn-primary btn-block" style="margin-bottom:5px;">Log
                    in
                </button>
            </form>

            <p class="text-center"><a href="signup.php">Ask for an account</a> - <a href="#" onClick="showReset()">Forgot
                    your password?</a></p>

        </div>
        <div class="col-md-4 col-md-offset-4 well" id="reset_div" style="display:none;">
            <h2>Reset your password</h2>

            <form id="reset_form">
                <input name="email" id="txt_email_reset_form" type="text" placeholder="Email" class="form-control"
                       style="margin-bottom:5px;">
                <button id="btn_reset" class="btn btn-primary btn-block" style="margin-bottom:5px;">Reset password
                </button>
            </form>

            <p class="text-center"><a href="signup.php">Ask for an account</a> - <a href="#"
                                                                                    onClick="showLogin()">Login</a></p>
        </div>
    </div>

</div> <!-- container -->

<?php
include('footer.php');
?>


<script type="text/javascript">


    /* ************ */
    /*   on ready   */
    /* ************ */
    $(document).ready(function () {

        if (document.location.hash == "#logout") {
            $("#div_error_login_and_reset_forms").show();
            $("#div_error_login_and_reset_forms").removeClass("alert-danger");
            $("#div_error_login_and_reset_forms").addClass("alert-success");
            $("#div_error_login_and_reset_forms").html("You have been successfully logged out.");
            document.location.hash = "";
        } else if (document.location.hash == "#notlogin") {
            $("#div_error_login_and_reset_forms").show();
            $("#div_error_login_and_reset_forms").removeClass("alert-success");
            $("#div_error_login_and_reset_forms").addClass("alert-danger");
            $("#div_error_login_and_reset_forms").html("You need to be logged in to log out.");
            document.location.hash = "";
        } else if (document.location.hash == "#signup") {
            $("#div_error_login_and_reset_forms").show();
            $("#div_error_login_and_reset_forms").removeClass("alert-danger");
            $("#div_error_login_and_reset_forms").addClass("alert-success");
            $("#div_error_login_and_reset_forms").html("Your registration has been saved and submitted to validation by an administrator.");
            document.location.hash = "";
        }

        $("#login").addClass('active');

        // submit login
        $('#login_form').bind('submit', function () {

            var userlogin = {"login": $("#txt_login_login_form").val(), "password": $("#txt_password_login_form").val()};

            $.ajax({
                url: "/testbed/scripts/auth.php",
                type: "POST",
                data: userlogin,
                success: function (response) {
                    window.location.href = "/testbed/dashboard.php";
                },
                error: function (XMLHttpRequest, textStatus, errorThrows) {
                    $("#txt_login_login_form").val("");
                    $("#txt_password_login_form").val("");
                    $("#div_error_login_and_reset_forms").addClass("alert-danger");
                    $("#div_error_login_and_reset_forms").html("Wrong login or password");
                    $("#div_error_login_and_reset_forms").show();
                }
            });
            return false;
        });

        // submit reset password
        $('#reset_form').bind('submit', function (e) {

            e.preventDefault();
            var user = {"email": $("#txt_email_reset_form").val()};

            $.ajax({
                url: "/rest/users/" + $("#txt_email_reset_form").val() + "?resetpassword",
                type: "PUT",
                dataType: "text",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify(user),
                success: function (data) {
                    $("#div_error_login_and_reset_forms").show();
                    $("#div_error_login_and_reset_forms").removeClass("alert-danger");
                    $("#div_error_login_and_reset_forms").addClass("alert-success");
                    $("#div_error_login_and_reset_forms").html("Your password was reset, check your inbox");
                },
                error: function (XMLHttpRequest, textStatus, errorThrows) {
                    $("#txt_email_reset_password_form").val("");
                    $("#div_error_login_and_reset_forms").show();
                    $("#div_error_login_and_reset_forms").removeClass("alert-success");
                    $("#div_error_login_and_reset_forms").addClass("alert-danger");
                    $("#div_error_login_and_reset_forms").html("This email was not found");
                }
            });
        });
    });

    function showReset() {
        $('#login_div').hide();
        $('#reset_div').show();
    }

    function showLogin() {
        $('#reset_div').hide();
        $('#login_div').show();
    }

</script>
