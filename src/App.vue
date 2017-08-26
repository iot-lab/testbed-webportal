<template>
  <div id="app">
    <!-- <img src="./assets/logo.png"> -->
    <!--  LOGGED IN NAV BAR  -->

    <!-- <?php if (isset($_SESSION['is_auth']) && $_SESSION['is_auth'] && !$is_activity) { ?> -->
        <!-- <div class="navbar navbar-default navbar-fixed-top navbar-grey" role="banner"> -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light" v-if="auth.loggedIn">
    <div class="container"> 
      <a class="navbar-brand" href="#"><img src="./assets/disc-iotlab.svg" width="30"></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <router-link tag="li" to="dashboard" active-class="active">
                <a class="nav-link"><i class="fa fa-tasks" aria-hidden="true"></i> Dashboard</a>
            </router-link>
            <router-link tag="li" to="experiment"  active-class="active">
                <a class="nav-link"><i class="fa fa-flask" aria-hidden="true"></i> Experiment</a>
            </router-link>
            <router-link tag="li" to="monitor" active-class="active">
                <a class="nav-link"><i class="fa fa-thermometer" aria-hidden="true"></i> Monitor</a>
            </router-link>
        </ul>
        <ul class="nav navbar-nav float-xs-right">
            <li class="dropdown nav-item" v-if="auth.loggedIn">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-user fa-lg" aria-hidden="true"></i> </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <h6 class="dropdown-header"><i class="fa fa-user-o" aria-hidden="true"></i> {{auth.username}}</h6>
                    <router-link to="account" class="dropdown-item" active-class="active">
                        <i class="fa fa-pencil" aria-hidden="true"></i> My account
                    </router-link>
                    <a class="dropdown-item" href="" @click="logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
                </div>
            </li>
            <li class="dropdown nav-item" v-if="auth.isAdmin">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" data-hover="dropdown">Admin</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="">Users</a>
                        <a class="dropdown-item" href="">Experiments</a>
                        <a class="dropdown-item" href="">Nodes</a>
                        <a class="dropdown-item" href="">Statistics</a>
                    </div>
                </li>
                </ul>
      </div>
    </div>
    </nav>

    <notifications position="bottom right" duration="2" animation-type="velocity">
      <template slot="body" scope="props">
        <div style="margin-right: 10px" :class="['alert', 'alert-'+(e => e==='error'?'danger':e)(props.item.type)]" @click="props.close">
            <h6 class="alert-heading" v-if="props.item.title" v-html="props.item.title"></h6>
            <div v-html="props.item.text"></div>
            <div v-html="props.item.icon"></div>
        </div>
      </template>
    </notifications>
    
    <router-view></router-view>
  </div>
</template>

<script>
import {auth} from '@/auth'

export default {
  name: 'app',
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
.navbar {
    margin-bottom: 20px;
}
.v-select .dropdown-toggle {
    padding: 1px !important; /* adjust v-select to bootstrap-4 */
}
</style>
