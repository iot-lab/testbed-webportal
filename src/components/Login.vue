
<template>
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
                <button id="btn_login" type="submit" class="btn btn-primary btn-block" style="margin-bottom:5px;" @click="login">Log in
                </button>
            </form>

            <p class="text-center"><router-link to="signup">Register for an account</router-link> - <a href="#" @click="showReset()">Forgot
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

            <p class="text-center"><router-link to="signup">Register for an account</router-link> - <a href="#" @click="showLogin()">Login</a></p>
        </div>
    </div>

</div> <!-- container -->

</template>

<script>
import {doLogin} from '../main.js'

export default {
  name: 'login',
  methods: {
    login: function (event) {
      doLogin()
      this.$router.push('dashboard')
    },
  },
}
</script>

<style>

</style>
