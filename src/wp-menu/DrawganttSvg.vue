<template>
  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:ev="http://www.w3.org/2001/xml-events" xml:space="preserve"
  :width="page_width + ' px'" :height="page_height + ' px' " :viewBox="`0 0 ${ page_width } ${ page_height}`"
  zoomAndPan="magnify" color-rendering="optimizeSpeed" image-rendering="optimizeSpeed" text-rendering="optimizeSpeed" shape-rendering="optimizeSpeed" >

<!-- outside box -->
    <rect :x="gantt_left_align" :y="gantt_top" :width="gantt_width" :height="gantt_height"
    stroke="#0000FF" stroke-width="1" fill="none" />-->
    <g class="node" v-for="node in svgNodes" v-bind:key='node.network_address'>
      <line stroke="#0000FF" stroke-width="1" shape-rendering="crispEdges"
          :x1="gantt_left_align"
          :x2="gantt_left_align + gantt_width"
          :y1="node.y + 0.5"
          :y2="node.y + 0.5" />

      <text font-size="10" text-anchor="end" dominant-baseline="central"
            :x="CONF.label_right_align"
            :y="node.y + scale/2" >
            {{node.label}}
      </text>
    </g>

    <g class="job" v-for="job in svgJobs" v-bind:key="job.key">
      <rect stroke-width="1" style="opacity: 0.3" stroke="#000088"
            :class="'job' + job.id"
            :x="date2px(job.start)"
            :y="job.y"
            :width="job.width"
            :height="job.height"
            :fill="`hsl(${job2int(job)},${CONF.job_color_saturation_lightness})`"/>
      <text font-size="10" text-anchor="middle" dominant-baseline="central"
            v-if="job.width > CONF.gantt_min_job_width_for_label"
            :x="date2px(job.start) + job.width / 2"
            :y="job.y + job.height / 2">
        {{job.id}}
      </text>
    </g>

    <g class="nodeState" v-for="nodesState in svgNodesStates" v-bind:key="nodesState.network_address">
      <rect stroke-width="0" style="opacity: 0.75"
            :fill="CONF.state_colors[nodesState.state]"
            :width="date2px(nodesState.stop) - date2px(nodesState.start)"
            :height="nodesState.height"
            :x="date2px(nodesState.start)" :y="nodesState.y" />
    </g>

    <g class="ruler" v-for="d in rulerValues" v-bind:key="d">
      <!-- top time ruler-->
      <text :x="date2px(d)" :y="gantt_top - 15" text-anchor="middle" font-size="10"  >{{ ymddate(d) }}</text>
      <text :x="date2px(d)" :y="gantt_top - 5" text-anchor="middle" font-size="10"  >{{ hmsdate(d) }}</text>
      <!-- bottom time ruler-->
      <text :x="date2px(d)" :y="gantt_top + gantt_height + 25" text-anchor="middle" font-size="10"  >{{ ymddate(d) }}</text>
      <text :x="date2px(d)" :y="gantt_top + gantt_height + 15" text-anchor="middle" font-size="10"  >{{ hmsdate(d) }}</text>
      <!-- time grid lines -->
      <line v-for="d in rulerValues" v-bind:key="d"
            :x1="date2px(d)"
            :y1="gantt_top-5"
            :x2="date2px(d)"
            :y2="gantt_top + gantt_height + 5"
            stroke="#0000FF" stroke-width="1" />
    </g>

    <!-- now line -->
    <line stroke="#FF0000" stroke-width="2" stroke-dasharray="10,10"
    :x1="date2px(gantt_now)"
    :y1="gantt_top - 5" :x2="date2px(gantt_now)" :y2="gantt_top + gantt_height + 5" />

    <!-- infobox -->
    <!-- <rect id="resourcemark" :x="resourcemark_x" y="0" rx="0" ry="0" width="2" :height="scale" fill="#888888" stroke="#888888" stroke-width="1" style="opacity: 0.9" /> -->
    <g id="infobox" display="none">
      <rect id="infoboxrect" x="0" y="0" rx="10" ry="10" width="200" height="150" fill="#FFFFFF" stroke="#888888" stroke-width="1" style="opacity: 0.9" />
      <text font-size="10" id="infoboxtext" x="10" y="10" fill="#000000" />
    </g>
    <rect x="0" y="0" width="0" height="0" id="zoom" stroke="#0000FF" stroke-width="1" fill="#8888FF" style="opacity: 0.25" display="none" />

    <defs>
      <pattern id="absentPattern" patternUnits="userSpaceOnUse" x="0" y="0" width="5" height="5" viewBox="0 0 5 5" >
      <line x1="5" y1="0" x2="0" y2="5" stroke="#000000" stroke-width="2" />
      </pattern>
      <pattern id="suspectedPattern" patternUnits="userSpaceOnUse" x="0" y="0" width="5" height="5" viewBox="0 0 5 5" >
      <line x1="5" y1="0" x2="0" y2="5" stroke="#ff0000" stroke-width="2" />
      </pattern>
      <pattern id="deadPattern" patternUnits="userSpaceOnUse" x="0" y="0" width="5" height="5" viewBox="0 0 5 5" >
      <line x1="5" y1="0" x2="0" y2="5" stroke="#ff8080" stroke-width="2" />
      </pattern>
      <pattern id="standbyPattern" patternUnits="userSpaceOnUse" x="0" y="0" width="5" height="5" viewBox="0 0 5 5" >
      <line x1="5" y1="0" x2="0" y2="5" stroke="#00ff00" stroke-width="2" />
      </pattern>
      <pattern id="drainPattern" patternUnits="userSpaceOnUse" x="0" y="0" width="15" height="10" viewBox="0 0 10 10" >
      <circle cx="5" cy="5" r="4" fill="#ff0000" stroke="#ff0000" stroke-width="1" />
      <line x1="2" y1="5" x2="9" y2="5" stroke="#ffffff" stroke-width="2" />
      </pattern>
      <pattern id="containerPattern" patternUnits="userSpaceOnUse" x="0" y="0" width="20" height="20" viewBox="0 0 20 20" >
      <text font-size="10" x="0" y="20" fill="#888888">C</text>
      </pattern>
      <pattern id="besteffortPattern" patternUnits="userSpaceOnUse" x="0" y="0" width="20" height="20" viewBox="0 0 20 20" >
      <text font-size="10" x="10" y="20" fill="#888888">B</text>
      </pattern>
      <pattern id="placeholderPattern" patternUnits="userSpaceOnUse" x="0" y="0" width="20" height="20" viewBox="0 0 20 20" >
      <text font-size="10" x="10" y="20" fill="#888888">P</text>
      </pattern>
      <pattern id="deployPattern" patternUnits="userSpaceOnUse" x="0" y="0" width="20" height="20" viewBox="0 0 20 20" >
      <text font-size="10" x="10" y="10" fill="#888888">D</text>
      </pattern>
      <pattern id="timesharingPattern" patternUnits="userSpaceOnUse" x="0" y="0" width="20" height="20" viewBox="0 0 20 20" >
      <text font-size="10" x="10" y="20" fill="#888888">T</text>
      </pattern>
    </defs>
  </svg>
