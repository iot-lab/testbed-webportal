<template>
  <div id="app">
    <!-- <img src="./assets/logo.png"> -->
    <!--  LOGGED IN NAV BAR  -->

    <!-- <?php if (isset($_SESSION['is_auth']) && $_SESSION['is_auth'] && !$is_activity) { ?> -->
        <!-- <div class="navbar navbar-default navbar-fixed-top navbar-grey" role="banner"> -->
        <div class="navbar navbar-default navbar-grey" role="banner" v-if="auth.loggedIn">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex2-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <nav class="navbar-collapse collapse navbar-ex2-collapse" role="navigation">
                    <ul class="nav navbar-nav">
                        <router-link tag="li" to="dashboard" active-class="active"><a><i class="fa fa-home" aria-hidden="true"></i> Dashboard</a></router-link>
                        <router-link tag="li" to="experiment" active-class="active"><a><i class="fa fa-plus-circle" aria-hidden="true"></i> Experiment</a></router-link>
                        <router-link tag="li" to="monitor" active-class="active"><a><i class="fa fa-tachometer" aria-hidden="true"></i> Monitor</a></router-link>
                    </ul>
                        <ul class="nav navbar-nav pull-right">
                            <li class="dropdown" v-if="auth.loggedIn">
                                <a href="#" data-toggle="dropdown" data-hover="dropdown"><i class="fa fa-user fa-lg" aria-hidden="true"></i> <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li class="dropdown-header"><i class="fa fa-user-o" aria-hidden="true"></i> {{auth.username}}</li>
                                    <router-link tag="li" to="account" active-class="active"><a><i class="fa fa-pencil" aria-hidden="true"></i> My account</a></router-link>
                                    <li><a href @click="logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
                                </ul>
                            </li>
                            <li class="dropdown" v-if="auth.isAdmin">
                                <a href="#" data-toggle="dropdown" data-hover="dropdown">Admin <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="">Users</a></li>
                                    <li><a href="">Experiments</a></li>
                                    <li><a href="">Nodes</a></li>
                                    <li><a href="">Statistics</a></li>
                                </ul>
                            </li>
                        </ul>
                </nav>
                <!--/.nav-collapse -->
            </div>
            <!--/.container -->
        </div><!--/.navbar -->

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
#app {

}
</style>
