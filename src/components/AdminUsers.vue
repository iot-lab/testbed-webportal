<template>
  <div class="container mt-3">

    <h2>Users</h2>
    <div class="row">
      <div class="col-md-4 mb-4">
        <div class="my-2">
          <span class="lead mr-3">Show</span>                
          <label class="custom-control custom-radio">
            <input id="radioStacked1" name="radio-stacked" type="radio" class="custom-control-input" v-model="show" value="pending" @click="showPending">
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">Pending users</span>
          </label>
          <label class="custom-control custom-radio mr-0">
            <input id="radioStacked2" name="radio-stacked" type="radio" class="custom-control-input" v-model="show" value="admin" @click="showAdmin">
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">Administrators</span>
          </label>
        </div>
        <form @submit.prevent="search">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Search for users" v-model="pattern">
            <div class="input-group-btn">
              <button type="submit" class="btn btn-secondary" aria-label="Search"> <i class="fa fa-search"></i> </button>
            </div>
          </div>
        </form>
      </div>
      <div class="col-md-8 text-right align-self-end mb-4">
        <router-link :to="{name:'addUsers'}" class="btn btn-success"><i class="fa fa-user-plus"></i> Add Users</router-link>
      </div>
    </div>
    <table class="table table-striped table-sm">
      <thead>
        <tr>
          <th class="header headerSortDown" aria-sort="ascending">Login</th>
          <th class="header">First name</th>
          <th class="header">Last name</th>
          <th class="header">Email</th>
          <th class="header">Created</th>
          <th class="header" width="50px">State</th>
          <th class="header" width="50px">Role</th>
          <th class="header" width="50px">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(user, i) in users">
          <td>{{user.login}}</td>
          <td>{{user.firstName}}</td>
          <td>{{user.lastName}}</td>
          <td><a :href="'mailto:' + user.email" class="email">{{user.email}}</a></td>
          <td :title="user.created | formatDateTime">{{user.created | formatDate}}</td>
          <td>
            <a href="" class="btn btn-sm" style="width: 70px"
              :class="(user.status === 'pending') ? 'btn-primary' : 'btn-outline-primary'"
              v-tooltip:top="(user.status === 'pending') ? 'Activate' : 'Deactivate'"
              @click.prevent="toggleActive(user)">
              {{user.status}}
            </a>
          </td>
          <td>
            <a href="" class="btn btn-sm" style="width: 70px"
              :class="user.admin ? 'btn-warning' : 'btn-outline-primary'"
              v-tooltip:top="user.admin ? ' User' : 'Admin'"
              @click.prevent="toggleAdmin(user)">
              {{user.admin ? 'Admin': 'User'}}
            </a>
          </td>
          <td>
            <div class="btn-group" role="group" aria-label="User actions">
              <a href="" class="btn btn-sm border-0 btn-outline-secondary" v-tooltip="'Experiments'" @click.prevent="">
                <i class="fa fa-fw fa-flask"></i>
              </a>
              <a href="" class="btn btn-sm border-0 btn-outline-secondary" v-tooltip="'Edit'"
                data-toggle="modal" data-target=".edit-user-modal" @click="currentUser = user; $refs.user.clean(user)">
                <i class="fa fa-fw fa-pencil"></i>
              </a>
              <a href="" class="btn btn-sm border-0 btn-outline-secondary" v-tooltip="'Email'"
                data-toggle="modal" data-target=".email-user-modal" @click="currentUser = user; $refs.mail.$validator.clean()">
                <i class="fa fa-fw fa-envelope"></i>
              </a>
              <a href="" class="btn btn-sm border-0 btn-outline-danger" v-tooltip="'Reset password'" @click.prevent="resetPassword(user)">
                <i class="fa fa-fw fa-unlock-alt"></i>
              </a>
              <a href="" class="btn btn-sm border-0 btn-outline-danger" v-tooltip="'Delete'" @click.prevent="deleteUser(user)">
                <i class="fa fa-fw fa-trash"></i>
              </a>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="text-muted">
      <p v-if="users && users.length == 1">{{users.length}} matching user</p>
      <p v-else-if="users && users.length > 1">{{users.length}} matching users</p>
      <p v-else>No matching user found</p>        
    </div>

    <div class="modal fade edit-user-modal" tabindex="-1" role="dialog" aria-labelledby="editUserModal" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-light">
            <h5 class="modal-title"><i class="fa fa-fw fa-pencil"></i> Edit <span class="text-primary">{{currentUser.login}}</span>'s profile</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body px-4 pt-3 pb-0">
            <user-form ref="user" :user="currentUser" :admin="true"></user-form>
          </div>
          <div class="modal-footer border-0 dbg-light">
            <button type="button" class="btn" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" @click.prevent="updateProfile">Save profile</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade email-user-modal" tabindex="-1" role="dialog" aria-labelledby="emailUserModal" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-light">
            <h5 class="modal-title">
              <i class="fa fa-fw fa-envelope"></i>
              Send email to {{currentUser.firstName}} {{currentUser.lastName}} <span class="text-muted">({{ currentUser.login }})</span>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body px-4 pt-3 pb-0">
            <email-form ref="mail" :to="currentUser.email + ', admin@iot-lab.info'"></email-form>
          </div>
          <div class="modal-footer border-0 dbg-light">
            <button type="button" class="btn" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" @click.prevent="$refs.mail.send">Send</button>
          </div>
        </div>
      </div>
    </div>

  </div> <!-- container -->