</template>
<script>
import { iotlab } from '@/rest'
import moment from 'moment-timezone'

var svgDocument
var infobox, infoboxtext, infoboxrect
var timeruler
var zoom, zoomDraw, zoomX1, zoomX2, zoomWidth
var parentContent
var parentInfobox
var resourcemark
var y
const CONF = {
  timezone: 'UTC',
  resources_labels: ['network_address', 'cpuset'],
  cpuset_label_display_string: '%02d',
  label_display_regex: {
    network_address: /([^.]+)\..*/g,
  },
  label_cmp_regex: {
    network_address: /([^-]+)-(\d+)\..*/g,
  },
  resource_properties: [
    'deploy', 'cpuset', 'besteffort', 'network_address', 'type', 'drain',
  ],
  resource_hierarchy: [
    'site', 'network_address',
  ],
  resource_base: 'cpuset',
  resource_group_level: 'network_address',
  resource_drain_property: 'drain',
  state_colors: {
    Absent: 'url(#absentPattern)',
    Suspected: 'url(#suspectedPattern)',
    Dead: 'url(#deadPattern)',
    Standby: 'url(#standbyPattern)',
    Drain: 'url(#drainPattern)',
  },
  job_colors: {
    'besteffort': 'url(#besteffortPattern)',
    'deploy(=\\w)?': 'url(#deployPattern)',
    'container(=\\w+)?': 'url(#containerPattern)',
    'timesharing=(\\*|user),(\\*|name)': 'url(#timesharingPattern)',
    'placeholder=\\w+': 'url(#placeholderPattern)',
  },
  job_click_url: '',
  resource_click_url: '',

  hierarchy_resource_width: 10,
  scale: 10,
  text_scale: 10,
  time_ruler_scale: 6,
  time_ruler_steps: [60, 120, 180, 300, 600, 1200, 1800, 3600, 7200, 10800, 21600, 28800, 43200, 86400, 172800, 259200, 604800],
  gantt_top: 50,
  bottom_margin: 45,
  right_margin: 30,
  label_right_align: 160,
  hierarchy_left_align: 190,
  gantt_left_align: 200,
  gantt_min_width: 900,
  gantt_min_height: 100,
  gantt_min_job_width_for_label: 0,
  min_state_duration: 2,
  job_color_saturation_lightness: '75%,75%',
  job_color_saturation_lightness_highlight: '50%,50%',
  standby_truncate_state_to_now: 1,
  besteffort_truncate_job_to_now: 1,
  besteffort_pattern: '<pattern id="%%PATTERN_ID%%" patternUnits="userSpaceOnUse" x="0" y="0" width="10" height="10" viewBox="0 0 10 10" ><polygon points="0,0 7,0 10,5 7,10 0,10 3,5" fill="%%PATTERN_COLOR%%" stroke-width="0"/></pattern>',
  min_timespan: 480,

}

