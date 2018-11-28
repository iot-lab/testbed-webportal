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
      <a href="" class="btn btn-sm btn-outline-danger mb-3 mr-1" @click.prevent="stopExperiment(id)"
        v-if="states.stoppable.includes(experiment.state)">
        <template v-if="experiment.state === 'Running'">
          <i class="fa fa-stop-circle"></i> Stop
        </template>
        <template v-else>
          <i class="fa fa-ban"></i> Cancel
        </template>
      </a>
      <a href="" class="btn btn-sm btn-outline-secondary mb-3 mr-1" @click.prevent="reloadExperiment(id)"
        v-if="states.completed.includes(experiment.state)">
        <i class="fa fa-refresh"></i> Restart
      </a>
      <!-- <a href="" class="btn btn-sm btn-outline-secondary mb-3" @click.prevent="this.alert('todo')">
        <i class="fa fa-clone"></i> Clone
      </a> -->
      <a href="" class="btn btn-sm btn-outline-secondary mb-3" @click.prevent="downloadExperiment(id)">
        <i class="fa fa-download"></i> Download
      </a>
      <div v-if="showNodesCommands" class="float-right">
        <button class="btn btn-sm dropdown-toggle cursor" type="button" data-toggle="popover" v-show="selectedNodes.length === 0" data-placement="right"
          data-content="<i class='fa fa-fw fa-lg fa-exclamation-circle text-warning'></i> Select nodes first">
          <i class="fa fa-wrench text-dark"></i> Actions on selected nodes
        </button>
        <div class="dropdown" v-show="selectedNodes.length">
          <button class="btn btn-sm dropdown-toggle cursor" type="button" id="actionMenuBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-wrench text-dark"></i> Actions on {{selectedNodes.length | pluralize('node')}}
          </button>
          <div class="dropdown-menu" aria-labelledby="actionMenuBtn">
            <a class="dropdown-item" href="#" @click.prevent="sendCmd('start')"><i class="fa fa-fw fa-play"></i> Start</a>
            <a class="dropdown-item" href="#" @click.prevent="sendCmd('stop')"><i class="fa fa-fw fa-power-off"></i> Stop</a>
            <a class="dropdown-item" href="#" @click.prevent="sendCmd('reset')"><i class="fa fa-fw fa-refresh"></i> Reset</a>
            <li class="dropdown-divider"></li>
            <!-- <a class="dropdown-item" href="#" @click.prevent="sendCmd('flash-idle')"><i class="fa fa-fw fa-eraser"></i> Flash idle firmware</a> -->
            <a class="dropdown-item" href="#" data-toggle="modal" data-target=".firmware-modal"><i class="fa fa-fw fa-microchip"></i> Flash firmware</a>
            <!-- <a class="dropdown-item disabled" href="#" v-tooltip:bottom="'Waiting for current flash to finish'" xv-else><i class="fa fa-fw fa-microchip"></i> Flash firmware</a> -->
            <!-- <li class="dropdown-divider"></li> -->
            <!-- <a class="dropdown-item" href="#" @click.prevent="sendCmd('debug-start')"><i class="fa fa-fw fa-step-forward"></i> Start debug</a> -->
            <!-- <a class="dropdown-item" href="#" @click.prevent="sendCmd('debug-stop')"><i class="fa fa-fw fa-stop"></i> Stop debug</a> -->
            <li class="dropdown-divider"></li>
            <a class="dropdown-item" href="#" @click.prevent="this.alert('todo')"><i class="fa fa-fw fa-thermometer fa-strike" style="position: relative"></i> Remove monitoring</a>
            <a class="dropdown-item" href="#" @click.prevent="this.alert('todo')"><i class="fa fa-fw fa-thermometer"></i> Update monitoring</a>
          </div>
        </div>
      </div>
    </template>

    <table class="table table-striped table-sm">
      <thead>
        <tr>
          <th>Nodes</th>
          <th>UID</th>
          <th>Firmware</th>
          <th>Monitoring</th>
          <th class="text-center">Deployment</th>
          <th width="50px" v-if="showNodesCommands" class="text-center">Actions</th>
          <th width="15px" v-if="showNodesCommands">
            <input type="checkbox" @change="toggleSelectedNodes" v-model="allSelected">
          </th>
        </tr>
      </thead>
      <tbody>
        <template v-for="node in experiment.nodes">
          <tr :class="{'text-danger': getDeploymentStatus(node) === 'Error'}">
            <td v-html="nodeOrAlias(node)"></td>
            <td>{{getUid(node)}}</td>
            <td>{{getFirmware(node)}}</td>
            <td>{{getMonitoring(node)}}</td>
            <td class="text-center">{{getDeploymentStatus(node)}}</td>
            <td v-if="showNodesCommands">
              <div class="btn-group" role="group" aria-label="Node actions" >
                <button class="btn btn-sm border-0 btn-outline-dark" v-tooltip="'Start'" :disabled="getDeploymentStatus(node) === 'Error'" @click="sendCmdToNode('start', node)">
                  <i class="fa fa-fw fa-play"></i>
                </button>
                <button class="btn btn-sm border-0 btn-outline-dark" v-tooltip="'Stop'" :disabled="getDeploymentStatus(node) === 'Error'" @click="sendCmdToNode('stop', node)">
                  <i class="fa fa-fw fa-power-off"></i>
                </button>
                <button class="btn btn-sm border-0 btn-outline-dark" v-tooltip="'Reset'" :disabled="getDeploymentStatus(node) === 'Error'" @click="sendCmdToNode('reset', node)">
                  <i class="fa fa-fw fa-refresh"></i>
                </button>
                <button class="btn btn-sm border-0 btn-outline-dark" v-tooltip="'Flash firmware'" data-toggle="modal" data-target=".firmware-modal" :disabled="getDeploymentStatus(node) === 'Error'" @click="currentNode = node">
                  <i class="fa fa-fw fa-microchip"></i>
                </button>
                <button class="btn btn-sm border-0 btn-outline-dark" data-toggle="button" aria-pressed="false" v-tooltip="'Open Terminal'" @click="toggleTerminal(node)">
                  <i class="fa fa-fw fa-terminal"></i>
                </button>
                <button v-show="hasCamera(node)" class="btn btn-sm border-0 btn-outline-dark" data-toggle="button" aria-pressed="false" v-tooltip="'Video'" :disabled="getDeploymentStatus(node) === 'Error'" @click="toggleCamera(node)">
                  <i class="fa fa-fw fa-video-camera"></i>
                </button>
              </div>
            </td>
            <td v-if="showNodesCommands">
              <input type="checkbox" :value="node" v-model="selectedNodes" :disabled="getDeploymentStatus(node) === 'Error'" @click="uncheckAll">
            </td>
          </tr>
          <tr>
            <td colspan="7" class="p-0">
              <div class="d-flex align-items-center justify-content-between bg-secondary">
                <terminal :ref="`term_${node}`" :cols="80" :rows="20" :node="node" :expId="id" :token="token" style="flex-grow: 1"></terminal>
                <img class="camera" v-if="hasCamera(node)" v-show="(token !== undefined) && cameraVisible(node)" :src="cameraUrl(node)" align="right">
              </div>
            </td>
          </tr>
        </template>
      </tbody>
    </table>

    <div class="modal fade firmware-modal" tabindex="-1" role="dialog" aria-labelledby="firmwareModal" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-light">
            <h5 class="modal-title">Upload firmware</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body py-4">
            <label class="custom-file">
              <input type="file" id="file" ref="firmwareFile" class="custom-file-input" @change="changeFirmwareFile('firmwareFile')">
              <span class="custom-file-control">{{firmwareFile && firmwareFile.name}}</span>
            </label>
          </div>
          <div class="modal-footer dborder-0 dbg-light">
            <button type="button" class="btn" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" @click.prevent="flashFirmware('firmwareFile')">Flash</button>
          </div>
        </div>
      </div>
    </div>

  </div> <!-- container -->
