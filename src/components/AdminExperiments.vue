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
          <i class="fa fa-spinner fa-spin fa-fw"></i>
        </p>
        <experiment-list title="Scheduled" :user="username" state="all_scheduled" @completed="updateTotal" :started="started"></experiment-list>
        <template v-if="total.terminated">
          <experiment-list title="Recent" :user="username" state="all_terminated" :show="20" :total="total.terminated" :step="100" @started="refreshScheduled"></experiment-list>
        </template>
      </div>
      <div class="col"></div>
    </div>

  </div> <!-- container -->
</template>

<script>
import {iotlab} from '@/rest'
import ExperimentList from '@/components/parts/ExperimentList'

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
    }
  },

  created () {
    this.updateTotal()
  },

  methods: {
    updateTotal () {
      iotlab.getAllExperimentsCount(this.username).then(data => { this.total = data })
    },
    refreshScheduled () {
      // increment started counter so that scheduled xp component can refresh itself
      this.started += 1
    },
  },
}
</script>

<style>

</style>
