<template>
  <table class="table table-striped table-sm" ref="table" v-if="nodes.length">
    <col width="15%"/>
    <col width="85%"/>
    <thead>
      <tr>
        <th class="col-sticky text-center">Node hostname</th>
        <th class="col-sticky">
          <div style="position: relative; height: 50px; width: 100%">
            <div class="timeRulerLabel" v-for="d in rulerValues" :key="`ruler-label-${d}`"
                 :style="{ left: date2pc(d) + '%' }">
              <div style="position: relative; left:-50%; text-align:">
                <div>{{ ymddate(d) }}</div>
                <div>{{ hmsdate(d) }}</div>
              </div>
            </div>
          </div>
        </th>
      </tr>
    </thead>
    <tbody ref='table'>
      <tr class="nodeIteration" v-for="node in nodesExperimentsStates" v-bind:key='node.network_address'>
        <td class="hostname-column text-center" v-tooltip:bottom="node.label">
          {{node.label}}
        </td>
        <td>
          <div style="position: relative; height: 25px; width: 100%"
               v-on:mousemove="mousemove"
               v-on:mouseup="mouseup"
               v-on:mousedown="mousedown"
               v-on:mouseleave="mouseleave">
            <div class="timeRuler primary" v-for="d in rulerValues" :key="`ruler-${d}`"
                 :style="{ left: date2pc(d) + '%' }">
            </div>
            <div class="timeRange" v-if="window_start && window_stop" :style="{ left: relativedate2pc(window_start) + '%', width: (relativedate2pc(window_stop) - relativedate2pc(window_start)) + '%'}"/>
            <div class="timeRuler secondary" v-for="d in secondaryRulerValues" :key="`ruler-secondary-${d}`"
                 :style="{ left: date2pc(d) + '%' }">
            </div>
            <div class="timeRuler now"
                 :style="{ left: date2pc(gantt_now) + '%' }">
            </div>

            <div v-show="!loaded">
              <i class="fa fa-spinner fa-spin fa-fw mr-1"></i>
              <i>loading experiments and nodes states...</i>
            </div>

            <div class="nodeState" v-for="nodeState in node.states" v-bind:key="nodeState.network_address"
                :style="nodeStateStyle(nodeState)"
                v-tooltip:auto.html="nodeState.info">
            </div>

            <router-link v-for="experiment in node.experiments" v-bind:key="experiment.key"
                tag="div" :to="{name: 'experimentDetails', params: { id: experiment.id }}"
                class="experiment justify-text-center cursor"
                :class="{ disabled: !experiment.reachable }"
                v-on:click.native="closeTooltip"
                :style="experimentStyle(experiment)"
                v-tooltip:auto.html="experiment.info">
              {{ experiment.id }}
            </router-link>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</template>
<script>
import { iotlab } from '@/rest'
import moment from 'moment-timezone'
import $ from 'jquery'
import { auth } from '@/auth'
import { S_PER_MIN, S_PER_HOUR, S_PER_DAY, S_PER_WEEK } from '@/constants'

const CONF = {
  timezone: 'UTC',
  state_colors: {
    Absent: '#ff0000',
    Suspected: '#000000',
    Dead: '#ff8080',
  },

  text_scale: 10,
  time_ruler_scale: 6,
  time_ruler_steps: [
    S_PER_MIN,
    2 * S_PER_MIN,
    3 * S_PER_MIN,
    5 * S_PER_MIN,
    10 * S_PER_MIN,
    20 * S_PER_MIN,
    30 * S_PER_MIN,

    S_PER_HOUR,
    2 * S_PER_HOUR,
    3 * S_PER_HOUR,
    6 * S_PER_HOUR,
    8 * S_PER_HOUR,
    12 * S_PER_HOUR,

    S_PER_DAY,
    2 * S_PER_DAY,
    4 * S_PER_DAY,
    8 * S_PER_DAY,
    12 * S_PER_DAY,

    S_PER_WEEK,
    2 * S_PER_WEEK,
    4 * S_PER_WEEK,
    8 * S_PER_WEEK,
    12 * S_PER_WEEK,
  ],
  experiment_color_saturation_lightness: '75%,75%',
  min_width: 10,
}

