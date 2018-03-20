<template>
  <div>
    <h4 class="text-secondary" v-if="title && experiments.length">{{title}}</h4>
    <table class="table table-striped table-sm" v-if="experiments.length">
      <thead>
        <tr>
          <th>Id</th>
          <th v-if="user != '@self'">User</th>
          <th>Name</th>
          <th>Date</th>
          <th style="text-align: center">Duration</th>
          <th style="text-align: right">Nodes</th>
          <th style="text-align: right">State</th>
          <th width="10px"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="exp in experiments">
          <td><router-link :to="{name: 'experimentDetails', params: { id: exp.id }}">{{exp.id}}</router-link></td>
          <td v-if="user != '@self'"><router-link :to="{name: 'users', query: { search: exp.user }}">{{exp.user}}</router-link></td>
          <td>{{exp.name}}</td>
          <td>{{startDate(exp) | formatDateTime}}</td>
          <td :class="{'durationProgress': showProgress(exp)}" :style="`text-align: center; --progress: ${experimentProgress(exp)}%`">
            {{(exp.effective_duration || exp.submitted_duration) | humanizeDuration}}
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
                </a>
                <a class="dropdown-item text-danger" href="" @click.prevent="stopExperiment(exp)"
                  v-if="expStates.stoppable.includes(exp.state)">
                  <i class="fa fa-fw fa-ban"></i> Cancel
                </a>
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
    <p v-show="spinner">
      <i class="fa fa-spinner fa-spin fa-fw"></i>
    </p>
  </div>
</template>

<script>
import { iotlab } from '@/rest'
import { experimentStates } from '@/assets/js/iotlab-utils'
import { sleep } from '@/utils'

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
      default: 50,
    },
    started: {
      // Started experiment counter, increment to force component to refresh
      type: Number,
      default: 0,
    },
  },

  data () {
    return {
      spinner: false,
      states: undefined,
      expStates: experimentStates,
      experiments: [],
      polling: false,
    }
  },

  async created () {
    this.states = this.state
    if (this.states === 'all_scheduled') {
      this.states = 'Running,Finishing,Resuming,toError,Waiting,Launching,Hold,toLaunch,toAckReservation,Suspended'
    }
    if (this.states === 'all_terminated') {
      this.states = 'Terminated,Stopped,Error'
    }

    this.loadMore(this.show)
  },

  destroyed () {
    this.stopPolling()
  },

  watch: {
    total: function (newTotal, oldTotal) {
      // experiment total changed, let's refresh including new xp
      this.refresh(newTotal - oldTotal)
    },
    started: function () {
      // started counter has been touched, let's refresh
      this.refresh()
    },
  },

  methods: {

    showProgress: exp => exp.state === 'Running',

    experimentProgress (exp) {
      // Gives the percentage of progress
      if (experimentStates.completed.includes(exp.state)) return 0
      return Math.min(100, Math.round(100 * exp.effective_duration / exp.submitted_duration))
    },

    startDate (exp) {
      return [exp.start_date, exp.scheduled_date, exp.submission_date].find(date => date !== '1970-01-01T00:00:00Z')
    },

    async loadMore (qty) {
      this.spinner = true

      this.experiments = this.experiments.concat((await iotlab.getAllExperiments({
        user: this.user,
        state: this.states,
        offset: Math.max(this.total - this.experiments.length - qty, 0),
        limit: Math.min(this.total - this.experiments.length, qty),
      })).sort((exp1, exp2) => exp2.id - exp1.id)) // order by reverse ID

      this.spinner = false

      if (this.experiments.some(exp => experimentStates.scheduled.includes(exp.state))) {
        this.startPolling()
      }
    },

    async refresh (more = 0) {
      let previous = this.experiments
      this.experiments = (await iotlab.getAllExperiments({
        user: this.user,
        state: this.states,
        // offset: Math.max(this.total - this.experiments.length - qty, 0),
        offset: Math.max(this.total - this.experiments.length - more, 0),
        limit: Math.max(this.experiments.length, this.show) + more,
      })).sort((exp1, exp2) => exp2.id - exp1.id) // order by reverse ID

      if (this.experiments.some(exp => experimentStates.scheduled.includes(exp.state))) {
        this.startPolling()
      } else {
        this.stopPolling()
      }

      if (this.experiments.length < previous.length) {
        // some experiments have ended, let's notify
        this.$emit('completed')
      }
    },

    startPolling () {
      // create watchdog to poll for scheduled experiments update
      if (!this.polling) this.polling = setInterval(() => this.refresh(), 5000)
    },

    stopPolling () {
      clearInterval(this.polling)
      this.polling = false
    },

    async stopExperiment (exp, confirmed = false) {
      if (!confirmed && !confirm('Cancel this experiment?')) return
      try {
        await iotlab.stopExperiment(exp.id)
        this.$notify({text: `Experiment ${exp.id} stopping`, type: 'success'})
        if (exp.state === 'Running') exp.state = 'Finishing'
      } catch (err) {
        this.$notify({text: err.message, type: 'error'})
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
        this.$notify({text: err.message, type: 'error'})
      }
    },
  },
}
</script>

<style scoped>
.dropdown .dropdown-toggle:hover {
  filter: brightness(0.5);
}

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
