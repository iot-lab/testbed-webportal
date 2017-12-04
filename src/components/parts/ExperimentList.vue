<template>
  <div>
    <table class="table table-striped table-sm">
      <thead>
        <tr>
          <th>Id</th>
          <th>Owner</th>
          <th>Name</th>
          <th>Date</th>
          <th>Duration</th>
          <th>Nodes</th>
          <th>State</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="experiment in experiments">
          <td><router-link :to="{name: 'experimentDetails', params: { id: experiment.id }}">{{experiment.id}}</router-link></td>
          <td v-if="user != '@self'"><router-link :to="{name: 'users', query: { search: experiment.owner }}">{{experiment.owner}}</router-link></td>
          <td v-else>{{experiment.owner}}</td>
          <td>{{experiment.name}}</td>
          <td>{{experiment.date | formatDateTime}}</td>
          <td>{{experiment.duration | humanizeDuration}}</td>
          <td>{{experiment.nb_resources}}</td>
          <td><span class="badge badge-state" :class="experiment.state | stateBadgeClass">{{experiment.state}}</span></td>
        </tr>
        <tr v-if="experiments && experiments.length === 0">
          <td colspan="7" class="font-italic">No items found</td>
        </tr>
      </tbody>
    </table>
    <p v-if="experiments === undefined">
      <i class="fa fa-spinner fa-spin fa-fw"></i>
    </p>
  </div>
</template>

<script>
import {iotlab} from '@/rest'
import {auth} from '@/auth'

export default {
  name: 'ExperimentList',

  props: {
    user: {
      // Filter experiments by user. Leave empty for all users. Or '@self' for current (logged in) user.
      type: String,
    },
    state: {
      // List of user fields to be hidden from the form
      type: String,
    },
    offset: {
      // Start query at offset
      type: Number,
    },
    limit: {
      // Max items to query
      type: Number,
    },
  },

  data () {
    return {
      experiments: undefined,
      auth: auth,
    }
  },

  async created () {
    this.experiments = await iotlab.getAllExperiments({
      user: this.user,
      state: this.state,
      offset: this.offset,
      limit: this.limit,
    })
  },
}
</script>
