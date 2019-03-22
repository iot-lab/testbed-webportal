<template>
  <svg ref="svgObj" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:ev="http://www.w3.org/2001/xml-events" xml:space="preserve"
  :width="page_width" :height="page_height" :viewBox="`0 0 ${ page_width } ${ page_height}`"
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

      <g class="job" v-for="job in node.jobs" v-bind:key="job.key"
        @mouseover="e => mouseOver(e, job.info, '')"
        @mouseout="mouseOut"
        @mousemove="mouseMove">
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
      <g class="nodeState" v-for="nodesState in node.states" v-bind:key="nodesState.network_address">
        <rect stroke-width="0" style="opacity: 0.75"
              :fill="CONF.state_colors[nodesState.state]"
              :width="date2px(nodesState.stop) - date2px(nodesState.start)"
              :height="nodesState.height"
              @mouseover="e => mouseOver(e, nodesState.info, '')"
              @mouseout="mouseOut"
              @mousemove="mouseMove"
              :x="date2px(nodesState.start)" :y="nodesState.y" />
      </g>
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
    <g ref="infobox" id="infobox"
       :display="infobox.visible ? 'inline' : 'none'"
       :transform="`translate(${infobox.x+20}, ${infobox.y+20})`"
       >
      <rect ref="infoboxrect" id="infoboxrect" x="0" y="0" rx="10" ry="10" fill="#FFFFFF" stroke="#888888" stroke-width="1" style="opacity: 0.9"
            :width="infobox.width" :height="infobox.height" />
      <text ref="infoboxtext" font-size="10" id="infoboxtext" x="10" y="10" fill="#000000">
        <tspan x=10 dy=10 v-for="line in infobox.text">{{line}}</tspan>
      </text>
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
import Vue from 'vue'
import moment from 'moment-timezone'

