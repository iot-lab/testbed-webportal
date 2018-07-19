<template>
  <div class="container mt-3">
    <h2>
      Experiment <span class="text-muted">{{experiment.name}} <small>#{{id}}</small></span>
    </h2>
    <ul class="list-unstyled">
      <li>User <b>{{experiment.user}}</b></li>
      <!-- <li v-if="experiment.name">Name <b>{{experiment.name}}</b></li> -->
      <li>Submitted <b>{{experiment.submission_date | formatDateTimeSec}}</b></li>
      <li v-if="experiment.state === 'Waiting'">Scheduled <b>{{experiment.scheduled_date | formatDateTimeSec}}</b></li>
      <li v-if="experiment.state !== 'Waiting'">Started <b>{{experiment.start_date | formatDateTimeSec}}</b></li>
      <li v-if="experiment.stop_date !== '1970-01-01T00:00:00Z'">Stopped <b>{{experiment.stop_date | formatDateTimeSec}}</b></li>
      <li v-if="experiment.state !== 'Running'">Duration <b>{{(experiment.effective_duration || experiment.submitted_duration) | humanizeDuration}}</b></li>
      <li v-if="experiment.state === 'Running'">
        Duration <b>{{experiment.submitted_duration | humanizeDuration}}</b>
        (Elapsed {{experiment.effective_duration | humanizeDuration}} – Remaining {{experiment.submitted_duration - experiment.effective_duration | humanizeDuration}})
      </li>
      <li>Number of nodes <b>{{experiment.nb_nodes}}</b></li>
      <!-- <li>Type <b>{{experiment.type}}</b></li> -->
      <li>State <span class="badge" :class="experiment.state | stateBadgeClass">{{experiment.state}}</span></li>
    </ul>
    <template v-if="experiment.user === currentUser">
      <a href="" class="btn btn-sm btn-outline-danger mb-3" @click.prevent="stopExperiment(id)"
        v-if="states.stoppable.includes(experiment.state)">
        <template v-if="experiment.state === 'Running'">
          <i class="fa fa-stop-circle"></i> Stop
        </template>
        <template v-else>
          <i class="fa fa-ban"></i> Cancel
        </template>
      </a>
      <a href="" class="btn btn-sm btn-outline-secondary mb-3" @click.prevent="reloadExperiment(id)"
        v-if="states.completed.includes(experiment.state)">
        <i class="fa fa-refresh"></i> Restart
      </a>
      <!-- <a href="" class="btn btn-sm btn-outline-secondary mb-3" @click.prevent="this.alert('todo')">
        <i class="fa fa-clone"></i> Clone
      </a> -->
      <a href="" class="btn btn-sm btn-outline-secondary mb-3" @click.prevent="downloadExperiment(id)">
        <i class="fa fa-download"></i> Download
      </a>
    </template>

    <table class="table table-striped table-sm">
      <thead>
        <tr>
          <th width="15px" v-if="showNodesCommands">
            <input type="checkbox" @change="toggleSelectedNodes" v-model="allSelected">
          </th>
          <th>Nodes</th>
          <th>Firmware</th>
          <th>Monitoring</th>
          <!-- <th>Mobility</th> -->
          <th>Deployment</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="node in experiment.nodes" :class="{'text-danger': getDeploymentStatus(node) === 'Error'}">
          <td v-if="showNodesCommands">
            <input type="checkbox" :value="node" v-model="selectedNodes" :disabled="getDeploymentStatus(node) === 'Error'" @click="uncheckAll">
          </td>
          <td v-html="nodeOrAlias(node)"></td>
          <td>{{getFirmware(node)}}</td>
          <td>{{getMonitoring(node)}}</td>
          <!-- <td>{{getMobility(node)}}</td> -->
          <td>{{getDeploymentStatus(node)}}</td>
        </tr>
      </tbody>
    </table>

    <div v-if="showNodesCommands">
      <button class="btn btn-sm dropdown-toggle cursor" type="button" data-toggle="popover" v-show="selectedNodes.length === 0" data-placement="right"
        data-content="<i class='fa fa-fw fa-lg fa-exclamation-circle text-warning'></i> Select nodes first">
        <i class="fa fa-wrench text-dark"></i> Actions on selected nodes
      </button>
      <div class="dropdown" v-show="selectedNodes.length">
        <button class="btn btn-sm dropdown-toggle cursor" type="button" id="actionMenuBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" @click="showFirmware = false">
          <i class="fa fa-wrench text-dark"></i> Actions on {{selectedNodes.length}} nodes
        </button>
        <div class="dropdown-menu" aria-labelledby="actionMenuBtn">
          <a class="dropdown-item" href="#" @click.prevent="sendCmd('start')"><i class="fa fa-fw fa-play"></i> Start</a>
          <a class="dropdown-item" href="#" @click.prevent="sendCmd('stop')"><i class="fa fa-fw fa-power-off"></i> Stop</a>
          <a class="dropdown-item" href="#" @click.prevent="sendCmd('reset')"><i class="fa fa-fw fa-refresh"></i> Reset</a>
          <li class="dropdown-divider"></li>
          <!-- <a class="dropdown-item" href="#" @click.prevent="sendCmd('flash-idle')"><i class="fa fa-fw fa-eraser"></i> Flash idle firmware</a> -->
          <a class="dropdown-item" href="#" @click.prevent="showFirmware = true"><i class="fa fa-fw fa-microchip"></i> Flash firmware</a>
          <!-- <li class="dropdown-divider"></li> -->
          <!-- <a class="dropdown-item" href="#" @click.prevent="sendCmd('debug-start')"><i class="fa fa-fw fa-step-forward"></i> Start debug</a> -->
          <!-- <a class="dropdown-item" href="#" @click.prevent="sendCmd('debug-stop')"><i class="fa fa-fw fa-stop"></i> Stop debug</a> -->
          <li class="dropdown-divider"></li>
          <a class="dropdown-item" href="#" @click.prevent="this.alert('todo')"><i class="fa fa-fw fa-thermometer fa-strike" style="position: relative"></i> Remove monitoring</a>
          <a class="dropdown-item" href="#" @click.prevent="this.alert('todo')"><i class="fa fa-fw fa-thermometer"></i> Update monitoring</a>
        </div>
      </div>
    </div>

    <div class="card my-3" style="max-width: 400px;" v-show="showFirmware">
      <div class="card-body">
        <h5 class="card-title">Upload firmware</h5>
        <!-- <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6> -->
        <p class="card-text">
          <label class="custom-file">
            <input type="file" id="file" ref="firmwareFile" class="custom-file-input" @change="flashFirmware('firmwareFile')">
            <span class="custom-file-control">{{firmwareFile && firmwareFile.name}}</span>
          </label>
        </p>
        <a href="#" class="card-link" @click.prevent="showFirmware = false">Close</a>
      </div>
    </div>

  </div> <!-- container -->
