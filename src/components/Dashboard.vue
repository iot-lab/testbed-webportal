<template>
<div>
<div class="alert alert-info rounded-0" v-if="auth.isAdmin && nbPendingUsers > 0" style="font-weight: 300">
  <router-link :to="{name:'users'}" tag="div" class="container cursor">
    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
    {{nbPendingUsers}} pending user accounts awaiting validation
  </router-link>
</div>
<div class="container mt-3">
  <div class="row">
    <div class="col-md-9">
      <h2>My experiments</h2>
      <p v-if="total.running != undefined">
        <span class="badge badge-pill badge-success">{{total.running}}</span> Running
        <span class="badge badge-pill badge-warning">{{total.upcoming}}</span> Scheduled
        <span class="badge badge-pill badge-dark">{{total.terminated}}</span> Completed
      </p>
      <p v-else>
        <i class="fa fa-spinner fa-spin fa-fw"></i>
      </p>
      <template v-if="total.running + total.upcoming">
        <h4 class="text-secondary">Scheduled</h4>
        <experiment-list user="@self" state="all_scheduled" :total="total.running + total.upcoming"></experiment-list>        
      </template>
      <p>
        <router-link :to="{name:'experiment'}" class="btn btn-primary">New experiment</router-link>
      </p>
      <template v-if="total.terminated">
        <h4 class="text-secondary">Recent</h4>
        <experiment-list user="@self" state="all_terminated" :total="total.terminated"></experiment-list>
      </template>
    </div>
    <div class="col">
      <h2>Platform status</h2>
      <p v-if="sites">
        <span class="badge badge-pill" :class="{'badge-primary': currentSite === 'all', 'badge-secondary': currentSite !== 'all'}" @click="currentSite = 'all'" style="cursor: pointer">{{sites.length}} sites</span>
        <span v-for="site in sites" class="badge badge-pill" :class="{'badge-primary': currentSite === site, 'badge-secondary': currentSite !== site}" style="margin-right: 4px; cursor: pointer"
        @click="currentSite = site">{{site.site}}</span>
      </p>
      <!-- <p v-if="stats.nodes">
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
        <a class="btn btn-light" href="" @click.prevent="this.alert('todo')">Check future availability</a>
      </p>
    </div>
  </div>

</div>

</div> <!-- container -->

</template>

<script>
import ExperimentList from '@/components/parts/ExperimentList'
import {iotlab} from '@/rest'
import {auth} from '@/auth'

export default {
  name: 'Dashboard',

  components: {ExperimentList},

  data () {
    return {
      total: {},
      // stats: {},
      resources: [],
      sites: [],
      experiments: [],
      currentSite: 'all',
      auth: auth,
      nbPendingUsers: 0,
    }
  },

  created () {
    iotlab.getUserExperimentsCount().then(data => { this.total = data })
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
