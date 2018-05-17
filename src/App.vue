<template>
  <div id="app">
    <wordpress-navbar></wordpress-navbar>
    <nav class="navbar navbar-expand-md navbar-light bg-light" v-if="auth.loggedIn">
    <div class="container"> 
      <!-- <a class="navbar-brand" href="#"><img src="./assets/disc-iotlab.svg" width="30"></a> -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <router-link tag="li" :to="{name:'dashboard'}" active-class="active">
            <a class="nav-link"><i class="fa fa-tasks" aria-hidden="true"></i> Dashboard</a>
          </router-link>
          <router-link tag="li" :to="{name:'experiment'}" active-class="active">
            <a class="nav-link"><i class="fa fa-flask" aria-hidden="true"></i> Experiment</a>
          </router-link>
          <router-link tag="li" :to="{name:'resources'}" active-class="active">
            <a class="nav-link"><i class="fa fa-folder-open" aria-hidden="true"></i> Resources</a>
            <!-- <a class="nav-link"><i class="fa fa-briefcase" aria-hidden="true"></i> Resources</a> -->
            <!-- <a class="nav-link"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> Resources</a> -->
          </router-link>
        </ul>
        <ul class="nav navbar-nav float-xs-right">
          <router-link tag="li" :to="{name:'allExperiments'}" active-class="active" v-if="auth.isAdmin">
            <a class="nav-link" v-tooltip:bottom="'All Experiments'">
              <i class="fa fa-lg fa-fw fa-flask" aria-label="All experiments"></i>
            </a>
          </router-link>
          <router-link tag="li" :to="{name:'users'}" active-class="active" v-if="auth.isAdmin">
            <a class="nav-link" v-tooltip:bottom="'Manage Users'">
              <i class="fa fa-lg fa-fw fa-users" aria-label="Users"></i>
            </a>
          </router-link>
          <router-link tag="li" :to="{name:'account'}" active-class="active" v-if="auth.loggedIn">
            <a class="nav-link" v-tooltip:bottom="'Account'">
              <i class="fa fa-lg fa-fw fa-user" aria-label="Account"></i>
            </a>
          </router-link>
          <li>
            <a class="nav-link" href="" @click="logout" v-tooltip:bottom="'Log out'"><i class="fa fa-lg fa-fw fa-sign-out" aria-label="Logout"></i></a>
          </li>
          <li class="nav-link icon-beta"
              data-toggle="popover"
              data-placement="bottom"
              :data-title="`<b>Welcome to IoT-LAB's new web portal Beta</b>`"
              :data-content="`<h6 class='font-italic font-weight-light'>We want it fast and ergonomic, we hope you'll like it!</h6>
              <a href='/testbed'>Switch to legacy</a>`"
            >
            <i class="fa fa-lg fa-fw" aria-label="Beta"></i>
          </li>
        </ul>
      </div>
    </div>
    </nav>

    <notifications position="top right" :duration="3000" animation-type="velocity" style="margin: 10px;">
      <template slot="body" slot-scope="props" style="margin: 10px">
        <!-- <div :class="['alert', `alert-${props.item.type==='error'?'danger':props.item.type}`]" @click="props.close"> -->
        <div class="alert text-light border-0" :class="[`bg-${props.item.type==='error'?'danger':props.item.type}`]" @click="props.close">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="font-weight: normal; font-size: 1.3rem; text-shadow: none">
            <span aria-hidden="true">&times;</span>
          </button>
          <h6 class="alert-heading" v-if="props.item.title" v-html="props.item.title"></h6>
          <div v-html="props.item.text" style="line-height: 1.3"></div>
        </div>
      </template>
    </notifications>

    <notifications group="alt" position="top center" :duration="-1" animation-type="css" style="margin-top: 6px;">
      <template slot="body" slot-scope="props" style="margin-top: 10px">
        <div class="alert alert-secondary text-center">
          <h6 class="alert-heading" v-if="props.item.title" v-html="props.item.title"></h6>
          <div v-html="props.item.text" style="line-height: 1.3"></div>
        </div>
      </template>
    </notifications>
    <keep-alive include="NewExperiment,AdminUsers">
      <router-view></router-view>
    </keep-alive>
  </div>
</template>

<script>
import WordpressNavbar from '@/wp-menu/WordpressNavbar'
import { auth } from '@/auth'

export default {
  name: 'app',
  components: { WordpressNavbar },
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
.cursor:hover {
    cursor: pointer;
}
.v-select .dropdown-toggle {
    padding: 1px !important; /* adjust v-select to bootstrap-4 */
}
.scrollable {
  overflow-y: auto !important;
  /*overflow-x: hidden !important;*/
  /*margin-bottom: 20px;*/
}
.scrollable.h200 {
  max-height: 200px;
}
.scrollable.h300 {
  max-height: 300px;
}
.scrollable.h400 {
  max-height: 400px;
}
.scrollable.h500 {
  max-height: 500px;
}
.scrollable.h600 {
  max-height: 600px;
}
.icon-beta {
  position: relative;
}
.icon-beta::before, .icon-beta::after {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
}
.icon-beta::after {
  content: "β";
  color: var(--light);
  font-size: 1em;
}
.icon-beta::before {
  font-family: fontawesome;
  content: "\f0a3";
  color: var(--info);
  font-size: 2em;
  top: 1px;
}
.icon-beta:hover::before {
  color: var(--dark);
}
</style>
