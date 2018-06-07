<template>
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
        <span class="badge badge-pill badge-success">?</span> Running
        <span class="badge badge-pill badge-warning">?</span> Scheduled
        <span class="badge badge-pill badge-dark">?</span> Completed
      </p>
      <experiment-list title="Scheduled" user="@self" state="all_scheduled" @completed="updateTotal" :started="started"></experiment-list>
      <p>
        <router-link :to="{name:'experiment'}" class="btn btn-primary">New experiment</router-link>
      </p>
      <template v-if="total.terminated">
        <experiment-list title="Recent" user="@self" state="all_terminated" :show="5" :total="total.terminated" @started="refreshScheduled" @loaded="spinner = false"></experiment-list>
      </template>
      <template v-if="spinner">
        <i class="fa fa-spinner fa-spin fa-fw"></i>
        <i>loading experiments</i>
      </template>
      
    </div>
    <div class="col">
      <h2>Platform status</h2>
      <p v-if="sites">
        <span class="badge badge-pill" :class="{'badge-primary': currentSite === 'all', 'badge-secondary': currentSite !== 'all'}" @click="currentSite = 'all'" style="cursor: pointer">{{sites.length}} sites</span>
        <span v-for="site in sites" class="badge badge-pill" :class="{'badge-primary': currentSite === site, 'badge-secondary': currentSite !== site}" style="margin-right: 4px; cursor: pointer"
        @click="currentSite = site">{{site.site}}</span>
      </p>
      <p v-if="nodes">
        <span class="badge badge-pill badge-success">{{getNodesCount(currentSite, ['Alive'])}}</span> nodes available
        <span class="badge badge-pill badge-warning">{{getNodesCount(currentSite, ['Busy'])}}</span> busy
        <span class="badge badge-pill badge-danger">{{getNodesCount(currentSite, ['Absent','Suspected','Dead'])}}</span> unavailable
      </p>
      <p v-else>
        <i class="fa fa-spinner fa-spin fa-fw"></i>
      </p>
      <!-- <p>
        <a class="btn btn-light" href="" @click.prevent="this.alert('todo')">Check future availability</a>
      </p> -->
    </div>
  </div>

</div> <!-- container -->

</template>

<script>
import ExperimentList from '@/components/parts/ExperimentList'
import {iotlab} from '@/rest'
import {auth} from '@/auth'
// import { sleep } from '@/utils'

export default {
  name: 'Dashboard',

  components: {ExperimentList},

  data () {
    return {
      total: {},
      sites: [],
      nodes: [],
      experiments: [],
      currentSite: 'all',
      auth: auth,
      started: 0,
      spinner: true,
    }
  },

  created () {
    this.updateTotal()
    iotlab.getSites().then(data => { this.sites = data })
    iotlab.getNodes().then(data => { this.nodes = data })
  },

  async mounted () {
    this.$notify({group: 'popup', clean: true})
    let user = await iotlab.getUserInfo()
    if (!user.category) {
      this.$notify({group: 'popup', type: 'fill-user-profile'})
    }
  },

  methods: {
    async updateTotal () {
      // await sleep(2000)
      this.total = await iotlab.getUserExperimentsCount()
      // hide spinner as we are not going to fetch terminated experiments
      if (this.total.terminated === 0) this.spinner = false
    },
    refreshScheduled () {
      // increment started counter so that scheduled xp component can refresh itself
      this.started += 1
    },
    getNodesCount (site, stateList) {
      let siteList = (site === 'all') ? this.sites.map(key => key.site) : [site.site]
      return this.nodes.filter(node => siteList.includes(node.site)).filter(node => stateList.includes(node.state)).length
    },
  },

}
</script>