export default {
  name: 'DrawganttSvg',

  props: {
    width: {
      type: Number,
      required: false,
    },
    scale: {
      type: Number,
      required: false,
      default: CONF.scale,
    },
    gantt_relative_start_date: {
      type: Number,
      default: -86400,
    },
    gantt_relative_stop_date: {
      type: Number,
      default: 86400,
    },
    resource_filter: {
      type: Object,
      default: () => { return {'archi_all': true, 'site_all': true} },
    },
    timezone: {
      type: String,
      default: 'UTC',
    },
    display: {
      type: String,
      default: 'all',
    },
  },

  data () {
    return {
      nodes: [],
      nodesStates: [],
      jobs: [],
      svgNodes: [],
      svgNodesMap: {},
      svgNodesStates: [],
      svgJobs: [],
      CONF: CONF,
      gantt_now: 0,
      gantt_start_date: 0,
      gantt_stop_date: 0,
      now: null,
      start: null,
      stop: null,
    }
  },

  computed: {
    ruler_step () {
      let value = (this.gantt_stop_date - this.gantt_start_date) / CONF.time_ruler_scale
      return CONF.time_ruler_steps.filter(r => r < value).pop()
    },
    rulerValues () {
      let d = this.gantt_start_date - this.gantt_start_date % this.ruler_step
      let values = []
      while ((d += this.ruler_step) < this.gantt_stop_date) {
        values.push(d)
      }
      return values
    },
    page_width () {
      return this.gantt_left_align + this.gantt_width + CONF.right_margin
    },
    page_height () {
      if (this.display !== 'mobile_ruler_only') {
        return Math.max(this.gantt_min_height, this.gantt_top + this.gantt_height + this.bottom_margin)
      } else {
        return 30
      }
    },
    gantt_left_align () {
      return CONF.hierarchy_left_align + (3 + CONF.resource_hierarchy.indexOf(this.resource_base)) * CONF.hierarchy_resource_width
    },
    gantt_top () {
      if (this.display !== 'no_ruler') {
        return CONF.gantt_top
      } else {
        return 1
      }
    },
    gantt_min_height () {
      if (this.display !== 'no_ruler') {
        return CONF.gantt_min_height
      } else {
        return 0
      }
    },
    bottom_margin () {
      if (this.display !== 'no_ruler') {
        return CONF.bottom_margin
      } else {
        return 0
      }
    },
    gantt_width () {
      let val = CONF.gantt_min_width
      if (this.width && this.width > this.gantt_left_align + CONF.gantt_min_width + CONF.right_margin) {
        val = this.width - this.gantt_left_align - CONF.right_margin
      }
      return val
    },
    gantt_height () {
      return this.scale * this.nodes.length
    },
  },
  created () {
    if (!this.scale) {
      this.scale = CONF.scale
    }
    global.moment = moment

    this.reload()
  },

  watch: {
    resource_filter () {
      this.reload()
    },
    timezone () {
      this.reload()
    },
    gantt_relative_start_date () {
      this.reload()
    },
    gantt_relative_stop_date () {
      this.reload()
    },
  },

  methods: {
    reload () {
      this.now = moment.tz(this.timezone)
      this.start = moment(this.now).add(this.gantt_relative_start_date, 'seconds')
      this.stop = moment(this.now).add(this.gantt_relative_stop_date, 'seconds')

      this.gantt_now = this.now.unix()
      this.gantt_start_date = this.start.unix()
      this.gantt_stop_date = this.stop.unix()

      // Compute gantt start and stop dates
      if (this.gantt_start_date === 0) {
        this.gantt_start_date = this.gantt_now + this.gantt_relative_start_date
      }
      if (this.gantt_stop_date === 0) {
        this.gantt_stop_date = this.gantt_now + this.gantt_relative_stop_date
      }
      let timespan = this.gantt_stop_date - this.gantt_start_date
      if (timespan < CONF.min_timespan) {
        // debug(2, "Timespan $timespan too small, using ".$CONF['min_timespan']);
        timespan = CONF.min_timespan
        this.gantt_start_date = (this.gantt_stop_date + this.gantt_start_date) / 2 - timespan / 2
        this.gantt_stop_date = this.gantt_start_date + timespan
      }

      this.nodes = []
      this.svgNodes = []
      this.jobs = []
      this.svgJobs = []
      this.nodesStates = []
      this.svgNodesStates = []
      this.svgNodesMap = {}

      Promise.all([
        iotlab.getNodes().catch((err) => this.errorHandler('nodes', err)),
        iotlab.getNodesStates(this.start, this.stop).catch((err) => this.errorHandler('nodes states', err)),
        iotlab.getExperimentsJobs(this.start, this.now).catch((err) => this.errorHandler('jobs', err)),
      ]).then(([nodes, nodesStates, jobs]) => {
        this.setNodes(nodes)
        this.setNodesStates(nodesStates)
        this.setJobs(jobs)
      })
    },
    errorHandler (type, err) {
      console.log(err)
      this.$notify({text: err.response.data.message || 'Failed to fetch ' + type, type: 'error'})
    },
    sortNodes () {
      this.nodes.sort((n1, n2) => {
        let short1 = this.$options.filters.stripDomain(n1.network_address).split(/[.-]/)
        let [archi1, id1] = [short1[0], Number(short1[1])]
        let short2 = this.$options.filters.stripDomain(n2.network_address).split(/[.-]/)
        let [archi2, id2] = [short2[0], Number(short2[1])]

        // sort by site, archi then id
        if (n1.site > n2.site) return 1
        if (n1.site < n2.site) return -1

        // by archi
        if (archi1 > archi2) return 1
        if (archi1 < archi2) return -1

        // by id
        if (id1 > id2) return 1
        if (id1 < id2) return -1
      })
    },
    ymddate (d) {
      let date = new Date(d * 1000)
      return date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2)
    },
    hmsdate (d) {
      let date = new Date(d * 1000)
      return ('0' + date.getHours()).slice(-2) + ':' + ('0' + date.getMinutes()).slice(-2) + ':' + ('0' + date.getSeconds()).slice(-2)
    },
    setNodesStates (nodesStates) {
      this.nodesStates = nodesStates
      for (let nodesState of nodesStates) {
        let stopDate = (new Date(nodesState.stop_date)).getTime() / 1000
        this.svgNodesStates.push({
          y: this.svgNodesMap[nodesState.network_address].y,
          start: (new Date(nodesState.start_date)).getTime() / 1000,
          stop: stopDate === 0 ? this.gantt_stop_date : stopDate,
          height: this.scale,
          state: nodesState.state,
        })
      }
    },
    setNodes (nodes) {
      nodes = nodes.filter(n => {
        if (this.resource_filter.archi_all || this.resource_filter.archi === n.archi) {
          if (this.resource_filter.site_all || this.resource_filter.site === n.site) {
            return n
          }
        }
      })
      this.nodes = nodes
      this.sortNodes()
      let y = this.gantt_top
      for (let node of this.nodes) {
        let svgNode = {
          y: y,
          label: this.$options.filters.stripDomain(node.network_address),
          network_address: node.network_address,
        }
        this.svgNodesMap[node.network_address] = svgNode
        this.svgNodes.push(svgNode)
        y += this.scale
      }
    },
    setJobs (jobs) {
      this.jobs = jobs
      let nodesNetworkAddresses = this.nodes.map(el => el.network_address)
      for (let job of this.jobs) {
        let indices = []
        for (let node of job.nodes) {
          indices.push(nodesNetworkAddresses.indexOf(node))
        }
        indices.sort()
        indices = indices.reduce((r, n) => {
          const lastSubArray = r[r.length - 1]
          if (!lastSubArray || lastSubArray[lastSubArray.length - 1] !== n - 1) {
            r.push([n])
          } else {
            r[r.length - 1][1] = n
          }
          return r
        }, [])

        let stopDate = (new Date(job.stop_date)).getTime() / 1000

        for (let node of job.nodes) {
          let svgJob = {
            node: node,
            y: this.svgNodesMap[node].y,
            start: (new Date(job.start_date)).getTime() / 1000,
            stop: stopDate === 0 ? this.gantt_stop_date : stopDate,
            id: job.id,
            height: this.scale,
            color: '#00FF00',
          }
          svgJob.width = this.date2px(svgJob.stop) - this.date2px(svgJob.start)
          this.svgJobs.push(svgJob)
        }
      }
    },
    fmod (a, b) {
      return Number((a - (Math.floor(a / b) * b)).toPrecision(8))
    },
    init (evt) {
      svgDocument = evt.target.ownerDocument
      resourcemark = svgDocument.getElementById('resourcemark')
      infobox = svgDocument.getElementById('infobox')
      infoboxrect = svgDocument.getElementById('infoboxrect')
      infoboxtext = svgDocument.getElementById('infoboxtext')
      timeruler = svgDocument.getElementById('timeruler')
      zoom = svgDocument.getElementById('zoom')
      zoomX1 = 0
      zoomX2 = 0
      zoomWidth = 0
      zoomDraw = false
      // workaround to make the script work on both firefox and chrome
      parentContent = (parent.content) ? (parent.content) : (parent)
      parentContent.addEventListener('scroll', this.drawTimeRuler, false)
      parentInfobox = parentContent.document.getElementById('infobox')
      if (parentInfobox == null || typeof (parentContent.infoboxDisplay) !== 'function' || typeof (parentContent.infoboxHide) !== 'function' || typeof (parentContent.infoboxMove) !== 'function') {
        parentInfobox = null
      }
      this.drawTimeRuler()
    },

    px2date (y) {
      if (y < this.gantt_left_align) {
        return this.gantt_start_date
      }
      if (y > this.gantt_left_align + this.gantt_width) {
        return this.gantt_stop_date
      }
      return Math.round((y - this.gantt_left_align) * (this.gantt_stop_date - this.gantt_start_date) / this.gantt_width + this.gantt_start_date)
    },

    zoomDraw () {
      if (zoomWidth > 5) {
        zoom.setAttribute('x', Math.min(zoomX1, zoomX2))
        zoom.setAttribute('y', this.gantt_top)
        zoom.setAttribute('width', zoomWidth)
        zoom.setAttribute('height', this.gantt_height)
        zoom.setAttribute('display', 'inline')
      } else {
        zoom.setAttribute('display', 'none')
      }
    },
    rootMouseDown (evt) {
      if (parentContent != null && typeof (parentContent.setZoomWindow) === 'function' && evt.pageX > this.gantt_left_align && evt.pageX < (this.gantt_left_align + this.gantt_width) && evt.pageY > this.gantt_top && evt.pageY < (this.gantt_top + this.gantt_height)) {
        zoomX1 = evt.pageX
        zoomX2 = zoomX1
        zoomWidth = 0
        zoomDraw = true
      }
    },
    rootMouseUp (evt) {
      zoomDraw = false
      if (zoomWidth > 5 && parentContent != null && typeof (parentContent.setZoomWindow) === 'function') {
        parentContent.setZoomWindow(this.gantt_now, this.px2date(Math.min(zoomX1, zoomX2)), this.px2date(Math.max(zoomX1, zoomX2)))
      }
      zoomX1 = 0
      zoomX2 = 0
      zoomWidth = 0
    },
    rootMouseMove (evt) {
      if (zoomDraw &&
        (evt.pageX > this.gantt_left_align) && (evt.pageX < (this.gantt_left_align + this.gantt_width)) &&
        (evt.pageY > this.gantt_top) && (evt.pageY < (this.gantt_top + this.gantt_height))) {
        zoomX2 = evt.pageX
        zoomWidth = Math.abs(zoomX2 - zoomX1)
        zoomDraw()
      }
      if (evt.pageY > this.gantt_top && evt.pageY < (this.gantt_top + this.gantt_height)) {
        y = Math.floor((evt.pageY - this.gantt_top) / this.scale) * this.scale + this.gantt_top
        resourcemark.setAttribute('transform', 'translate(0,' + y + ')')
        resourcemark.setAttribute('display', 'inline')
      } else {
        resourcemark.setAttribute('display', 'none')
      }
    },
    rootClick (evt) {
      zoomDraw = false
      zoomX1 = 0
      zoomX2 = 0
      zoomWidth = 0
      zoomDraw()
    },
    drawTimeRuler (evt) {
      if (this.display !== 'mobile_ruler_only' && timeruler != null) {
        if (parentContent != null && this.page_height > parentContent.innerHeight) {
          y = parentContent.scrollY + parentContent.innerHeight - 45
          timeruler.setAttribute('transform', 'translate(0,' + y + ')')
          timeruler.setAttribute('display', 'inline')
        } else {
          timeruler.setAttribute('display', 'none')
        }
      }
    },
    resourceclick (evt, type, id) {
      var url = CONF.resource_click_url
      if (url !== '' && evt.detail > 1) {
        window.open(url.replace('%%TYPE%%', type).replace('%%ID%%', id))
      }
    },
    jobclick (evt, jobid) {
      var url = CONF.job_click_url
      if (url !== '' && evt.detail > 1) {
        window.open(url.replace('%%JOBID%%', jobid))
      }
    },
    highlight (object, hlElementClass, bool) {
      if (object == null || object !== svgDocument.object_ref) {
        var elems = document.getElementsByClassName(hlElementClass)
        for (var i = 0; i < elems.length; i++) {
          if (bool) {
            elems[i].setAttribute('fill', elems[i].getAttribute('fill').replace(CONF['job_color_saturation_lightness'], ',', CONF.job_color_saturation_lightness_highlight))
            elems[i].setAttribute('fill', elems[i].getAttribute('fill').replace('besteffort', 'besteffortHL'))
            elems[i].setAttribute('stroke-width', 1.5)
          } else {
            elems[i].setAttribute('fill', elems[i].getAttribute('fill').replace(CONF['job_color_saturation_lightness_highlight'], ',', CONF.job_color_saturation_lightness))
            elems[i].setAttribute('fill', elems[i].getAttribute('fill').replace('besteffortHL', 'besteffort'))
            elems[i].setAttribute('stroke-width', 1)
          }
        }
      }
      if (object == null && parentContent != null && typeof (parentContent.highlightOthers) === 'function') {
        parentContent.highlightOthers(svgDocument.object_ref, hlElementClass, bool)
      }
    },
    mouseOver (evt, message, hlElementClass) {
      var length = 0
      var array
      var i = 0
      var tspan
      if (hlElementClass !== '') {
        this.highlight(null, hlElementClass, true)
      }
      array = message.split('|')
      if (parentInfobox != null) {
        parentContent.infoboxDisplay(array)
      } else if (infobox != null && infoboxtext != null && infoboxrect != null) {
        while (infoboxtext.hasChildNodes()) {
          infoboxtext.removeChild(infoboxtext.lastChild)
        }
        infobox.setAttribute('display', 'inline')
        for (i in array) {
          tspan = svgDocument.createElementNS('http://www.w3.org/2000/svg', 'tspan')
          tspan.setAttribute('x', 10)
          tspan.setAttribute('dy', 10)
          tspan.appendChild(svgDocument.createTextNode(array[i]))
          infoboxtext.appendChild(tspan)
          length = Math.max(length, tspan.getComputedTextLength())
        }
        infoboxrect.setAttribute('width', length + 20)
        infoboxrect.setAttribute('height', array.length * CONF['text_scale'] + 20)
      }
    },
    mouseOut (evt, hlElementClass) {
      if (parentInfobox !== null) {
        parentContent.infoboxHide(evt)
      } else if (infobox !== null && infoboxtext != null && infoboxrect != null) {
        infobox.setAttribute('display', 'none')
      }
      if (hlElementClass !== '') {
        this.highlight(null, hlElementClass, false)
      }
    },
    mouseMove (evt) {
      var width, height
      var x, y
      if (parentInfobox != null) {
        // get the position of the mouse in the parent window
        x = svgDocument.object_ref.getBoundingClientRect().left + evt.clientX
        y = svgDocument.object_ref.getBoundingClientRect().top + evt.clientY
        parentContent.infoboxMove(x, y, 'svg')
      } else if (infobox != null && infoboxtext != null && infoboxrect != null) {
        width = parseInt(infoboxrect.getAttribute('width'))
        height = parseInt(infoboxrect.getAttribute('height'))
        if ((evt.pageX + 10 + width) < this.page_width) {
          x = (evt.pageX + 10)
        } else {
          x = (this.page_width - width)
        }
        if (parentContent != null && (evt.pageY + 20 + height) < Math.min(this.page_height, parentContent.scrollY + parentContent.innerHeight)) {
          y = (evt.pageY + 20)
        } else {
          y = (evt.pageY - height - 5)
        }
        infobox.setAttribute('transform', 'translate(' + x + ',' + y + ')')
      }
    },
    date2px (date) {
      if (date < this.gantt_start_date) {
        return this.gantt_left_align
      }
      if (date > this.gantt_stop_date) {
        return this.gantt_left_align + this.gantt_width
      }
      return Math.round(this.gantt_left_align + (this.gantt_width * (date - this.gantt_start_date)) / (this.gantt_stop_date - this.gantt_start_date))
    },
    job2int (job) {
      // compute a suffled number for id, so that colors are not too close
      let magicNumber = (1 + Math.sqrt(5)) / 2
      return parseInt(360 * this.fmod(job.id * magicNumber, 1))
    },
  },
}
</script>
