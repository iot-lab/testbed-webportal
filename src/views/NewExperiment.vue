<template>
<div class="container my-3">
  <h2 class="mb-4 d-none d-sm-block d-md-none">New experiment</h2>
  <div id="accordion" role="tablist">
    <div class="card">
      <div class="card-header pl-3" role="tab" id="headingZero">
        <a data-toggle="collapse" href="#collapseZero" aria-expanded="true" aria-controls="collapseZero" class="text-dark">
          <h6 class="mb-0"><i class="fa fa-fw fa-clock-o" aria-hidden="true"></i> Schedule
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
            <input v-model="name" type="text" class="form-control" placeholder="New experiment" autofocus
              style="max-width: 300px; display: inline-block"
              :class="{'is-invalid': !nameValidation}">
            <div class="invalid-feedback">
              Invalid name. Only alphanumeric characters allowed [0-9A-Za-z_]
            </div>
          </div>
          <div class="form-group">
            <label class="lead mr-3 my-label">Duration</label>
            <div class="input-group" style="display: inline-flex; max-width: 300px;">
              <input type="number" class="form-control" min="1" v-model="duration">
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-secondary" :class="{'active': durationMultiplier == 1}" @click="durationMultiplier = 1"> minutes </button>
                <button type="button" class="btn btn-outline-secondary" :class="{'active': durationMultiplier == 60}" @click="durationMultiplier = 60"> hours </button>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="lead mr-3 my-label">Start</label>
            <label class="custom-control custom-control-inline custom-radio">
              <input id="radioStacked1" name="radio-stacked" type="radio" class="custom-control-input" v-model="start" value="asap">
              <span class="custom-control-label">As soon as possible</span>
            </label>
            <label class="custom-control custom-control-inline custom-radio mr-0">
              <input id="radioStacked2" name="radio-stacked" type="radio" class="custom-control-input" v-model="start" value="scheduled" @click="startScheduled">
              <span class="custom-control-label">Scheduled</span>
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
        <a data-toggle="collapse" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="collapsed text-dark" @click="refreshNodes">
          <h6 class="mb-0"><i class="fa fa-fw fa-share-alt" aria-hidden="true"></i> Nodes <span class="badge float-right" :class="nodeCount ? 'badge-info' : 'badge-secondary' ">{{nodeCount}}</span></h6>
        </a>
      </div>

      <div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
          <ul class="nav nav-tabs pt-2 font-size-sm bg-light">
            <li class="nav-item">
              <!-- <a class="nav-link disabled px-1"></a> -->
              <span class="nav-link disabled text-dark fdont-weight-bold">Select by</span>
            </li>
            <li class="nav-item" v-tooltip:top="'Select sets of nodes with the same properties'">
              <a class="nav-link active" id="list-byprop-list" data-toggle="list" href="#list-byprop" role="tab" aria-controls="byprop" @click="setMode('byprop')"> node properties </a>
            </li>
            <li class="nav-item" v-tooltip:top="'Select nodes from list or map'">
              <a class="nav-link" id="list-byname-list" data-toggle="list" href="#list-byname" role="tab" aria-controls="byname" @click="setMode('byname'); refreshNodes()"> host name / map </a>
            </li>
            <li class="nav-item" v-tooltip:top="'Select nodes with given ids'">
              <a class="nav-link" id="list-byid-list" data-toggle="list" href="#list-byid" role="tab" aria-controls="byid" @click="setMode('byid'); refreshNodes()"> node id </a>
            </li>
          </ul>
        <div class="py-3 card-body">

          <div class="tab-content" id="nav-tabContent">
            <!-- Select By PROPS -->
            <div class="tab-pane fade show active" id="list-byprop" role="tabpanel" aria-labelledby="list-byprop-list">
              <p class="mb-2 lead text-muted">Select an architecture, site and quantity.</p>
              <div class="d-md-flex" style="max-width: 850px">
                <filter-select v-model="filterArchi" title="Architecture" @input="filterSite = sites4Archi[0]; qty = 1"
                  :items="sortedArchis">
                </filter-select>
                <filter-select title="Site" :items="sites4Archi.sort()" v-model="filterSite"></filter-select>
                <filter-select :items="qtyAvailable" title="Qty" v-model.number="qty" style="max-width: 90px"></filter-select>
                <button class="btn btn-sm btn-success" @click="addProps"><i class="fa fa-plus" aria-hidden="true"></i> Add to experiment</button>
              </div>
            </div>
            <!-- Select By HOSTNAME -->
            <div class="tab-pane fade show" id="list-byname" role="tabpanel" aria-labelledby="list-byname-list">

              <p class="mb-2 lead text-muted">Pick nodes from the list. Add some filters or a search pattern.</p>
              <div>
                <span>Filters</span>&nbsp;
                <filter-select :items="sites.map(s => s.site).sort()" all="All sites" v-model="filterSite"></filter-select>
                <filter-select all="All architectures" v-model="filterArchi"
                  :items="sortedArchis4Site">
                </filter-select>
              </div>
              <div class="d-md-flex flex-row mt-3" style="align-items: center;">
                <multiselect v-model="currentNodes" :options="filteredNodes" :multiple="true" :close-on-select="false" :clear-on-select="false" :hide-selected="true" :preserve-search="true" :placeholder="searchNodesPlaceholder" label="network_address" track-by="network_address" class="multiselect-nodes mr-1">
                  <template slot="tag" slot-scope="props">
                    <span class="badge-tag badge badge-primary">
                      <span>{{props.option.network_address | stripDomain}}</span>
                      <span class="tag-remove cursor" @click="props.remove(props.option)">&times;</span>
                    </span>
                  </template>
                  <template slot="option" slot-scope="props">
                    <div class="option__desc">
                      <span class="option__title">{{props.option.network_address}}</span>
                      <span class="float-right badge badge-tag badge-secondary" :class="props.option.archi">{{props.option.archi}}</span>
                      <span class="float-right badge badge-tag" :class="props.option.state | stateBadgeClass">{{props.option.state}}</span>
                    </div>
                  </template>
                </multiselect>
                <button class="btn btn-success" @click="addNodes">Add to experiment</button>
              </div>
              <p class="mt-2 mb-2 font-size-sm">
                <a href="#collapseMap" @click.prevent="showMap = !showMap">
                  <i class="fa fa-map-o fa-fw fa-lg" aria-hidden="true"></i> View/select nodes on map <i class="fa fa-caret-down" aria-hidden="true"></i>
                </a>
              </p>
              <div id="collapseMap" v-show="showMap">
                <map-3d :nodes="filteredNodes" v-model="currentNodes" :selectedNodes="selectedNodesForSite" :shows="showMap" @selectSite="(site) => filterSite = site"></map-3d>
              </div>
            </div>
            <!-- Select By ID -->
            <div class="tab-pane fade show" id="list-byid" role="tabpanel" aria-labelledby="list-byid-list">
              <p class="mb-2 lead tex t-muted">Select a site, architecture and desired ids.</p>
              <div class="form-inline">
                <filter-select :items="sites.map(s => s.site).sort()" title="Site" v-model="filterSite"
                  @input="testArchi"></filter-select>
                <filter-select title="Architecture" v-model="filterArchi"
                  :items="sortedArchis4Site">
                </filter-select>
                <input id="nodeIds" v-model="nodeIds" class="form-control form-control-sm mr-2" type="text" placeholder="IDs (e.g. 1-5+7)"
                  data-toggle="popover"
                  data-placement="bottom"
                  data-title="Available node ids"
                  :data-content="nodeIdsInfo"
                >
                <button class="btn btn-sm btn-success" @click="expandNodeIds"><i class="fa fa-plus" aria-hidden="true"></i> Add to experiment</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Selected nodes by host or ID -->
      <div v-if="selectedNodes.length" v-show="mode !== 'byprop'" class="card-body font-size-sm" style="background: #22222222">
        <p class="mb-0 font-size-1">{{selectedNodes.length}} nodes selected: <a href="" @click.prevent="clearAllNodes">clear all</a></p>
        <div style="margin-top: 0.35rem" v-for="(group, index) in selectedNodeGroups">
          <span class="badge badge-info badge-tag" v-for="node in group.nodes" :class="{'badge-even': index % 2}">
            <span>{{node.network_address | stripDomain}}</span> <span class="tag-remove cursor" @click="removeNode(node)">&times;</span>
          </span>
          <span class="badge badge-light badge-tag cursor" v-if="group.hasFirmware" data-toggle="dropdown" v-tooltip:top="'Add firmware'">
            <i class="fa fa-microchip text-dark"></i> <span class="mx-1" v-if="group.firmware.name" v-html="$options.filters.md5Tag(group.firmware.name)"></span> <span v-if="group.firmware.name" class="tag-remove cursor" @click.stop="group.firmware = {name:undefined}">&times;</span>
          </span>
          <div class="dropdown-menu dropdown-menu-right">
            <div class="card-body">
              <p class="lead">Assign a firmware <span class="text-muted">(upload or select from list)</span></p>
              <div class="custom-file mb-2">
                <input type="file" id="file" :ref="'firmwareFile' + index" class="custom-file-input" @change="loadFirmwareFile('firmwareFile' + index, index)">
                <label class="custom-file-label">{{group.firmware.name}}</label>
              </div>
              <hr>
              <firmware-list :archi="[group.archi, undefined]" :select="true" @select="fw => { avoidCollision(fw); group.firmware = {name: fw} }"></firmware-list>
            </div>
          </div>
          <span>
            <span class="badge badge-light badge-tag cursor" data-toggle="dropdown" v-tooltip:top="'Add monitoring profile'">
              <i class="fa fa-thermometer text-dark" style="width: 12px;"></i> {{group.monitoring}} <span v-if="group.monitoring" class="tag-remove cursor" @click.stop="group.monitoring = undefined">&times;</span>
            </span>
            <div class="dropdown-menu dropdown-menu-right">
              <div class="card-body">
                <p class="lead">Assign a monitoring profile</p>
                <monitoring-list :archi="group.archi" :select="true" @select="profile => { group.monitoring = profile }"></monitoring-list>
              </div>
            </div>
          </span>
          <span class="badge badge-light badge-tag tag-remove cursor"
            @click="hideTooltip(); group.nodes.map(node => removeNode(node))"
            v-tooltip:top="`Clear ${group.nodes.length} nodes`"><i class="fa fa-trash-o"></i></span>
        </div>
      </div>
      <!-- Selected nodes by Props -->
      <div v-if="selectedProps.length" v-show="mode === 'byprop'" class="card-body font-size-sm" style="background: #22222222">
        <p class="mb-0 font-size-1">{{nodeCount}} nodes selected: <a href="" @click.prevent="clearAllNodes">clear all</a></p>
        <div style="margin-top: 0.35rem" v-for="(p, index) in selectedProps">
          <span class="badge badge-info badge-tag"> {{p.prop.properties.archi | formatArchiRadio}} @ {{p.prop.properties.site}}</span>
          x {{p.prop.nbnodes}}
          <span class="badge badge-light badge-tag cursor" v-if="p.hasFirmware" data-toggle="dropdown" v-tooltip:top="'Add firmware'">
            <i class="fa fa-microchip text-dark"></i> <span class="mx-1" v-if="p.firmware.name" v-html="$options.filters.md5Tag(p.firmware.name)"></span> <span v-if="p.firmware.name" class="tag-remove cursor" @click.stop="p.firmware = {name:undefined}">&times;</span>
          </span>
          <div class="dropdown-menu dropdown-menu-right">
            <div class="card-body">
              <p class="lead">Assign a firmware <span class="text-muted">(upload or select from list)</span></p>
              <div class="custom-file mb-2">
                <input type="file" id="file" :ref="'firmwarePropFile' + index" class="custom-file-input" @change="loadFirmwareFile('firmwarePropFile' + index, index)">
                <label class="custom-file-label">{{p.firmware.name}}</label>
              </div>
              <hr>
              <firmware-list :archi="[p.archi, undefined]" :select="true" @select="fw => { avoidCollision(fw); p.firmware = {name: fw} }"></firmware-list>
            </div>
          </div>
          <span>
            <span class="badge badge-light badge-tag cursor" data-toggle="dropdown" v-tooltip:top="'Add monitoring profile'">
              <i class="fa fa-thermometer text-dark" style="width: 12px;"></i> {{p.monitoring}} <span v-if="p.monitoring" class="tag-remove cursor" @click.stop="p.monitoring = undefined">&times;</span>
            </span>
            <div class="dropdown-menu dropdown-menu-right">
              <div class="card-body">
                <p class="lead">Assign a monitoring profile</p>
                <monitoring-list :archi="p.archi" :select="true" @select="profile => { p.monitoring = profile }"></monitoring-list>
              </div>
            </div>
          </span>
          <span class="badge badge-light badge-tag tag-remove cursor"
            @click="hideTooltip(); removeProp(index)"
            v-tooltip:top="`Clear ${p.prop.nbnodes} nodes`"><i class="fa fa-trash-o"></i></span>
        </div>
      </div>
    </div>
    <!-- <div class="card">
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
          TODO
        </div>
      </div>
    </div> -->
  </div>

  <h5 class="my-3">Summary</h5>
  <p class="lead">Your experiment on <span class="text-primary">{{nodeCount}}</span> nodes is set to start <span class="text-primary">{{scheduleText}}</span> for <span class="text-primary">{{duration}}</span> {{ durationMultiplier == 1 ? 'minutes' : 'hours'}}.</p>

  <button class="btn btn-lg btn-primary" @click="submitExperiment">Submit experiment</button>

