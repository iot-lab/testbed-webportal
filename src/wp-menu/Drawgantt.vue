<template>
  <div ref="container" class="container mt-3" id="container_drawgantt">
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
    <p class="mb-2">
      <button class="btn mr-2" type="button" v-on:click="shift(-S_PER_WEEK)">&lt;1w</button>
      <button class="btn mr-2" type="button" v-on:click="shift(-S_PER_DAY)">&lt;1d</button>
      <button class="btn mr-2" type="button" v-on:click="shift(-6 * S_PER_HOUR)">&lt;6h</button>
      <button class="btn mr-2" type="button" v-on:click="shift(-S_PER_HOUR)">&lt;1h</button>
      <button class="btn mr-2" type="button" v-on:click="prev()">&lt;&lt;</button>
      <button class="btn mr-2" type="button" v-on:click="zoomout()">-</button>
      <button class="btn mr-2" type="button" v-on:click="zoomin()">+</button>
      <button class="btn mr-2" type="button" v-on:click="next()">&gt;&gt;</button>
      <button class="btn mr-2" type="button" v-on:click="shift(S_PER_HOUR)">&gt;1h</button>
      <button class="btn mr-2" type="button" v-on:click="shift(6 * S_PER_HOUR)">&gt;6h</button>
      <button class="btn mr-2" type="button" v-on:click="shift(S_PER_DAY)">&gt;1d</button>
      <button class="btn mr-2" type="button" v-on:click="shift(S_PER_WEEK)">&gt;1w</button>
    </p>
    <p class="mb-2" style="align-items: center;">
      <multiselect v-model="timezone" placeholder="select timezone"
                :options="tzNames"
                :allow-empty="false"
                style="width: 250px; z-index: 2"
                id="timezoneSelect"
    />
    </p>
    <gantt :timezone="timezone" :resource_filter="resource_filter" :gantt_relative_start_date="relative_start" :gantt_relative_stop_date="relative_stop"></gantt>
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
      relative_start: -S_PER_DAY,
      relative_stop: S_PER_DAY,
      timezone: 'UTC',
      sites: [],
      zoom_relative_start: 0,
      zoom_relative_stop: 0,
      width: 500,
      currentSite: 'all',
      currentArchi: 'all',
      tzUser: moment.tz.guess(),

      S_PER_DAY: S_PER_DAY,
      S_PER_WEEK: S_PER_WEEK,
      S_PER_HOUR: S_PER_HOUR,
    }
  },

  computed: {
    tzNames () {
      // filtered tz names
      let tzNames = Object.keys(moment.tz._zones)
        .map(k => moment.tz._zones[k].split('|')[0])
        .filter(z => z.indexOf('/') >= 0)
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
      let filter = {}
      if (this.currentSite !== 'all') {
        filter.site = this.currentSite.site
      } else {
        filter.site_all = true
      }
      if (this.currentArchi !== 'all') {
        filter.archi = this.currentArchi
      } else {
        filter.archi_all = true
      }
      return filter
    },
    filter () {
      let filters = ['production!=\'NO\'']
      if (this.currentSite !== 'all') {
        filters.push(`site='${this.currentSite.site}'`)
      }
      if (this.currentArchi !== 'all') {
        filters.push(`archi='${this.currentArchi}'`)
      }
      return filters.join(' and ')
    },
    svgUrl () {
      const query = {
        width: this.width,
        relative_start: this.relative_start,
        relative_stop: this.relative_stop,
        filter: this.filter,
        timezone: this.timezone,
      }

      var esc = encodeURIComponent
      return `https://${process.env.VUE_APP_IOTLAB_HOST}/drawgantt/drawgantt-svg.php?` + Object.keys(query)
        .filter(k => query[k])
        .map(k => esc(k) + '=' + esc(query[k]))
        .join('&')
    },
  },

  created () {
    Promise.all([
      iotlab.getSitesDetails().catch((err) => this.errorHandler('sites', err)),
      iotlab.getNodes().catch((err) => this.errorHandler('nodes', err)),
    ]).then(([sites, nodes, nodesStates, jobs]) => {
      this.setSites(sites)
      this.setNodes(nodes)
    })
  },

  mounted () {
    window.addEventListener('resize', () => {
      this.width = this.$refs.container.clientWidth - 50
    })
  },

  methods: {
    errorHandler (type, err) {
      this.$notify({text: err.response.data.message || 'Failed to fetch ' + type, type: 'error'})
    },

    setSites (sites) {
      this.sites = sites.sort((a, b) => a.site.localeCompare(b.site))
    },

    sleep (millis, callback) {
      setTimeout(() => callback(), millis)
    },

    setNodes (nodes) {
      this.nodes = nodes
    },

    reset () {
      this.relative_start = -S_PER_DAY
      this.relative_stop = S_PER_DAY
    },

    shift (time) {
      this.relative_start += time
      this.relative_stop += time
    },

    next () {
      var t = this.relative_stop + (this.relative_stop - this.relative_start)
      this.relative_start = this.relative_stop
      this.relative_stop = t
    },

    prev () {
      var t = this.relative_start - (this.relative_stop - this.relative_start)
      this.relative_stop = this.relative_start
      this.relative_start = t
    },

    zoomin () {
      var t = this.relative_start + (this.relative_stop - this.relative_start) / 4
      this.relative_stop = this.relative_stop - (this.relative_stop - this.relative_start) / 4
      this.relative_start = t
    },

    zoomout () {
      var t = (this.relative_stop - this.relative_start) / 2
      this.relative_stop += t
      this.relative_start -= t
    },
  },
}
</script>
<style>
.sidebar {
  position: sticky;
  top: 0px;
}
</style>
