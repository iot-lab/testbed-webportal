<template>
<div class="container mt-3">
  <div class="row">
    <div class="col-lg-8">
      <h2>Sign up to FIT IoT-LAB</h2>
      <div v-if="success" class="card border-success my-5">
        <div class="card-header bg-success text-white"><i class="fa fa-check mr-1"></i> An email has been sent to <i>{{user.email}}</i></div>
        <div class="card-body text-success">
          <h5>Check your inbox and follow link to validate your account.</h5>
        </div>
      </div>
      <form v-else class="mb-5" @submit.prevent="signup">
        <div class="text-right text-italic text-muted small">
          * All fields are mandatory
        </div>

        <user-form :user="user" ref="user"></user-form>
        
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
          <label class="custom-control custom-checkbox">
            <input v-model="charter" name="charter" type="checkbox" class="custom-control-input" v-validate="'required'" :class="{'is-invalid': errors.has('charter') }">
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">
              I read and I accept <a href="/charter/" :class="{'text-danger': errors.has('charter') }" target="_blank">IoT-LAB Terms of Service</a>.
            </span>
          </label>
          <div class="invalid-feedback mb-3" :class="{'d-block': errors.has('charter') }" v-show="errors.has('charter')">
            You must check the box and accept the terms of service.
          </div>
        </div>
        <div class="form-group">
          <button class="btn btn-lg btn-primary" type="submit">Sign up</button>
          <router-link to="login" class="btn btn-lg btn-secondary">Login</router-link>
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
      reCaptchaSitekey: '6Ld8cR4UAAAAAC-zBLP9m2bC35xyyYwTbvkBcx4q',
      user: {},
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
        try {
          await iotlab.signup(this.user)
          this.success = true
        } catch (err) {
          this.success = false
          this.$notify({text: 'Error during signup', type: 'error'})
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

<style scoped>
ul {
  padding-left: 15px;
}
</style>

<style>
/* Let multiselect style match bootstrap 4 */

.multiselect__single, .multiselect__input {
  font-size: 1rem !important;
  line-height: 1.25 !important;
  color: #495057 !important;
}
.multiselect__tags {
  border: 1px solid rgba(0,0,0,.15) !important;
  border-radius: .25rem !important;
}
.mymultiselect .multiselect__tags {
  min-height: 38px !important;
}
.invalid .multiselect__tags {
  border-color: #dc3545 !important;
}
.multiselect__option--highlight {
  background: #007bff !important;
}
.multiselect__option--selected.multiselect__option--highlight {
  background: #868e96 !important;
}
.multiselect__option--highlight:after {
  background: linear-gradient(90deg, rgba(0,0,0,0) 0%, #007bff 10%, #007bff 100%) !important;
}
.multiselect__option--highlight:hover:after {
  opacity: 0;
}
</style>