</div> <!-- container -->

</template>

<script>
import $ from 'jquery'
import Multiselect from 'vue-multiselect'
import FilterSelect from '@/components/FilterSelect'
import MonitoringList from '@/components/MonitoringList'
import FirmwareList from '@/components/FirmwareList'
import Map3d from '@/components/Map3d'
import 'vue-multiselect/dist/vue-multiselect.min.css'
import 'tempusdominus-bootstrap-4'
import 'tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css'
import { iotlab } from '@/rest'
import { expandIds, groupBy, sleep, md5Hash } from '@/utils'
import { allowedFirmwares4Archi, extractArchi } from '@/assets/js/iotlab-utils'

function Exception (message) {
  this.message = message
  this.name = 'Exception'
}

function newNode (node) {
  // Expand node object returned by IoT-LAB API with computed attributes and functions
  return Object.assign({}, node, {
    shortArchi: extractArchi(node.archi),
  })
}

function newNodeGroup (nodes) {
  if (new Set(nodes.map(node => node.shortArchi)).size > 1) throw new Exception('Node groups should have the same archi')
  return Object.assign({}, {
    nodes: nodes,
    firmware: {name: undefined},
    monitoring: undefined,
    archi: nodes[0].shortArchi,
    allowedFirmwareTypes: allowedFirmwares4Archi(nodes[0].shortArchi),
    hasFirmware: allowedFirmwares4Archi(nodes[0].shortArchi).length > 0,
  })
}

