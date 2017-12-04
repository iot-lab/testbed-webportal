<template>
  <div class="container mt-3">
    <h2>
      Experiment <span class="text-muted">{{experiment.name}} <small>#{{id}}</small></span>
    </h2>
    <ul class="list-unstyled">
      <li>Owner <b>{{experiment.owner}}</b></li>
      <li>Name <b>{{experiment.name}}</b></li>
      <li>Duration <b>{{experiment.duration * 60 | humanizeDuration}}</b></li>
      <li>Number of nodes <b>{{experiment.nodes.length}}</b></li>
      <li>State <span class="badge" :class="experiment.state | stateBadgeClass">{{experiment.state}}</span></li>
      <li>Type <b>{{experiment.type}}</b></li>
    </ul>
    <table class="table table-striped table-sm">
      <thead>
        <tr>
          <th>Nodes</th>
          <th>Firmware</th>
          <th>Monitoring</th>
          <th>Mobility</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="node in experiment.nodes">
          <td>{{node}}</td>
          <td>{{getFirmware(node)}}</td>
          <td>{{getMonitoring(node)}}</td>
          <td>{{getMobility(node)}}</td>
        </tr>
      </tbody>
    </table>
  </div> <!-- container -->
</template>

<script>
import {iotlab} from '@/rest'

export default {
  name: 'ExperimentDetails',

  props: {
    id: {
      type: Number,
    },
  },

  data () {
    return {
      experiment: undefined,
    }
  },

  async created () {
    try {
      this.experiment = await iotlab.getExperiment(this.id)
    } catch (err) {
      this.$notify({text: err.message, type: 'error'})
    }
  },

  methods: {
    getFirmware (node) {
      if (!this.experiment.firmwareassociations) return
      return this.experiment.firmwareassociations.reduce((acc, asso) => (asso.nodes.includes(node) ? asso.firmwarename : acc), '')
    },
    getMobility (node) {
      if (!this.experiment.associations || !this.experiment.associations.mobility) return
      return this.experiment.associations.mobility.reduce((acc, asso) => (asso.nodes.includes(node) ? asso.mobilityname : acc), '')
    },
    getMonitoring (node) {
      return '?'
    },
  },
}
</script>

<style>

</style>
