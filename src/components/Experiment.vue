<template>
<div class="container my-3">
  <h2 class="mb-4">New experiment</h2>

  <div id="accordion" role="tablist">
    <div class="card">
      <div class="card-header pl-3" role="tab" id="headingZero">
        <a data-toggle="collapse" href="#collapseZero" aria-expanded="true" aria-controls="collapseZero" class="text-dark">
          <h6 class="mb-0"><i class="fa fa-fw fa-clock-o" aria-hidden="true"></i> Schedule
            <!-- <span class="badge badge-secondary float-right" v-html="(start == 'asap') ? 'asap' : startDate"></span> -->
            <!-- <span class="badge badge-secondary float-right mr-1">{{scheduleText}}</span> -->
            <!-- <span class="badge badge-secondary float-right mr-1">{{duration}} {{ durationMultiplier == 1 ? 'min' : 'hours'}}</span> -->
            <span class="text-muted font-weight-normal font-size-sm float-right">
              <i class="fa fa-clock-o" aria-hidden="true"></i> {{duration}} {{ durationMultiplier == 1 ? 'min' : 'hours'}}, {{scheduleText}}
            </span>
          </h6>
        </a>
      </div>

      <div id="collapseZero" class="collapse show" role="tabpanel" aria-labelledby="headingZero" data-parent="#accordion">
        <div class="card-body">
          <div class="form-group">
            <label class="lead mr-3 my-label">Name</label>
            <input type="text" class="form-control" style="max-width: 300px; display: inline-block" placeholder="New experiment" v-focus>
          </div>
          <div class="form-group">
            <label class="lead mr-3 my-label">Duration</label>
            <div class="input-group" style="display: inline-flex; max-width: 300px;">
              <input type="number" class="form-control" min="1" v-model="duration">
              <div class="input-group-btn">
                <button type="button" class="btn" :class="{'btn-secondary': durationMultiplier == 1}" @click="durationMultiplier = 1"> minutes </button>
                <button type="button" class="btn" :class="{'btn-secondary': durationMultiplier == 60}" @click="durationMultiplier = 60"> hours </button>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="lead mr-3 my-label">Start</label>
            <label class="custom-control custom-radio">
              <input id="radioStacked1" name="radio-stacked" type="radio" class="custom-control-input" v-model="start" value="asap" @click="startAsap">
              <span class="custom-control-indicator"></span>
              <span class="custom-control-description">As soon as possible</span>
            </label>
            <label class="custom-control custom-radio mr-0">
              <input id="radioStacked2" name="radio-stacked" type="radio" class="custom-control-input" v-model="start" value="scheduled" @click="startScheduled">
              <span class="custom-control-indicator"></span>
              <span class="custom-control-description">Scheduled</span>
            </label>
          </div>
          <div class="form-group" v-show="start == 'scheduled'">
            <label class="lead mr-3 my-label"></label>
            <input type="text" class="form-control datetimepicker-input" id="datetimepicker1" data-toggle="datetimepicker" data-target="#datetimepicker1" style="max-width: 300px; display: inline-block" placeholder="">
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header pl-3" role="tab" id="headingOne">
        <a data-toggle="collapse" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="collapsed text-dark">
          <h6 class="mb-0"><i class="fa fa-fw fa-share-alt" aria-hidden="true"></i> Nodes <span class="badge float-right" :class="nodeCount ? 'badge-info' : 'badge-secondary' ">{{nodeCount}}</span></h6>
        </a>
      </div>

      <div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
        <div class="card-body">
          <span class="lead align-middle">Select nodes for your experiment
            <div class="btn-group btn-group-sm mb-2 ml-2" data-toggle="buttons">
              <button class="btn" :class="selectBy == 'names' ? 'btn-primary': 'btn-outline-primary'" @click="selectBy = 'names'" id="list-byname-list" data-toggle="list" href="#list-byname" role="tab" aria-controls="byname">by name</button>
              <button class="btn" :class="selectBy == 'set' ? 'btn-primary': 'btn-outline-primary'" @click="selectBy = 'set'" id="list-byset-list" data-toggle="list" href="#list-byset" role="tab" aria-controls="byset">by set</button>
            </div>
          </span>
          <!-- <ul class="nav nav-pills mb-4">
            <li class="nav-item">
              <a class="nav-link active" id="list-byname-list" data-toggle="list" href="#list-byname" role="tab" aria-controls="byname"> by name </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="list-byset-list" data-toggle="list" href="#list-byset" role="tab" aria-controls="byset"> by set </a>
            </li>
          </ul> -->
          <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="list-byname" role="tabpanel" aria-labelledby="list-byname-list">
              <div class="mb-2">
                <span class="lead align-middle my-filter">Sites</span>
                <div class="btn-group btn-group-sm btn-filter" role="group" aria-label="Filter by site">
                  <button type="button" class="btn" :class="{'btn-secondary': filterSite == 'all'}" @click="filterSite = 'all'">all</button>
                  <button type="button" class="btn" v-for="site in sites" :class="{'btn-secondary': filterSite == site.site}" @click="filterSite = site.site">{{site.site}}</button>
                </div>
              </div>
              <div>
                <span class="lead align-middle my-filter">Archi</span>
                <div class="btn-group btn-group-sm btn-filter" role="group" aria-label="Filter by architecture">
                  <button type="button" class="btn" :class="{'btn-secondary': filterArchi == 'all'}" @click="filterArchi = 'all'">all</button>
                  <button type="button" class="btn" v-for="archi in archis" :class="{'btn-secondary': filterArchi == archi}" @click="filterArchi = archi">{{archi}}</button>
                  <!-- <button type="button" class="btn" :class="{'btn-secondary': filterArchi == 'a8'}" @click="filterArchi = 'a8'">A8</button> -->
                  <!-- <button type="button" class="btn" :class="{'btn-secondary': filterArchi == 'm3'}" @click="filterArchi = 'm3'">M3</button> -->
                  <!-- <button type="button" class="btn" :class="{'btn-secondary': filterArchi == 'wsn430'}" @click="filterArchi = 'wsn430'">WSN430</button> -->
                </div>
                  <button type="button" class="btn btn-sm btn-filter" :class="{'btn-secondary': filterMobile}" @click="filterMobile = !filterMobile">mobile</button>
              </div>
              <div class="d-md-flex flex-row">
                <multiselect v-model="currentNodes" :options="filteredNodes" :multiple="true" :close-on-select="false" :clear-on-select="false" :hide-selected="true" :preserve-search="true" :placeholder="searchNodesPlaceholder" label="network_address" track-by="network_address" class="mr-2 mt-3">
                  <template slot="tag" slot-scope="props">
                    <span class="custom__tag badge badge-primary">
                      <span>{{props.option.network_address}}</span> <span class="custom__remove cursor" @click="props.remove(props.option)">&times;</span>
                    </span>
                  </template>
                  <template slot="option" slot-scope="props">
                    <div class="option__desc">
                      <span class="option__title">{{props.option.network_address}}</span>
                      <span class="float-right badge custom__tag badge-secondary" :class="props.option.archi">{{props.option.archi}}</span>
                      <span class="float-right badge custom__tag badge-primary" v-if="props.option.mobile">mobile</span>
                      <span class="float-right badge custom__tag" :class="props.option.state | stateBadgeClass">{{props.option.state}}</span>
                    </div>
                  </template>
                </multiselect>
                <button class="btn btn-outline-success mt-3" @click="addNodes"><i class="fa fa-plus" aria-hidden="true"></i> Add to experiment</button>
              </div>
              <p class="ml-1 mt-2" style="font-size: 0.875rem;">
                <a href="" data-toggle="collapse" href="#collapseMap" aria-expanded="false" aria-controls="collapseMap"><i class="fa fa-map-o fa-lg" aria-hidden="true"></i> View/select nodes on map <i class="fa fa-caret-down" aria-hidden="true"></i></a> 
              </p>
              <div class="collapse" id="collapseMap">
                <div class="card card-body">
                  Map !
                </div>
              </div>
              <!-- <span class="align-middle text-muted">0 nodes currently in experiment</span> -->
            </div>
            <div class="tab-pane fade show active" id="list-byset" role="tabpanel" aria-labelledby="list-byset-list">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header pl-3" role="tab" id="headingTwo">
        <a class="collapsed text-dark" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          <h6 class="mb-0"><i class="fa fa-fw fa-microchip" aria-hidden="true"></i> Firmwares <small class="text-muted">(optional)</small></h6>
        </a>
      </div>
      <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
        <div class="card-body">
          <p class="lead mb-1 mb-2">Pick a firmware</p>
          <label class="custom-file">
            <input type="file" id="file2" ref="firmwareFile" class="custom-file-input" @change="previewFirmwareFile">
            <span class="custom-file-control">{{firmwareFile.name}}</span>
          </label>
          <p class="lead mt-3">Assign it to following nodes <span class="badge badge-primary">M3 (elf)</span> <span class="badge badge-secondary">WSN430 (ihex &amp; hex)</span></p>
          <!-- <label>Select nodes</label> -->
          <!-- <label>Node type </label> -->
          <multiselect v-model="value" :options="selectedNodes" :multiple="true" :close-on-select="false" :clear-on-select="false" :hide-selected="true" :preserve-search="true" placeholder="Select nodes" label="network_address" track-by="network_address">
            <template slot="tag" slot-scope="props">
              <span class="custom__tag badge badge-primary">
                <span>{{props.option.network_address}}</span> <span class="custom__remove cursor" @click="props.remove(props.option)">&times;</span>
              </span>
            </template>
          </multiselect>
          <button class="btn btn-secondary mt-3">Assign firmware</button>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header pl-3" role="tab" id="headingThree">
        <a class="collapsed text-dark" data-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          <h6 class="mb-0"><i class="fa fa-fw fa-thermometer" aria-hidden="true"></i> Monitoring <small class="text-muted">(optional)</small></h6>
        </a>
      </div>
      <div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion">
        <div class="card-body">
          <p class="lead">You can pick monitoring profiles</p>
          <label class="custom-file">
            <input type="file" id="file" ref="monitoringFile" class="custom-file-input" @change="previewMonitoringFile">
            <span class="custom-file-control">{{monitoringFile.name}}</span>
          </label>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header pl-3" role="tab" id="headingFour">
        <a class="collapsed text-dark" data-toggle="collapse" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
          <h6 class="mb-0"><i class="fa fa-fw fa-terminal" aria-hidden="true"></i> Scripts <small class="text-muted">(optional)</small></h6>
        </a>
      </div>
      <div id="collapseFour" class="collapse" role="tabpanel" aria-labelledby="headingFour" data-parent="#accordion">
        <div class="card-body">
          <p class="lead">Run a script on IoT-LAB frontend <span class="text-muted">(one per IoT-LAB site)</span>.</p>
          <label class="custom-file">
            <input type="file" id="file2" ref="scriptFile" class="custom-file-input" @change="previewScriptFile">
            <span class="custom-file-control">{{scriptFile.name}}</span>
          </label>
        </div>
      </div>
    </div>
  </div>

  <h5 class="my-3">Summary</h5>
  <p class="lead">Your experiment on <span class="text-primary">4</span> nodes is set to start <span class="text-primary">{{scheduleText}}</span> for <span class="text-primary">{{duration}}</span> {{ durationMultiplier == 1 ? 'minutes' : 'hours'}}.</p>
  <p>
    <a href="" @click.prevent="showSummary = !showSummary">show nodes and associations</a>
  </p>
  <div class="scrollable h300" v-show="showSummary">
    <table class="table table-striped table-sm">
      <thead>
        <tr>
          <th class="header">Nodes</th>
          <th class="header">Firmwares</th>
          <th class="header">Monitoring profiles</th>
          <th class="header">Scripts</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(node, i) in nodes">
          <td>{{node.name}}</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>

  <button class="btn btn-lg btn-success">Create experiment</button>

