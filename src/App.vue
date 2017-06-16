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
                        <router-link tag="li" to="Dashboard" active-class="active"><a><i class="fa fa-home" aria-hidden="true"></i> Dashboard</a></router-link>
                        <router-link tag="li" to="Experiment" active-class="active"><a><i class="fa fa-plus-circle" aria-hidden="true"></i> Experiment</a></router-link>
                        <router-link tag="li" to="Monitor" active-class="active"><a><i class="fa fa-tachometer" aria-hidden="true"></i> Monitor</a></router-link>
                        <!--<li><a id='profilesModalLink' data-toggle="modal" data-target="#profiles_modal" style="cursor:pointer">Manage Profiles</a></li>-->
                    </ul>
                    <!-- <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) { ?> -->
                        <ul class="nav navbar-nav pull-right">
                            <li class="dropdown" v-if="auth.loggedIn">
                                <a href="#" data-toggle="dropdown" data-hover="dropdown"><i class="fa fa-user fa-lg" aria-hidden="true"></i> <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li class="dropdown-header"><i class="fa fa-user-o" aria-hidden="true"></i> {{auth.username}}</li>
                                    <li><router-link to="account"><i class="fa fa-pencil" aria-hidden="true"></i> Edit my account</router-link></li>
                                    <li><a href @click="logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
                                </ul>
                            </li>
                            <li class="dropdown" v-if="auth.isAdmin">
                                <a href="#" data-toggle="dropdown" data-hover="dropdown">Admin <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li id="admin_users"><a href="./admin_users.php">Users</a></li>
                                    <li id="admin_exps"><a href="./admin_exps.php">Experiments</a></li>
                                    <li id="admin_nodes"><a href="./admin_nodes.php">Nodes</a></li>
                                    <li id="admin_stats"><a href="./admin_stats.php">Statistics</a></li>
                                </ul>
                            </li>
                        </ul>
                    <!-- <?php } ?> -->
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
