<template>
  <div>
    <table class="table table-striped table-sm">
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
          <td :class="{'durationProgress': experimentProgress(exp) !== 0}" :style="`text-align: center; --progress: ${experimentProgress(exp)}%`">
            {{(exp.effective_duration || exp.submitted_duration) | humanizeDuration}}
            <small class="text-dark" v-if="experimentProgress(exp) !== 0">({{experimentProgress(exp)}}%)</small>
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
                <a class="dropdown-item" href="" @click.prevent="reloadExperiment(exp)">
                  <i class="fa fa-fw fa-refresh"></i> Restart
                </a>
                <a class="dropdown-item" href="" @click.prevent="this.alert('todo')">
                  <i class="fa fa-fw fa-clone"></i> Clone
                </a>
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

export default {
  name: 'ExperimentList',

  props: {
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
      default: 5,
    },
    step: {
      // Number of items to load on "load more" action
      type: Number,
      default: 50,
    },
  },

  data () {
    return {
      spinner: false,
      states: undefined,
      expStates: experimentStates,
      experiments: [],
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

  methods: {

    experimentProgress (exp) {
      // Gives the percentage of progress
      if (experimentStates.completed.includes(exp.state)) return 0
      return Math.min(100, 100 * exp.effective_duration / exp.submitted_duration)
    },

    startDate (exp) {
      return [exp.start_date, exp.scheduled_date, exp.submission_date].find(date => date !== '1970-01-01T01:00:00Z')
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
    },

    async stopExperiment (exp, confirmed = false) {
      if (!confirmed && !confirm('Cancel this experiment?')) return
      try {
        await iotlab.stopExperiment(exp.id)
        this.$notify({text: `Experiment ${exp.id} stopped`, type: 'success'})
        exp.state = 'Finishing'
      } catch (err) {
        console.log(err)
        this.$notify({text: err.message, type: 'error'})
      }
    },

    async reloadExperiment (exp) {
      try {
        let newExp = await iotlab.reloadExperiment(exp.id)
        this.$notify({text: `Experiment ${newExp.id} submitted`, type: 'success'})
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
  background: var(--light);
  /*border-radius: 3px;*/
}
.durationProgress::before {
  content: '';
  position: absolute;
  top: 0;
  /*top: -1px;*/
  left: 0;
  height: 100%;
  /*height: calc(100% + 1px);*/
  width: var(--progress);
  background-color: #67c37c;
  /*border: 1px solid var(--success);*/
  border-radius: 3px 0 0 3px;
  z-index: -1;
}
</style>
