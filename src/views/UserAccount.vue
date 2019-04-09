<template>
<div class="container mt-3">
    <h2 class="d-none d-sm-block d-md-none">
      <i class="fa fa-fw fa-user" aria-hidden="true"></i> My Account
    </h2>
    <div class="row">
      <div class="col-md-3 mb-4">
        <div class="list-group" id="list-tab" role="tablist">
          <a class="list-group-item list-group-item-action active" id="list-profile-list" data-toggle="list" href="#list-profile" role="tab" aria-controls="profile"><i class="fa fa-fw fa-address-card" aria-hidden="true"></i> Profile</a>
          <a class="list-group-item list-group-item-action" id="list-password-list" data-toggle="list" href="#list-password" role="tab" aria-controls="password"><i class="fa fa-fw fa-unlock-alt" aria-hidden="true"></i> Password</a>
          <a class="list-group-item list-group-item-action" id="list-sshkeys-list" data-toggle="list" href="#list-sshkeys" role="tab" aria-controls="sshkeys"><i class="fa fa-fw fa-key" aria-hidden="true"></i> SSH Keys</a>
          <a class="list-group-item list-group-item-action" id="list-delete-list" data-toggle="list" href="#list-delete" role="tab" aria-controls="delete"><i class="fa fa-fw fa-trash-o" aria-hidden="true"></i> Delete account</a>
          <a class="list-group-item list-group-item-action" id="list-mailing-list" data-toggle="list" href="#list-mailing" role="tab" aria-controls="mailing"><i class="fa fa-fw fa-envelope-o" aria-hidden="true"></i> Mailing list</a>
        </div>
      </div>
      <div class="col-md-9">
        <div class="tab-content" id="nav-tabContent">
          <div class="tab-pane fade show active" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">

            <h5><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit your profile</h5>
            <form @submit.prevent="updateProfile">
              <div class="form-group">
                <span class="lead text-muted mr-1">{{auth.username}}</span>
                <span class="badge ml-1" v-for="group in [...user.groups].sort()"
                        :class="group === 'admin' ? 'badge-warning' : 'badge-primary'">
                        {{group}}
                </span>
              </div>
              <user-form :user="user" ref="user" :hidden="['sshkeys', 'groups', 'password']"></user-form>
              <div class="form-group">
                <button class="btn btn-success mr-1" type="submit">Update profile</button>
                <button class="btn btn-secondary" type="button" @click="reset">Reset</button>
              </div>
            </form>

          </div>
          <div class="tab-pane fade" id="list-password" role="tabpanel" aria-labelledby="list-password-list">

            <h5><i class="fa fa-fw fa-unlock-alt" aria-hidden="true"></i> Change your password</h5>
            <div class="row">
              <form @submit.prevent="changePassword" class="col-md-5">
                <div class="form-group">
                  <label class="form-control-label">Current password</label>
                  <input v-model="pwd.old" type="password" placeholder="Type your current password" class="form-control"
                         name="current_password" data-vv-as="current password" v-validate="'required'"
                         :class="{'is-invalid': errors.has('current_password') }">
                <div class="invalid-feedback" v-show="errors.has('current_password')">
                  {{ errors.first('current_password') }}
                </div>
                </div>
                <div class="form-group">
                  <label class="form-control-label">New password</label>
                  <input v-model="pwd.new" type="password" placeholder="Choose a new password" class="form-control"
                         name="new_password" data-vv-as="new password" v-validate="'required'" ref="new_password"
                         :class="{'is-invalid': errors.has('new_password') }">
                  <div class="invalid-feedback" v-show="errors.has('new_password')">
                    {{ errors.first('new_password') }}
                  </div>
                </div>
                <div class="form-group">
                  <label class="form-control-label">Confirm new password</label>
                  <input v-model="pwd.confirm" type="password" placeholder="Type again your new password" class="form-control"
                         name="confirm_new_password" data-vv-as="new password" v-validate="'required|confirmed:new_password'"
                         :class="{'is-invalid': errors.has('confirm_new_password') }">
                  <div class="invalid-feedback" v-show="errors.has('confirm_new_password')">
                    {{ errors.first('confirm_new_password') }}
                  </div>
                </div>
                <button class="btn btn-success" type="submit">Change Password</button>
              </form>
              <div class="col-md-6">
                <div id="policy" class="card mt-2 border-info text-info">
                  <div class="card-body"><h6>Password Policy</h6>
                    <ul class="pl-3 mb-0">
                      <li>one upper case letter [A-Z]</li>
                      <li>three lower case letters [a-z]</li>
                      <li>one digit [0-9]</li>
                      <li>one special character [!@#$%^&*_=+-/]</li>
                      <li>minimum length of eight characters</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="tab-pane fade" id="list-sshkeys" role="tabpanel" aria-labelledby="list-sshkeys-list">

            <h5><i class="fa fa-fw fa-key" aria-hidden="true"></i> SSH keys</h5>
            <form @submit.prevent="saveKeys">
                <ssh-keys :keys="keys" :rows="6"></ssh-keys>
                <button class="btn btn-success" type="submit" style="margin-top: 10px;">Update SSH keys</button>
            </form>
          </div>
          <div class="tab-pane fade" id="list-delete" role="tabpanel" aria-labelledby="list-delete-list">
            <p class="lead">Permanently delete your account?</p>
            <p><strong>All your data will be lost</strong></p>
            <button class="btn btn-danger" type="button" @click="deleteUser"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i> Delete Account</button>
          </div>
          <div class="tab-pane fade" id="list-mailing" role="tabpanel" aria-labelledby="list-mailing-list">
            <h5 class="mb-3"><i class="fa fa-envelope-o" aria-hidden="true"></i> Mailing list subscription</h5>
            <p>You can manage your subscription to the mailing list for FIT IoT-LAB users, or browse the archive, from this page:<br>
              <a href="https://sympa.inria.fr/sympa/info/iot-lab-users">https://sympa.inria.fr/sympa/info/iot-lab-users</a>
            </p>
          </div>
        </div>
      </div>
    </div>
</div> <!-- container -->
</template>

<script>
import UserForm from '@/components/UserForm'
import SshKeys from '@/components/SshKeysForm'
import { iotlab } from '@/rest'
import { auth } from '@/auth'

export default {
  name: 'UserAccount',
  components: { UserForm, SshKeys },

  data () {
    return {
      user: {},
      keys: [''],
      pwd: {},
      auth: auth,
      activeKey: 0,
    }
  },

  created () {
    iotlab.getSSHkeys().then(data => { this.keys = data })
      .catch(err => {
        this.$notify({text: err.response.data.message || 'An error occured', type: 'error'})
      })
    iotlab.getUserInfo().then(data => { this.user = data })
      .then(_ => {
        if (this.$route.query.validate !== undefined) {
          this.$nextTick(function () {
            this.updateProfile()
          })
        }
      })
      .catch(err => {
        this.$notify({text: err.response.data.message || 'An error occured', type: 'error'})
      })
  },

  methods: {
    async reset () {
      this.user = await iotlab.getUserInfo()
    },
    async updateProfile () {
      if (await this.$refs.user.validate()) {
        try {
          await iotlab.setUserInfo(this.user)
          this.$notify({text: 'Profile updated', type: 'success'})
        } catch (err) {
          this.$notify({text: err.response.data.message || 'An error occured', type: 'error'})
        }
      }
    },
    async changePassword () {
      try {
        await iotlab.changePassword(this.pwd.old, this.pwd.new, this.pwd.confirm)
        this.pwd = {}
        this.$notify({text: 'Password successfully changed', type: 'success'})
      } catch (err) {
        this.pwd = {}
        this.$notify({title: 'Failed to change password', text: err.response.data.message, type: 'error', duration: 5000})
      }
    },
    async saveKeys () {
      try {
        await iotlab.modifySSHkeys(this.keys)
        this.$notify({text: 'SSH keys updated', type: 'success'})
        this.user.sshkeys = this.keys
      } catch (err) {
        this.$notify({text: 'Failed to update SSH keys', type: 'error'})
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
    async deleteUser () {
      if (confirm(`Delete your account?`)) {
        try {
          await iotlab.deleteUser()
          auth.doLogout()
          this.$notify({text: `User account deleted`, type: 'success'})
          this.$router.push({name: 'login'})
        } catch (err) {
          this.$notify({title: 'An error occured', text: err.response.data.message, type: 'error'})
        }
      }
    },
  },
}
</script>
