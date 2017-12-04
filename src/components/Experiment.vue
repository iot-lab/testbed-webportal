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
            <input v-model="name" type="text" class="form-control" style="max-width: 300px; display: inline-block" placeholder="New experiment" autofocus>
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
          <ul class="nav nav-tabs pt-2 font-size-sm bg-light">
            <li class="nav-item">
              <!-- <a class="nav-link disabled px-1"></a> -->
              <span class="nav-link disabled text-dark fdont-weight-bold">Select by</span>
            </li>
            <li class="nav-item" v-tooltip:top="'Select nodes from a list'">
              <a class="nav-link active" id="list-byname-list" data-toggle="list" href="#list-byname" role="tab" aria-controls="byname"> host name </a>
            </li>
            <li class="nav-item" v-tooltip:top="'Select sets of nodes with the same properties'">
              <a class="nav-link" id="list-byprop-list" data-toggle="list" href="#list-byprop" role="tab" aria-controls="byprop"> node properties </a>
            </li>
            <li class="nav-item" v-tooltip:top="'Select nodes with given ids'">
              <a class="nav-link" id="list-byid-list" data-toggle="list" href="#list-byid" role="tab" aria-controls="byid"> node id </a>
            </li>
          </ul>
        <div class="py-3 card-body">
          <!-- <span class="lead align-middle">Select nodes for your experiment</span> -->

          <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="list-byname" role="tabpanel" aria-labelledby="list-byname-list">
              
              <p class="mb-2 lead text-muted">Pick nodes from the list. Add some filters or a search pattern.</p>
