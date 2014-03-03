<?php
/* SIGNUP PAGE */
session_start();
if (isset($_SESSION['is_auth']) && $_SESSION['is_auth']) {
    header("location: .");
    exit();
}
include("header.php");

?>

<div class="container">

    <div class="row" id="signup_div">
        <div class="col-md-8">
            <h2>Sign up</h2>

            <div class="alert alert-error" id="div_error_signup" style="display:none"></div>

            <form class="well form-horizontal" id="signup_form">

                <div class="form-group">
                    <label class="col-lg-3 control-label" for="txt_firstname">First Name:</label>

                    <div class="col-lg-9">
                        <input id="txt_firstname" class="form-control" type="text" required="required">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label" for="txt_lastname">Last Name:</label>

                    <div class="col-lg-9">
                        <input id="txt_lastname" class="form-control" type="text" required="required">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label" for="txt_email">Email:</label>

                    <div class="col-lg-9">
                        <span style="color:orange">Please fill with an <b>academic</b> or <b>professional</b> address in order to validate your account <b>(no
                                gmail, no hotmail, ...)</b></span>
                        <input id="txt_email" class="form-control" type="email" required="required"
                               placeholder="&lt;Please fill with an academic or professional address&gt;">

                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label" for="txt_structure">Structure:</label>

                    <div class="col-lg-9">
                        <input id="txt_structure" class="form-control" type="text" required="required">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label" for="txt_city">City:</label>

                    <div class="col-lg-9">
                        <input id="txt_city" class="form-control" type="text" required="required">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label" for="txt_country">Country:</label>

                    <div class="col-lg-9">
                        <select id="txt_country" class="form-control"
                                required="required"><?php include 'scripts/countries.html'; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label" for="txt_sshkey">SSH public key:</label>

                    <div class="col-lg-9">
                        <textarea id="txt_sshkey" class="form-control" id="textarea" rows="3"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label" for="txt_motivation">Motivation:</label>

                    <div class="col-lg-9">
                        <textarea id="txt_motivation" class="form-control" id="textarea" rows="5"
                                  required="required"></textarea>
                    </div>
                </div>


                <div class="form-group" id="cg_captcha">
                    <label class="col-lg-3 control-label" for="txt_motivation">Anti-spam:</label>

                    <div class="col-lg-9">
                        <input id="captcha" class="form-control" name="captcha" type="text" required="required"/>
                        <br/>
                        <a href="#" onclick="
                    document.getElementById('captcha-img').src='/testbed/scripts/captcha/captcha.php?'+Math.random();
                    document.getElementById('captcha').focus();"
                           id="change-image" title="Click to change text">
                            <img style="border:solid 1px" src="/testbed/scripts/captcha/captcha.php" id="captcha-img"/></a>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-9">
                        <div class="checkbox">
                            <label>
                                <input id="charter" class="form-control" name="charter" type="checkbox"
                                       required="required"/>I read and I accept <a
                                    href="http://www.senslab.info/users/senslab-charter/" target="_blank">Senslab Terms
                                    of Service</a>.
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-9">
                        <button id="btn_signup" class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>

            </form>

        </div>
        <!-- span -->
        <div class="col-md-4">
            <div class="alert alert-info" style="margin-top:430px;">
                <img src="img/help.png"> <b>Motivation:</b><br/>
                - Research domain (Radio communication, networking protocol, distributed applications, …).<br/>
                - What kind of experiments do you want to run with SensLAB?<br/>
                - Goal: (Verify something existing in large scale, new development, …)<br/>
                - Network sensor previous experience (n00b, experiments with X platform, former SensLAB user, Guru, God)
            </div>
        </div>

    </div>
    <!-- row -->

</div>

<script type="text/javascript">

    $(document).ready(function () {

        if (document.location.hash == "#signup") showSignup();

        // submit sign up
        $('#signup_form').bind('submit', function () {

            var userregister = {};
            userregister.firstName = $("#txt_firstname").val();
            userregister.lastName = $("#txt_lastname").val();
            userregister.email = $("#txt_email").val();
            userregister.structure = $("#txt_structure").val();
            userregister.city = $("#txt_city").val();
            userregister.country = $("#txt_country").val();
            userregister.motivations = $("#txt_motivation").val();
            userregister.captcha = $("#captcha").val();
            userregister.sshPublicKeys = eval('["' + $("#txt_sshkey").val() + '"]');

            //console.log(userregister);

            $.ajax({
                url: "scripts/captcha.php",
                type: "POST",
                data: JSON.stringify(userregister),
                contentType: "application/json; charset=utf-8",
                success: function (data) {
                    window.location.href = '/testbed/index.php#signup';
                },
                error: function (XMLHttpRequest, textStatus, errorThrows) {
                    if (XMLHttpRequest.status == 409) {
                        $("#div_error_signup").show();
                        $("#div_error_signup").removeClass("alert-success");
                        $("#div_error_signup").addClass("alert-error");
                        $("#div_error_signup").html("This user is already registered");
                    }
                    else if (XMLHttpRequest.status == 403) {
                        $("#div_error_signup").show();
                        $("#div_error_signup").removeClass("alert-success");
                        $("#div_error_signup").addClass("alert-error");
                        $("#div_error_signup").html("Error");
                        $("#cg_captcha").addClass("error");
                        $("#captcha").focus();
                    }
                    else {
                        $("#div_error_signup").show();
                        $("#div_error_signup").removeClass("alert-success");
                        $("#div_error_signup").addClass("alert-error");
                        $("#div_error_signup").html(errorThrows);
                    }
                }
            });
            return false;
        });
    });

</script>

<?php include('footer.php') ?>
