<template>
  <div class="container mt-3">
    <h2>
      Experiment <span class="text-muted">{{experiment.name}} <small>#{{id}}</small></span>
    </h2>
    <ul class="list-unstyled">
      <li>User <b>{{experiment.user}}</b></li>
      <li>Name <b>{{experiment.name}}</b></li>
      <li>Duration <b>{{experiment.duration | humanizeDuration}}</b></li>
      <li>Number of nodes <b>{{experiment.nodes.length}}</b></li>
      <li>Type <b>{{experiment.type}}</b></li>
      <li>State <span class="badge" :class="experiment.state | stateBadgeClass">{{experiment.state}}</span></li>
    </ul>
    <a href="" class="btn btn-sm btn-outline-danger mb-3" @click.prevent="delExperiment(id)"
      v-if="states.scheduled.includes(experiment.state)">
      <i class="fa fa-ban"></i> Stop
    </a>
    <a href="" class="btn btn-sm btn-outline-secondary mb-3" @click.prevent="reloadExperiment(id)">
      <i class="fa fa-refresh"></i> Restart
    </a>
    <a href="" class="btn btn-sm btn-outline-secondary mb-3" @click.prevent="this.alert('todo')">
      <i class="fa fa-clone"></i> Clone
    </a>

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
import { iotlab } from '@/rest'
import { experimentStates } from '@/assets/js/iotlab-utils'

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
      states: experimentStates,
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
    async delExperiment (id) {
      try {
        await iotlab.stopExperiment(id)
        this.$notify({text: `Experiment ${id} stopped`, type: 'success'})
        this.experiment.state = 'Finishing'
      } catch (err) {
        this.$notify({text: err.message, type: 'error'})
      }
    },
    async reloadExperiment (id) {
      try {
        let newExp = await iotlab.reloadExperiment(id)
        this.$notify({text: `Experiment ${newExp.id} submitted`, type: 'success'})
      } catch (err) {
        this.$notify({text: err.message, type: 'error'})
      }
    },

  },
}
</script>

<style>

</style>