<!--               <div class="mb-2">
                <span class="lead align-middle my-filter">Sites</span>
                <div class="btn-group btn-group-sm btn-filter" role="group" aria-label="Filter by site">
                  <button type="button" class="btn" :class="{'btn-secondary': filterSite == 'all'}" @click="filterSite = 'all'">All</button>
                  <button type="button" class="btn text-capitalize" v-for="site in sites" :class="{'btn-secondary': filterSite == site.site}" @click="filterSite = site.site">{{site.site}}</button>
                </div>
              </div>
              <div class="mb-2">
                <span class="lead align-middle my-filter">Archi</span>
                <div class="btn-group btn-group-sm btn-filter" role="group" aria-label="Filter by architecture">
                  <button type="button" class="btn" :class="{'btn-secondary': filterArchi == 'all'}" @click="filterArchi = 'all'">All</button>
                  <button type="button" class="btn text-capitalize" v-for="archi in archis4Site.sort()" :class="{'btn-secondary': filterArchi == archi}" @click="filterArchi = archi">{{archi|formatArchi}}</button>
                </div>
                <button type="button" class="btn btn-sm btn-filter" :class="{'btn-secondary': filterMobile}" @click="filterMobile = !filterMobile">Mobile</button>
              </div> -->
              <div>
                <span>Filters</span>&nbsp;
                <filter-select :items="sites.map(s => s.site).sort()" all="All sites" @changed="function (value) {filterSite = value}"></filter-select>
                <filter-select all="All architectures" @changed="function (value) {filterArchi = value}"
                  :items="archis4Site.map(archi => Object({value: archi, option: this.$options.filters.formatArchiRadio(archi)}))">
                </filter-select>
                <filter-select :items="['All mobility', 'Mobile', 'Not mobile']" @changed="tototo"></filter-select>
                
              </div>
              <div class="d-md-flex flex-row mt-3">
                <multiselect v-model="currentNodes" :options="filteredNodes" :multiple="true" :close-on-select="false" :clear-on-select="false" :hide-selected="true" :preserve-search="true" :placeholder="searchNodesPlaceholder" label="network_address" track-by="network_address" class="mr-1">
                  <template slot="tag" slot-scope="props">
                    <span class="custom__tag badge badge-primary">
                      <span>{{props.option.network_address | stripDomain}}</span>
                      <span class="custom__remove cursor" @click="props.remove(props.option)">&times;</span>
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
                <button class="btn btn-success mr-1" @click="addNodes">Add to experiment</button>
                <div class="btn-group xmr-1">
                  <div class="btn-group" role="group">
                    <button class="btn btn-icon dropdown-toggle" v-tooltip="'Add firmware'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fa fa-fw fa-microchip"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <div class="card-body">
                        <p class="lead">Pick a firmware <span class="text-muted">(optional)</span></p>
                        <label class="custom-file">
                          <input type="file" id="file" ref="firmwareFile" class="custom-file-input" @change="previewFirmwareFile">
                          <span class="custom-file-control">{{firmwareFile.name}}</span>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="btn-group" role="group">
                    <button class="btn btn-icon dropdown-toggle" v-tooltip="'Add monitoring profile'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fa fa-fw fa-thermometer"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <div class="card-body">
                        <p class="lead">Pick a monitoring profile <span class="text-muted">(optional)</span></p>
                        <label class="custom-file">
                          <input type="file" id="file" ref="monitoringFile" class="custom-file-input" @change="previewMonitoringFile">
                          <span class="custom-file-control">{{monitoringFile.name}}</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
              <p class="ml-1 mt-2 font-size-sm">
                <a href="" data-toggle="collapse" href="#collapseMap" aria-expanded="false" aria-controls="collapseMap"><i class="fa fa-map-o fa-lg" aria-hidden="true"></i> View/select nodes on map <i class="fa fa-caret-down" aria-hidden="true"></i></a> 
              </p>
              <div class="collapse" id="collapseMap">
                <div class="card card-body">
                  Map !
                </div>
              </div>
            </div>
            <div class="tab-pane fade show" id="list-byprop" role="tabpanel" aria-labelledby="list-byprop-list">
              <p class="mb-2 lead text-muted">Select an architecture, site and quantity.</p>
              <div>
                <span class="lead align-middle my-filter">Archi</span>
                <div class="btn-group btn-group-sm btn-filter" role="group" aria-label="Filter by architecture">
                  <button type="button" class="btn text-capitalize" v-for="archi in archis.sort()" :class="{'btn-secondary': filterArchi == archi}" @click="filterArchi = archi">{{archi|formatArchi}}</button>
                </div>
                <button type="button" class="btn btn-sm btn-filter" :class="{'btn-secondary': filterMobile}" @click="filterMobile = !filterMobile">Mobile</button>
              </div>
              <div class="mb-2 mt-2">
                <span class="lead align-middle my-filter">Sites</span>
                <div class="btn-group btn-group-sm btn-filter" role="group" aria-label="Filter by site">
                  <button type="button" class="btn text-capitalize" v-for="site in sites4Archi" :class="{'btn-secondary': filterSite == site.site}" @click="filterSite = site.site">{{site.site}}</button>
                </div>
              </div>
                <span class="lead align-middle my-filter">Qty</span>
                <div class="d-inline-block">
                  <div class="input-group input-group-sm mt-2" style="max-width:120px;">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-danger" :disabled="qty<=1" @click="qty--">
                        <i class="fa fa-minus"></i>
                      </button>
                    </span>
                    <input type="text" class="form-control" v-model="qty">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-success" @click="qty++">
                        <i class="fa fa-plus"></i>
                      </button>
                    </span>
                  </div>
                </div>
              <!-- <div class="d-md-flex" style="max-width: 800px"> -->
                <!-- <input type="" name=""> -->
              <!-- </div> -->
              <hr>
              <div class="d-md-flex" style="max-width: 850px">
                <select class="form-control form-control-sm text-capitalize mr-2">
                  <option selected disabled>Architecture</option>
                  <option v-for="archi in archis" class="text-capitalize">{{archi | formatArchiRadio}}</option>
                </select>
                <select class="form-control form-control-sm text-capitalize mr-2">
                  <option selected disabled>Site</option>
                  <option v-for="site in sites4Archi">{{site.site}}</option>
                </select>
                <select class="form-control form-control-sm mr-2" style="max-width: 90px;">
                  <option selected disabled>Qty</option>
                  <option v-for="i in [1,2,3,4,5,6,7,8,9,10]">{{i}}</option>
                </select>
                <label class="custom-control custom-checkbox mb-0 mt-1">
                  <input type="checkbox" class="custom-control-input">
                  <span class="custom-control-indicator"></span>
                  <span class="custom-control-description">Mobile</span>
                </label>
                <button class="btn btn-sm btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add to experiment</button>
              </div>
            </div>
            <!-- Select By ID -->
            <div class="tab-pane fade show" id="list-byid" role="tabpanel" aria-labelledby="list-byid-list">
              <p class="mb-2 lead text-muted">Select a site, architecture and desired ids.</p>
              <div class="d-md-flex" style="max-width: 850px">
                <filter-select :items="sites.map(s => s.site).sort()" title="Site" @changed="function (value) {filterSite = value}"></filter-select>
                <filter-select title="Architecture" @changed="function (value) {filterArchi = value}"
                  :items="archis4Site.map(archi => Object({value: archi, option: this.$options.filters.formatArchiRadio(archi)}))">
                </filter-select>
                <input v-model="nodeIds" class="form-control form-control-sm mr-2" type="text" placeholder="IDs (e.g. 1-5+7)">
                <button class="btn btn-sm btn-success mr-1" @click="expand"><i class="fa fa-plus" aria-hidden="true"></i> Add to experiment</button>
                <div class="btn-group btn-group-sm xmr-1">
                  <div class="btn-group btn-group-sm" role="group">
                    <button class="btn btn-icon dropdown-toggle" v-tooltip="'Add firmware'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fa fa-fw fa-microchip"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <div class="card-body">
                        <p class="lead">Pick a firmware <span class="text-muted">(optional)</span></p>
                        <label class="custom-file">
                          <input type="file" id="file" ref="firmwareFile" class="custom-file-input" @change="previewFirmwareFile">
                          <span class="custom-file-control">{{firmwareFile.name}}</span>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="btn-group btn-group-sm" role="group">
                    <button class="btn btn-icon dropdown-toggle" v-tooltip="'Add monitoring profile'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fa fa-fw fa-thermometer"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <div class="card-body">
                        <p class="lead">Pick a monitoring profile <span class="text-muted">(optional)</span></p>
                        <label class="custom-file">
                          <input type="file" id="file" ref="monitoringFile" class="custom-file-input" @change="previewMonitoringFile">
                          <span class="custom-file-control">{{monitoringFile.name}}</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Selected nodes -->
      <div v-if="selectedNodes.length" class="card-body font-size-sm" style="background: #22222222">
        <p class="mb-1 font-size-1">{{selectedNodes.length}} selected nodes: <a href="" @click.prevent="selectedNodes = []">clear all</a></p>
        <span class="badge badge-info custom__tag" v-for="node in selectedNodes">
          <span>{{node.network_address | stripDomain}}</span> <span class="custom__remove cursor" @click="removeNode(node)">&times;</span>
        </span>
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
  <p class="lead">Your experiment on <span class="text-primary">{{nodeCount}}</span> nodes is set to start <span class="text-primary">{{scheduleText}}</span> for <span class="text-primary">{{duration}}</span> {{ durationMultiplier == 1 ? 'minutes' : 'hours'}}.</p>
  <p>
    <a href="" @click.prevent="showSummary = !showSummary">show nodes and associations</a>
  </p>
  <div class="scrollable h300" v-show="showSummary">
    <table class="table table-striped table-sm font-size-sm">
      <thead>
        <tr>
          <th class="header">Nodes</th>
          <th class="header">Firmwares</th>
          <th class="header">Monitoring profiles</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(node, i) in selectedNodes">
          <td>{{node.network_address}}</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>

  <button class="btn btn-lg btn-success" @click="submitExperiment">Create experiment</button>

