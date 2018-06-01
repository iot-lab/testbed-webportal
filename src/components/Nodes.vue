<template>
<div class="container mt-3">
  <p class="float-right mt-2 mb-4" v-if="isAdmin">
    <span class="d-block h5 text-right"><i class="fa fa-lock"></i> Admin</span>
    <a class="btn btn-warning" href="" @click.prevent="updateNodesProperties">Update nodes</a>
  </p>
  
  <h2>IoT-LAB nodes <button class="btn btn-light text-dark ml-2" @click="downloadJson" v-tooltip:right="'Download all nodes (JSON)'"><i class="fa fa-download"></i></button></h2>
  <p class="lead mb-0">Sites</p>
  <p class="mb-2" v-if="sites">
    <span class="badge badge-pill" :class="{'badge-primary': currentSite === 'all', 'badge-secondary': currentSite !== 'all'}" @click="currentSite = 'all'" style="cursor: pointer">{{sites.length}} sites</span>
    <span v-for="site in sites" class="badge badge-pill" :class="{'badge-primary': currentSite === site, 'badge-secondary': currentSite !== site}" style="margin-right: 4px; cursor: pointer"
    @click="currentSite = site">{{site.site}}</span>
  </p>
  <p class="lead mb-0">Architectures</p>
  <p class="mb-2" v-if="sites">
    <span class="badge badge-pill" :class="{'badge-primary': currentArchi === 'all', 'badge-secondary': currentArchi !== 'all'}" @click="currentArchi = 'all'" style="cursor: pointer">{{archis.length}} archis</span>
    <span v-for="archi in archis" class="badge badge-pill" :class="{'badge-primary': currentArchi === archi, 'badge-secondary': currentArchi !== archi}" style="margin-right: 4px; cursor: pointer"
    @click="currentArchi = archi">{{archi | formatArchi}}
      <span class="font-weight-normal" v-if="$options.filters.formatRadio(archi)">({{archi | formatRadio}})</span>
    </span>
  </p>
  <p class="lead mb-0">Nodes</p>
  <p class="mb-2" v-if="nodes">
    <span class="cursor badge badge-pill" :class="(nodeFilter === null) ? 'badge-primary' : 'badge-secondary'" @click="nodeFilter = null">{{getNodes().length}} nodes</span>
    <span class="cursor badge badge-pill" :class="(nodeFilter === nodeFilters.alive) ? 'badge-primary' : 'badge-success'" @click="nodeFilter = nodeFilters.alive" v-if="getNodes(['Alive']).length">available {{getNodes(['Alive']).length}}</span>
    <span class="cursor badge badge-pill" :class="(nodeFilter === nodeFilters.busy) ? 'badge-primary' : 'badge-warning'" @click="nodeFilter = nodeFilters.busy" v-if="getNodes(['Busy']).length">busy {{getNodes(['Busy']).length}}</span> 
    <span class="cursor badge badge-pill" :class="(nodeFilter === nodeFilters.unavailable) ? 'badge-primary' : 'badge-danger'" @click="nodeFilter = nodeFilters.unavailable" v-if="getNodes(['Absent','Suspected']).length">unavailable {{getNodes(['Absent','Suspected']).length}}</span>
    <span class="cursor badge badge-pill" :class="(nodeFilter === nodeFilters.dead) ? 'badge-primary' : 'badge-dark'" @click="nodeFilter = nodeFilters.dead" v-if="getNodes(['Dead']).length">dead {{getNodes(['Dead']).length}}</span>
    <span class="cursor badge badge-pill" :class="(nodeFilter === nodeFilters.mobile) ? 'badge-primary' : 'badge-info'" @click="nodeFilter = nodeFilters.mobile" v-if="getNodes().filter(node => node.mobile).length">mobile {{getNodes().filter(node => node.mobile).length}}</span>
  </p>
  <p v-else>
    <i class="fa fa-spinner fa-spin fa-fw"></i>
  </p>
  <div class="row">
    <div class="col-md-8">
      <label class="mt-2 text-muted font-italic">Showing {{filteredNodes.length}} nodes</label>
    </div>
    <div class="col-md-4">
      <input type="text" class="form-control mb-3" placeholder="Search hostname or uid" v-model="search">
    </div>
  </div>
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
        <td class="text-center"><span class="badge badge-state" :class="node.state | stateBadgeClass">{{node.state}}</span></td>
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
import { iotlab } from '@/rest'
import { auth } from '@/auth'
import { downloadObjectAsJson } from '@/utils'
import json2csv from 'json2csv'

export default {
  name: 'Nodes',

  data () {
    return {
      isAdmin: auth.isAdmin,
      sites: [],
      nodes: [],
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
    }
  },

  created () {
    iotlab.getSitesDetails().then(data => { this.sites = data.sort((a, b) => a.site.localeCompare(b.site)) })
    iotlab.getNodes().then(data => { this.nodes = data })
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
      console.log(json2csv.parse(await iotlab.getNodes()))
      downloadObjectAsJson(await iotlab.getNodes(), 'iotlab-nodes')
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
      if (!confirm('This can take a while. Are you sure?')) return
      try {
        await iotlab.updateNodesProperties()
        this.$notify({text: 'Nodes properties updated', type: 'success'})
      } catch (err) {
        this.$notify({text: 'An error occured', type: 'error'})
      }
    },

  },

}
</script>

<style scoped>
.text-capitalize-first:first-letter {
  text-transform: uppercase;
}
.badge {
  font-size: 80%;
}
</style>
