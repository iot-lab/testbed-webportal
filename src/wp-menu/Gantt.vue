<template>
  <div class="container">
  <table class="table table-striped table-sm" v-if="nodes.length">
    <thead>
      <tr>
        <th class="cursor" title="sort by hostname" @click="sortBy(node => nodeSortByHostname(node))">Node hostname</th>
        <th>State</th>
        <th class="cursor text-center" title="sort by state" @click="sortBy(node => node.state)">Schedule</th>
      </tr>
      <tr>
        <th></th><th></th>
        <th>
          <div :style="`position: relative; height: 100px; width: ${gantt_width}px`">
            <div class="timeRulerBorder" v-for="d in rulerValues" v-bind:key="d"
              v-bind:style="{
                position: 'absolute',
                left: date2px(d) + 'px',
                width: '1px',
                top: '85%',
                height: (gantt_height + 5) + 'px',
                borderWidth: '1px',
                borderLeft: 'solid blue 1px',
                }">
            </div>
            <div class="ruler" v-for="d in rulerValues" v-bind:key="d"
              v-bind:style="{
                position: 'absolute',
                top: '0px',
                left: date2px(d) + 'px',
                textAlign: 'middle',
                display: 'inline-block',
                }">
              <div style="position: relative; left:-50%; text-align:">
                <div>{{ ymddate(d) }}</div>
                <div>{{ hmsdate(d) }}</div>
              </div>
            </div>
          </div>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr class="nodeIteration" v-for="node in svgNodes" v-bind:key='node.network_address'>
        <td>
          {{node.label}}
        </td>
        <td class="text-center">
          <span class="badge badge-state cursor" :class="node.state | stateBadgeClass">{{node.state}}</span>
        </td>
        <td>
          <div :style="`position: relative; height: 25px; width: ${gantt_width}px`">
            <div v-for="nodesState in node.states" v-bind:key="nodesState.network_address"
                v-bind:style="nodeStateStyle(nodesState)"
                @mouseover="e => mouseOver(e, nodesState.info, '')"
                @mouseout="mouseOut"
                @mousemove="mouseMove">
            </div>

            <!--<div class='gridLine'
                v-bind:style="{
                  position: 'absolute',
                  left: date2px(0) + 'px',
                  textAlign: 'right',
                  width: gantt_width + 'px',
                  top: node.y + 'px',
                  borderWidth: '1px',
                  borderBottom: '1px blue solid',
                }">
            </div>-->

            <div v-for="job in node.jobs" v-bind:key="job.key"
                class="job"
                v-bind:style="{
                  position: 'absolute',
                  backgroundColor: `hsl(${job2int(job)},${CONF.job_color_saturation_lightness})`,
                  // height: job.height + 'px',
                  width: job.width + 'px',
                  left: date2px(job.start) + 'px',
                  zIndex: 50,
                  userSelect: 'none',
                }"
                @mouseover="e => mouseOver(e, job.info, '')"
                @mouseout="mouseOut"
                @mousemove="mouseMove">
              {{job.id}}
            </div>
          </div>
        </td>
      </tr>
    </tbody>
        <!--<line v-for="d in rulerValues" v-bind:key="d"
              :x1="date2px(d)"
              :y1="gantt_top-5"
              :x2="date2px(d)"
              :y2="gantt_top + gantt_height + 5"
              stroke="#0000FF" stroke-width="1" />
      </div>
    <div class="row">
      <div class='ganttBorder'
          v-bind:style="{
            position: 'absolute',
            left: date2px(0) + 'px',
            textAlign: 'right',
            width: gantt_width + 'px',
            height: gantt_height + 'px',
            borderStyle: 'solid',
            borderWidth: '1px',
            borderColor: 'blue',
            top: gantt_top + 'px',
          }">
        </div>
    </div>-->
  </table>
  <div ref='infobox' class="infobox rounded" v-show="infobox.visible" id="infobox"
       :style="{
         position: 'absolute',
         backgroundColor: 'white',
         left: (infobox.x + 20) + 'px',
         top: (infobox.y + 20) + 'px',
         zIndex: 70,
        }"
       >
      <div v-for="line in infobox.text">{{line}}</div>
  </div>
  </div>
</template>
<script>
import { iotlab } from '@/rest'
import moment from 'moment-timezone'

var svgDocument
var timeruler
var parentContent
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
    Absent: '#ff0000',
    Suspected: '#000000',
    Dead: '#ff8080',
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
  min_timespan: 480,

}