function newPropGroup (prop) {
  return Object.assign({}, {
    prop: prop,
    firmware: {name: undefined},
    monitoring: undefined,
    archi: extractArchi(prop.properties.archi),
    allowedFirmwareTypes: allowedFirmwares4Archi(extractArchi(prop.properties.archi)),
    hasFirmware: allowedFirmwares4Archi(extractArchi(prop.properties.archi)).length > 0,
  })
}

export default {
  name: 'NewExperiment',

  components: {
    Multiselect,
    FilterSelect,
    MonitoringList,
    FirmwareList,
    Map3d,
  },

  data () {
    return {
      name: '',
      start: 'asap',
      duration: 20,
      durationMultiplier: 1,
      startDate: '',
      mode: 'byprop',
      filterSite: 'all',
      filterArchi: 'all',
      showMap: false,
      propMobile: false,
      firmwareFiles: [{name: undefined}],
      scriptFile: {name: undefined},
      selectedNodeGroups: [],
      selectedProps: [],
      currentNodes: [],
      currentFirmware: {name: undefined},
      currentMonitoring: {name: undefined},
      firmwares: {},
      firmwaresHashes: {},
      sites: [],
      node_ids: [],
      nodes: [],
      qty: 0,
      nodeIds: '',
    }
  },

  created () {
    iotlab.getSites().then(data => { this.sites = data })
    this.refreshNodes()
  },

  mounted () {
    $('#datetimepicker1').datetimepicker({
      sideBySide: true,
      format: 'YYYY-MM-DD HH:mm',
    })
    $('#datetimepicker1').on('change.datetimepicker', e => {
      this.startDate = e.date
    })
    this.$nextTick(function () {
      $('[data-toggle="popover"]').popover({
        trigger: 'focus',
        html: true,
      })
    })
    $('#collapseMap').on('shown.bs.collapse', () => {
      this.showMap = true
    })
    $('#collapseMap').on('hidden.bs.collapse', () => {
      this.showMap = false
    })
    $(document).on('click', 'span.data-ids', e => {
      this.nodeIds = e.target.textContent
    })
    $(document).on('click', '.dropdown-menu a[data-toggle="list"]', e => {
      // prevent dropdown menu to close while switching tabs
      e.stopPropagation()
    })
  },

  computed: {
    nameValidation () {
      return this.name.match(/^[0-9A-Za-z_]*$/)
    },
    scheduleText () {
      if (this.start === 'asap') {
        return 'as soon as possible'
      }
      if (this.startDate) {
        return 'on ' + this.startDate.format('YYYY-MM-DD HH:mm')
      }
      return ''
    },
    scheduleEpoch () {
      return (this.start === 'asap') ? undefined : this.startDate.unix()
    },
    filteredNodes () {
      return this.nodes.filter((node) => {
        return this.filterArchi === 'all' || node.archi === this.filterArchi
      })
        .filter((node) => {
          return this.filterSite === 'all' || node.site === this.filterSite
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
    nodeIdsInfo () {
      if (this.mode !== 'byid') return 'select node by ids tab'
      if (this.filterSite === 'all' || this.filterArchi === 'all') return 'Select a site and architecture first'
      try {
        return this.nodes_ids
          .filter(site => site.site === this.filterSite)[0].archis
          .filter(archi => archi.archi === this.filterArchi)[0].states
          .sort((a, b) => a.state > b.state)
          .map(state => `<span class="badge ${this.$options.filters.stateBadgeClass(state.state)}">${state.state}</span> <span class="cursor data-ids">${state.ids}</span>`)
          .join('<br>')
      } catch (err) {
        return 'no matching ids'
      }
    },
    selectedNodes () {
      return this.selectedNodeGroups.reduce((a, e) => a.concat(e.nodes), [])
    },
    selectedNodesForSite () {
      // selected nodes for current site to display on the map
      return this.selectedNodes.filter(node => node.site === this.filterSite)
    },
    nodeCount () {
      if (this.mode === 'byprop') {
        return this.selectedProps.reduce((a, e) => a + parseInt(e.prop.nbnodes), 0)
      } else {
        return this.selectedNodes.length
      }
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
    sortedArchis () {
      // let's sort the archis with (a8, m3 & wsn) on top
      let mainArchis = ['a8:at86rf231', 'm3:at86rf231', 'wsn430:cc1101']
      let main = this.archis.filter(a => mainArchis.includes(a)).sort()
      let other = this.archis.filter(a => !mainArchis.includes(a)).sort()
      let all = main.map(archi => Object({value: archi, option: this.$options.filters.formatArchiRadio(archi)}))
        .concat([{value: '_separator_', option: '───────────'}])
        .concat(other.map(archi => Object({value: archi, option: this.$options.filters.formatArchiRadio(archi)})))
      return all
    },
    sortedArchis4Site () {
      // let's sort the archis with (a8, m3 & wsn) on top
      let mainArchis = ['a8:at86rf231', 'm3:at86rf231', 'wsn430:cc1101']
      let main = this.archis4Site.filter(a => mainArchis.includes(a)).sort()
      let other = this.archis4Site.filter(a => !mainArchis.includes(a)).sort()
      let all = main.map(archi => Object({value: archi, option: this.$options.filters.formatArchiRadio(archi)}))
        .concat([{value: '_separator_', option: '───────────'}])
        .concat(other.map(archi => Object({value: archi, option: this.$options.filters.formatArchiRadio(archi)})))
      return all
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
    qtyAvailable () {
      // qty available for (site, archi) = total qty - qty already selected
      return this.nodes.filter((node) => node.site === this.filterSite &&
                                         node.archi === this.filterArchi).length -
             this.selectedProps.filter((p) => p.prop.properties.site === this.filterSite &&
                                              p.prop.properties.archi === this.filterArchi)
               .reduce((a, e) => a + parseInt(e.prop.nbnodes), 0)
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
    firmwareAssociations () {
      let fwasso
      if (this.mode === 'byprop') {
        fwasso = this.selectedProps.filter(prop => prop.firmware !== undefined && prop.firmware.name !== undefined)
          .map(prop => ({
            nodes: [prop.prop.alias],
            firmwarename: prop.firmware.name,
          }))
      } else {
        fwasso = this.selectedNodeGroups.filter(group => group.firmware !== undefined && group.firmware.name !== undefined)
          .map(group => ({
            nodes: group.nodes.map(node => node.network_address),
            firmwarename: group.firmware.name,
          }))
      }
      if (fwasso.length === 0) return null
      return fwasso
    },
    monitoringAssociations () {
      let monasso
      if (this.mode === 'byprop') {
        monasso = this.selectedProps.filter(prop => prop.monitoring !== undefined)
          .map(prop => ({
            nodes: [prop.prop.alias],
            profilename: prop.monitoring,
          }))
      } else {
        monasso = this.selectedNodeGroups.filter(group => group.monitoring !== undefined)
          .map(group => ({
            nodes: group.nodes.map(node => node.network_address),
            profilename: group.monitoring,
          }))
      }
      if (monasso.length === 0) return null
      return monasso
    },
  },

  methods: {
    hideTooltip () {
      $('.tooltip[role=tooltip]').remove()
    },
    refreshNodes () {
      iotlab.getNodes().then(data => { this.nodes = data.map(node => newNode(node)) })
        .catch(err => {
          this.$notify({text: 'An error occured while fetching data', type: 'error'})
          throw err
        })
      iotlab.getNodesIds().then(data => { this.nodes_ids = data })
        .catch(err => {
          this.$notify({text: 'An error occured while fetching data', type: 'error'})
          throw err
        })
    },
    startScheduled () {
      this.$forceUpdate()
      this.$nextTick(() => {
        $('#datetimepicker1').datetimepicker('show')
      })
    },
    setMode (mode) {
      this.mode = mode
      this.filterArchi = 'all'
      this.filterSite = 'all'
    },
    clearAllNodes () {
      this.selectedNodeGroups = []
      this.selectedProps = []
      this.firmwares = {}
      this.firmwaresHashes = {}
    },
    avoidCollision (resourceName) {
      // Names should be unique in firmwareassociations
      // Let's rename any uploaded firmware colliding with a firmware from the resources
      if (resourceName in this.firmwares) {
        let name = `${this.firmwaresHashes[resourceName] || '0'}_${resourceName}`
        this.firmwares[name] = this.firmwares[resourceName]
        this.firmwaresHashes[name] = this.firmwaresHashes[resourceName]
        delete this.firmwares[resourceName]
        delete this.firmwaresHashes[resourceName]
        for (let group of this.selectedNodeGroups) {
          if (group.firmware && group.firmware.name === resourceName) {
            group.firmware.name = name
          }
        }
        for (let group of this.selectedProps) {
          if (group.firmware && group.firmware.name === resourceName) {
            group.firmware.name = name
          }
        }
      }
    },
    loadFirmwareFile (ref, index) {
      this.$notify({text: 'Uploading file...', type: 'info', duration: -1})

      this.firmwareFiles[index] = this.$refs[ref][0].files[0]
      // console.log(this.$refs[ref][0])
      var group = (this.mode === 'byprop') ? this.selectedProps[index] : this.selectedNodeGroups[index]
      var reader = new FileReader()

      reader.onload = (function (file, vm, index, allowedFirmwares) {
        return async function (e) {
          vm.$notify({clean: true}) // close pending notification

          var res = await iotlab.checkFirmware(e.target.result)
          vm.$notify({text: `firmware format ${res.format}`, type: res.format === 'unknown' ? 'error' : 'info'})
          if (allowedFirmwares.includes(res.format)) {
            file.bin = e.target.result
            vm.firmwareFiles[index] = file
            let name = file.name
            let hash = md5Hash(file.bin)
            // check if a firmware with the same name is already associated
            if (vm.selectedNodeGroups.find(group => group.firmware && group.firmware.name === file.name) ||
              vm.selectedProps.find(group => group.firmware && group.firmware.name === file.name)
            ) {
              // if hash already exist and is the same then it's the same firmware, no need to rename
              if (hash !== vm.firmwaresHashes[file.name]) {
                name = `${hash}_${file.name}` // rename firmware to avoid collision
              }
            }
            vm.firmwares[name] = file.bin
            vm.firmwaresHashes[name] = hash
            if (vm.mode === 'byprop') {
              vm.selectedProps[index].firmware = {name: name}
            } else {
              vm.selectedNodeGroups[index].firmware = {name: name}
            }
          } else {
            vm.$notify({text: `Wrong format for this type of nodes.\n(expected ${allowedFirmwares})`, type: 'error'})
          }
        }
      })(this.firmwareFiles[index], this, index, group.allowedFirmwareTypes)

      reader.readAsDataURL(this.firmwareFiles[index])
    },
    previewScriptFile () {
      this.scriptFile = this.$refs.scriptFile.files[0]
    },
    testArchi () {
      if (!this.archis4Site.includes(this.filterArchi)) this.filterArchi = this.archis4Site[0]
    },
    addNodes () {
      for (let nodes of Object.values(groupBy(this.currentNodes, 'archi'))) {
        this.selectedNodeGroups.push(newNodeGroup(nodes)) // make one group of nodes per archi
      }
      this.currentNodes = []
    },
    removeNode (node) {
      this.selectedNodeGroups = this.selectedNodeGroups.map(function (g) {
        g.nodes = g.nodes.filter(n => n.network_address !== node.network_address)
        return g
      }).filter(g => g.nodes.length !== 0) // remove empty node groups
    },
    addProps () {
      if (this.filterArchi === 'all') {
        this.$notify({text: `Select an architecture first`, type: 'warning'})
        return
      }
      if (this.filterSite === 'all') {
        this.$notify({text: `Select a site first`, type: 'warning'})
        return
      }
      if (this.qty === 0 && this.qtyAvailable >= 1) {
        this.$notify({text: `Select a quantity first`, type: 'warning'})
        return
      }
      if (this.qtyAvailable < 1) {
        this.$notify({text: `No nodes available with such properties`, type: 'error'})
        return
      }
      this.selectedProps.push(newPropGroup({
        alias: this.selectedProps.length + 1, // XXXX collision ??
        nbnodes: this.qty,
        properties: {
          archi: this.filterArchi,
          site: this.filterSite,
          mobile: this.propMobile,
        },
      }))
      this.$nextTick(function () {
        this.qty = Math.min(this.qty, this.qtyAvailable)
      })
    },
    removeProp (i) {
      this.selectedProps.splice(i, 1)
    },

    expandNodeIds () {
      var node
      var nodeGroup = []
      if (!this.filterSite || this.filterSite === 'all') {
        this.$notify({text: `Select a site first`, type: 'warning'})
        return
      }
      if (!this.filterArchi || this.filterArchi === 'all') {
        this.$notify({text: `Select an architecture first`, type: 'warning'})
        return
      }
      if (!this.nodeIds) {
        this.$notify({text: `Select node ids first`, type: 'warning'})
        return
      }
      for (var i of expandIds(this.nodeIds)) {
        node = this.nodes.find(n => n.network_address === `${extractArchi(this.filterArchi)}-${i}.${this.filterSite}.iot-lab.info`)
        if (node) {
          if (!this.selectedNodes.includes(node)) nodeGroup.push(node)
        } else {
          this.$notify({text: `Invalid node id ${i}`, type: 'error'})
        }
      }
      if (nodeGroup.length > 0) {
        this.currentNodes = nodeGroup
        this.addNodes()
      }
    },

    async submitExperiment () {
      if (!this.nameValidation) {
        this.$notify({text: 'Name is invalid', type: 'warning'})
        return
      }
      if (this.nodeCount === 0) {
        this.$notify({text: 'Select nodes first', type: 'warning'})
        return
      }
      this.$notify({text: 'Submission in progress', type: 'info', duration: -1})
      try {
        let newExp = await iotlab.submitExperiment({
          type: (this.mode === 'byprop') ? 'alias' : 'physical',
          name: this.name,
          duration: this.duration * this.durationMultiplier,
          reservation: this.scheduleEpoch,
          nodes: (this.mode === 'byprop') ? this.selectedProps.map(p => p.prop) : this.selectedNodes.map(node => node.network_address),
          profileassociations: this.monitoringAssociations,
          firmwareassociations: this.firmwareAssociations,
          firmwares: this.firmwares,
        })
        await sleep(200)
        this.$notify({clean: true})
        this.$notify({text: `Experiment ${newExp.id} submitted`, type: 'success', duration: 6000})
        await sleep(200)
        this.$router.push({name: 'dashboard'})
      } catch (err) {
        this.$notify({clean: true})
        this.$notify({text: err.message + '<br><br>' + err.response.data.message, type: 'error', duration: 10000})
        throw err
      }
    },
  },
}
</script>

<style>
.badge-tag.badge {
  font-weight: normal;
  font-size: 100%;
  margin: 1px 2px;
}
.badge-even {
  /*filter: brightness(0.8);*/
  filter: saturate(0.35);
  /*filter: grayscale(0.8);*/
  /*filter: sepia(0.6);*/
  /*filter: hue-rotate(25deg);*/
  /*filter: invert();*/
}
.tag-remove:hover {
  color: black;
}
.badge-light.tag-remove:hover, .badge-light .tag-remove:hover {
  color: var(--danger);
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

/* Hide "press enter to select" during nodes selection */
.multiselect-nodes .multiselect__option--highlight:hover:after {
  opacity: 0;
}
</style>
