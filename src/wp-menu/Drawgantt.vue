<template>
  <div ref="container" class="container-fluid mt-3" id="container_drawgantt">
    <p class="lead mb-0">Sites</p>
    <p class="mb-2" v-if="sites">
      <span class="badge badge-pill mr-1 cursor" :class="{'badge-primary': currentSite === 'all', 'badge-secondary': currentSite !== 'all'}" @click="currentSite = 'all'">{{sites.length}} sites</span>
      <span v-bind:key="site.site" v-for="site in sites" class="badge badge-pill mr-1 cursor" :class="{'badge-primary': currentSite === site, 'badge-secondary': currentSite !== site}"
      @click="currentSite = site">{{site.site}}</span>
    </p>
    <p class="lead mb-0">Architectures</p>
    <p class="mb-2" v-if="sites">
      <span class="badge badge-pill mr-1 cursor" :class="{'badge-primary': currentArchi === 'all', 'badge-secondary': currentArchi !== 'all'}" @click="currentArchi = 'all'">{{archis.length}} archis</span>
      <span v-bind:key="archi.name"  v-for="archi in archis" class="badge badge-pill mr-1 cursor" :class="{'badge-primary': currentArchi === archi, 'badge-secondary': currentArchi !== archi}"
      @click="currentArchi = archi">{{archi | formatArchi}}
        <span class="font-weight-normal" v-if="$options.filters.formatRadio(archi)">({{archi | formatRadio}})</span>
      </span>
    </p>
    <p class="lead mb-0">Nodes</p>
    <p class="mb-2" v-if="nodes">
      <span class="badge badge-pill mr-1 cursor" :class="(nodeFilter === nodeFilters.all) ? 'badge-primary' : 'badge-secondary'" @click="nodeFilter = nodeFilters.all">{{getNodes().length}} nodes</span>
      <span class="badge badge-pill mr-1 cursor" :class="(nodeFilter === nodeFilters.alive) ? 'badge-primary' : 'badge-success'" @click="nodeFilter = nodeFilters.alive" v-if="getNodes(['Alive']).length">{{getNodes(['Alive']).length}} available</span>
      <span class="badge badge-pill mr-1 cursor" :class="(nodeFilter === nodeFilters.busy) ? 'badge-primary' : 'badge-warning'" @click="nodeFilter = nodeFilters.busy" v-if="getNodes(['Busy']).length">{{getNodes(['Busy']).length}} busy</span>
      <span class="badge badge-pill mr-1 cursor" :class="(nodeFilter === nodeFilters.unavailable) ? 'badge-primary' : 'badge-danger'" @click="nodeFilter = nodeFilters.unavailable" v-if="getNodes(['Absent','Suspected']).length">{{getNodes(['Absent','Suspected']).length}} unavailable</span>
      <span class="badge badge-pill mr-1 cursor" :class="(nodeFilter === nodeFilters.dead) ? 'badge-primary' : 'badge-dark'" @click="nodeFilter = nodeFilters.dead" v-if="getNodes(['Dead']).length">{{getNodes(['Dead']).length}} dead</span>
      <span class="badge badge-pill mr-1 cursor" :class="(nodeFilter === nodeFilters.mobile) ? 'badge-primary' : 'badge-info'" @click="nodeFilter = nodeFilters.mobile" v-if="getNodes().filter(node => node.mobile).length">{{getNodes().filter(node => node.mobile).length}} mobile</span>
    </p>
    <div class="mb-2 center col-5">
      <multiselect v-model="timezone" placeholder="select timezone"
                :options="tzNames"
                :allow-empty="false"
                style="z-index: 10"
                id="timezoneSelect"/>
    </div>
    <div class="text-center time-header">
      <div>
        <button class="btn mr-2" type="button" v-on:click="shift(-S_PER_WEEK)">&lt;1w</button>
        <button class="btn mr-2" type="button" v-on:click="shift(-S_PER_DAY)">&lt;1d</button>
        <button class="btn mr-2" type="button" v-on:click="shift(-6 * S_PER_HOUR)">&lt;6h</button>
        <button class="btn mr-2" type="button" v-on:click="shift(-S_PER_HOUR)">&lt;1h</button>
        <button class="btn mr-2" type="button" v-on:click="prev()">&lt;&lt;</button>
        <button class="btn mr-2" type="button" v-on:click="zoomout()">-</button>
        <button class="btn mr-2" type="button" v-on:click="refresh()">refresh</button>
        <button class="btn mr-2" type="button" v-on:click="zoomin()">+</button>
        <button class="btn mr-2" type="button" v-on:click="next()">&gt;&gt;</button>
        <button class="btn mr-2" type="button" v-on:click="shift(S_PER_HOUR)">&gt;1h</button>
        <button class="btn mr-2" type="button" v-on:click="shift(6 * S_PER_HOUR)">&gt;6h</button>
        <button class="btn mr-2" type="button" v-on:click="shift(S_PER_DAY)">&gt;1d</button>
        <button class="btn mr-2" type="button" v-on:click="shift(S_PER_WEEK)">&gt;1w</button>
      </div>
    </div>
    <gantt ref='gantt' :timezone="timezone" :resource_filter="resource_filter" :gantt_relative_window="relative_window"></gantt>
  </div>
