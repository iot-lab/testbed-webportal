<template>
<div class="container mt-3">
  <div class="row">
    <div class="col-md-10">
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
      <experiment-list ref="runningExpList" title="Scheduled" user="@self" state="all_scheduled" @completed="updateTotal"></experiment-list>
      <p>
        <router-link :to="{name:'experiment'}" class="btn btn-primary">New experiment</router-link>
      </p>
      <template v-if="total.terminated">
        <experiment-list title="Recent" user="@self" state="all_terminated" :show="5" :total="total.terminated" @started="refreshRunning" @loaded="spinner = false"></experiment-list>
      </template>
      <template v-if="spinner">
        <i class="fa fa-spinner fa-spin fa-fw mr-1"></i>
        <i>loading experiments</i>
      </template>
      
    </div>
  </div>
</div> <!-- container -->

</template>

<script>
import ExperimentList from '@/components/ExperimentList'
import { iotlab } from '@/rest'
// import { sleep } from '@/utils'

export default {
  name: 'Dashboard',

  components: {ExperimentList},

  data () {
    return {
      total: {},
      experiments: [],
      started: 0,
      spinner: true,
      created: false,
    }
  },

  beforeRouteEnter (to, from, next) {
    // refresh data when reentering the dashboard
    next(vm => {
      if (!vm.created) return // do not refresh until initial render as be done in created()
      vm.$notify({group: 'popup', clean: true})
      vm.$refs.runningExpList.refresh()
      vm.$refs.runningExpList.createPolling()
      vm.updateTotal()
    })
  },

  beforeRouteLeave (to, from, next) {
    // Indicate to the SubComponent to stop experiments polling
    this.$refs.runningExpList.destroyPolling()
    // Make sure to always call the next function, otherwise the hook will never be resolved
    // Ref: https://router.vuejs.org/en/advanced/navigation-guards.html
    next()
  },

  created () {
    this.updateTotal()
    this.created = true
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
    refreshRunning () {
      this.$refs.runningExpList.refresh()
    },
  },

}
</script>
