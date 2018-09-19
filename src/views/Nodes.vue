<template>
<div class="container mt-3">
  <h3><i class="fa fa-fw fa-hourglass-half" aria-hidden="true"></i> Running experiments</h3>
  <running-experiments :exp-list="runningExp"></running-experiments>
  <div class="float-right mt-1 mb-4">
    <div class="dropdown d-inline-block ">
      <button class="btn btn-light mr-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-fw fa-download"></i> Download</button>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="#" @click.prevent="downloadJson">IoT-LAB nodes properties <span class="badge badge-pill badge-info">JSON</span></a>
        <a class="dropdown-item" href="#" @click.prevent="downloadCsv">IoT-LAB nodes  properties <span class="badge badge-pill badge-info">CSV</span></a>
      </div>
    </div>
    <button v-if="isAdmin" class="btn btn-warning" @click="updateNodesProperties"><i class="fa fa-lock"></i> Update properties</button>
  </div>
  <h3><i class="fa fa-fw fa-share-alt" aria-hidden="true"></i> Nodes properties</h3>
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
    <span class="badge badge-pill mr-1 cursor" :class="(nodeFilter === nodeFilters.mobile) ? 'badge-primary' : 'badge-info'" @click="nodeFilter = nodeFilters.mobile" v-if="getNodes().filter(node => node.mobile).length">{{getNodes().filter(node => node.mobile).length}} mobile</span>
  </p>
  <p v-else>
    <i class="fa fa-spinner fa-spin fa-fw"></i>
  </p>
  <div class="row">
    <div class="col-md-8">
      <label class="mt-2 mr-3 text-muted font-italic">Showing {{filteredNodes.length}} nodes</label>
      <a href="" @click.prevent="showMap = !showMap">
        <i class="fa fa-map-o fa-fw fa-lg" aria-hidden="true"></i> view on site map <i class="fa fa-caret-down" aria-hidden="true"></i>
      </a>
    </div>
    <div class="col-md-4">
      <input type="text" class="form-control mb-3" placeholder="Search by hostname or uid" v-model="search">
    </div>
  </div>
  <map-3d :nodes="filteredNodes" :shows="showMap" v-show="showMap" @selectSite="(site) => currentSite = sites.find(s => s.site === site)"></map-3d>
  <table class="table table-striped table-sm" v-if="nodes.length">
    <thead>
      <tr>
        <th class="cursor" title="sort by hostname" @click="sortBy(node => nodeSortByHostname(node))">Node hostname</th>
        <th class="cursor text-center" title="sort by state" @click="sortBy(node => node.state)">State</th>
        <th class="cursor" title="sort by archi" @click="sortBy(node => node.archi)">Archi <span class="text-muted font-weight-normal">(radio)</span></th>
        <th class="cursor" title="sort by site" @click="sortBy(node => node.site)">Site</th>
        <th class="cursor" title="sort by mobility" @click="sortBy(node => node.mobility_type, reverse = true)">Mobility</th>
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
        <td v-if="node.mobile">Yes <span class="text-muted">({{node.mobility_type}})</span></td>
        <td v-else></td>
        <td>{{node.uid}}</td>
        <td>{{node.x}}</td>
        <td>{{node.y}}</td>
        <td>{{node.z}}</td>
      </tr>
    </tbody>
  </table>

</div> <!-- container -->

</template>

<script>
import Map3d from '@/components/Map3d'
import RunningExperiments from '@/components/RunningExperiments'
import { iotlab } from '@/rest'
import { auth } from '@/auth'
import { downloadObjectAsJson, downloadObjectAsCsv } from '@/utils'

export default {
  name: 'Nodes',

  components: {
    Map3d,
    RunningExperiments,
  },

  data () {
    return {
      isAdmin: auth.isAdmin,
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
    iotlab.getNodes().then(data => { this.nodes = data }).catch(err => {
      this.$notify({text: err.response.data.message || 'Failed to fetch nodes', type: 'error'})
    })
    iotlab.getRunningExperiments().then(data => { this.runningExp = data }).catch(err => {
      this.$notify({text: err.response.data.message || 'Failed to fetch running experiments', type: 'error'})
    })
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
        if (func(a) === func(b)) return this.nodeSortByHostname(a).localeCompare(this.nodeSortByHostname(b))
        if (typeof func(a) === 'string' || typeof func(b) === 'string') return func(a).localeCompare(func(b))
        if (typeof func(a) === 'number' || typeof func(b) === 'number') return func(a) - func(b)
        console.log('Unhandled sort criteria', a.network_address, func(a), b.network_address, func(b))
      })
      if (reverse) this.nodes.reverse()
    },

    // Allow sorting of hostnames by domain then by servername
    // the trick is to append the servername after the domain (e.g 'a8-1.grenoble.iot-lab.info' -> 'grenoble.iot-lab.info.a8-1')
    // then it's possible to simply sort the strings
    nodeSortByHostname (node) {
      let chuncks = node.network_address.split('.')
      chuncks.push(chuncks.shift()) // or we could use chunks.reverse()
      return chuncks.join('.')
    },

    async updateNodesProperties () {
      if (!confirm('Update node properties can take a while. Are you sure?')) return
      try {
        await iotlab.updateNodesProperties()
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