export default {
  name: 'Gantt',

  props: {
    width: {
      type: Number,
      required: false,
    },
    gantt_relative_window: {
      type: Object,
    },
    nodes: {
      type: Array,
    },
    timezone: {
      type: String,
      default: 'UTC',
    },
  },

  data () {
    return {
      nodesStates: [],
      experiments: [],
      CONF: CONF,
      now: null,
      loaded: false,
      window_start: undefined,
      window_stop: undefined,
      drag: false,
    }
  },

  computed: {
    nodesExperimentsStates () {
      let map = {}
      let nodes = this.nodes

      for (let node of nodes) {
        let svgNode = {
          label: this.$options.filters.stripDomain(node.network_address),
          state: node.state,
          network_address: node.network_address,
          states: [],
          experiments: [],
        }
        map[node.network_address] = svgNode
      }

      if (!this.nodesStates) return Object.values(map)

      for (let nodesState of this.nodesStates) {
        if (!(nodesState.network_address in map)) {
          continue
        }
        let stopDate = (new Date(nodesState.stop_date)).getTime() / 1000
        let openEnded = stopDate === 0
        let svgNodeState = {
          start: moment(nodesState.start_date).unix(),
          start_date: moment(nodesState.start_date).tz(this.timezone),
          stop: openEnded ? this.gantt_stop_date : stopDate,
          stop_date: moment(nodesState.stop_date).tz(this.timezone),
          state: nodesState.state,
          open_ended: openEnded,
        }
        svgNodeState.info = `<b>${svgNodeState.state}</b><br>Since: ${this.formatDateTime(svgNodeState.start_date)}`
        if (!svgNodeState.open_ended) {
          svgNodeState.info += `<br>Until: ${this.formatDateTime(svgNodeState.stop_date)}`
        }

        map[nodesState.network_address].states.push(svgNodeState)
        if (svgNodeState.start === this.start && svgNodeState.stop === this.stop) {
          map[nodesState.network_address].state = svgNodeState
        }
      }

      let nodesNetworkAddresses = nodes.map(el => el.network_address)

      if (!this.experiments) return Object.values(map)

      for (let experiment of this.experiments) {
        let indices = []
        for (let node of experiment.nodes) {
          if (!(node in map)) {
            continue
          }
          indices.push(nodesNetworkAddresses.indexOf(node))
        }
        indices.sort()
        let stopDate = (new Date(experiment.stop_date)).getTime() / 1000

        for (let indice of indices) {
          let node = nodes[indice].network_address
          let svgNode = map[node]
          if (svgNode) {
            let svgExperiment = {
              start: (new Date(experiment.start_date)).getTime() / 1000,
              stop: stopDate === 0 ? (experiment.submitted_duration ? this.gantt_start_date + 60 * experiment.submitted_duration : this.gantt_stop_date) : stopDate,
              id: experiment.id,
              info: `Id:&nbsp;${experiment.id}<br>User:&nbsp;${experiment.user}${experiment.name ? '<br>Name:&nbsp;' + experiment.name : ''}<br>Nodes:&nbsp;${experiment.nb_nodes}<br>Submission:&nbsp;${experiment.submission_date}<br>Duration:&nbsp;${experiment.submitted_duration}&nbsp;min`,
              reachable: auth.isAdmin || experiment.user === auth.username,
            }
            svgNode.experiments.push(svgExperiment)
          }
        }
      }

      return Object.values(map)
    },
    ruler_step () {
      let value = (this.gantt_stop_date - this.gantt_start_date) / CONF.time_ruler_scale
      return CONF.time_ruler_steps.find(r => r >= value)
    },
    secondary_ruler_step () {
      return this.ruler_step / 4
    },
    secondaryRulerValues () {
      let d = this.gantt_start_date - this.gantt_start_date % this.secondary_ruler_step
      let values = []
      while ((d += this.secondary_ruler_step) < this.gantt_stop_date) {
        values.push(d)
      }
      return values
    },
    rulerValues () {
      let d = this.gantt_start_date - this.gantt_start_date % this.ruler_step
      let values = []
      while ((d += this.ruler_step) < this.gantt_stop_date) {
        values.push(d)
      }
      return values
    },
    gantt_relative_stop_date () {
      return this.gantt_relative_window.stop
    },
    gantt_relative_start_date () {
      return this.gantt_relative_window.start
    },
    start () {
      return this.time(this.gantt_relative_start_date)
    },
    stop () {
      return this.time(this.gantt_relative_stop_date)
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
    global.moment = moment

    this.now = moment.tz(this.timezone)
    this.gantt_now = this.now.unix()
    this.load()
  },

  watch: {
    gantt_relative_window (val, oldVal) {
      this.update(this.time(val.start), this.time(val.stop))
    },
  },

  methods: {
    click2date (e) {
      let rect = e.currentTarget.getBoundingClientRect()
      let ratio = (e.clientX - rect.left) / rect.width
      return this.gantt_relative_start_date + ratio * (this.gantt_relative_stop_date - this.gantt_relative_start_date)
    },
    mouseup (e) {
      this.$emit('set_zoom', {start: this.window_start, stop: this.click2date(e)})
      this.drag = false
    },
    mousedown (e) {
      this.drag = true
      this.window_start = this.click2date(e)
      this.window_stop = this.click2date(e)
    },
    mouseleave (e) {
      this.drag = false
    },
    clear_zoom () {
      this.window_start = undefined
      this.window_stop = undefined
    },
    mousemove (e) {
      if (this.drag) {
        this.window_stop = this.click2date(e)
      }
    },

    closeTooltip () {
      $('.tooltip').remove()
    },
    time (val) {
      return moment(this.now).add(val, 'seconds')
    },
    refresh () {
      console.log('Refreshing...')
      this.update(this.start, this.stop)
    },
    update (start, stop) {
      // only the time window changed, update the nodes states and experiments
      this.experiments = []
      this.nodesStates = []
      this.loaded = false

      Promise.all([
        iotlab.getDrawganttNodes(start, stop).catch((err) => this.errorHandler('drawgantt nodes', err)),
        iotlab.getDrawganttExperiments(start, stop).catch((err) => this.errorHandler('drawgantt experiments', err)),
      ]).then(([nodesStates, experiments]) => {
        this.nodesStates = nodesStates
        this.experiments = experiments
        this.loaded = true
      })
    },

    load () {
      this.update(this.start, this.stop)
    },

    formatDateTime (value) {
      return value.format('YYYY-MM-DD HH:mm')
    },

    errorHandler (type, err) {
      this.$notify({text: err.response.data.message || 'Failed to fetch ' + type, type: 'error'})
    },

    nodeStateStyle (nodeState) {
      let stateColor = CONF.state_colors[nodeState.state]
      return {
        background: `repeating-linear-gradient(-45deg, transparent, transparent 2.5px, ${stateColor} 2.5px, ${stateColor} 4.5px)`,
        width: (this.date2pc(nodeState.stop) - this.date2pc(nodeState.start)) + '%',
        left: this.date2pc(nodeState.start) + '%',
      }
    },

    experimentStyle (experiment) {
      return {
        backgroundColor: `hsl(${this.experiment2int(experiment)},${CONF.experiment_color_saturation_lightness})`,
        width: (this.date2pc(experiment.stop) - this.date2pc(experiment.start)) + '%',
        left: this.date2pc(experiment.start) + '%',
      }
    },

    ymddate (d) {
      return moment(d * 1000).tz(this.timezone).format('YYYY-MM-DD')
    },

    hmsdate (d) {
      return moment(d * 1000).tz(this.timezone).format('HH:mm')
    },

    fmod (a, b) {
      return Number((a - (Math.floor(a / b) * b)).toPrecision(8))
    },

    ratio2date (ratio) {
      return this.gantt_start_date + ratio * (this.gantt_stop_date - this.gantt_start_date)
    },

    relativedate2pc (relativedate) {
      if (relativedate < this.gantt_relative_start_date) {
        return 0
      }
      if (relativedate > this.gantt_relative_stop_date) {
        return 100
      }
      return 100 * (relativedate - this.gantt_relative_start_date) / (this.gantt_relative_stop_date - this.gantt_relative_start_date)
    },

    date2pc (date) {
      if (date < this.gantt_start_date) {
        return 0
      }
      if (date > this.gantt_stop_date) {
        return 100
      }
      return 100 * (date - this.gantt_start_date) / (this.gantt_stop_date - this.gantt_start_date)
    },

    experiment2int (experiment) {
      // compute a shuffled number for id, so that colors are not too close
      let magicNumber = (1 + Math.sqrt(5)) / 2
      return parseInt(360 * this.fmod(experiment.id * magicNumber, 1))
    },
  },
}
</script>
<style scoped>
th.col-sticky {
  position: sticky;
  top: 40px;  /* 0px if you don't have a navbar, but something is required */
  background: white;
  z-index: 2;
}
.hostname-column {
  overflow: hidden;
}
table {
  table-layout: fixed;
}
td, th {
  white-space: nowrap;
}
.timeRange {
  position: absolute;
  border-width: 1px;
  z-index: 1;
  height: 100%;
  background: #20539d22;
  pointer-events : none;
}
.timeRuler {
  position: absolute;
  width: 1px;
  border-width: 1px;
  z-index: 1;
  height: 100%;
  pointer-events : none;
}
.timeRulerLabel {
  position: absolute;
  text-align: center;
  display: inline-block;
}
.primary {
  border-left: solid blue 1px;
}
.now {
  border-left: dotted red 2px;
}
.secondary {
  border-left: dotted blue 1px;
}
.nodeState {
  background-size: 5px 5px;
  position: absolute;
  height: 100%;
}
.experiment {
  position: absolute;
  user-select: none;
}
</style>
