<template>
<div class="container">

    <div class="row">
        <div class="col-md-12">
            <h1>Sign up</h1>

            <div v-if="success" class="alert alert-success">Success</div>

            <div v-else-if="error" class="alert alert-danger">Failed</div>

            <form v-else @submit.prevent="signup">

                <div class="form-group">
                    <label class="control-label" for="txt_firstname">First name:</label>
                    <input id="txt_firstname" placeholder="First name" v-model="newuser.firstName" class="form-control" type="text" required>
                </div>

                <div class="form-group">
                    <label class="control-label" for="txt_lastname">Last name:</label>
                    <input id="txt_lastname" placeholder="Last name" v-model="newuser.lastName" class="form-control" type="text" required>
                </div>

                <div class="form-group">
                    <label class="control-label" for="txt_email">Email:</label>
                    <input id="txt_email" v-model="newuser.email" class="form-control" type="email" required
                               placeholder="Academic or professional email">
                    <span class="help-block">Please fill with an <b>academic</b> or <b>professional</b> email in order
                                to validate your account (no gmail, no hotmail, ...)</span>
                </div>

                <div class="form-group">
                    <label class="control-label" for="txt_profile">User category:</label>
                    <v-select id="txt_profile" v-model="category" :options="categories" placeholder="Category" required></v-select>
                </div>

                <div class="form-group">
                    <label class="control-label" for="txt_organization">Organization:</label>
                    <input id="txt_organization" placeholder="Organization" v-model="newuser.organization" class="form-control" type="text" required>
                </div>

                <div class="form-group">
                    <label class="control-label" for="txt_city">City:</label>
                    <input id="txt_city" placeholder="City" v-model="newuser.city" class="form-control" type="text" required>
                </div>

                <div class="form-group" id="spambot">
                    <label class="control-label">Leave empty:</label>
                    <input placeholder="whatever" v-model="whatever" class="form-control" type="text">
                </div>

                <div class="form-group">
                    <label class="control-label" for="txt_country">Country:</label>
                    <v-select id="txt_country" :options="countries" v-model="newuser.country" placeholder="Country" @keydown.enter.prevent="" required></v-select>
                </div>

                <div class="form-group">
                    <label class="control-label" for="txt_motivation">Motivation:</label>
                    <textarea id="txt_motivation" v-model="newuser.motivations" class="form-control" rows="5" required></textarea>
                    <span class="help-block">
                        Tell us about your motivations:
                        <ul>
                            <li>Research domain (Radio communication, networking protocol, distributed applications, …).</li>
                            <li>What kind of experiments do you want to run with IoT-LAB ?</li>
                            <li>Goal: (Verify something existing in large scale, new development, …)</li>
                            <li>Network sensor previous experience (n00b, experiments with X platform, former IoT-LAB user, Guru, God)</li>
                        </ul>
                    </span>
                </div>

                <div class="checkbox">
                    <label>
                        <input v-model="charter" name="charter" type="checkbox" required/>
                        I read and I accept <a href="/charter/" target="_blank">IoT-LAB Terms of Service</a>.
                    </label>
                </div>

                <button id="btn_signup" class="btn btn-primary" type="submit">Sign up</button>
                
            </form>

        </div>

    </div>
    <!-- row -->
</div>
</template>

<script>
import vSelect from 'vue-select'
import {iotlab} from '@/rest'
import countries from '@/assets/js/countries'
import categories from '@/assets/js/categories'

export default {
  name: 'signup',
  components: {vSelect},

  data () {
    return {
      charter: false,
      whatever: undefined,
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
  },
}
</script>

<style scoped>
#spambot {
    display: none;
}
</style>
