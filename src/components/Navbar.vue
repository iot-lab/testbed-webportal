<template>
  <header>
    <nav class="navbar navbar-expand navbar-light bg-white border-bottom d-flex flex-column flex-md-row justify-content-center">
      <div>
        <a class="navbar-brand" href="/">
          <img src="../assets/fit-iotlab3.png" alt="FIT IoT-LAB">
        </a>
      </div>
      <div class="navbar-nav">
        <a href="/" class="nav-item nav-link">
          Home
        </a>
        <a href="/docs/" class="nav-item nav-link">
          Docs
        </a>
        <a href="/learn/" class="nav-item nav-link">
          Learn
        </a>
        <a href="/community/publications" class="nav-item nav-link">
          Community
        </a>
        <a href="/blog/" class="nav-item nav-link">
          Blog
        </a>
      </div>
      <div class="ml-md-auto" role="toolbar" v-if="auth.loggedIn">
        <div class="btn-group mr-1" role="group" v-if="auth.isAdmin">
          <router-link :to="{name:'allExperiments'}" active-class="active" role="button" class="btn btn-warning" v-tooltip:bottom="'All Experiments'">
            <i class="fa fa-flask" aria-label="All experiments"></i>
          </router-link>
          <router-link :to="{name:'users'}" active-class="active" role="button" class="btn btn-warning" v-tooltip:bottom="'Manage Users'">
              <i class="fa fa-users" aria-label="Users"></i>
          </router-link>
        </div>
        <div class="btn-group" role="group" >
          <router-link :to="{name:'dashboard'}" active-class="active" role="button" class="btn btn-primary" v-tooltip:bottom="'My experiments'">
            <i class="fa fa-flask"></i>
          </router-link>
          <router-link :to="{name:'experiment'}" active-class="active" role="button" class="btn btn-primary" v-tooltip:bottom="'New experiment'">
            <i class="fa fa-plus"></i>
          </router-link>
          <router-link :to="{name:'resources'}" active-class="active" role="button" class="btn btn-primary" v-tooltip:bottom="'My resources'">
            <i class="fa fa-folder-open"></i>
          </router-link>
          <router-link :to="{name:'status'}" active-class="active" role="button" class="btn btn-primary" v-tooltip:bottom="'Testbed status'">
            <i class="fa fa-tasks"></i>
          </router-link>
          <router-link :to="{name:'account'}" active-class="active" role="button" class="btn btn-primary" v-tooltip:bottom.html="`Account <b>${auth.username}</b>`">
            <i class="fa fa-user" aria-label="Account"></i>
          </router-link>
          <a role="button" class="btn btn-primary" href="" @click="logout" v-tooltip:bottom="'Log out'"><i class="fa fa-sign-out" aria-label="Logout"></i></a>
        </div>
      </div>
      <div class="navbar-nav ml-md-auto" v-else>
        <router-link :to="{name: 'dashboard'}" role="button" class="btn btn-outline-primary">
          Access the testbed
        </router-link>
      </div>
    </nav>
  </header>
</template>

<script>
import { auth } from '@/auth'

export default {
  name: 'WebsiteNavbar',
  data () {
    return {
      auth: auth,
    }
  },
  methods: {
    logout: function (event) {
      auth.doLogout()
    },
  },
}
</script>

<style>
:target {
  // Padding top bottom by navbar height, used with .sticky-top navbar
  padding-top: 5rem;
  margin-top: -5rem;
}

@media (min-width: 768px) {
    @supports ((position: -webkit-sticky) or(position: sticky)) {
        header {
            /* position:-webkit-sticky; */position: sticky;
            top: 0;
            z-index:1040
        }
    }
}

header nav .navbar-brand img {
     height: 47px;
}
</style>
