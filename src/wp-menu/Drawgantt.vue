<template>
  <div class="container" style="max-width: 1250px" id="container_drawgantt">
    <div id="panel" style="top: 0px; left: 0px;" align="center">
      <select id="filterSelect" v-on:change="select_filter(true)">
        <option selected="" value="">all nodes</option>
        <option v-for="site in sites" v-bind:key="site.site" :value="`site='${site.site}'`">{{site.site}} only</option>
        <option v-for="archi in archis" v-bind:key="archi.name" :value="`archi='${archi.full}'`">all {{archi.name}}{{archi.radio ? ' w ' + archi.radio : ''}}</option>
        <option value="mobile=1">mobile only</option>
        <option value="mobility_type='turtlebot2'">all turtlebot2</option>
      </select>
      <button class="btn mr-2" type="button" v-on:click="shift(-S_PER_WEEK)">&lt;1w</button>
      <button class="btn mr-2" type="button" v-on:click="shift(-S_PER_DAY)">&lt;1d</button>
      <button class="btn mr-2" type="button" v-on:click="shift(-6 * S_PER_HOUR)">&lt;6h</button>
      <button class="btn mr-2" type="button" v-on:click="shift(-S_PER_HOUR)">&lt;1h</button>
      <button class="btn mr-2" type="button" v-on:click="prev()">&lt;&lt;</button>
      <button class="btn mr-2" type="button" v-on:click="zoomout()">-</button>
      <button class="btn mr-2" type="button" v-on:click="zoom()">zoom</button>
      <button class="btn mr-2" type="button" v-on:click="zoomin()">+</button>
      <button class="btn mr-2" type="button" v-on:click="next()">&gt;&gt;</button>
      <button class="btn mr-2" type="button" v-on:click="shift(S_PER_HOUR)">&gt;1h</button>
      <button class="btn mr-2" type="button" v-on:click="shift(6 * S_PER_HOUR)">&gt;6h</button>
      <button class="btn mr-2" type="button" v-on:click="shift(S_PER_DAY)">&gt;1d</button>
      <button class="btn mr-2" type="button" v-on:click="shift(S_PER_WEEK)">&gt;1w</button>
      <button class="btn mr-2" type="button" v-on:click="reload_content()">reload</button>
      <button class="btn mr-2" type="button" v-on:click="reset()">reset</button>
      <select id="timezoneSelect" v-on:change="select_timezone(true)">
        <option selected="" value="UTC">UTC</option>
        <option value="Europe/Paris">Paris</option>
      </select>
    </div>
    <object ref="svgObj" id="svgObj" type="image/svg+xml" :data="svgUrl" v-on:load="restore_scrolling()">{{ svgUrl }}</object>
    <div id="waiter" v-if="processing">Processing data... please wait...</div>
  </div>
</template>

<script>

import { S_PER_DAY, S_PER_WEEK, S_PER_HOUR } from '@/constants'
import { iotlab } from '@/rest'

