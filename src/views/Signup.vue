<template>
<div class="container mt-3">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <h2>Sign up to FIT IoT-LAB</h2>
      <div class="card border-danger bg-danger my-2 mx-auto" v-if="hostname === 'devwww.iot-lab.info'">
        <div class="card-body text-white p-2">
          Signup on <b>devwww</b> is restricted to <b>administrators</b>.
          Users should sign up to <a class="text-white alert-link" href="https://www.iot-lab.info/testbed">www.iot-lab.info</a>.
        </div>
      </div>
      <div v-if="success" class="card border-success my-5">
        <div class="card-header bg-success text-white"><i class="fa fa-check mr-1"></i> An email has been sent to <i>{{user.email}}</i></div>
        <div class="card-body text-success">
          <h5>Check your inbox and follow the link to activate your account.</h5>
        </div>
      </div>
      <form v-else class="mb-5" @submit.prevent="signup">
        <div class="text-right text-italic text-muted small">
          * All fields are mandatory
        </div>

        <user-form :user="user" ref="user" :hidden="['groups']"></user-form>

        <div class="form-group">
          <vue-recaptcha ref="recaptcha" @verify="onCaptchaVerify" @expired="onCaptchaExpired"
            :sitekey="reCaptchaSitekey"
            :class="(!captcha.verified && captcha.dirty) ? 'border-danger form-control p-2' : ''">
          </vue-recaptcha>
          <div class="invalid-feedback mb-3" :class="{'d-block': !captcha.verified && captcha.dirty }" v-show="!captcha.verified && captcha.dirty">
            Please verify the captcha.
          </div>
        </div>
        <div class="form-check">
          <label class="custom-control custom-control-inline custom-checkbox">
            <input v-model="charter" name="charter" type="checkbox" class="custom-control-input" v-validate="'required'" :class="{'is-invalid': errors.has('charter') }">
            <span class="custom-control-label">
              I read and I accept <a href="/charter/" :class="{'text-danger': errors.has('charter') }" target="_blank">IoT-LAB Terms of Service</a>.
            </span>
          </label>
          <div class="invalid-feedback mb-3" :class="{'d-block': errors.has('charter') }" v-show="errors.has('charter')">
            You must check the box and accept the terms of service.
          </div>
        </div>
        <div class="form-group mt-3">
          <button class="btn btn-lg btn-primary mr-1" type="submit">Sign up</button>
          <router-link :to="{name:'login'}" class="btn btn-lg btn-secondary">Login</router-link>
        </div>
      </form>

    </div>
  </div> <!-- row -->
</div>
</template>

<script>
import VueRecaptcha from 'vue-recaptcha'
import {iotlab} from '@/rest'
import UserForm from '@/components/UserForm'

export default {
  name: 'signup',
  components: {UserForm, VueRecaptcha},

  data () {
    return {
      hostname: location.hostname,
      reCaptchaSitekey: '6Ld8cR4UAAAAAC-zBLP9m2bC35xyyYwTbvkBcx4q',
      user: {
        'sshkeys': [''],
      },
      charter: false,
      success: false,
      captcha: {
        dirty: false,
        verified: false,
      },
    }
  },

  methods: {
    signup () {
      this.$validator.validateAll().then(async (validated) => {
        if (!(await this.$refs.user.validate()) || !validated || !this.captcha.verified) {
          this.captcha.dirty = true
          console.log('Form is not valid.')
          return
        }
        if (location.hostname === 'devwww.iot-lab.info') {
          alert('Signup on devwww is restricted to IoT-LAB administrators')
          return
        }
        try {
          await iotlab.signup(this.user)
          this.success = true
        } catch (err) {
          this.success = false
          this.$notify({text: 'Error during signup<br>' + err.response.data.message, type: 'error', duration: -1})
        }
      })
    },
    onCaptchaVerify: function (response) {
      console.log('Captcha Verified')
      this.captcha.verified = true
    },
    onCaptchaExpired: function () {
      console.log('Captcha Expired')
      this.captcha.verified = false
    },
  },
}
</script>