</template>

<script>
import { iotlab } from '@/rest'
import { auth } from '@/auth'
import { experimentStates } from '@/assets/js/iotlab-utils'
import { capitalize } from '@/utils'
import axios from 'axios'
import $ from 'jquery'

var polling = true

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
      deploymentStatus: undefined,
      states: experimentStates,
      selectedNodes: [],
      allSelected: false,
      showFirmware: false,
      firmwareFile: undefined,
      firmware: undefined,
      currentUser: auth.username,
    }
  },

  computed: {
    deployedNodes () {
      return this.experiment.nodes.filter(node => this.getDeploymentStatus(node) === 'Success')
    },
    startDate () {
      return [this.experiment.start_date, this.experiment.scheduled_date, this.experiment.submission_date].find(date => date !== '1970-01-01T00:00:00Z')
    },
    showNodesCommands () {
      return this.experiment.state === 'Running' && this.experiment.user === this.currentUser
    },
  },

  async created () {
    await this.getExperiment()
    this.$nextTick(function () {
      $('[data-toggle="popover"]').popover({
        trigger: 'focus',
        html: true,
      })
    })
  },

  destroyed () {
    polling = clearTimeout(polling)
  },

  methods: {
    async getExperiment () {
      try {
        this.experiment = await iotlab.getExperiment(this.id)

        // poll for experiment update
        if (polling && ['Launching', 'Finishing'].includes(this.experiment.state)) {
          polling = setTimeout(this.getExperiment, 3000) // fast poll
        } else if (experimentStates.scheduled.includes(this.experiment.state)) {
          polling = setTimeout(this.getExperiment, 10000) // slow poll
        }

        if (!this.deploymentStatus && !['Waiting', 'toLaunch', 'Launching'].includes(this.experiment.state)) {
          this.deploymentStatus = await iotlab.getExperimentDeployment(this.id)
        }
      } catch (err) {
        this.$notify({text: err.message, type: 'error'})
      }
    },
    nodeOrAlias (node) {
      if (typeof node === 'string') return node
      // else it's an alias
      return `<span class="badge badge-secondary">${this.$options.filters.formatArchiRadio(node.properties.archi)} ${node.properties.mobile ? '(mobile)' : ''} @ ${node.properties.site}</span> x ${node.nbnodes} `
    },
    getFirmware (node) {
      if (!this.experiment.firmwareassociations) return
      return this.experiment.firmwareassociations.reduce((acc, asso) => (asso.nodes.some(n => n === (node.alias || node)) ? asso.firmwarename : acc), '')
    },
    getMobility (node) {
      if (!this.experiment.associations || !this.experiment.associations.mobility) return
      return this.experiment.associations.mobility.reduce((acc, asso) => (asso.nodes.some(n => n === (node.alias || node)) ? asso.mobilityname : acc), '')
    },
    getMonitoring (node) {
      if (!this.experiment.profileassociations) return
      return this.experiment.profileassociations.reduce((acc, asso) => (asso.nodes.some(n => n === (node.alias || node)) ? asso.profilename : acc), '')
    },
    getDeploymentStatus (node) {
      if (this.deploymentStatus === undefined) return ''
      if ((this.deploymentStatus['0'] || []).includes(node)) return 'Success'
      if ((this.deploymentStatus['1'] || []).includes(node)) return 'Error'
      return 'Unknown'
    },
    async stopExperiment (id) {
      if (!confirm('Cancel this experiment?')) return
      try {
        await iotlab.stopExperiment(id)
        this.$notify({text: `Experiment ${id} stopping`, type: 'success'})
      } catch (err) {
        this.$notify({text: err.message, type: 'error'})
      }
    },
    async reloadExperiment (id) {
      try {
        let newExp = await iotlab.reloadExperiment(id)
        this.$notify({text: `Experiment ${newExp.id} submitted`, type: 'success'})
        this.$router.push({name: 'dashboard'})
      } catch (err) {
        this.$notify({text: err.message, type: 'error'})
      }
    },
    downloadExperiment (id) {
      axios({
        method: 'get',
        url: `https://${process.env.VUE_APP_IOTLAB_HOST}/api/experiments/${id}/data`,
        responseType: 'arraybuffer',
        auth: JSON.parse(localStorage.getItem('apiAuth') || '{}'),
      })
      .then(function (response) {
        let blob = new Blob([response.data], { type: 'application/gzip' })
        let link = document.createElement('a')
        link.href = window.URL.createObjectURL(blob)
        link.download = `experiment_${id}.tar.gz`
        link.click()
      })
    },
    toggleSelectedNodes () {
      if (this.selectedNodes.length === this.deployedNodes.length) {
        this.selectedNodes = []
      } else {
        this.selectedNodes = this.deployedNodes
      }
    },
    uncheckAll () {
      this.$nextTick(function () {
        this.allSelected = this.selectedNodes.length === this.deployedNodes.length
      })
    },

    async sendCmd (cmd) {
      if (this.selectedNodes.length === 0) {
        this.$notify({text: `Select some nodes first`, type: 'warning'})
        return
      }
      let nodes = await iotlab.sendNodesCommand(this.id, cmd, this.selectedNodes).catch(err => {
        this.$notify({text: err.response.data.message, type: 'error'})
        return
      })
      let validNodes = Object.values(nodes).reduce((a, b) => a.concat(b))
      let invalidNodes = this.selectedNodes.filter(n => !validNodes.includes(n))

      if (invalidNodes.length > 0) {
        this.$notify({
          text: `${capitalize(cmd)} not supported on ${invalidNodes.length} nodes:<br><br>` + invalidNodes.join('<br>'),
          type: 'warning',
          duration: 6000,
        })
      }
      if (nodes['1'] && nodes['1'].length > 0) {
        this.$notify({
          text: `${capitalize(cmd)} failed on ${nodes['1'].length} nodes:<br><br>` + nodes['1'].join('<br>'),
          type: 'error',
          duration: 10000,
        })
      }
      if (nodes['0'] && nodes['0'].length > 0) {
        this.$notify({
          text: `${capitalize(cmd)} successfull on ${nodes['0'].length} nodes:<br><br>` + nodes['0'].join('<br>'),
          type: 'success',
          duration: 6000,
        })
      }
    },
    flashFirmware (ref) {
      if (this.selectedNodes.length === 0) {
        this.$notify({text: `Select some nodes first`, type: 'warning'})
        return
      }

      this.$notify({text: 'Uploading file...', type: 'info', duration: -1})

      this.firmwareFile = this.$refs[ref].files[0]
      var reader = new FileReader()

      reader.onload = (function (vm) {
        return async function (e) {
          vm.$notify({clean: true}) // close pending notification

          let res = await iotlab.checkFirmware(e.target.result)
          vm.$notify({text: `firmware format ${res.format}`, type: res.format === 'unknown' ? 'error' : 'info'})
          if (res.format === 'unknown') return

          vm.$notify({text: 'Flashing firmware...', type: 'info', duration: -1})
          vm.showFirmware = false

          let nodes = await iotlab.flashFirmware(vm.id, vm.selectedNodes, e.target.result).catch(err => {
            vm.$notify({clean: true}) // close pending notification
            vm.$notify({text: err.response.data.message, type: 'error'})
            return
          })
          let validNodes = Object.values(nodes).reduce((a, b) => a.concat(b))
          let invalidNodes = vm.selectedNodes.filter(n => !validNodes.includes(n))

          vm.$notify({clean: true}) // close pending notification

          if (invalidNodes.length > 0) {
            vm.$notify({
              text: `Flash not supported on ${invalidNodes.length} nodes:<br><br>` + invalidNodes.join('<br>'),
              type: 'warning',
              duration: 6000,
            })
          }
          if (nodes['1'] && nodes['1'].length > 0) {
            vm.$notify({
              text: `Flash failed on ${nodes['1'].length} nodes:<br><br>` + nodes['1'].join('<br>'),
              type: 'error',
              duration: 10000,
            })
          }
          if (nodes['0'] && nodes['0'].length > 0) {
            vm.$notify({
              text: `Flash successfull on ${nodes['0'].length} nodes:<br><br>` + nodes['0'].join('<br>'),
              type: 'success',
              duration: 6000,
            })
          }
        }
      })(this)

      reader.readAsDataURL(this.firmwareFile)
    },
  },
}
</script>

<style>
.fa-strike::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  border-bottom: 1.5px solid var(--danger);
  transform: translateY(-6px) translateX(-5px) rotate(-35deg);
}
</style>