const CONF = {
  timezone: 'UTC',
  state_colors: {
    Absent: 'url(#absentPattern)',
    Suspected: 'url(#suspectedPattern)',
    Dead: 'url(#deadPattern)',
    Standby: 'url(#standbyPattern)',
    Drain: 'url(#drainPattern)',
  },
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
  job_color_saturation_lightness: '75%,75%',

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
      CONF: CONF,
      now: null,
      infobox: {
        visible: false,
        x: 0,
        y: 0,
        text: '',
      },
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
      return CONF.hierarchy_left_align
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
    start () {
      return moment(this.now).add(this.gantt_relative_start_date, 'seconds')
    },
    stop () {
      return moment(this.now).add(this.gantt_relative_stop_date, 'seconds')
    },
    gantt_start_date () {
      let ganttStartDate = this.start.unix()
      if (ganttStartDate === 0) {
        ganttStartDate = this.gantt_now + this.gantt_relative_start_date
      }
      return ganttStartDate
    },
    gantt_stop_date () {
      let ganttStopDate = this.stop.unix()
      if (ganttStopDate === 0) {
        ganttStopDate = this.gantt_now + this.gantt_relative_stop_date
      }
      return ganttStopDate
    },
  },
  created () {
    if (!this.scale) {
      this.scale = CONF.scale
    }
    global.moment = moment

    this.now = moment.tz(this.timezone)
    this.gantt_now = this.now.unix()
    this.load()
  },

  watch: {
    gantt_relative_start_date () {
      this.load()
    },
    gantt_relative_stop_date () {
      this.load()
    },
    resource_filter () {
      this.load()
    },
  },

  methods: {
    load () {
      this.nodes = []
      this.svgNodes = []
      this.jobs = []
      this.svgJobs = []
      this.nodesStates = []
      this.svgNodesStates = []

      Promise.all([
        iotlab.getNodes(this.start, this.stop).catch((err) => this.errorHandler('nodes', err)),
        iotlab.getNodesStates(this.start, this.stop).catch((err) => this.errorHandler('nodes states', err)),
        iotlab.getExperimentsJobs(this.start, this.now).catch((err) => this.errorHandler('jobs', err)),
      ]).then((data) => {
        this.setNodesStatesJobs(...data)
      })
    },
    formatDateTime (value) {
      return value.format('YYYY-MM-DD HH:mm')
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
    addNodesStatesJobs (nodesStates, jobs) {
      this.sortNodes()

      let svgNodesMap = {}

      let y = this.gantt_top
      for (let node of this.nodes) {
        let svgNode = {
          y: y,
          label: this.$options.filters.stripDomain(node.network_address),
          network_address: node.network_address,
          states: [],
          jobs: [],
        }
        svgNodesMap[node.network_address] = svgNode
        y += this.scale
      }

      for (let nodesState of nodesStates) {
        if (!(nodesState.network_address in svgNodesMap)) {
          continue
        }
        let stopDate = (new Date(nodesState.stop_date)).getTime() / 1000
        stopDate = nodesState.stop_date === 0 ? this.gantt_stop_date : stopDate

        let svgNodeState = {
          y: svgNodesMap[nodesState.network_address].y,
          start: moment(nodesState.start_date).unix(),
          start_date: moment(nodesState.start_date).tz(this.timezone),
          stop: stopDate,
          stop_date: moment(nodesState.stop_date).tz(this.timezone),
          closed_end: nodesState.stop_date === 0,
          height: this.scale,
          state: nodesState.state,
        }
        svgNodeState.info = `State: ${svgNodeState.state}|Since: ${this.formatDateTime(svgNodeState.start_date)}`
        if (svgNodeState.closed_end) {
          svgNodeState.info += `|Until: ${this.formatDateTime(svgNodeState.stop_date)}`
        }

        svgNodesMap[nodesState.network_address].states.push(svgNodeState)
      }

      let nodesNetworkAddresses = this.nodes.map(el => el.network_address)
      for (let job of this.jobs) {
        let indices = []
        for (let node of job.nodes) {
          if (!(node in svgNodesMap)) {
            continue
          }
          indices.push(nodesNetworkAddresses.indexOf(node))
        }
        indices.sort()
        indices = indices.reduce((r, n) => {
          const lastSubArray = r[r.length - 1]
          if (!lastSubArray || lastSubArray[1] !== n - 1) {
            r.push([n, n])
          } else {
            r[r.length - 1][1] = n
          }
          return r
        }, [])

        let stopDate = (new Date(job.stop_date)).getTime() / 1000

        for (let indicesArray of indices) {
          let node = this.nodes[indicesArray[0]].network_address
          let svgJob = {
            y: svgNodesMap[node].y,
            start: (new Date(job.start_date)).getTime() / 1000,
            stop: job.stop_date === 0 ? (job.submitted_duration ? this.gantt_start_date + 60 * job.submitted_duration : this.gantt_stop_date) : stopDate,
            closed_end: job.stop_date === 0,
            id: job.id,
            height: this.scale * (indicesArray[1] - indicesArray[0] + 1),
            color: '#00FF00',
            info: `Id: ${job.id}|User: ${job.user}|Name: ${job.name}|Nodes: ${job.nb_nodes}|Submission: ${job.submission_date}`,
          }
          svgJob.width = this.date2px(svgJob.stop) - this.date2px(svgJob.start)
          svgNodesMap[node].jobs.push(svgJob)
        }
      }

      this.svgNodes = Object.values(svgNodesMap)
    },
    setNodesStatesJobs (nodes, nodesStates, jobs) {
      nodes = nodes.filter(n => {
        if (this.resource_filter.archi_all || this.resource_filter.archi === n.archi) {
          if (this.resource_filter.site_all || this.resource_filter.site === n.site) {
            return n
          }
        }
      })
      this.nodes = nodes
      this.nodesStates = nodesStates
      this.jobs = jobs

      this.addNodesStatesJobs(nodesStates, jobs)
    },
    fmod (a, b) {
      return Number((a - (Math.floor(a / b) * b)).toPrecision(8))
    },
    mouseOver (evt, message, hlElementClass) {
      let array = message.split('|')
      this.infobox.visible = true
      this.infobox.text = array

      Vue.nextTick(() => {
        var length = 0
        for (var tspan of this.$refs.infoboxtext.childNodes) {
          length = Math.max(length, tspan.getComputedTextLength())
        }
        this.infobox.width = length + 20
      })

      this.infobox.height = array.length * CONF.text_scale + 20
    },
    mouseOut (evt, hlElementClass) {
      this.infobox.visible = false
    },
    mouseMove (evt) {
      let dim = this.$refs.svgObj.getBoundingClientRect()
      this.infobox.x = evt.clientX - dim.left
      this.infobox.y = evt.clientY - dim.top
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
<style>
svg text {
  -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;

  cursor: default;
}
svg text::selection {
    background: none;
}
</style>