</template>

<script>
import Terminal from '@/components/Terminal'
import { iotlab } from '@/rest'
import { auth } from '@/auth'
import { experimentStates } from '@/assets/js/iotlab-utils'
import { capitalize, pluralize, downloadAsFile } from '@/utils'
import $ from 'jquery'

var polling = true

export default {
  name: 'ExperimentDetails',

  components: {
    Terminal,
  },

  props: {
    id: {
      type: [Number, String],
    },
  },

  data () {
    return {
      experiment: {name: undefined},
      deploymentStatus: undefined,
      states: experimentStates,
      selectedNodes: [],
      nodes: [],
      nodesCameraState: {},
      token: undefined,
      allSelected: false,
      firmwareFile: undefined,
      firmware: undefined,
      currentUser: auth.username,
      currentNode: undefined,
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
    await this.getExperiment(this.id)
    this.$nextTick(function () {
      $('[data-toggle="popover"]').popover({
        trigger: 'focus',
        html: true,
      })
    })
  },

  beforeRouteUpdate (to, from, next) {
    // called when the route that renders this component has changed,
    // but this component is reused in the new route.
    // For example, for a route with dynamic params `/foo/:id`, when we
    // navigate between `/foo/1` and `/foo/2`, the same `Foo` component instance
    // will be reused, and this hook will be called when that happens.
    this.getExperiment(to.params.id)
  },

  destroyed () {
    polling = clearTimeout(polling)
  },

  methods: {
    async getExperiment (id = this.id) {
      try {
        this.experiment = await iotlab.getExperiment(id)

        if (this.nodes.length === 0 && this.experiment.type !== 'alias') {
          this.nodes = await iotlab.getExperimentNodes(id)
        }

        // poll for experiment update
        if (polling && ['Launching', 'Finishing'].includes(this.experiment.state)) {
          polling = setTimeout(this.getExperiment, 3000) // fast poll
        } else if (experimentStates.scheduled.includes(this.experiment.state)) {
          polling = setTimeout(this.getExperiment, 10000) // slow poll
        }

        if (!this.deploymentStatus && !['Waiting', 'toLaunch', 'Launching'].includes(this.experiment.state)) {
          this.deploymentStatus = await iotlab.getExperimentDeployment(id)
        }

        if (!this.token && this.experiment.state === 'Running') {
          this.token = await iotlab.getExperimentToken(id)
        }
        if (['Finishing', 'Terminated', 'Stopped'].includes(this.experiment.state)) {
          this.token = undefined
        }
      } catch (err) {
        this.$notify({ text: err.message, type: 'error' })
      }
    },
    nodeOrAlias (node) {
      if (typeof node === 'string') return node
      // else it's an alias
      return `<span class="badge badge-info">${this.$options.filters.formatArchiRadio(node.properties.archi)} ${node.properties.mobile ? '(mobile)' : ''} @ ${node.properties.site}</span> x ${node.nbnodes} `
    },
    getUid (node) {
      let n = this.nodes.find((n) => n.network_address === node)
      return n ? n.uid : ''
    },
    getFirmware (node) {
      if (!this.experiment.firmwareassociations) return
      return this.experiment.firmwareassociations.reduce((acc, asso) => (asso.nodes.some(n => n === (node.alias || node)) ? asso.firmwarename : acc), '')
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
        this.$notify({ text: `Experiment ${id} stopping`, type: 'success' })
      } catch (err) {
        this.$notify({ text: err.message, type: 'error' })
      }
    },
    async reloadExperiment (id) {
      try {
        let newExp = await iotlab.reloadExperiment(id)
        this.$notify({ text: `Experiment ${newExp.id} submitted`, type: 'success' })
        this.$router.push({ name: 'dashboard' })
      } catch (err) {
        this.$notify({ text: err.message, type: 'error' })
      }
    },
    async downloadExperiment (id) {
      try {
        downloadAsFile(`experiment_${id}.tar.gz`, await iotlab.getExperimentArchive(id), 'application/gzip')
      } catch (err) {
        this.$notify({text: err.response.data.message || 'Failed to download archive', type: 'error'})
      }
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

    async sendCmdToNode (cmd, node) {
      let nodes = [node]
      await this.sendCmd(cmd, nodes)
    },

    async sendCmd (cmd, nodesList = this.selectedNodes) {
      if (nodesList.length === 0) {
        this.$notify({ text: `Select some nodes first`, type: 'warning' })
        return
      }

      let nodes = await iotlab.sendNodesCommand(this.id, cmd, nodesList).catch(err => {
        this.$notify({ text: err.response.data.message, type: 'error' })
      })
      let validNodes = Object.values(nodes).reduce((a, b) => a.concat(b))
      let invalidNodes = nodesList.filter(n => !validNodes.includes(n))

      if (invalidNodes.length > 0) {
        this.$notify({
          text: `${capitalize(cmd)} not supported on ${pluralize(invalidNodes.length, 'node')}:<br><br>` + invalidNodes.join('<br>'),
          type: 'warning',
          duration: 6000,
        })
      }
      if (nodes['1'] && nodes['1'].length > 0) {
        this.$notify({
          text: `${capitalize(cmd)} failed on ${pluralize(nodes['1'].length, 'node')}:<br><br>` + nodes['1'].join('<br>'),
          type: 'error',
          duration: 10000,
        })
      }
      if (nodes['0'] && nodes['0'].length > 0) {
        this.$notify({
          text: `${capitalize(cmd)} successfull on ${pluralize(nodes['0'].length, 'node')}:<br><br>` + nodes['0'].join('<br>'),
          type: 'success',
          duration: 6000,
        })
      }
    },

    changeFirmwareFile (ref) {
      this.firmwareFile = this.$refs[ref].files[0]
    },

    flashFirmware (ref) {
      if (this.currentNode !== undefined) {
        this.flashFirmwareToCurrentNode(ref)
        this.currentNode = undefined
      } else {
        this.flashFirmwareToNodes(ref, this.selectedNodes)
      }
    },

    flashFirmwareToCurrentNode (ref) {
      let nodes = [this.currentNode]
      this.flashFirmwareToNodes(ref, nodes)
    },

    flashFirmwareToNodes (ref, nodes) {
      if (nodes.length === 0) {
        this.$notify({ text: `Select some nodes first`, type: 'warning' })
        return
      }

      if (this.firmwareFile === undefined) return

      this.$notify({ text: 'Uploading file...', type: 'info', duration: -1 })

      var reader = new FileReader()

      reader.onload = (function (vm, selectedNodes) {
        return async function (e) {
          vm.$notify({ clean: true }) // close pending notification

          let res = await iotlab.checkFirmware(e.target.result)
          vm.$notify({ text: `firmware format ${res.format}`, type: res.format === 'unknown' ? 'error' : 'info' })
          if (res.format === 'unknown') return

          vm.$notify({ text: 'Flashing firmware...', type: 'info', duration: -1 })
          $('.modal').modal('hide')

          let nodes = await iotlab.flashFirmware(vm.id, selectedNodes, e.target.result).catch(err => {
            vm.$notify({ clean: true }) // close pending notification
            vm.$notify({ text: err.response.data.message, type: 'error' })
          })
          let validNodes = Object.values(nodes).reduce((a, b) => a.concat(b))
          let invalidNodes = selectedNodes.filter(n => !validNodes.includes(n))

          vm.$notify({ clean: true }) // close pending notification

          if (invalidNodes.length > 0) {
            vm.$notify({
              text: `Flash not supported on ${pluralize(invalidNodes.length, 'node')}:<br><br>` + invalidNodes.join('<br>'),
              type: 'warning',
              duration: 6000,
            })
          }
          if (nodes['1'] && nodes['1'].length > 0) {
            vm.$notify({
              text: `Flash failed on ${pluralize(nodes['1'].length, 'node')}:<br><br>` + nodes['1'].join('<br>'),
              type: 'error',
              duration: 10000,
            })
          }
          if (nodes['0'] && nodes['0'].length > 0) {
            vm.$notify({
              text: `Flash successfull on ${pluralize(nodes['0'].length, 'node')}:<br><br>` + nodes['0'].join('<br>'),
              type: 'success',
              duration: 6000,
            })
          }
        }
      })(this, nodes)

      reader.readAsDataURL(this.firmwareFile)
    },

    hasCamera (node) {
      let n = this.nodes.find(n => n.network_address === node)
      return n ? n.camera === '1' : false
    },

    toggleCamera (node) {
      this.nodesCameraState[node] = !this.nodesCameraState[node]
      this.$forceUpdate()
      this.$nextTick(() => {
        let term = this.$refs[`term_${node}`][0]
        term.fit()
      })
    },

    cameraVisible (node) {
      return this.nodesCameraState[node] === true
    },

    cameraUrl (hostname) {
      if (this.token !== undefined && this.hasCamera(hostname) && this.cameraVisible(hostname)) {
        let [node, site] = hostname.split('.')
        let apiHost = process.env.VUE_APP_IOTLAB_HOST
        let url = `https://${apiHost}/camera/${site}/${this.id}/${node}/${this.token}`
        return url
      }
    },

    toggleTerminal (node) {
      let term = this.$refs[`term_${node}`][0]
      if (term.attached) {
        // hide terminal
        term.disconnect()
        term.detach()
      } else {
        // show terminal
        term.attach()
        term.focus()
        term.connect()
      }
    },
  },
}
</script>

<style scoped>
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
.table-striped tbody tr:nth-of-type(4n+1),
.table-striped tbody tr:nth-of-type(4n+2) {
  background-color: rgba(0, 0, 0, 0.05);
}
.table-striped tbody tr:nth-of-type(4n+3),
.table-striped tbody tr:nth-of-type(4n) {
  background-color: rgba(0, 0, 0, 0);
}
.v-resizable {
  resize: vertical;
  overflow-y: scroll;
}
.camera {
  height: 240px;
  width: 320px;
}
</style>
