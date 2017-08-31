<template>
<div class="container mt-3">
  <div class="row">
    <div class="col-lg-8">
      <h2>Sign up to FIT IoT-LAB</h2>
      <div v-if="success" class="anlert alert-success">Success</div>
      <div v-else-if="error" class="alert alert-danger">Failed</div>
      <form v-else class=" mb-5" @submit.prevent="signup">
        <div class="text-right text-italic text-muted small">
          * All fields are mandatory
        </div>
        <div class="row">
          <div class="col-md">
            <div class="form-group">
              <label class="form-control-label">First name</label>
              <input placeholder="First name" v-model="user.firstName" class="form-control" type="text" required>
            </div>
          </div>
          <div class="col-md">                    
            <div class="form-group">
              <label class="form-control-label">Last name</label>
              <input placeholder="Last name" v-model="user.lastName" class="form-control" type="text" required>
            </div>
          </div>
        </div>
            <div class="form-group">
              <label class="form-control-label">Email</label>
              <input v-model="user.email" class="form-control" type="email" required placeholder="Academic or professional email">
              <span class="text-danger" style="font-size: 0.9rem">Please fill with an <b>academic</b> or <b>professional</b> email in order to validate your account <b>(no gmail, hotmail, &#x2026;)</b></span>
            </div>
        <div class="row">
          <div class="col-md">
            <div class="form-group">
              <label class="form-control-label">User category</label>
              <v-select v-model="user.category" :options="categories" placeholder="Category" :searchable="false" required></v-select>
            </div>                    
          </div>
          <div class="col-md">
            <div class="form-group">
              <label class="form-control-label">Organization</label>
              <input placeholder="Organization" v-model="user.organization" class="form-control" type="text" required>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md">
            <div class="form-group">
              <label class="form-control-label">City</label>
              <input placeholder="City" v-model="user.city" class="form-control" type="text" required>
            </div>
          </div>
          <div class="col-md">
            <div class="form-group">
              <label class="form-control-label">Country</label>
              <v-select :options="countries" v-model="user.country" required placeholder="Country" @keydown.enter.prevent=""></v-select>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="form-control-label">Motivations</label>
          <textarea v-model="user.motivations" class="form-control" rows="5" placeholder="Tell us about you" required></textarea>
        </div>
        <div class="form-group">
          <div class="alert alert-info form-control">
            <i class="fa fa-pencil"></i> <b>Tell us about your motivations</b>
            <ul style="margin: 0">
              <li>Research domain (Radio communication, networking protocol, distributed applications, &#x2026;)</li>
              <li>What kind of experiments do you want to run with IoT-LAB?</li>
              <li>Goal (Verify something existing in large scale, new development, &#x2026;)</li>
              <li>Experience with sensor networks (n00b, experiments with X platform, former IoT-LAB user, Guru, God)</li>
            </ul>
          </div>
        </div>
        <div class="form-group">
          <vue-recaptcha ref="recaptcha" @verify="onCaptchaVerify" @expired="onCaptchaExpired" :sitekey="reCaptchaSitekey"></vue-recaptcha>
        </div>
        <div class="form-check">
          <label class="custom-control custom-checkbox">
            <input v-model="charter" type="checkbox" class="custom-control-input">
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">
              I read and I accept <a href="/charter/" target="_blank">IoT-LAB Terms of Service</a>.
            </span>
          </label>
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
import vSelect from 'vue-select'
import VueRecaptcha from 'vue-recaptcha'
import {iotlab} from '@/rest'
import countries from '@/assets/js/countries'
import categories from '@/assets/js/categories'

export default {
  name: 'signup',
  components: {vSelect, VueRecaptcha},

  data () {
    return {
      reCaptchaSitekey: '6Ld8cR4UAAAAAC-zBLP9m2bC35xyyYwTbvkBcx4q',
      charter: false,
      verified: false,
      user: {
        'sshPublicKeys': [''],
      },
      countries: countries,
      categories: categories,
      category: undefined,
    }
  },

  methods: {
    async signup () {
      // check charter read
      if (!this.charter) {
        alert('Please read and accept the Terms of Service')
        return
      }
      // check reCaptcha verified
      if (!this.verified) {
        alert('Please verify the captcha "I\'m not a robot"')
      }

      try {
        await iotlab.signup(this.user)
        // this.$router.push('dashboard')
        alert('Success')
        this.success = true
      } catch (err) {
        this.error = true
        alert('Error')
      }
    },
    onCaptchaVerify: function (response) {
      console.log('Captcha Verified')
      this.verified = true
    },
    onCaptchaExpired: function () {
      console.log('Captcha Expired')
      this.verified = false
    },
  },
}
</script>

<style scoped>
ul {
  padding-left: 15px;
}
.v-select {
  background: white;
}
</style>
