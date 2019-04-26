<template>
  <div class='container mt-3'>
    <ul class="nav nav-tabs" style="position: relative; top: 1px">
      <li class="nav-item" v-tooltip:top="'Users statistics'">
        <router-link to="/statistics/users" class="nav-link" :class="{active: statsType === 'users'}"
        data-toggle="list" href="#users" role="tab" aria-controls="users"><i class='fa fa-lg fa-fw fa-users' aria-hidden='true'></i> Users statistics </router-link>
      </li>
      <li class="nav-item" v-tooltip:top="'Experiments statistics'">
        <router-link to="/statistics/experiments" class="nav-link" :class="{active: statsType === 'experiments'}"
        data-toggle="list" href="#experiments" role="tab" aria-controls="experiments"><i class='fa fa-lg fa-fw fa-flask' aria-hidden='true'></i> Experiments statistics </router-link>
      </li>
    </ul>
    <admin-users-statistics class="tab-pane" :class="{active: statsType === 'users'}" v-if="statsType === 'users'"/>
    <admin-experiments-statistics class="tab-pane" :class="{active: statsType === 'experiments'}" v-if="statsType === 'experiments'"/>
  </div> <!-- container -->
</template>

<script>
import AdminUsersStatistics from '@/views/AdminUsersStatistics'
import AdminExperimentsStatistics from '@/views/AdminExperimentsStatistics'

export default {
  name: 'AdminStatistics',

  components: {
    AdminUsersStatistics,
    AdminExperimentsStatistics,
  },

  props: {
    statsType: {
      type: String,
      default: 'users',
    },
  },
}

</script>

<style scoped>
</style>
