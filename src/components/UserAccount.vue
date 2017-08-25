<template>
<div class="container">
    <h2><i class="fa fa-fw fa-user" aria-hidden="true"></i> My Account</h2>
    <div class="row">
      <div class="col-md-3">
        <div class="list-group" id="list-tab" role="tablist">
          <a class="list-group-item list-group-item-action active" id="list-profile-list" data-toggle="list" href="#list-profile" role="tab" aria-controls="profile"><i class="fa fa-fw fa-address-card" aria-hidden="true"></i> My profile</a>
          <a class="list-group-item list-group-item-action" id="list-password-list" data-toggle="list" href="#list-password" role="tab" aria-controls="password"><i class="fa fa-fw fa-unlock-alt" aria-hidden="true"></i> Password</a>
          <a class="list-group-item list-group-item-action" id="list-sshkeys-list" data-toggle="list" href="#list-sshkeys" role="tab" aria-controls="sshkeys"><i class="fa fa-fw fa-key" aria-hidden="true"></i> SSH Keys</a>
          <a class="list-group-item list-group-item-action" id="list-delete-list" data-toggle="list" href="#list-delete" role="tab" aria-controls="delete"><i class="fa fa-fw fa-trash-o" aria-hidden="true"></i> Delete account</a>
        </div>
      </div>
      <div class="col-9">
        <div class="tab-content" id="nav-tabContent">
          <div class="tab-pane fade show active" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
            
            <h5><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit my profile</h5>
            <form @submit.prevent="saveDetails">
                <div class="row">
                  <div class="col-md">
                    <div class="form-group">
                        <label class="form-control-label" for="txt_firstname">First name</label>
                        <input placeholder="First name" v-model="user.firstName" class="form-control"
                        type="text" required>
                    </div>
                  </div>
                  <div class="col-md">                    
                    <div class="form-group">
                        <label class="form-control-label" for="txt_lastname">Last name</label>
                        <input placeholder="Last name" v-model="user.lastName" class="form-control"
                        type="text" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md">
                    <div class="form-group">
                        <label class="form-control-label" for="txt_email">Email</label>
                        <input v-model="user.email" class="form-control" type="email" required placeholder="Academic or professional email">
                    </div>
                  </div>
                  <div class="col-md"></div>
                </div>
                <div class="row">
                  <div class="col-md">
                    <div class="form-group">
                        <label class="form-control-label" for="txt_profile">User category</label>
                        <v-select v-model="user.category" :options="categories" placeholder="Category" :searchable="false" required></v-select>
                    </div>                    
                  </div>
                  <div class="col-md">
                    <div class="form-group">
                        <label class="form-control-label" for="txt_structure">Organization</label>
                        <input placeholder="Organization" v-model="user.organization" class="form-control" type="text" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md">
                    <div class="form-group">
                        <label class="form-control-label" for="txt_city">City</label>
                        <input placeholder="City" v-model="user.city" class="form-control" type="text" required>
                    </div>
                  </div>
                  <div class="col-md">
                    <div class="form-group">
                        <label class="form-control-label" for="txt_country">Country</label>
                        <v-select :options="countries" v-model="user.country" required placeholder="Country"
                        @keydown.enter.prevent=""></v-select>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="txt_motivation">Motivation</label>
                    <textarea v-model="user.motivations" class="form-control" rows="5" placeholder="Motivations" required></textarea>
                </div>
                <div class="form-group">
                    <div class="alert alert-info form-control">
                        <i class="fa fa-pencil"></i> <b>Tell us about your motivations:</b>
                        <ul style="margin: 0">
                            <li>Research domain (Radio communication, networking protocol, distributed applications, &#x2026;)</li>
                            <li>What kind of experiments do you want to run with IoT-LAB?</li>
                            <li>Goal (Verify something existing in large scale, new development, &#x2026;)</li>
                            <li>Experience with sensor networks (n00b, experiments with X platform, former IoT-LAB user, Guru, God)</li>
                        </ul>
                    </div>
                </div>
                <div class="form-group">
                  <button class="btn btn-success" type="submit">Update profile</button>
                  <button class="btn btn-secondary" type="button" @click="reset">Reset</button>
                </div>
            </form>
            
          </div>
          <div class="tab-pane fade" id="list-password" role="tabpanel" aria-labelledby="list-password-list">
            
            <h5><i class="fa fa-fw fa-user" aria-hidden="true"></i> Username <span class="text-muted">{{auth.username}}</span></h5>
            <p>Your username cannot be modified.</p>
            <h5><i class="fa fa-fw fa-unlock-alt" aria-hidden="true"></i> Change my password</h5>
            <form class="" @submit.prevent="changePassword"> 
              <input v-model="pwd.old" type="password" placeholder="Current password" class="form-control" style="margin-top: 8px;" required>
              <input v-model="pwd.new" type="password" placeholder="New password" class="form-control" style="margin-top: 8px;" required>
              <input v-model="pwd.confirm" type="password" placeholder="Confirm password" class="form-control" style="margin-top: 8px;" required>
              <button class="btn btn-success" :class="pwdState" type="submit" style="margin-top: 10px;">Change Password</button>
            </form>            
          </div>

          <div class="tab-pane fade" id="list-sshkeys" role="tabpanel" aria-labelledby="list-sshkeys-list">

            <h5><i class="fa fa-fw fa-key" aria-hidden="true"></i> SSH keys</h5>
            <form class="" @submit.prevent="saveKeys">
                <ul class="nav nav-tabs" style="border-bottom: 1px solid transparent; position: relative; top: 1px">
                    <li v-for="(key, i) in keys" class="nav-item"> <a :href="'#tab_SSH'+i" :class="{ 'active': i === activeKey }" data-toggle="tab" @click="activeKey = i"
                        class="nav-link">
                        SSH key {{i+1}}
                        <a @click.stop.prevent="delKey(i)" v-show="i === activeKey && keys.length > 1"><i class="fa fa-times-circle"></i></a> </a>
                    </li>
                    <li class="nav-item"><a @click="addKey" class="nav-link"><i class="fa fa-plus"></i></a>
                    </li>
                </ul>
                <div class="tab-content" id="sshkeysTabContent">
                    <div v-for="(key, i) in keys" class="tab-pane" :class="{ 'active': i === activeKey, 'show': i === 0 }"
                    :id="'tab_SSH'+i">
                        <div class="control-group">
                            <div class="controls">
                                <textarea v-model="keys[i]" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-success" :class="keyState" type="submit" style="margin-top: 10px;">Save SSH keys</button>
            </form>
          </div>
          <div class="tab-pane fade" id="list-delete" role="tabpanel" aria-labelledby="list-delete-list">
            <p class="lead">Permanently delete your account?</p>
            <p><strong>All your data will be lost</strong></p>
            <button class="btn btn-danger" type="button"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i> Delete Account</button>
          </div>
        </div>
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
import {messages} from '@/App'

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
      activeKey: 0,
      keyState: [],
      pwdState: [],
    }
  },

  created () {
    iotlab.getSSHkeys().then(data => { this.keys = data })
    iotlab.getUserInfo().then(data => { this.user = data })
  },

  methods: {
    async reset () {
      this.user = await iotlab.getUserInfo()
    },
    async saveDetails () {
      if (typeof this.user.category === 'object') {
        this.user.category = this.user.category.value
      }
      try {
        await iotlab.setUserInfo(this.user)
        this.messages.
      } catch (err) {

      }
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
.v-select {
    background: white;
    border-radius: 3px;
}
label {
    font-weight: normal;
}

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
