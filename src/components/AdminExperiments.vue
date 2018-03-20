<template>
  <div class="container mt-3">
    <div class="row">
      <div class="col-md-10">
        <h2>
          All experiments
          <span v-if="username">for user <span class="text-muted">{{username}}</span></span>
        </h2>
        <template v-if="total != undefined">
          <div class="d-flex align-items-baseline">
            <h4 class="text-secondary mr-3">Scheduled</h4>
            <div>
              <span class="badge badge-pill badge-success">{{total.running}}</span> Running
              <span class="badge badge-pill badge-warning">{{total.upcoming}}</span> Scheduled
            </div>
          </div>
          <experiment-list :user="username" state="all_scheduled" @completed="updateTotal" :started="started"></experiment-list>
          <div class="d-flex align-items-baseline">
            <h4 class="text-secondary mr-3">Recent</h4>
            <div>
              <span class="badge badge-pill badge-dark">{{total.terminated}}</span> Completed
            </div>
          </div>
          <experiment-list :user="username" state="all_terminated" :show="40" :total="total.terminated" :step="100" @started="refreshScheduled"></experiment-list>
        </template>
        <p v-else>
          <i class="fa fa-spinner fa-spin fa-fw"></i>
        </p>
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
      total: undefined,
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
