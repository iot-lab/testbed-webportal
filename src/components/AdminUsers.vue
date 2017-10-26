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
        <router-link to="/adduser" class="btn btn-success"><i class="fa fa-user-plus"></i> Add User·s</router-link>
      </div>
    </div>
    <table class="table table-striped table-sm">
      <thead>
        <tr>
          <th class="header headerSortDown" aria-sort="ascending">Login</th>
          <th class="header">FirstName</th>
          <th class="header">LastName</th>
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
          <td><a href="" class="btn btn-sm" :class="(user.status === 'pending') ? 'btn-primary' : 'btn-outline-primary'" style="width: 70px;">{{user.status}}</a></td>
          <td><a href="" class="btn btn-sm" :class="(user.admin) ? 'btn-warning' : 'btn-outline-primary'" style="width: 70px;">{{user.admin ? 'Admin': 'User'}}</a></td>
          <td>
            <div class="btn-group" role="group" aria-label="User actions">
              <a href="" class="btn btn-sm border-0 btn-outline-secondary" title="Experiments"><i class="fa fa-fw fa-flask"></i></a>
              <a href="" class="btn btn-sm border-0 btn-outline-secondary" title="Edit"><i class="fa fa-fw fa-pencil"></i></a>
              <a href="" class="btn btn-sm border-0 btn-outline-secondary" title="Email"><i class="fa fa-fw fa-envelope"></i></a>
              <a href="" class="btn btn-sm border-0 btn-outline-danger" title="Reset password"><i class="fa fa-fw fa-unlock-alt"></i></a>
              <a href="" class="btn btn-sm border-0 btn-outline-danger" title="Delete"><i class="fa fa-fw fa-trash"></i></a>
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

  </div> <!-- container -->
</template>

<script>
import {iotlab} from '@/rest'
import {auth} from '@/auth'

export default {
  name: 'AdminUsers',

  data () {
    return {
      users: [],
      auth: auth,
      pattern: '',
      show: 'pending',
    }
  },

  created () {
    iotlab.getUsers().then(data => { this.users = data })
  },

  methods: {
    async showPending () {
      this.pattern = ''
      this.users = await iotlab.getUsers({status: 'pending'})
    },
    async showAdmin () {
      this.pattern = ''
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
  },
}
</script>

<style>

</style>
