<template>
  <div class="container mt-3">
    <div class="row">
      <div class="col-md-10">
        <h2>
          All experiments
          <span v-if="username">for user <span class="text-muted">{{username}}</span></span>
        </h2>
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
        <experiment-list title="Scheduled" :user="username" state="all_scheduled" @completed="updateTotal" :started="started"></experiment-list>
        <template v-if="total.terminated">
          <experiment-list title="Recent" :user="username" state="all_terminated" :show="20" :total="total.terminated" :step="100" @started="refreshScheduled" @loaded="spinner = false"></experiment-list>
        </template>
        <template v-if="spinner">
          <i class="fa fa-spinner fa-spin fa-fw mr-1"></i>
          <i>loading experiments</i>
        </template>
    </div>
      <div class="col"></div>
    </div>

  </div> <!-- container -->
</template>

<script>
import {iotlab} from '@/rest'
import ExperimentList from '@/components/ExperimentList'

export default {
  name: 'AdminExperiments',

  components: {ExperimentList},

  props: {
    username: {
      type: String,
      default: () => '',
    },
  },

  data () {
    return {
      total: {},
      started: 0,
      spinner: true,
    }
  },

  created () {
    this.updateTotal()
  },

  methods: {
    async updateTotal () {
      this.total = await iotlab.getAllExperimentsCount(this.username)
      // hide spinner as we are not going to fetch terminated experiments
      if (this.total.terminated === 0) this.spinner = false
    },
    refreshScheduled () {
      // increment started counter so that scheduled xp component can refresh itself
      this.started += 1
    },
  },
}
</script>