</template>

<script>
import UserForm from '@/components/UserForm'
import EmailForm from '@/components/EmailForm'
import {iotlab} from '@/rest'
import {auth} from '@/auth'
import $ from 'jquery'

export default {
  name: 'AdminUsers',

  components: {UserForm, EmailForm},

  data () {
    return {
      users: [],
      currentUser: {},
      auth: auth,
      pattern: '',
      show: 'pending',
    }
  },

  async created () {
    this.users = await iotlab.getUsers()
  },

  methods: {
    async showPending () {
      this.pattern = ''
      this.show = 'pending'
      this.users = await iotlab.getUsers({status: 'pending'})
    },
    async showAdmin () {
      this.pattern = ''
      this.show = 'admin'
      this.users = await iotlab.getUsers({isAdmin: true})
    },
    async search () {
      if (this.pattern) {
        this.show = false
        this.users = await iotlab.getUsers({search: this.pattern})
      } else {
        this.show = 'pending'
        this.showPending()
      }
    },
    async updateProfile () {
      if (await this.$refs.user.validate()) {
        try {
          await iotlab.setUserInfo(this.currentUser, this.currentUser.login)
          $('.edit-user-modal').modal('hide')
        } catch (err) {
          this.$notify({text: 'An error occured', type: 'error'})
        }
      }
    },
    async toggleActive (user) {
      if (user.status === 'pending') {
        if (confirm('Activate user?')) {
          try {
            await iotlab.activateUser(user.login)
            user.status = 'active'
            user.admin = false
          } catch (err) {
            this.$notify({text: 'An error occured', type: 'error'})
          }
        }
      } else {
        if (confirm('Deactivate user?')) {
          try {
            await iotlab.deactivateUser(user.login)
            user.status = 'pending'
            user.admin = false
          } catch (err) {
            this.$notify({text: 'An error occured', type: 'error'})
          }
        }
      }
    },
    async toggleAdmin (user) {
      if (confirm(`${user.admin ? 'Remove' : 'Set'} role admin for user?`)) {
        try {
          await iotlab.setAdminRole(user.login, !user.admin)
          user.admin = !user.admin
        } catch (err) {
          this.$notify({text: 'An error occured', type: 'error'})
        }
      }
    },
    async resetPassword (user) {
      if (confirm(`Reset password for user ${user.login}?\n\nThe user will receive a new password by email.`)) {
        try {
          await iotlab.resetPassword(user.email)
          this.$notify({text: 'New password sent by email', type: 'success'})
        } catch (err) {
          this.$notify({text: 'An error occured', type: 'error'})
        }
      }
    },
    async deleteUser (user) {
      if (confirm(`Delete user ${user.login}?`)) {
        try {
          await iotlab.deleteUser(user.login)
          this.users = this.users.filter(item => item.login !== user.login)
          this.$notify({text: `User ${user.login} deleted`, type: 'success'})
        } catch (err) {
          this.$notify({text: 'An error occured', type: 'error'})
        }
      }
    },
  },
}
</script>

<style>

</style>