export default {
  name: 'Drawgantt',

  data () {
    return {
      processing: true,
      relative_start: -S_PER_DAY,
      relative_stop: S_PER_DAY,
      filterSQL: null,
      timezoneSQL: null,
      timezone: 'UTC',
      scrolledX: 0,
      scrolledY: 0,
      sites: [],
      zoom_relative_start: 0,
      zoom_relative_stop: 0,
      windowWidth: 500,
      svgURL2: null,

      S_PER_DAY: S_PER_DAY,
      S_PER_WEEK: S_PER_WEEK,
      S_PER_HOUR: S_PER_HOUR,
    }
  },

  computed: {
    archis () {
      return Array.from(this.sites.reduce((acc, site) => {
        site.archis.map(archi => acc.add(archi.archi))
        return acc
      }, new Set()))
        .sort((a, b) => a.localeCompare(b))
        .map(a => {
          let archiRadio = a.split(':')
          return { full: a, name: archiRadio[0], radio: archiRadio[1] }
        })
    },
    svgUrl () {
      const query = {
        width: this.windowWidth - 50,
        relative_start: this.relative_start,
        relative_stop: this.relative_stop,
        filter: this.filterSQL ? this.filterSQL : undefined,
        timezone: this.timezoneSQL ? this.timezoneSQL : undefined,
      }

      var esc = encodeURIComponent
      return `https://${process.env.VUE_APP_IOTLAB_HOST}/drawgantt/drawgantt-svg.php?` + Object.keys(query)
        .filter(k => query[k])
        .map(k => esc(k) + '=' + esc(query[k]))
        .join('&')
    },
  },

  created () {
    iotlab.getSitesDetails().then(data => { this.sites = data.sort((a, b) => a.site.localeCompare(b.site)) }).catch(err => {
      this.$notify({text: err.response.data.message || 'Failed to fetch sites details', type: 'error'})
    })
  },

  mounted () {
    window.addEventListener('resize', () => {
      this.windowWidth = window.innerWidth
      this.reload_content()
    })
  },

  methods: {
    sleep (millis, callback) {
      setTimeout(() => callback(), millis)
    },
    show_panel () {
      var panelDiv = document.getElementById('panel')
      panelDiv.style.top = window.scrollY + 'px'
      panelDiv.style.left = window.scrollX + 'px'
    },
    reset () {
      this.relative_start = -S_PER_DAY
      this.relative_stop = S_PER_DAY
      this.reload_content()
    },
    shift (time) {
      this.relative_start += time
      this.relative_stop += time
      this.reload_content()
    },
    next () {
      var t = this.relative_stop + (this.relative_stop - this.relative_start)
      this.relative_start = this.relative_stop
      this.relative_stop = t
      this.reload_content()
    },
    prev () {
      var t = this.relative_start - (this.relative_stop - this.relative_start)
      this.relative_stop = this.relative_start
      this.relative_start = t
      this.reload_content()
    },
    zoomin () {
      var t = this.relative_start + (this.relative_stop - this.relative_start) / 4
      this.relative_stop = this.relative_stop - (this.relative_stop - this.relative_start) / 4
      this.relative_start = t
      this.reload_content()
    },
    zoomout () {
      var t = (this.relative_stop - this.relative_start) / 2
      this.relative_stop += t
      this.relative_start -= t
      this.reload_content()
    },
    set_zoom_window (now, start, stop) {
      this.zoom_relative_start = start - now
      this.zoom_relative_stop = stop - now
    },
    zoom () {
      if (this.zoom_relative_start !== this.zoom_relative_stop) {
        this.relative_start = this.zoom_relative_start
        this.relative_stop = this.zoom_relative_stop
        this.reload_content()
      }
    },
    reload_content () {
      this.scrolledX = window.scrollX
      this.scrolledY = window.scrollY
      this.show_panel()
      var svgObj = document.getElementById('svgObj')
      svgObj.data = this.svgUrl
      svgObj.innerHTML = this.svgUrl
      svgObj.parent_content = window
      this.processing = true
    },
    select_filter (reload) {
      var filterSelect = document.getElementById('filterSelect')
      const production = 'production != \'NO\''
      this.filterSQL = filterSelect.value ? filterSelect.value + ' and ' + production : production
      window.scrollTo(0, 0)
      reload && this.reload_content()
    },
    select_timezone (reload) {
      var timezoneSelect = document.getElementById('timezoneSelect')
      this.timezoneSQL = timezoneSelect.value
      window.scrollTo(0, 0)
      reload && this.reload_content()
    },
    openURL (url) {
      window.open(url)
    },
    restore_scrolling () {
      window.scrollTo(this.scrolledX, this.scrolledY)
      this.processing = false
    },
    init () {
      this.show_panel()
      this.select_filter(false)
      this.select_timezone(false)
      this.sleep(100, this.reload_content)
    },
  },
}
</script>
