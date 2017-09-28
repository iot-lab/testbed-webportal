<template>
<div class="container my-3">
  <h2 class="mb-4">New experiment</h2>

  <div id="accordion" role="tablist">
    <div class="card">
      <div class="card-header pl-3" role="tab" id="headingZero">
        <a data-toggle="collapse" href="#collapseZero" aria-expanded="true" aria-controls="collapseZero" class="text-dark">
          <h6 class="mb-0"><i class="fa fa-fw fa-clock-o" aria-hidden="true"></i> Schedule</h6>
        </a>
      </div>

      <div id="collapseZero" class="collapse show" role="tabpanel" aria-labelledby="headingZero" data-parent="#accordion">
        <div class="card-body">
          <div class="form-group">
            <span class="lead text-right mr-3" style="display: inline-block; width: 80px;">Name</span>
            <input type="text" class="form-control" style="width: 300px; display: inline-block" placeholder="New experiment">
          </div>
          <div class="form-group">
            <span class="lead text-right mr-3" style="display: inline-block; width: 80px;">Duration</span>
            <div class="input-group" style="display: inline-flex; width: 300px;">
              <input type="number" class="form-control" min="1" v-model="duration">
              <div class="input-group-btn">
                <button type="button" class="btn" :class="{'btn-secondary': durationMultiplier == 1}" @click="durationMultiplier = 1"> minutes </button>
                <button type="button" class="btn" :class="{'btn-secondary': durationMultiplier == 60}" @click="durationMultiplier = 60"> hours </button>
              </div>
            </div>
          </div>
          <div class="form-group">
            <span class="lead text-right mr-3" style="display: inline-block; width: 80px;">Start</span>
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
            <span class="lead text-right mr-3" style="display: inline-block; width: 80px;"></span>
            <input type="text" class="form-control datetimepicker-input" id="datetimepicker1" data-toggle="datetimepicker" data-target="#datetimepicker1" style="width: 300px; display: inline-block" placeholder="">
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header pl-3" role="tab" id="headingOne">
        <a data-toggle="collapse" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="collapsed text-dark">
          <h6 class="mb-0"><i class="fa fa-fw fa-share-alt" aria-hidden="true"></i> Nodes</h6>
        </a>
      </div>

      <div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
        <div class="card-body">
          <p class="lead">Let's select some nodes for your experiment</p>
          <!-- <ul class="nav nav-pills mb-4">
            <li class="nav-item">
              <a class="nav-link active" href="#">by name</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">by type</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">by map</a>
            </li>
          </ul> -->
          <div class="row">
            <div class="col-md-auto mb-4">
              <div class="list-group" id="list-tab" role="tablist">
                <a class="list-group-item list-group-item-action active" id="list-byname-list" data-toggle="list" href="#list-byname" role="tab" aria-controls="byname">
                  by name
                </a>
                <a class="list-group-item list-group-item-action" id="list-bytype-list" data-toggle="list" href="#list-bytype" role="tab" aria-controls="bytype">
                  by type
                </a>
                <a class="list-group-item list-group-item-action" id="list-bymap-list" data-toggle="list" href="#list-bymap" role="tab" aria-controls="bymap">
                  by map
                </a>
              </div>
            </div>
            <div class="col">
              <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="list-byname" role="tabpanel" aria-labelledby="list-byname-list">
                  <label>
                    
                    <span class="align-middle mr-3">Filters</span>
                    <div class="btn-group mr-3" role="group" aria-label="Filter by site">
                      <button type="button" class="btn btn-sm" :class="{'btn-secondary': filterSite == 'all'}" @click="filterSite = 'all'">all sites</button>
                      <button type="button" class="btn btn-sm" :class="{'btn-secondary': filterSite == 'devgrenoble'}" @click="filterSite = 'devgrenoble'">devgrenoble</button>
                      <button type="button" class="btn btn-sm" :class="{'btn-secondary': filterSite == 'devlille'}" @click="filterSite = 'devlille'">devlille</button>
                    </div>
                    <div class="btn-group" role="group" aria-label="Filter by architecture">
                      <button type="button" class="btn btn-sm" :class="{'btn-secondary': filterType == 'all'}" @click="filterType = 'all'">all types</button>
                      <button type="button" class="btn btn-sm" :class="{'btn-secondary': filterType == 'a8'}" @click="filterType = 'a8'">A8</button>
                      <button type="button" class="btn btn-sm" :class="{'btn-secondary': filterType == 'm3'}" @click="filterType = 'm3'">M3</button>
                      <button type="button" class="btn btn-sm" :class="{'btn-secondary': filterType == 'wsn430'}" @click="filterType = 'wsn430'">WSN430</button>
                    </div>

                  </label>
                  <multiselect v-model="value" :options="filteredNodes" :multiple="true" :close-on-select="false" :clear-on-select="false" :hide-selected="true" :preserve-search="true" :placeholder="searchNodesPlaceholder" label="name" track-by="name" class="mb-3">
                    <template slot="tag" scope="props">
                      <span class="custom__tag badge badge-primary">
                        <span>{{props.option.name}}</span> <span class="custom__remove cursor" @click="props.remove(props.option)">&times;</span>
                      </span>&nbsp;
                    </template>
                  </multiselect>
                  <button class="btn btn-success mr-3">Add to experiment</button>
                  <span class="align-middle text-muted">0 nodes currently in experiment</span>
                </div>
                <div class="tab-pane fade show active" id="list-bytype" role="tabpanel" aria-labelledby="list-bytype-list">
                </div>
                <div class="tab-pane fade show active" id="list-bymap" role="tabpanel" aria-labelledby="list-bymap-list">
                </div>
              </div>
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
          <multiselect v-model="value" :options="nodes" :multiple="true" :close-on-select="false" :clear-on-select="false" :hide-selected="true" :preserve-search="true" placeholder="Select nodes" label="name" track-by="name">
            <template slot="tag" scope="props">
              <span class="custom__tag badge badge-primary">
                <span>{{props.option.name}}</span> <span class="custom__remove cursor" @click="props.remove(props.option)">&times;</span>
              </span>&nbsp;
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
// import {iotlab} from '@/rest'

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
      filterSite: 'all',
      filterType: 'all',
      monitoringFile: {name: undefined},
      firmwareFile: {name: undefined},
      scriptFile: {name: undefined},
      value: [],
      nodes: [
        { type: 'a8', name: 'a8-1.devgrenoble.iot-lab.info', site: 'devgrenoble' },
        { type: 'a8', name: 'a8-2.devgrenoble.iot-lab.info', site: 'devgrenoble' },
        { type: 'm3', name: 'm3-1.devgrenoble.iot-lab.info', site: 'devgrenoble' },
        { type: 'm3', name: 'm3-2.devgrenoble.iot-lab.info', site: 'devgrenoble' },
        { type: 'm3', name: 'm3-3.devlille.iot-lab.info', site: 'devlille' },
        { type: 'm3', name: 'm3-4.devlille.iot-lab.info', site: 'devlille' },
        { type: 'm3', name: 'm3-5.devlille.iot-lab.info', site: 'devlille' },
        { type: 'wsn430', name: 'wsn430-1.devlille.iot-lab.info', site: 'devlille' },
        { type: 'wsn430', name: 'wsn430-2.devlille.iot-lab.info', site: 'devlille' },
        { type: 'wsn430', name: 'wsn430-3.devlille.iot-lab.info', site: 'devlille' },
      ],
    }
  },

  created () {
    // iotlab.getUsers().then(data => { this.users = data })
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
      console.log('test' + this.startDate)
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
        return this.filterType === 'all' || node.type === this.filterType
      })
      .filter((node) => {
        return this.filterSite === 'all' || node.site === this.filterSite
      })
    },
    searchNodesPlaceholder () {
      if (this.filteredNodes.length > 0) {
        return 'Search nodes'
      }
      return 'No matching nodes found. Try to clear filters.'
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
      this.$nextTick(() => {
        $('#datetimepicker1').datetimepicker('show')
      })
    },
    setStartDate () {
      console.log(this.startSchedule)
      console.log('ok')
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
  },
}
</script>

<style>
.custom__tag.badge {
  font-weight: normal;
  font-size: 100%;
}
.multiselect__element, .multiselect__content-wrapper {
  z-index: 100 !important;
}

.custom-file-control {
  overflow: hidden;
}
.custom-file {
  width: 400px;
}
</style>