</template>

<script>
import Multiselect from 'vue-multiselect'
import { S_PER_DAY, S_PER_WEEK, S_PER_HOUR } from '@/constants'
import { iotlab } from '@/rest'
import moment from 'moment-timezone'
import Gantt from '@/wp-menu/Gantt'

export default {
  name: 'Drawgantt',

  components: {
    Multiselect,
    Gantt,
  },

  data () {
    return {
      active: 0,
      relative_window: {start: -S_PER_DAY, stop: S_PER_DAY},
      timezone: 'UTC',
      sites: [],
      nodes: [],
      currentSite: 'all',
      currentArchi: 'all',
      nodeFilter: node => true,
      nodeFilters: {
        all: node => true,
        alive: node => node.state === 'Alive',
        busy: node => node.state === 'Busy',
        unavailable: node => ['Absent', 'Suspected'].includes(node.state),
        dead: node => node.state === 'Dead',
        mobile: node => node.mobile,
      },
      tzUser: moment.tz.guess(),

      S_PER_DAY: S_PER_DAY,
      S_PER_WEEK: S_PER_WEEK,
      S_PER_HOUR: S_PER_HOUR,
    }
  },

  computed: {
    relative_start () {
      return this.relative_window.start
    },
    relative_stop () {
      return this.relative_window.stop
    },
    tzNames () {
      // filtered tz names
      let tzNames = moment.tz.names()
        .filter(z => z !== 'UTC' && z !== this.tzUser)
        .sort()
      tzNames.unshift(this.tzUser)
      tzNames.unshift('UTC')
      return tzNames
    },
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
    resource_filter () {
      return {
        site: n => this.currentSite !== 'all' ? n.site === this.currentSite.site : true,
        archi: n => this.currentArchi !== 'all' ? n.archi === this.currentArchi : true,
        node: this.nodeFilter,
      }
    },
  },

  created () {
    Promise.all([
      iotlab.getSitesDetails().catch((err) => this.errorHandler('sites', err)),
      iotlab.getNodes().catch((err) => this.errorHandler('nodes', err)),
    ]).then(([sites, nodes]) => {
      this.sites = sites.sort((a, b) => a.site.localeCompare(b.site))
      this.nodes = nodes
    })
  },

  methods: {
    refresh () {
      this.$ref.gantt.refresh()
    },

    errorHandler (type, err) {
      this.$notify({text: err.response.data.message || 'Failed to fetch ' + type, type: 'error'})
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

    sleep (millis, callback) {
      setTimeout(() => callback(), millis)
    },

    reset () {
      this.relative_window = { start: -S_PER_DAY, stop: S_PER_DAY }
    },

    shift (time) {
      this.relative_window = { start: this.relative_start + time, stop: this.relative_stop + time }
    },

    next () {
      var t = this.relative_stop + (this.relative_stop - this.relative_start)
      this.relative_window = { start: this.relative_stop, stop: t }
    },

    prev () {
      var t = this.relative_start - (this.relative_stop - this.relative_start)
      this.relative_window = { start: t, stop: this.relative_start }
    },

    zoomin () {
      var t = this.relative_start + (this.relative_stop - this.relative_start) / 4
      this.relative_window = { start: t, stop: this.relative_stop - (this.relative_stop - this.relative_start) / 4 }
    },

    zoomout () {
      var t = (this.relative_stop - this.relative_start) / 2
      this.relative_window = { start: this.relative_start - t, stop: this.relative_stop + t }
    },
  },
}
</script>
<style>
.time-header {
  position: sticky;
  top: 0px;
  background: white;
  z-index: 2;
  margin-bottom: 0;
  height: 40px;
}
</style>
