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
        <div class="row">
          <div class="col-md">
            <div class="form-group">
              <label class="form-control-label">First name</label>
              <input placeholder="First name" v-model="user.firstName" name="firstname" class="form-control" type="text" v-validate="'required'" :class="{'is-invalid': errors.has('firstname') }">
              <div class="invalid-feedback" v-show="errors.has('firstname')">
                {{ errors.first('firstname') }}
              </div>
            </div>
          </div>
          <div class="col-md">                    
            <div class="form-group">
              <label class="form-control-label">Last name</label>
              <input placeholder="Last name" v-model="user.lastName" name="lastname" class="form-control" type="text" v-validate="'required'" :class="{'is-invalid': errors.has('lastname') }">
              <div class="invalid-feedback" v-show="errors.has('lastname')">
                {{ errors.first('lastname') }}
              </div>
            </div>
          </div>
        </div>
            <div class="form-group">
              <label class="form-control-label">Email</label>
              <input v-model="user.email" name="email" class="form-control" type="email" v-validate="'required|email|noWebMail'" placeholder="Academic or professional email" :class="{'is-invalid': errors.has('email') }">
              <div class="invalid-feedback" v-show="errors.has('email')" v-html="errors.first('email')">
              </div>
            </div>
        <div class="row">
          <div class="col-md">
            <div class="form-group">
              <label class="form-control-label">User category</label>
              <multiselect v-model="user.category"
                placeholder="Category"
                :options="Object.keys(categories)"
                :allow-empty="false"
                :searchable="false"
                :show-labels="false"
                :custom-label="userLabel"
                :class="{'mymultiselect': true, 'invalid': dirty.category && !user.category}"
                @close="dirty.category = true">
              </multiselect>
              <div class="invalid-feedback" :style="{'display': (dirty.category && !user.category) ? 'block': 'none'}">
                The user category field is required.
              </div>
            </div>
          </div>
          <div class="col-md">
            <div class="form-group">
              <label class="form-control-label">Organization</label>
              <input placeholder="Organization" v-model="user.organization" name="organization" class="form-control" type="text" v-validate="'required'" data-vv-rules="required" :class="{'is-invalid': errors.has('organization') }">
              <div class="invalid-feedback" v-show="errors.has('organization')">
                {{ errors.first('organization') }}
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md">
            <div class="form-group">
              <label class="form-control-label">City</label>
              <input placeholder="City" v-model="user.city" name="city" class="form-control" type="text" v-validate="'required'" :class="{'is-invalid': errors.has('city') }">
              <div class="invalid-feedback" v-show="errors.has('city')">
                {{ errors.first('city') }}
              </div>
            </div>
          </div>
          <div class="col-md">
            <div class="form-group">
              <label class="form-control-label">Country</label>
              <multiselect v-model="user.country"
                placeholder="Country"
                :options="countries"
                :class="{'mymultiselect': true, 'invalid': dirty.country && !user.country}"
                @close="dirty.country = true">
              </multiselect>
              <div class="invalid-feedback" :style="{'display': (dirty.country && !user.country) ? 'block': 'none'}">
                The country field is required.
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="form-control-label">Motivations</label>
          <textarea v-model="user.motivations"
            name="motivations"
            class="form-control"
            rows="5"
            placeholder="Tell us about you"
            v-validate="'required'"
            @focus="toggleMotivations"
            @blur="toggleMotivations"
            :class="{'is-invalid': errors.has('motivations') }">
          </textarea>
          <div class="invalid-feedback" v-show="errors.has('motivations')">
            {{ errors.first('motivations') }}
          </div>
        </div>
        <div class="form-group" id="motivations" style="display: none">
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
          <vue-recaptcha ref="recaptcha" @verify="onCaptchaVerify" @expired="onCaptchaExpired"
            :sitekey="reCaptchaSitekey"
            :class="(!captchaVerified && dirty.captcha) ? 'border-danger form-control p-2' : ''">
          </vue-recaptcha>
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
import Multiselect from 'vue-multiselect'
import VueRecaptcha from 'vue-recaptcha'
import {iotlab} from '@/rest'
import WebmailDomains from '@/assets/js/webmail-domains'
import countries from '@/assets/js/countries'
import UserCategories from '@/assets/js/categories'
import $ from 'jquery'
import { Validator } from 'vee-validate'

export default {
  name: 'signup',
  components: {Multiselect, VueRecaptcha},

  data () {
    return {
      reCaptchaSitekey: '6Ld8cR4UAAAAAC-zBLP9m2bC35xyyYwTbvkBcx4q',
      charter: false,
      captchaVerified: false,
      user: {},
      countries: countries,
      categories: UserCategories,
      category: undefined,
      success: false,
      dirty: {
        category: false,
        country: false,
        captcha: false,
      },
    }
  },

  created () {
    Validator.extend('noWebMail', {
      getMessage: field => `Your email must be <b>academic</b> or <b>professional</b> in order to validate your account (<b>${this.user.email.split('@')[1]}</b> not allowed).`,
      validate: email => !WebmailDomains.includes(email.split('@')[1].toLowerCase()),
    })
  },

  methods: {
    signup () {
      this.$validator.validateAll().then(async (validated) => {
        if (!validated || !this.user.country || !this.user.category) {
          this.dirty.category = this.dirty.country = this.dirty.captcha = true
          console.log('Form is not valid.')
          return
        }
        // check reCaptcha verified
        if (!this.captchaVerified) {
          this.dirty.captcha = true
          alert('Please verify the captcha "I\'m not a robot"')
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
      this.captchaVerified = true
    },
    onCaptchaExpired: function () {
      console.log('Captcha Expired')
      this.captchaVerified = false
    },
    userLabel (cat) {
      return UserCategories[cat]
    },
    toggleMotivations () {
      $('#motivations').slideToggle()
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
