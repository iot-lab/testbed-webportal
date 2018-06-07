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
        <div class="dropdown d-inline-block">
          <button class="btn dropdown-toggle cursor" type="button" id="actionMenuBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-wrench text-dark"></i> Actions on {{selectedUsers.length || 'selected'}} users
          </button>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="actionMenuBtn">
            <a class="dropdown-item text-danger" href="#" @click.prevent="deleteSelectedUsers"><i class="fa fa-fw fa-trash"></i> Delete users</a>
          </div>
        </div>
      </div>
    </div>
    <table class="table table-striped table-sm">
      <thead>
        <tr>
          <th class="header" width="15px"><input type="checkbox" @change="toggleSelectedUsers" v-model="allSelected"></th>
          <th class="header headerSortDown" aria-sort="ascending">Login</th>
          <th class="header">First name</th>
          <th class="header">Last name</th>
          <th class="header">Email</th>
          <th class="header">Created</th>
          <th class="header" width="50px">State</th>
          <th class="header">Groups</th>
          <th class="header" width="50px">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(user, i) in users">
          <td><input type="checkbox" :value="user" v-model="selectedUsers" @click="checkUncheckAll"></td>
          <td>{{user.login}}</td>
          <td>{{user.firstName}}</td>
          <td>{{user.lastName}}</td>
          <td><a :href="'mailto:' + user.email" class="email">{{user.email}}</a></td>
          <td :title="user.created | formatDateTime" style="white-space: nowrap">{{user.created | formatDate}}</td>
          <td>
            <a href="" class="badge"
              :class="(user.status === 'pending') ? 'badge-dark' : 'badge-info'"
              v-tooltip:top="(user.status === 'pending') ? 'Activate' : 'Deactivate'"
              @click.prevent="toggleActive(user)">
              {{user.status}}
            </a>
          </td>
          <td>
            <span class="badge mr-1" v-for="group in [...user.groups].sort()" 
              :class="group === 'admin' ? 'badge-warning' : 'badge-primary'">
              {{group}}
            </span>
          </td>
          <td>
            <div class="btn-group" role="group" aria-label="User actions">
              <a href="" class="btn btn-sm border-0 btn-outline-secondary" v-tooltip="'Groups'"
                data-toggle="modal" data-target=".edit-groups-modal" @click="currentUser = user; currentGroups = getCurrentGroups(user)">
                <i class="fa fa-fw fa-user-circle"></i>
              </a>
              <router-link :to="{name: 'userExperiments', params: {username: user.login}}"
                class="btn btn-sm border-0 btn-outline-secondary" v-tooltip="'Experiments'">
                <i class="fa fa-fw fa-flask" @click="hideTooltip"></i>
              </router-link>
              <a href="" class="btn btn-sm border-0 btn-outline-secondary" v-tooltip="'Edit'"
                data-toggle="modal" data-target=".edit-user-modal"
                @click="currentUser = JSON.parse(JSON.stringify(user)); $refs.user.clean(user)">
                <i class="fa fa-fw fa-pencil"></i>
              </a>
              <a href="" class="btn btn-sm border-0 btn-outline-secondary" v-tooltip="'Email'"
                data-toggle="modal" data-target=".email-user-modal" @click="currentUser = user; $refs.mail.$validator.clean()">
                <i class="fa fa-fw fa-envelope"></i>
              </a>
              <a href="" class="btn btn-sm border-0 btn-outline-dark" v-tooltip="'Reset password'" @click.prevent="resetPassword(user)">
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

    <div class="modal fade edit-groups-modal" tabindex="-1" role="dialog" aria-labelledby="editGroupsModal" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-light">
            <h5 class="modal-title"><i class="fa fa-fw fa-pencil"></i> Edit user groups for <span class="text-primary">{{currentUser.login}}</span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pl-5 pt-3 pb-0">
            <label class="custom-control custom-checkbox d-block" v-for="group in groups">
              <input name="checkbox-monitor" type="checkbox" class="custom-control-input" v-model="currentGroups[group.name]">
              <span class="custom-control-indicator"></span>
              <span class="custom-control-description text-capitalize">{{group.name}}</span>
            </label>
          </div>
          <div class="modal-footer border-0 dbg-light">
            <button type="button" class="btn" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" @click.prevent="updateGroups">Save groups</button>
          </div>
        </div>
      </div>
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
import UserForm from '@/components/parts/UserForm'
import EmailForm from '@/components/parts/EmailForm'
import { iotlab } from '@/rest'
import { auth } from '@/auth'
import { sleep } from '@/utils'
import $ from 'jquery'

