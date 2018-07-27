<template>
  <div>
    <h4 class="text-secondary" v-if="title && experiments.length">{{title}}</h4>
    <table class="table table-striped table-sm" v-if="experiments.length">
      <thead>
        <tr>
          <th class="cursor" title="sort by id" @click="sortBy(xp => xp.id)">Id</th>
          <th class="cursor" title="sort by user" @click="sortBy(xp => xp.user)" v-if="user != '@self'">User</th>
          <th class="cursor" title="sort by name" @click="sortBy(xp => xp.name)">Name</th>
          <th class="cursor" title="sort by date" @click="sortBy(xp => expDate(xp))">Date</th>
          <th class="cursor" title="sort by duration" @click="sortBy(xp => expDuration(xp))" style="text-align: center">Duration</th>
          <th class="cursor" title="sort by nodes" @click="sortBy(xp => xp.nb_nodes)" style="text-align: right">Nodes</th>
          <th class="cursor" title="sort by state" @click="sortBy(xp => xp.state)" style="text-align: right">State</th>
          <th width="10px"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="exp in experiments">
          <td><router-link :to="{name: 'experimentDetails', params: { id: exp.id }}">{{exp.id}}</router-link></td>
          <td v-if="user != '@self'"><router-link :to="{name: 'users', query: { search: exp.user }}">{{exp.user}}</router-link></td>
          <td v-html="allowBreak(exp.name)"></td>
          <td :title="showEnding(exp)">{{expDate(exp) | formatDateTime}}</td>
          <td :class="{'durationProgress': showProgress(exp)}" :style="`text-align: center; --progress: ${experimentProgress(exp)}%`" :title="showRemaining(exp)">
            {{expDuration(exp) | humanizeDuration}}
            <small class="text-dark" v-if="showProgress(exp)">({{experimentProgress(exp)}}%)</small>
          </td>
          <td style="text-align: right">{{exp.nb_nodes}}</td>
          <td style="text-align: right"><span class="badge badge-state" :class="exp.state | stateBadgeClass">{{exp.state}}</span></td>
          <td>
            <div class="dropdown">
              <button class="btn-link border-0 text-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
              <div class="dropdown-menu dropdown-menu-right">
                <router-link class="dropdown-item" :to="{name: 'experimentDetails', params: { id: exp.id }}">
                  <i class="fa fa-fw fa-eye"></i> View details
                </router-link>
                <a class="dropdown-item text-danger" href="" @click.prevent="stopExperiment(exp)"
                  v-if="expStates.stoppable.includes(exp.state)">
                  <template v-if="exp.state === 'Running'">
                    <i class="fa fa-fw fa-stop-circle"></i> Stop
                  </template>
                  <template v-else>
                    <i class="fa fa-fw fa-ban"></i> Cancel
                  </template>
                </a>
                <template v-if="exp.user === currentUser">
                  <a class="dropdown-item" href="" @click.prevent="startExperiment(exp)"
                    v-if="exp.state === 'Waiting'">
                    <i class="fa fa-fw fa-play"></i> Start
                  </a>
                  <a class="dropdown-item" href="" @click.prevent="reloadExperiment(exp)"
                    v-if="expStates.completed.includes(exp.state)">
                    <i class="fa fa-fw fa-refresh"></i> Restart
                  </a>
                  <!-- <a class="dropdown-item" href="" @click.prevent="this.alert('todo')">
                    <i class="fa fa-fw fa-clone"></i> Clone
                  </a> -->
                </template>
              </div>
            </div>

          </td>
        </tr>
        <tr v-show="!spinner && experiments.length === 0">
          <td colspan="7" class="font-italic">No items to display</td>
        </tr>
      </tbody>
      <tfoot v-if="total > experiments.length && !spinner">
        <tr>
          <td colspan="7" class="font-italic cursor btn-link" @click="loadMore(step)">Load more...</td>
        </tr>
      </tfoot>
    </table>
    <div v-show="spinner">
      <i class="fa fa-spinner fa-spin fa-fw"></i>
      <i>loading experiments</i>
    </div>
  </div>
