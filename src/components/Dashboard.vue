<template>
<div>
<div class="alert alert-info rounded-0 p-2" v-if="auth.isAdmin && nbPendingUsers > 0" style="font-weight: 300">
    <router-link to="users" tag="div" class="container cursor">
        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
        {{nbPendingUsers}} pending user accounts awaiting validation
    </router-link>
</div>
<div class="container mt-3">
    
    <!-- <a href="" class="btn btn-outline-info" v-if="auth.isAdmin && nbPendingUsers > 0">
        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
        8 pending users
    </a> -->
    <h2>Platform status</h2>
    <p v-if="sites">
        <span class="badge badge-pill" :class="{'badge-primary': currentSite === 'all', 'badge-secondary': currentSite !== 'all'}" @click="currentSite = 'all'" style="cursor: pointer">{{sites.length}} sites</span>
        <span v-for="site in sites" class="badge badge-pill" :class="{'badge-primary': currentSite === site, 'badge-secondary': currentSite !== site}" style="margin-right: 4px; cursor: pointer"
        @click="currentSite = site">{{site.site}}</span>
    </p>
<!--     <p v-if="stats.nodes">
        <span class="badge badge-pill badge-success">{{stats.nodes.Alive}}</span> nodes available
        <span class="badge badge-pill badge-warning">{{stats.nodes.Busy}}</span> busy
        <span class="badge badge-pill badge-danger">{{stats.nodes.Unavailable}}</span> unavailable
    </p> -->
    <p v-if="resources">
        <span class="badge badge-pill badge-success">{{getNodesCount(currentSite, ['Alive'])}}</span> nodes available
        <span class="badge badge-pill badge-warning">{{getNodesCount(currentSite, ['Busy'])}}</span> busy
        <span class="badge badge-pill badge-danger">{{getNodesCount(currentSite, ['Absent','Suspected'])}}</span> unavailable
    </p>
    <p v-else>
        <i class="fa fa-spinner fa-spin fa-fw"></i>
    </p>
    <p>
        <a class="btn btn-light" href="">Check future availability</a>
    </p>

    <h2>My experiments</h2>
    <p v-if="total.running != undefined">
        <span class="badge badge-pill badge-success">{{total.running}}</span> running
        <span class="badge badge-pill badge-warning">{{total.upcoming}}</span> upcoming
        <span class="badge badge-pill badge-danger">{{total.terminated}}</span> terminated
    </p>
    <p v-else>
        <i class="fa fa-spinner fa-spin fa-fw"></i>
    </p>
    <p>
        <a href="" class="btn btn-primary">New experiment</a>
    </p>
</div>

</div> <!-- container -->

</template>

<script>
import {iotlab} from '@/rest'
import {auth} from '@/auth'

export default {
  name: 'Dashboard',

  data () {
    return {
      total: {},
      // stats: {},
      resources: [],
      currentSite: 'all',
      auth: auth,
      nbPendingUsers: 0,
    }
  },

  created () {
    iotlab.getUserExperiments().then(data => { this.total = data })
    // iotlab.getStats().then(data => { this.stats = data })
    iotlab.getSites().then(data => { this.sites = data })
    iotlab.getSiteResources().then(data => { this.resources = data })
    if (auth.isAdmin) {
      iotlab.getUsers().then(data => { this.nbPendingUsers = data.length })
    }
  },

  methods: {
    getNodesCount (site, stateList) {
      let siteList = (site === 'all') ? this.sites.map(key => key.site) : [site.site]
      return this.resources.filter(node => siteList.includes(node.site)).filter(node => stateList.includes(node.state)).length
    },
  },

}
</script>
