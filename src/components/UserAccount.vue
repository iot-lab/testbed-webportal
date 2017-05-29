<template>
<div class="container">

    <h1>My Account</h1>

    <div class="row">
        <div class="col-md-12">

        <h2><i class="fa fa-pencil" aria-hidden="true"></i> Edit my details</h2>

        <form @submit.prevent="saveDetails">
            <div class="form-group">
                <label class="control-label">Username</label>
                <input v-model="auth.username" class="form-control" type="text" disabled/>
            </div>

            <div class="form-group">
                <label class="control-label" for="txt_firstname">First name</label>
                <input id="txt_firstname" placeholder="First name" v-model="user.firstName" class="form-control" type="text" required>
            </div>

            <div class="form-group">
                <label class="control-label" for="txt_lastname">Last name</label>
                <input id="txt_lastname" placeholder="Last name" v-model="user.lastName" class="form-control" type="text" required>
            </div>

            <div class="form-group">
                <label class="control-label" for="txt_email">Email</label>
                <input id="txt_email" v-model="user.email" class="form-control" type="email" required
                            placeholder="Academic or professional email">
                <span class="help-block">Please fill with an <b>academic</b> or <b>professional</b> email in order
                            to validate your account (no gmail, no hotmail, ...)</span>
            </div>

            <div class="form-group">
                <label class="control-label" for="txt_profile">User category</label>
                <v-select id="txt_profile" v-model="user.category" :options="categories" placeholder="Category" required></v-select>
            </div>

            <div class="form-group">
                <label class="control-label" for="txt_structure">Organization</label>
                <input id="txt_structure" placeholder="Organization" v-model="user.structure" class="form-control" type="text" required>
            </div>

            <div class="form-group">
                <label class="control-label" for="txt_city">City</label>
                <input id="txt_city" placeholder="City" v-model="user.city" class="form-control" type="text" required>
            </div>

            <div class="form-group">
                <label class="control-label" for="txt_country">Country</label>
                <v-select id="txt_country" :options="countries" v-model="user.country" required placeholder="Country" @keydown.enter.prevent=""></v-select>
            </div>

            <div class="form-group">
                <label class="control-label" for="txt_motivation">Motivation:</label>
                <textarea id="txt_motivation" v-model="user.motivations" class="form-control" rows="5" required></textarea>
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

            <button class="btn btn-primary" type="submit">Update details</button>
            <button class="btn btn-default" type="reset">Reset</button>

        </form>

        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-12">

          <h2><i class="fa fa-key" aria-hidden="true"></i> SSH keys</h2>
          <form class="form-horizontal" @submit.prevent="saveKeys">
              <ul class="nav nav-tabs" style="border-bottom: 1px solid transparent; position: relative; top: 1px">
                  <li role="presentation" v-for="(key, i) in keys" :class="{ 'active': i === activeKey }">
                      <a :href="'#tab_SSH'+i" data-toggle="tab" @click="activeKey = i">
                          SSH key {{i+1}}
                          <a @click.stop.prevent="delKey(i)" v-show="i === activeKey && keys.length > 1"><i class="fa fa-times-circle"></i></a>
                      </a>
                  </li>
                  <li><a @click="addKey"><i class="fa fa-plus"></i></a></li>
              </ul>

              <div class="tab-content">
                  <div v-for="(key, i) in keys" class="tab-pane" :class="{ 'active': i === activeKey }" :id="'tab_SSH'+i">
                      <div class="control-group">
                          <div class="controls">
                              <textarea v-model="keys[i]" class="form-control" rows="5"></textarea>
                          </div>
                      </div>
                  </div>
              </div>

              <button class="btn btn-primary" :class="keyState" type="submit" style="margin-top: 10px;">Save SSH keys</button>
          </form>

      </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-12">

            <h2><i class="fa fa-unlock-alt" aria-hidden="true"></i> Change password</h2>
            <form class="form" @submit.prevent="changePassword">
                <div class="form-group">
                    <label class="control-label" for="txt_password">Current password</label>
                    <input for="txt_password" v-model="pwd.old" type="password" placeholder="Current password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="control-label" for="txt_new_password">New password</label>
                    <input for="txt_new_password" v-model="pwd.new" type="password" placeholder="New password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="control-label" for="txt_confirm_password">Confirm new password</label>
                    <input for="txt_confirm_password" v-model="pwd.confirm" type="password" placeholder="Confirm new password" class="form-control" required>
                </div>
                    <button class="btn btn-primary" :class="pwdState" type="submit" style="margin-top: 10px;">Change Password</button>
            </form>

        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-12">
            <h2><i class="fa fa-trash-o" aria-hidden="true"></i> Delete account</h2>
            <button class="btn btn-danger" type="button">Delete your account</button>
        </div>
    </div>

</div> <!-- container -->

</template>

<script>
import vSelect from 'vue-select'
import countries from '@/assets/js/countries'
import categories from '@/assets/js/categories'
import {iotlab} from '@/rest'
import {auth} from '@/auth'

export default {
  name: 'UserAccount',
  components: {vSelect},

  data () {
    return {
      user: {},
      keys: [''],
      pwd: {},
      countries: countries,
      categories: categories,
      auth: auth,
      showPopover: false,
      activeKey: 0,
      keyState: [],
      pwdState: [],
    }
  },

  async created () {
    this.keys = await iotlab.getSSHkeys()
  },

  methods: {
    async saveDetails () {

    },
    async changePassword () {
      this.pwdState = []
      try {
        await iotlab.changePassword(this.pwd.old, this.pwd.new, this.pwd.confirm)
        this.pwdState = ['isSuccess']
        this.pwd = {}
        alert('Success')
      } catch (err) {
        this.pwdState = ['isError']
        this.pwd = {}
        alert('Failed')
      }
    },
    async saveKeys () {
      this.keyState = []
      try {
        await iotlab.modifySSHkeys(this.keys)
        this.keyState = ['isSuccess']
      } catch (err) {
        this.keyState = ['isError']
      }
    },
    addKey () {
      this.keys.push('')
      this.activeKey = this.keys.length - 1
    },
    delKey (index) {
      this.keys.splice(index, 1)
      this.activeKey = Math.min(this.activeKey, this.keys.length - 1)
    },
  },
}
</script>

<style scoped>
.isSuccess, .isError {
    position: relative;
}
.isSuccess::after, .isError::after {
    font-family: "FontAwesome";
    position: absolute;
    font-size: 1.2em;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    background-color: green;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: 1s ease 0s normal forwards 1 fadein;
}
.isSuccess::after {
    content:"\f00c";
    background-color: green;
}
.isError::after {
    content:"\f00d";
    background-color: darkred;
}

@keyframes fadein{
    0% { opacity: 1; }
    50% { opacity: 1; }
    100% { opacity: 0; }
}
</style>