export default {
  name: 'AdminUsers',

  components: {UserForm, EmailForm},

  data () {
    return {
      groups: [],
      users: [],
      currentUser: {},
      currentGroups: {},
      auth: auth,
      pattern: '',
      show: 'pending',
      selectedUsers: [],
      allSelected: false,
    }
  },

  async created () {
    this.pattern = this.$route.query.search
    this.search()
    this.$nextTick(function () {
      $('[data-toggle="popover"]').popover({
        trigger: 'focus',
        html: true,
      })
    })
    this.groups = (await iotlab.getUserGroups()).sort((a, b) => a.name.localeCompare(b.name))
  },

  async beforeUpdate () {
    if (this.$route.query.search && this.$route.query.search !== this.pattern) {
      this.pattern = this.$route.query.search
      this.search()
    }
  },

  methods: {
    getCurrentGroups (user) {
      const groups = {}
      if (!user.groups) return {}
      for (const g of this.groups) {
        groups[g.name] = user.groups.includes(g.name)
      }
      return groups
    },
    async showPending () {
      delete this.$route.query.search
      this.pattern = ''
      this.show = 'pending'
      this.users = await iotlab.getUsers({status: 'pending'})
    },
    async showAdmin () {
      delete this.$route.query.search
      this.pattern = ''
      this.show = 'admin'
      this.users = await iotlab.getUsers({isAdmin: true})
    },
    async search () {
      this.show = false
      if (this.pattern) {
        delete this.$route.query.search
        this.users = await iotlab.getUsers({search: this.pattern})
      } else {
        this.users = []
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
    async updateGroups () {
      const groups = Object.keys(this.currentGroups).filter(g => this.currentGroups[g] === true)
      await iotlab.setUserGroups(this.currentUser.login, groups)
      this.currentUser.groups = groups
      $('.edit-groups-modal').modal('hide')
    },
    async toggleActive (user) {
      if (user.status === 'pending') {
        if (confirm('Activate user?')) {
          try {
            await iotlab.activateUser(user.login)
            user.status = 'active'
          } catch (err) {
            this.$notify({text: 'An error occured', type: 'error'})
          }
        }
      } else {
        if (confirm('Deactivate user?')) {
          try {
            await iotlab.deactivateUser(user.login)
            user.status = 'pending'
          } catch (err) {
            this.$notify({text: 'An error occured', type: 'error'})
          }
        }
      }
    },
    async toggleAdmin (user) {
      let groups = user.groups.includes('admin') ? user.groups.filter(g => g !== 'admin') : user.groups.concat(['admin'])
      if (confirm(`${groups.includes('admin') ? 'Set' : 'Remove'} group admin for user ${user.login}?`)) {
        try {
          await iotlab.setUserGroups(user.login, groups)
          user.groups = groups
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
    hideTooltip () {
      $('.tooltip').fadeOut()
    },
    toggleSelectedUsers () {
      if (this.selectedUsers.length === this.users.length) {
        this.selectedUsers = []
      } else {
        this.selectedUsers = this.users
      }
    },
    checkUncheckAll () {
      this.$nextTick(function () {
        this.allSelected = this.selectedUsers.length === this.users.length
      })
    },
    async deleteSelectedUsers () {
      if (this.selectedUsers.length === 0) {
        this.$notify({text: `No user selected`, type: 'warning'})
        return
      }
      if (!confirm(`Delete ${this.selectedUsers.length} users. Are you sure?`)) return
      let count = 0
      let deleted = 0
      this.selectedUsers.forEach(async user => {
        try {
          await iotlab.deleteUser(user.login)
          this.users = this.users.filter(item => item.login !== user.login)
          count += 1
          deleted += 1
        } catch (err) {
          this.$notify({text: `Delete user <b>${user.login}</b> failed`, type: 'error'})
          count += 1
        }
      })
      // wait for all requests to complete
      while (count < this.selectedUsers.length) {
        await sleep(50)
      }
      this.$notify({text: `${deleted} users deleted`, type: 'success'})
    },
  },
}
</script>

<style>

</style>
