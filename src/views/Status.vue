<template>
<div class="container mt-3">
  <h4><i class="fa fa-fw fa-hourglass-half" aria-hidden="true"></i> Running experiments ({{runningExp.length}})</h4>
  <running-experiments :exp-list="runningExp"></running-experiments>
  <div class="float-right mt-1 mb-4" v-if="showData === 'properties'">
    <div class="dropdown d-inline-block ">
      <button class="btn btn-light mr-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-fw fa-download"></i> Download</button>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="#" @click.prevent="downloadJson">IoT-LAB nodes properties <span class="badge badge-pill badge-info">JSON</span></a>
        <a class="dropdown-item" href="#" @click.prevent="downloadCsv">IoT-LAB nodes  properties <span class="badge badge-pill badge-info">CSV</span></a>
      </div>
    </div>
    <button v-if="isAdmin" class="btn btn-warning" @click="updateNodesProperties"><i class="fa fa-lock"></i> Update properties</button>
  </div>
  <p class="lead mb-0">Sites</p>
  <p class="mb-2" v-if="sites">
    <span class="badge badge-pill mr-1 cursor" :class="{'badge-primary': currentSite === 'all', 'badge-secondary': currentSite !== 'all'}" @click="currentSite = 'all'">{{sites.length}} sites</span>
    <span v-for="site in sites" class="badge badge-pill mr-1 cursor" :class="{'badge-primary': currentSite === site, 'badge-secondary': currentSite !== site}"
    @click="currentSite = site">{{site.site}}</span>
  </p>
  <p class="lead mb-0">Architectures</p>
  <p class="mb-2" v-if="sites">
    <span class="badge badge-pill mr-1 cursor" :class="{'badge-primary': currentArchi === 'all', 'badge-secondary': currentArchi !== 'all'}" @click="currentArchi = 'all'">{{archis.length}} archis</span>
    <span v-for="archi in archis" class="badge badge-pill mr-1 cursor" :class="{'badge-primary': currentArchi === archi, 'badge-secondary': currentArchi !== archi}"
    @click="currentArchi = archi">{{archi | formatArchi}}
      <span class="font-weight-normal" v-if="$options.filters.formatRadio(archi)">({{archi | formatRadio}})</span>
    </span>
  </p>
  <p class="lead mb-0">Nodes</p>
  <p class="mb-2" v-if="nodes">
    <span class="badge badge-pill mr-1 cursor" :class="(nodeFilter === null) ? 'badge-primary' : 'badge-secondary'" @click="nodeFilter = null">{{getNodes().length}} nodes</span>
    <span class="badge badge-pill mr-1 cursor" :class="(nodeFilter === nodeFilters.alive) ? 'badge-primary' : 'badge-success'" @click="nodeFilter = nodeFilters.alive" v-if="getNodes(['Alive']).length">{{getNodes(['Alive']).length}} available</span>
    <span class="badge badge-pill mr-1 cursor" :class="(nodeFilter === nodeFilters.busy) ? 'badge-primary' : 'badge-warning'" @click="nodeFilter = nodeFilters.busy" v-if="getNodes(['Busy']).length">{{getNodes(['Busy']).length}} busy</span>
    <span class="badge badge-pill mr-1 cursor" :class="(nodeFilter === nodeFilters.unavailable) ? 'badge-primary' : 'badge-danger'" @click="nodeFilter = nodeFilters.unavailable" v-if="getNodes(['Absent','Suspected']).length">{{getNodes(['Absent','Suspected']).length}} unavailable</span>
    <span class="badge badge-pill mr-1 cursor" :class="(nodeFilter === nodeFilters.dead) ? 'badge-primary' : 'badge-dark'" @click="nodeFilter = nodeFilters.dead" v-if="getNodes(['Dead']).length">{{getNodes(['Dead']).length}} dead</span>
  </p>
  <p v-else>
    <i class="fa fa-spinner fa-spin fa-fw"></i>
  </p>
  <div class="col-md-4 float-right">
    <input type="text" class="form-control mb-3" placeholder="Search by hostname or uid" v-model="search">
  </div>
  <ul class="nav nav-tabs">
    <li class="nav-item" v-tooltip:top="'Nodes properties'">
      <router-link class="nav-link" :to="{name: 'status'}" :class="{active: showData === 'properties'}" role="tab"><i class="fa fa-fw fa-share-alt"></i>Nodes properties</router-link>
    </li>
    <li class="nav-item" v-tooltip:top="'Testbed Activity'">
      <router-link class="nav-link" :to="{name: 'activity'}" :class="{active: showData === 'activity'}" role="tab"><i class="fa fa-fw fa-calendar"></i>Testbed Activity</router-link>
    </li>
  </ul>
  <div class="row">
    <div class="col-md-8">
      <label class="mt-2 mr-3 text-muted font-italic">Showing {{filteredNodes.length}} nodes</label>
      <a href="" @click.prevent="showMap = !showMap">
        <i class="fa fa-map-o fa-fw fa-lg" aria-hidden="true"></i> view on site map <i class="fa fa-caret-down" aria-hidden="true"></i>
      </a>
    </div>
  </div>
  <map-3d :nodes="filteredNodes" :shows="showMap" v-show="showMap" @selectSite="(site) => currentSite = sites.find(s => s.site === site)"></map-3d>
  <table class="table table-striped table-sm" v-if="nodes.length && showData === 'properties'">
    <thead>
      <tr>
        <th class="cursor" title="sort by hostname" @click="sortBy(node => node.network_address)">Node hostname</th>
        <th class="cursor text-center" title="sort by state" @click="sortBy(node => node.state)">State</th>
        <th class="cursor" title="sort by archi" @click="sortBy(node => node.archi)">Archi <span class="text-muted font-weight-normal">(radio)</span></th>
        <th class="cursor" title="sort by site" @click="sortBy(node => node.site)">Site</th>
        <th class="cursor text-center" title="sort by power control" @click="sortBy(node => node.power_control, reverse = true)">Power control</th>
        <th class="cursor text-center" title="sort by power consumption" @click="sortBy(node => node.power_consumption, reverse = true)">Power consumption</th>
        <th class="cursor text-center" title="sort by radio sniffing" @click="sortBy(node => node.radio_sniffing, reverse = true)">Radio sniffing</th>
        <th class="cursor" title="sort by uid" @click="sortBy(node => node.uid)">UID</th>
        <th class="cursor" title="sort by X" @click="sortBy(node => node.x, reverse = true)">X</th>
        <th class="cursor" title="sort by Y" @click="sortBy(node => node.y, reverse = true)">Y</th>
        <th class="cursor" title="sort by Z" @click="sortBy(node => node.z, reverse = true)">Z</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="node in filteredNodes">
        <td>{{node.network_address}}</td>
        <td class="text-center">
          <span v-if="node.state !== 'Busy'" class="badge badge-state" :class="node.state | stateBadgeClass">{{node.state}}</span>
          <router-link v-else-if="isAdmin" :to="{name: 'experimentDetails', params: { id: getBusyJob(node).id }}" class="badge badge-state" :class="node.state | stateBadgeClass" :title="getBusyTitle(node)">{{node.state}}</router-link>
          <span v-else class="badge badge-state cursor-help" :class="node.state | stateBadgeClass" :title="getBusyTitle(node)">{{node.state}}</span>
        </td>
        <td class="text-capitalize-first">{{node.archi | formatArchi}}
          <span class="text-muted" v-if="$options.filters.formatRadio(node.archi)">({{node.archi | formatRadio}})</span>
        </td>
        <td class="text-capitalize">{{node.site}}</td>
        <td class="text-center" v-if="node.power_control"><i class="fa fa-power-off"></i></td>
        <td v-else></td>
        <td class="text-center" v-if="node.power_consumption"><i class="fa fa-battery-three-quarters"></i></td>
        <td v-else></td>
        <td class="text-center" v-if="node.radio_sniffing"><i class="fa fa-wifi"></i></td>
        <td v-else></td>
        <td>{{node.uid}}</td>
        <td>{{node.x}}</td>
        <td>{{node.y}}</td>
        <td>{{node.z}}</td>
      </tr>
    </tbody>
  </table>
  <div class="card card-body bg-light text-center" v-if="showData === 'activity' && currentSite === 'all'">
    <p class="lead mt-2">
      <i class="fa fa-fw fa-lg fa-calendar" aria-hidden="true"></i>
      Select a site to view its Gantt chart
    </p>
    <ul class="nav nav-pills justify-content-center">
      <li class="nav-item" v-for="site in sites">
        <a class="nav-link text-capitalize bg-light" href="#" @click="currentSite = site">{{site.site}}</a>
      </li>
    </ul>
  </div>
  <drawgantt :nodes="filteredNodes" :sites="sites" v-if="showData === 'activity' && currentSite !== 'all'"/>