</div> <!-- container -->

</template>

<script>
import $ from 'jquery'
import Multiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.min.css'
import 'tempusdominus-bootstrap-4'
import 'tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css'
import {iotlab} from '@/rest'

export default {
  name: 'NewExperiment',

  components: {
    Multiselect,
  },

  data () {
    return {
      pattern: '',
      start: 'asap',
      duration: 20,
      durationMultiplier: 1,
      startDate: '',
      showSummary: false,
      selectBy: 'names',
      filterSite: 'all',
      filterArchi: 'all',
      filterMobile: false,
      monitoringFile: {name: undefined},
      firmwareFile: {name: undefined},
      scriptFile: {name: undefined},
      selectedNodeGroups: [],
      currentNodes: [],
      value: [],
      sites: [],
      nodes: [],
    }
  },

  created () {
    iotlab.getSites().then(data => { this.sites = data })
    iotlab.getNodes().then(data => { this.nodes = data })
  },

  mounted () {
    $('#datetimepicker1').datetimepicker({
      sideBySide: true,
      format: 'YYYY-MM-DD HH:mm',
    })
    $('#datetimepicker1').on('change.datetimepicker', (e) => {
      this.startDate = e.date
    })
  },

  computed: {
    scheduleText () {
      if (this.start === 'asap') {
        return 'as soon as possible'
      }
      if (this.startDate) {
        return 'on ' + this.startDate.format('YYYY-MM-DD HH:mm')
      }
      return ''
    },
    filteredNodes () {
      return this.nodes.filter((node) => {
        return this.filterArchi === 'all' || node.archi === this.filterArchi
      })
      .filter((node) => {
        return this.filterSite === 'all' || node.site === this.filterSite
      })
      .filter((node) => {
        return this.filterMobile === false || node.mobile === 1
      })
      .filter((node) => {
        return !this.selectedNodes.some(e => e.network_address === node.network_address)
      })
    },
    searchNodesPlaceholder () {
      if (this.filteredNodes.length > 0) {
        return `Search among ${this.filteredNodes.length} nodes`
      }
      return 'No matching nodes found. Try to clear filters.'
    },
    selectedNodes () {
      return this.selectedNodeGroups.reduce((a, e) => a.concat(e), [])
    },
    nodeCount () {
      return this.selectedNodes.length
    },
    archis () {
      return this.nodes.filter((node) => {
        return this.filterSite === 'all' || node.site === this.filterSite
      })
      .reduce((list, node) => {
        if (!list.includes(node.archi)) { list.push(node.archi) }
        return list
      }, [])
    },
  },

  methods: {
    // async showPending () {
    //   this.pattern = ''
    //   this.users = await iotlab.getUsers({status: 'pending'})
    // },
    startAsap () {
    },
    startScheduled () {
      this.$forceUpdate()
      this.$nextTick(() => {
        $('#datetimepicker1').datetimepicker('show')
      })
    },
    previewMonitoringFile () {
      this.monitoringFile = this.$refs.monitoringFile.files[0]
    },
    previewFirmwareFile () {
      this.firmwareFile = this.$refs.firmwareFile.files[0]
    },
    previewScriptFile () {
      this.scriptFile = this.$refs.scriptFile.files[0]
    },
    addNodes () {
      this.selectedNodeGroups.push(this.currentNodes)
      this.currentNodes = []
    },
  },
}
</script>

<style>
.custom__tag.badge {
  font-weight: normal;
  font-size: 100%;
  margin: 1px 2px;
}
.custom__remove:hover {
  color: black;
}
.multiselect__option--highlight:after {
  background: linear-gradient(90deg, rgba(0,0,0,0) 0%, rgba(65,184,131,1) 10%, rgba(65,184,131,1) 100%) !important;
}
/*.multiselect__element, .multiselect__content-wrapper {
  z-index: 100 !important;
}*/

.custom-file-control {
  overflow: hidden;
}
.custom-file {
  width: 400px;
}

@media screen and (min-width: 576px) {
  .my-label {
    display: inline-block;
    width: 80px;
    text-align: right;
  }
}
@media screen and (max-width: 576px) {
  .my-label {
    display: block;
    text-align: left;
  }
}

.my-filter {
  width: 55px;
  display: inline-block;
}

.btn-filter > .btn:hover {
  background-color: #727b84;
  color: white;
}
.font-size-sm {
  font-size: 0.875rem;
}
.btn-group {
  flex-flow: wrap;
}
</style>
