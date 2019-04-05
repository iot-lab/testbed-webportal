<template>
  <table class="table table-striped table-sm" ref="table" v-if="nodes.length">
    <col width="10%"/>
    <col width="10%"/>
    <col width="80%"/>
    <thead>
      <tr>
        <th class="col-sticky">Node hostname</th>
        <th class="col-sticky text-center">State</th>
        <th class="col-sticky text-center">
          <div>Schedule</div>
          <div :style="`position: relative; height: 50px; width: 100%`">
            <div class="ruler" v-for="d in rulerValues"
              v-bind:style="{
                position: 'absolute',
                top: '0px',
                left: date2pc(d) + '%',
                textAlign: 'center',
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
    <tbody ref='table'>
      <tr class="nodeIteration" v-for="node in svgNodes" v-bind:key='node.network_address'>
        <td class="col-lg-2" v-tooltip:bottom="node.label">
          {{node.label}}
        </td>
        <td class="col-lg-1 text-center">
          <span class="badge badge-state cursor" :class="node.state | stateBadgeClass">{{node.state}}</span>
        </td>
        <td class="col-lg-8">
          <div :style="`position: relative; height: 25px; width: 100%`">
            <div class="timeRuler primary" v-for="d in rulerValues"
              v-bind:style="{
                top: '0px',
                left: date2pc(d) + '%',
                height: '100%',
                }">
            </div>
            <div class="timeRuler secondary" v-for="d in secondaryRulerValues"
              v-bind:style="{
                top: '0px',
                left: date2pc(d) + '%',
                height: '100%',
                }">
            </div>
            <div class="timeRuler now"
              v-bind:style="{
                position: 'absolute',
                top: '0px',
                left: date2pc(gantt_now) + '%',
                height: '100%',
                }">
            </div>

            <div v-for="nodesState in node.states" v-bind:key="nodesState.network_address"
                v-bind:style="nodeStateStyle(nodesState)"
                v-tooltip.auto.html="nodesState.info">
            </div>

            <router-link v-for="job in node.jobs" v-bind:key="job.key"
                tag="div" :to="{name: 'experimentDetails', params: { id: job.id }}"
                class="job justify-text-center cursor"
                :class="{ disabled: !job.reachable }"
                v-on:click.native="closeTooltip"
                v-bind:style="{
                  position: 'absolute',
                  backgroundColor: `hsl(${job2int(job)},${CONF.job_color_saturation_lightness})`,
                  width: job.width + '%',
                  left: date2pc(job.start) + '%',
                  userSelect: 'none',
                }"
                v-tooltip.auto.html="job.info">
              {{ job.id }}
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