</div> <!-- container -->

</template>

<script>
import Map3d from '@/components/Map3d'
import RunningExperiments from '@/components/RunningExperiments'
import Drawgantt from '@/components/Drawgantt'
import { iotlab } from '@/rest'
import { auth } from '@/auth'
import { downloadObjectAsJson, downloadObjectAsCsv } from '@/utils'

export default {
  name: 'Status',

  components: {
    Map3d,
    RunningExperiments,
    Drawgantt,
  },

  props: {
    showData: {
      type: String,
      default: () => 'properties',
      validator: val => ['activity', 'properties'].includes(val),
    },
  },

  data () {
    return {
      isAdmin: auth.isAdmin,
      isLoggedIn: auth.loggedIn,
      sites: [],
      nodes: [],
      runningExp: [],
      currentSite: 'all',
      currentArchi: 'all',
      nodeFilter: null,
      nodeFilters: {
        alive: node => node.state === 'Alive',
        busy: node => node.state === 'Busy',
        unavailable: node => ['Absent', 'Suspected'].includes(node.state),
        dead: node => node.state === 'Dead',
        mobile: node => node.mobile,
      },
      search: '',
      showMap: false,
    }
  },

  created () {
    iotlab.getSitesDetails().then(data => { this.sites = data.sort((a, b) => a.site.localeCompare(b.site)) }).catch(err => {
      this.$notify({text: err.response.data.message || 'Failed to fetch sites details', type: 'error'})
    })
    iotlab.getNodes().then(data => { this.nodes = data.sort((a, b) => this.stringCompare(a.network_address, b.network_address)) }).catch(err => {
      this.$notify({text: err.response.data.message || 'Failed to fetch nodes', type: 'error'})
    })
    if (auth.loggedIn) {
      iotlab.getRunningExperiments().then(data => { this.runningExp = data }).catch(err => {
        this.$notify({text: err.response.data.message || 'Failed to fetch running experiments', type: 'error'})
      })
    }
  },

  computed: {
    archis () {
      let archis
      if (this.currentSite === 'all') {
        archis = Array.from(this.sites.reduce((acc, site) => {
          site.archis.map(archi => acc.add(archi.archi))
          return acc
        }, new Set()))
      } else {
        archis = this.sites.find(site => site.site === this.currentSite.site).archis.map(archi => archi.archi)
      }
      return archis.sort((a, b) => a.localeCompare(b))
    },
    filteredNodes () {
      let nodes = this.getNodes()
      if (this.search) nodes = nodes.filter(node => node.network_address.includes(this.search) || node.uid.includes(this.search))
      if (this.nodeFilter) nodes = nodes.filter(this.nodeFilter)
      if (this.currentSite !== 'all') nodes = nodes.filter(node => node.site === this.currentSite.site)
      if (this.currentArchi !== 'all') nodes = nodes.filter(node => node.archi === this.currentArchi)
      return nodes
    },
  },

  methods: {
    async downloadJson () {
      let nodes = await iotlab.getNodes()
      downloadObjectAsJson(nodes, 'iotlab-nodes')
    },

    async downloadCsv () {
      let nodes = await iotlab.getNodes()
      downloadObjectAsCsv(nodes, 'iotlab-nodes', {fields: Object.keys(nodes[0]).sort()})
    },

    stringCompare (a, b) {
      // eslint-disable-next-line no-useless-escape
      let networkAddress = /^([a-z0-9-_]+\.){2}iot-lab.info$/
      if (networkAddress.test(a) && networkAddress.test(b)) {
        // m3-1.grenoble.iot-lab.info
        let chuncksa = a.split('.')
        let chuncksb = b.split('.')
        // site comparator
        let site = chuncksa[1].localeCompare(chuncksb[1])
        if (site !== 0) return site
        // archi comparator (ex: m3-1, arduino-zero-1)
        let archia = chuncksa[0].slice(0, chuncksa[0].lastIndexOf('-') + 1)
        let archib = chuncksb[0].slice(0, chuncksb[0].lastIndexOf('-') + 1)
        let archi = archia.localeCompare(archib)
        if (archi !== 0) return archi
        // id comparator
        let ida = chuncksa[0].split('-').pop()
        let idb = chuncksb[0].split('-').pop()
        return ida.localeCompare(idb, undefined, {numeric: true})
      }
      // standard string compare
      return a.localeCompare(b)
    },

    getNodes (stateList = null) {
      let nodes = this.nodes
      if (this.currentSite !== 'all') nodes = nodes.filter(node => node.site === this.currentSite.site)
      if (this.currentArchi !== 'all') nodes = nodes.filter(node => node.archi === this.currentArchi)
      if (stateList) {
        return nodes.filter(node => stateList.includes(node.state))
      } else {
        return nodes
      }
    },

    sortBy (func, reverse = false) {
      this.nodes = this.nodes.sort((a, b) => {
        if (typeof func(a) === 'string' || typeof func(b) === 'string') return this.stringCompare(func(a), func(b))
        if (typeof func(a) === 'number' || typeof func(b) === 'number') return func(a) - func(b)
        console.log('Unhandled sort criteria', a.network_address, func(a), b.network_address, func(b))
      })
      if (reverse) this.nodes.reverse()
    },

    async updateNodesProperties () {
      if (!confirm('Update all nodes properties. Are you sure?')) return
      try {
        await iotlab.updateNodesProperties()
        await iotlab.updateNodesPropertiesOldApi()
        this.$notify({text: 'Nodes properties updated', type: 'success'})
      } catch (err) {
        this.$notify({text: 'An error occured', type: 'error'})
      }
    },

    getBusyJob (node) {
      return this.runningExp.find(xp => xp.nodes.includes(node.network_address))
    },

    getBusyTitle (node) {
      const job = this.getBusyJob(node)
      if (job === undefined) return ''
      return `job #${job.id}
user ${job.user}
ending ${this.$options.filters.formatDateTime(job.start_date, job.submitted_duration)}
remaining ${this.$options.filters.humanizeDuration(job.submitted_duration - job.effective_duration)}`
    },

  },

}
</script>

<style scoped>
.badge {
  font-size: 80%;
}
</style>