</div> <!-- container -->

</template>

<script>
import $ from 'jquery'
import Multiselect from 'vue-multiselect'
import FilterSelect from '@/components/parts/FilterSelect'
import 'vue-multiselect/dist/vue-multiselect.min.css'
import 'tempusdominus-bootstrap-4'
import 'tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css'
import {iotlab} from '@/rest'
import {expand} from '@/utils'

export default {
  name: 'NewExperiment',

  components: {
    Multiselect,
    FilterSelect,
  },

  data () {
    return {
      name: '',
      start: 'asap',
      duration: 20,
      durationMultiplier: 1,
      startDate: '',
      showSummary: true,
      filterSite: 'all',
      filterArchi: 'all',
      filterMobile: false,
      filterState: 'all',
      monitoringFile: {name: undefined},
      firmwareFile: {name: undefined},
      scriptFile: {name: undefined},
      selectedNodes: [],
      currentNodes: [],
      value: [],
      sites: [],
      nodes: [],
      qty: 1,
      nodeIds: '',
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
        return this.filterState === 'all' || node.state === this.filterState
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
    // selectedNodes () {
      // return this.selectedNodeGroups.reduce((a, e) => a.concat(e), [])
    // },
    nodeCount () {
      return this.selectedNodes.length
    },
    archis () {
      return this.nodes.reduce((list, node) => {
        if (!list.includes(node.archi)) { list.push(node.archi) }
        return list
      }, [])
    },
    archis4Site () {
      return this.nodes.filter((node) => {
        return this.filterSite === 'all' || node.site === this.filterSite
      })
      .reduce((list, node) => {
        if (!list.includes(node.archi)) { list.push(node.archi) }
        return list
      }, [])
    },
    sites4Archi () {
      return this.nodes.filter((node) => {
        return this.filterArchi === 'all' || node.archi === this.filterArchi
      })
      .reduce((list, node) => {
        if (!list.includes(node.site)) { list.push(node.site) }
        return list
      }, [])
    },
    states () {
      return this.nodes.filter((node) => {
        return this.filterSite === 'all' || node.site === this.filterSite
      })
      .reduce((list, node) => {
        if (!list.includes(node.state)) { list.push(node.state) }
        return list
      }, [])
    },
  },

  methods: {
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
      this.selectedNodes = this.selectedNodes.concat(this.currentNodes)
      this.currentNodes = []
    },
    removeNode (node) {
      this.selectedNodes = this.selectedNodes.filter(n => n.network_address !== node.network_address)
    },

    expand () {
      var node
      for (var i of expand(this.nodeIds)) {
        node = this.nodes.find(n => n.network_address === `${this.filterArchi.split(':')[0]}-${i}.${this.filterSite}.iot-lab.info`)
        if (node) {
          if (!this.selectedNodes.includes(node)) this.selectedNodes.push(node)
        } else {
          this.$notify({text: `Invalid node id ${i}`, type: 'error'})
        }
      }
    },

    tototo (filterValue) {
      alert(filterValue)
    },

    async submitExperiment () {
      if (this.selectedNodes.length === 0) return
      try {
        let newExp = await iotlab.submitPhysicalExperiment({
          name: this.name,
          duration: this.duration * this.durationMultiplier,
          nodes: this.selectedNodes.map(node => node.network_address),
        })
        this.$notify({text: `Experiment ${newExp.id} submitted`, type: 'success'})
      } catch (err) {
        this.$notify({text: 'An error occured', type: 'error'})
      }
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
.font-size-1 {
  font-size: 1rem;
}
.btn-filter {
  flex-flow: wrap;
}

.btn-icon {
  background-color: hsl(134, 61%, 81%);
}
.btn-icon:hover {
  background-color: hsl(134, 62%, 61%);
}
</style>