</template>

<script>
import { auth } from '@/auth'
import { iotlab } from '@/rest'
import { experimentStates } from '@/assets/js/iotlab-utils'
import { sleep } from '@/utils'

var polling
var blured = false

export default {
  name: 'ExperimentList',

  props: {
    title: {
      // Optional title
      type: String,
      default: '',
    },
    user: {
      // Filter experiments by user. Leave empty for all users. Or '@self' for current (logged in) user.
      type: String,
    },
    state: {
      // Comma separated list of states to be included in the query
      // or use meta state:
      // 'all_scheduled' -> 'Running,Finishing,Resuming,toError,Waiting,Launching,Hold,toLaunch,toAckReservation,Suspended'
      // 'all_terminated' -> 'Terminated,Stopped,Error'
      type: String,
    },
    total: {
      // Total number of experiments
      // (mandatory to display experiments with bigger ID first, since the API results are ordered by ID ascending)
      type: Number,
      default: 0,
    },
    show: {
      // Max number of items to load intially
      type: Number,
      default: 500,
    },
    step: {
      // Number of items to load on "load more" action
      type: Number,
      default: 40,
    },
    loader: {
      // Show a loading message during initial render
      type: Boolean,
      default: false,
    },
  },

  data () {
    return {
      currentUser: auth.username,
      spinner: false,
      states: [],
      expStates: experimentStates,
      experiments: [],
    }
  },

  async created () {
    this.states = this.state.split(',')

    if (this.state === 'all_scheduled') {
      this.states = experimentStates.scheduled
    }
    if (this.state === 'all_terminated') {
      this.states = experimentStates.completed
    }

    this.createPolling()

    await this.loadMore(this.show, this.loader)
    this.$emit('loaded')
  },

  destroyed () {
    this.destroyPolling()
  },

  computed: {
    hasPolling () {
      return this.states.some(state => experimentStates.scheduled.includes(state))
    },
  },

  watch: {
    total: function (newTotal, oldTotal) {
      // experiment total changed, let's refresh including new xp
      this.refresh(newTotal - oldTotal)
    },
  },

  methods: {

    allowBreak (name) {
      // Insert break points for the browser to split long names into several lines if necessary
      return name.replace(/_/g, '_<wbr>') // allow split on "_"
    },

    showProgress: exp => exp.state === 'Running',

    showEnding (exp) {
      if (this.showProgress(exp)) {
        return `ending ${this.$options.filters.formatDateTime(exp.start_date, exp.submitted_duration)}`
      }
    },

    showRemaining (exp) {
      if (this.showProgress(exp)) {
        return `remaining ${this.$options.filters.humanizeDuration(exp.submitted_duration - exp.effective_duration)}`
      }
    },

    experimentProgress (exp) {
      // Gives the percentage of progress
      if (experimentStates.completed.includes(exp.state)) return 0
      return Math.min(100, Math.round(100 * exp.effective_duration / exp.submitted_duration))
    },

    expDate (exp) {
      return [exp.start_date, exp.scheduled_date, exp.submission_date].find(date => date !== '1970-01-01T00:00:00Z')
    },

    expDuration (exp) {
      return exp.effective_duration || exp.submitted_duration
    },

    sortBy (func) {
      this.experiments = this.experiments.sort((a, b) => {
        if (func(a) === func(b)) return b.id - a.id
        if (typeof func(a) === 'string' || typeof func(b) === 'string') return func(b).localeCompare(func(a))
        if (typeof func(a) === 'number' || typeof func(b) === 'number') return func(b) - func(a)
        console.log('Unhandled sort criteria', a.id, func(a), b.id, func(b))
      })
    },

    async loadMore (qty, spinner = true) {
      if (spinner) this.spinner = true

      // await sleep(2000)
      this.experiments = this.experiments.concat((await iotlab.getAllExperiments({
        user: this.user,
        state: this.states.join(','),
        offset: Math.max(this.total - this.experiments.length - qty, 0),
        limit: Math.min(this.total - this.experiments.length, qty),
      })).sort((exp1, exp2) => exp2.id - exp1.id)) // order by reverse ID

      if (spinner) this.spinner = false
    },

    async refresh (more = 0) {
      console.debug('refresh')
      let previous = this.experiments
      this.experiments = (await iotlab.getAllExperiments({
        user: this.user,
        state: this.states.join(','),
        // offset: Math.max(this.total - this.experiments.length - qty, 0),
        offset: Math.max(this.total - this.experiments.length - more, 0),
        limit: Math.max(this.experiments.length, this.show) + more,
      })).sort((exp1, exp2) => exp2.id - exp1.id) // order by reverse ID

      if (this.experiments.length < previous.length) {
        // some experiments have ended, let's notify
        this.$emit('completed')
      }
    },

    pollingLoop () {
      // console.debug('polling loop')
      // refresh experiments only if browser is active
      if (this.hasPolling && !blured) this.refresh()
    },

    stopPolling () {
      console.debug('polling stopped')
      polling = clearInterval(polling)
    },

    disablePolling () {
      // console.debug('polling disabled')
      if (!blured) {
        blured = true
        this.$notify({text: 'Dashboard refresh paused', type: 'info', duration: -1, group: 'alt'})
      }
    },

    enablePolling () {
      // console.debug('polling enabled')
      blured = false
      this.$notify({group: 'alt', clean: true})
    },

    createPolling () {
      if (this.hasPolling) {
        console.debug('polling started')
        // start polling callback (5 seconds)
        polling = setInterval(() => this.pollingLoop(), 5000)
        // poll only when browser window is active
        addEventListener('blur', this.disablePolling)
        addEventListener('focus', this.enablePolling)
      }
    },

    destroyPolling () {
      if (polling) {
        this.enablePolling()
        this.stopPolling()
        removeEventListener('blur', this.disablePolling)
        removeEventListener('focus', this.enablePolling)
      }
    },

    async stopExperiment (exp, confirmed = false) {
      if (!confirmed && !confirm('Cancel this experiment?')) return
      try {
        await iotlab.stopExperiment(exp.id)
        this.$notify({text: `Experiment ${exp.id} stopping`, type: 'success'})
      } catch (err) {
        this.$notify({text: err.response.data.message || 'Failed to stop experiment', type: 'error'})
      }
    },

    async startExperiment (exp) {
      if (!confirm('Resubmit now and cancel this experiment?')) return
      await this.stopExperiment(exp, true)
      await this.reloadExperiment(exp)
    },

    async reloadExperiment (exp) {
      try {
        let newExp = await iotlab.reloadExperiment(exp.id)
        this.$notify({text: `Experiment ${newExp.id} submitted`, type: 'success'})
        await sleep(200)
        this.$emit('started')
      } catch (err) {
        this.$notify({text: err.response.data.message || 'Failed to reload experiment', type: 'error'})
      }
    },
  },
}
</script>

<style scoped>
.dropdown .dropdown-toggle:hover {
  filter: brightness(0.5);
}
</style>

<style>
.durationProgress {
  position: relative;
  z-index: 1;
  text-align: center;
}
.durationProgress::after {
  content: '';
  position: absolute;
  top: 5px;
  left: 0;
  height: calc(100% - 10px);
  width: var(--progress);
  background-color: #17a2b890;
  border-left: 0.5px solid #17a2b850;
  border-top: 0.5px solid #17a2b850;
  border-bottom: 0.5px solid #17a2b850;
  border-radius: 3px 0 0 3px;
  z-index: -1;
}
.durationProgress::before {
  content: '';
  position: absolute;
  top: 5px;
  left: 0;
  height: calc(100% - 10px);
  width: 100%;
  background-color: var(--light);
  border: 0.5px solid lightgrey;
  border-radius: 3px;
  z-index: -1;
}
</style>