const CONF = {
  timezone: 'UTC',
  resources_labels: ['network_address', 'cpuset'],
  state_colors: {
    Absent: '#ff0000',
    Suspected: '#000000',
    Dead: '#ff8080',
  },

  text_scale: 10,
  time_ruler_scale: 6,
  time_ruler_steps: [60, 120, 180, 300, 600, 1200, 1800, 3600, 7200, 10800, 21600, 28800, 43200, 86400, 172800, 259200, 604800],
  job_color_saturation_lightness: '75%,75%',
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
      default: () => { return {start: -86400, stop: 86400} },
    },
    nodes: {
      type: Array,
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
      nodesStates: [],
      jobs: [],
      CONF: CONF,
      now: null,
      table_height: 100,
    }
  },

  computed: {
    svgNodes () {
      let svgNodesMap = {}
      let nodes = this.nodes

      for (let node of nodes) {
        let svgNode = {
          label: this.$options.filters.stripDomain(node.network_address),
          state: node.state,
          network_address: node.network_address,
          states: [],
          jobs: [],
        }
        svgNodesMap[node.network_address] = svgNode
      }

      if (!this.nodesStates) return Object.values(svgNodesMap)

      for (let nodesState of this.nodesStates) {
        if (!(nodesState.network_address in svgNodesMap)) {
          continue
        }
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
        svgNodeState.info = `<b>${svgNodeState.state}</b><br>Since: ${this.formatDateTime(svgNodeState.start_date)}`
        if (!svgNodeState.open_ended) {
          svgNodeState.info += `<br>Until: ${this.formatDateTime(svgNodeState.stop_date)}`
        }

        svgNodesMap[nodesState.network_address].states.push(svgNodeState)
        if (svgNodeState.start === this.start && svgNodeState.stop === this.stop) {
          svgNodesMap[nodesState.network_address].state = svgNodeState
        }
      }

      let nodesNetworkAddresses = nodes.map(el => el.network_address)

      if (!this.jobs) return Object.values(svgNodesMap)

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
          let node = nodes[indicesArray[0]].network_address
          let svgNode = svgNodesMap[node]
          if (svgNode) {
            let svgJob = {
              y: svgNode.y,
              start: (new Date(job.start_date)).getTime() / 1000,
              stop: stopDate === 0 ? (job.submitted_duration ? this.gantt_start_date + 60 * job.submitted_duration : this.gantt_stop_date) : stopDate,
              id: job.id,
              height: this.scale * (indicesArray[1] - indicesArray[0] + 1),
              color: '#00FF00',
              info: `Id:&nbsp;${job.id}<br>User:&nbsp;${job.user}${job.name ? '<br>Name:&nbsp;' + job.name : ''}<br>Nodes:&nbsp;${job.nb_nodes}<br>Submission:&nbsp;${job.submission_date}<br>Duration:&nbsp;${job.submitted_duration}&nbsp;min`,
              reachable: auth.isAdmin || job.user === auth.username,
            }
            svgJob.width = this.date2pc(svgJob.stop) - this.date2pc(svgJob.start)
            svgNode.jobs.push(svgJob)
          }
        }
      }

      return Object.values(svgNodesMap)
    },
    ruler_step () {
      let value = (this.gantt_stop_date - this.gantt_start_date) / CONF.time_ruler_scale
      return CONF.time_ruler_steps.filter(r => r < value).pop()
    },
    secondary_ruler_step () {
      let value = (this.gantt_stop_date - this.gantt_start_date) / (4 * CONF.time_ruler_scale)
      return CONF.time_ruler_steps.filter(r => r < value).pop()
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
    gantt_top () {
      if (this.display !== 'no_ruler') {
        return CONF.gantt_top
      } else {
        return 1
      }
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
    if (!this.scale) {
      this.scale = CONF.scale
    }
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
    closeTooltip () {
      $('.tooltip').remove()
    },
    time (val) {
      return moment(this.now).add(val, 'seconds')
    },
    update (start, stop) {
      // only the time window changed, update the nodes states and jobs
      this.jobs = []
      this.nodesStates = []

      Promise.all([
        iotlab.getNodesStates(start, stop).catch((err) => this.errorHandler('nodes states', err)),
        iotlab.getExperimentsJobs(start, stop).catch((err) => this.errorHandler('jobs', err)),
      ]).then(([nodesStates, jobs]) => {
        this.nodesStates = nodesStates
        this.jobs = jobs
      })
    },

    load () {
      this.update(this.start, this.stop)
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
        width: (this.date2pc(nodesState.stop) - this.date2pc(nodesState.start)) + '%',
        left: this.date2pc(nodesState.start) + '%',
        // top: nodesState.y + 'px',
      }
    },

    nodeHostnameSort (n1, n2) {
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

    date2pc (date) {
      if (date < this.gantt_start_date) {
        return 0
      }
      if (date > this.gantt_stop_date) {
        return 100
      }
      return 100 * (date - this.gantt_start_date) / (this.gantt_stop_date - this.gantt_start_date)
    },

    job2int (job) {
      // compute a suffled number for id, so that colors are not too close
      let magicNumber = (1 + Math.sqrt(5)) / 2
      return parseInt(360 * this.fmod(job.id * magicNumber, 1))
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
table {
  table-layout: fixed;
}
td, th {
  white-space: nowrap;
}
.timeRuler {
  position: absolute;
  width: 1px;
  border-width: 1px;
  z-index: 1;
}
.primary {
  border-left: solid blue 1px;
  top: 47px;
}
.now {
  border-left: dotted red 2px;
  top: 47px;
}
.secondary {
  border-left: dotted blue 1px;
  top: 50px;
}
</style>
