<template>
  <div class="container mt-3">
    <div class="row">
      <div class="col-md-9">
        <h2>
          All experiments
          <span v-if="username">for user <span class="text-muted">{{username}}</span></span>
        </h2>
        <h4 class="text-secondary">In progress</h4>
        <experiment-list :user="username" state="Running,Finishing,Resuming,toError,Waiting,Launching,Hold,toLaunch,toAckReservation,Suspended"></experiment-list>
        <h4 class="text-secondary">History</h4>
        <experiment-list :user="username" state="Terminated,Error"></experiment-list>
      </div>
      <div class="col"></div>
    </div>

  </div> <!-- container -->
</template>

<script>
// import {iotlab} from '@/rest'
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
      experiments: undefined,
    }
  },

  methods: {},
}
</script>

<style>

</style>
