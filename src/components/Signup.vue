<template>
<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <h2>Sign up</h2>
            <div v-if="success" class="alert alert-success">Success</div>
            <div v-else-if="error" class="alert alert-danger">Failed</div>
            <form v-else="" class="card bg-light card-body mb-3 " @submit.prevent="signup">
                <div class="form-group">
                    <label class="col-xl-3 form-control-label" for="txt_firstname">First name:</label>
                    <div class="col-xl-9">
                        <input placeholder="First name" v-model="newuser.firstName" class="form-control"
                        type="text" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xl-3 form-control-label" for="txt_lastname">Last name:</label>
                    <div class="col-xl-9">
                        <input placeholder="Last name" v-model="newuser.lastName" class="form-control"
                        type="text" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xl-3 form-control-label" for="txt_email">Email:</label>
                    <div class="col-xl-9"> <span class="text-danger">Please fill with an <b>academic</b> or <b>professional</b> email in order to validate your account <b>(no gmail, no hotmail, ...)</b></span>
                        <input
                        v-model="newuser.email" class="form-control" type="email" required placeholder="Academic or professional email">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xl-3 form-control-label" for="txt_profile">User category:</label>
                    <div class="col-xl-9">
                        <v-select v-model="category" :options="categories" placeholder="Category"
                        required></v-select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xl-3 form-control-label">Organization:</label>
                    <div class="col-xl-9">
                        <input placeholder="Organization" v-model="newuser.organization" class="form-control"
                        type="text" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xl-3 form-control-label" for="txt_city">City:</label>
                    <div class="col-xl-9">
                        <input placeholder="City" v-model="newuser.city" class="form-control"
                        type="text" required>
                    </div>
                </div>
                <div class="form-group" id="spambot">
                    <label class="col-xl-3 form-control-label" for="txt_city">Leave empty:</label>
                    <div class="col-xl-9">
                        <input placeholder="whatever" v-model="whatever" class="form-control"
                        type="text">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xl-3 form-control-label" for="txt_country">Country:</label>
                    <div class="col-xl-9">
                        <v-select :options="countries" v-model="newuser.country" placeholder="Country"
                        @keydown.enter.prevent="" required></v-select>
                    </div>
                </div>
                <!-- <div class="form-group">

                    <label class="col-xl-3 form-control-label" for="txt_sshkey">SSH public key:</label>



                    <div class="col-xl-9">

                        <textarea v-model="newuser.sshkey" class="form-control" rows="3"></textarea>

                    </div>

                </div>

 -->
                <div class="form-group">
                    <label class="col-xl-3 form-control-label" for="txt_motivation">Motivation:
                        <br> <i class="fa fa-question-circle fa-lg cursor" @click="showPopover = !showPopover"
                        @mouseover="showPopover = true" @mouseout="showPopover = false">

                        </i>
                        <!-- <div class="col-lg-4"> -->
                        <div class="alert alert-info mypopover" :class="{ 'hidden': !showPopover }"> <i class="fa fa-info-circle"></i>  <b>Tell us about your motivations:</b>
                            <ul>
                                <li>Research domain (Radio communication, networking protocol, distributed
                                    applications, &#x2026;).</li>
                                <li>What kind of experiments do you want to run with IoT-LAB ?</li>
                                <li>Goal: (Verify something existing in large scale, new development, &#x2026;)</li>
                                <li>Network sensor previous experience (n00b, experiments with X platform,
                                    former IoT-LAB user, Guru, God)</li>
                            </ul>
                        </div>
                        <!-- </div> -->
                    </label>
                    <div class="col-xl-9">
                        <textarea v-model="newuser.motivations" class="form-control" rows="5"
                        required></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="mx-lg-auto col-xl-9">
                        <vue-recaptcha ref="recaptcha" @verify="onCaptchaVerify" @expired="onCaptchaExpired"
                        :sitekey="reCaptchaSitekey"></vue-recaptcha>
                    </div>
                </div>
                <div class="form-group">
                    <div class="mx-lg-auto col-xl-9">
                        <div class="form-check">
                            <label>
                                <input v-model="charter" name="charter" type="checkbox" required>I read and I accept <a href="/charter/" target="_blank">IoT-LAB Terms of Service</a>.</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="mx-lg-auto col-xl-9">
                        <button id="btn_signup" class="btn btn-primary btn-lg" type="submit">Sign up</button>
                    </div>
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
      whatever: undefined,
      verified: false,
      showPopover: false,
      newuser: {
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
      // try to fool spam bots
      if (this.whatever) {
        console.log('aborted')
        return
      }
      // check reCaptcha verified
      if (!this.verified) {
        alert('Please verify the captcha "I\'m not a robot"')
      }

      try {
        await iotlab.signup(this.newuser)
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
#spambot {
    display: none;
}
.cursor:hover {
    cursor: pointer;
}
.mypopover {
    position: absolute;
    top: -207px;
    left: 190px;
    width: 300px;
    z-index: 2;
    font-weight: normal;
    text-align: left;
}
.mypopover ul {
    margin-top: 5px;
}
label {
    position: relative;
}
.v-select {
    background: white;
    border-radius: 3px;
}
</style>
