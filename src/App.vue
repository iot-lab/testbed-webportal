<template>
  <div id="app">
    <!-- <img src="./assets/logo.png"> -->
    <!--  LOGGED IN NAV BAR  -->

    <!-- <?php if (isset($_SESSION['is_auth']) && $_SESSION['is_auth'] && !$is_activity) { ?> -->
        <!-- <div class="navbar navbar-default navbar-fixed-top navbar-grey" role="banner"> -->
        <div class="navbar navbar-default navbar-grey" role="banner">
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
                        <li class="divider-vertical"></li>
                        <li id="dashboard2"><router-link to="Dashboard">Dashboard</router-link></li>
                        <li id="exp_new2"><router-link to="Experiment">New Experiment</router-link></li>
                        <!--<li><a id='profilesModalLink' data-toggle="modal" data-target="#profiles_modal" style="cursor:pointer">Manage Profiles</a></li>-->
                        <li id="profiles2"><router-link to="Monitoring">Monitoring</router-link></li>
                        <li class="divider-vertical"></li>
                    </ul>
                    <!-- <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) { ?> -->
                        <ul class="nav navbar-nav pull-right">
                            <li class="dropdown">
                                <a href="#" data-toggle="dropdown" data-hover="dropdown"><i class="fa fa-user fa-lg" aria-hidden="true"></i> <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><router-link to="UserAccount"><i class="fa fa-pencil" aria-hidden="true"></i> Edit my account</router-link></li>
                                    <li><a href @click="logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
                                </ul>
                            </li>
                            <li class="dropdown" id="admin">
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
import {doLogout} from './main.js'

export default {
  name: 'app',
  methods: {
    logout: function (event) {
      doLogout()
    },
  },
}
</script>

<style>
#app {

}
</style>
