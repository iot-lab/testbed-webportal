<template>
<div class="container">

    <h2>My Account</h2>

    <div class="row">
        <div class="col-md-7">

        <h3><i class="fa fa-pencil" aria-hidden="true"></i> Edit my details</h3>

    <form class="well form-horizontal" @submit.prevent="saveDetails">

        <div class="form-group">
            <label class="col-lg-3 control-label">Username</label>
            <label class="col-lg-9 control-label" style="text-align: left"><b>{{auth.username}}</b> <small class="text-muted pull-right">(note: the username cannot be modified)</small></label>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label" for="txt_firstname">First name</label>

            <div class="col-lg-9">
                <input placeholder="First name" v-model="user.firstName" class="form-control" type="text" required>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label" for="txt_lastname">Last name</label>

            <div class="col-lg-9">
                <input placeholder="Last name" v-model="user.lastName" class="form-control" type="text" required>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label" for="txt_email">Email</label>

            <div class="col-lg-9">
                <input v-model="user.email" class="form-control" type="email" required
                       placeholder="Academic or professional email">
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label" for="txt_profile">User category</label>

            <div class="col-lg-9">
                <v-select v-model="user.category" :options="categories" placeholder="Category" required></v-select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label" for="txt_structure">Organization</label>

            <div class="col-lg-9">
                <input placeholder="Organization" v-model="user.structure" class="form-control" type="text" required>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label" for="txt_city">City</label>

            <div class="col-lg-9">
                <input placeholder="City" v-model="user.city" class="form-control" type="text" required>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label" for="txt_country">Country</label>

            <div class="col-lg-9">
                <v-select :options="countries" v-model="user.country" required placeholder="Country" @keydown.enter.prevent=""></v-select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label" for="txt_motivation">
                Motivation:<br>
                <i class="fa fa-question-circle fa-lg cursor"
                    @click="showPopover = !showPopover"
                    @mouseover="showPopover = true"
                    @mouseout="showPopover = false">
                </i>
                <!-- <div class="col-md-4"> -->
                    <div class="alert alert-info mypopover" :class="{ 'hidden': !showPopover }">
                        <i class="fa fa-info-circle"></i> <b>Tell us about your motivations:</b>
                        <ul>
                            <li>Research domain (Radio communication, networking protocol, distributed applications, …).</li>
                            <li>What kind of experiments do you want to run with IoT-LAB ?</li>
                            <li>Goal: (Verify something existing in large scale, new development, …)</li>
                            <li>Network sensor previous experience (n00b, experiments with X platform, former IoT-LAB user, Guru, God)</li>
                        </ul>
                    </div>
                <!-- </div> -->
            </label>

            <div class="col-lg-9">
                <textarea v-model="user.motivations" class="form-control" rows="5"
                          required></textarea>
            </div>

        </div>


        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-9">
                <button class="btn btn-primary" type="submit">Save</button>
                <button class="btn btn-default" type="reset">Reset</button>
            </div>
        </div>

    </form>

        </div>

        <div class="col-md-5">
        <div class="row">
            
            <h3><i class="fa fa-key" aria-hidden="true"></i> SSH keys</h3>
            <form class="form-horizontal" @submit.prevent="saveKeys">
                <ul class="nav nav-tabs" style="border-bottom: 1px solid transparent; position: relative; top: 1px">
                    <li v-for="(key, i) in keys" :class="{ 'active': i === activeKey }">
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
            <hr>
            <h3><i class="fa fa-unlock-alt" aria-hidden="true"></i> Change password</h3>
            <form class="form-horizontal" @submit.prevent="changePassword">
                <button class="btn btn-primary" type="button" @click="showPwd = true" v-if="!showPwd" style="margin-top: 6px;">Change Password</button>
                <div v-if="showPwd">
                    <input v-model="pwd.old" type="password" placeholder="Current password" class="form-control" style="margin-top: 8px;" required>
                    <input v-model="pwd.new" type="password" placeholder="New password" class="form-control" style="margin-top: 8px;" required>
                    <input v-model="pwd.confirm" type="password" placeholder="Confirm password" class="form-control" style="margin-top: 8px;" required>
                    <button class="btn btn-primary" :class="pwdState" type="submit" style="margin-top: 10px;">Change Password</button>
                </div>
            </form>
        </div>
        </div>
    </div>

    <button class="btn btn-danger" type="button"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i> Delete Account</button>

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
      showPwd: false,
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
        this.showPwd = false
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