export default {
  name: 'Gantt',

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
        width: 10,
        height: 10,
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
    nodeStateStyle (nodesState) {
      let stateColor = CONF.state_colors[nodesState.state]
      return {
        background: `repeating-linear-gradient(-45deg, transparent, transparent 2.5px, ${stateColor} 2.5px, ${stateColor} 4.5px)`,
        backgroundSize: '5px 5px',
        position: 'absolute',
        height: '100%',
        width: (this.date2px(nodesState.stop) - this.date2px(nodesState.start)) + 'px',
        left: this.date2px(nodesState.start) + 'px',
        // top: nodesState.y + 'px',
      }
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
          state: node.state,
          network_address: node.network_address,
          states: [],
          jobs: [],
        }
        svgNodesMap[node.network_address] = svgNode
        y += this.scale
      }

      for (let nodesState of nodesStates) {
        let stopDate = (new Date(nodesState.stop_date)).getTime() / 1000
        let openEnded = stopDate === 0
        let svgNodeState = {
          y: svgNodesMap[nodesState.network_address].y,
          start: moment(nodesState.start_date).unix(),
          start_date: moment(nodesState.start_date).tz(this.timezone),
          stop: openEnded ? this.gantt_stop_date : stopDate,
          stop_date: moment(nodesState.stop_date).tz(this.timezone),
          height: this.scale,
          state: nodesState.state,
          open_ended: openEnded,
        }
        svgNodeState.info = `State: ${svgNodeState.state}|Since: ${this.formatDateTime(svgNodeState.start_date)}`
        if (!svgNodeState.open_ended) {
          svgNodeState.info += `|Until: ${this.formatDateTime(svgNodeState.stop_date)}`
        }

        svgNodesMap[nodesState.network_address].states.push(svgNodeState)
        if (svgNodeState.start === this.start && svgNodeState.stop === this.stop) {
          svgNodesMap[nodesState.network_address].state = svgNodeState
        }
      }

      let nodesNetworkAddresses = this.nodes.map(el => el.network_address)
      for (let job of this.jobs) {
        let indices = []
        for (let node of job.nodes) {
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
            stop: stopDate === 0 ? (job.submitted_duration ? this.gantt_start_date + 60 * job.submitted_duration : this.gantt_stop_date) : stopDate,
            id: job.id,
            height: this.scale * (indicesArray[1] - indicesArray[0] + 1),
            color: '#00FF00',
            info: `Id: ${job.id}|User: ${job.user}|Name: ${job.name}|Nodes: ${job.nb_nodes}|Submission: ${job.submission_date}| Duration: ${job.submitted_duration} min`,
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

    px2date (y) {
      if (y < this.gantt_left_align) {
        return this.gantt_start_date
      }
      if (y > this.gantt_left_align + this.gantt_width) {
        return this.gantt_stop_date
      }
      return Math.round((y - this.gantt_left_align) * (this.gantt_stop_date - this.gantt_start_date) / this.gantt_width + this.gantt_start_date)
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
    },
    mouseOver (evt, message, hlElementClass) {
      let array = message.split('|')
      this.infobox.visible = true
      this.infobox.text = array
      this.infobox.height = array.length * CONF.text_scale + 20

      /* var length = 0
      var array
      var i = 0
      var tspan
      if (hlElementClass !== '') {
        this.highlight(null, hlElementClass, true)
      }
      array = message.split('|')
      let infoboxtext = this.$refs.infoboxtext
      let infoboxrect = this.$refs.infoboxrect
      let infobox = this.$refs.infobox
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
      infoboxrect.setAttribute('height', array.length * CONF['text_scale'] + 20) */
    },
    mouseOut (evt, hlElementClass) {
      this.infobox.visible = false
    },
    mouseMove (evt) {
      // let dim = this.$refs.table.getBoundingClientRect()
      this.infobox.x = evt.pageX
      this.infobox.y = evt.pageY
    },
    date2px (date) {
      if (date < this.gantt_start_date) {
        return 0
      }
      if (date > this.gantt_stop_date) {
        return this.gantt_width
      }
      return Math.round((this.gantt_width * (date - this.gantt_start_date)) / (this.gantt_stop_date - this.gantt_start_date))
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
.infobox .job {
  -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;

  cursor: default;
}
.job::selection .infobox::selection {
    background: none;
}
</style>
