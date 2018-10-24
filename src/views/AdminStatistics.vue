<template>
  <div class="container mt-3">
    <h3><i class="fa fa-lg fa-fw fa-users" aria-hidden="true"></i> Users statistics</h3>
    <div class="dropdasn d-inline-block ">
      <button class="btn btn-light mr-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-fw fa-download"></i> Download All Users statistics</button>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="#" @click.prevent="downloadUsersStatisticsJson">IoT-LAB users statistics <span class="badge badge-pill badge-info">JSON</span></a>
        <a class="dropdown-item" href="#" @click.prevent="downloadUsersStatisticsCsv">IoT-LAB users statistics <span class="badge badge-pill badge-info">CSV</span></a>
      </div>
    </div>
    <h2>Total number of accounts: {{usersStatistics.length}}</h2>
    <barcharttable label="Number of users by country"
                category_title='Country'
                value_title='Number of users'
                v-bind:data='usersByCountry'></barcharttable>

    <h3><i class="fa fa-lg fa-fw fa-flask" aria-hidden="true"></i> Experiments statistics</h3>
    <div class="dropdasn d-inline-block ">
      <button class="btn btn-light mr-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-fw fa-download"></i> Download All Experiments statistics</button>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="#" @click.prevent="downloadExperimentsStatisticsJson">IoT-LAB experiments statistics <span class="badge badge-pill badge-info">JSON</span></a>
        <a class="dropdown-item" href="#" @click.prevent="downloadExperimentsStatisticsCsv">IoT-LAB experiments statistics <span class="badge badge-pill badge-info">CSV</span></a>
      </div>
    </div>
    <h2 v-if="experimentsStatistics">Total number of experiments: {{experimentsStatistics.length}}</h2>
    <barcharttable label="Number of experiment per month"
                     category_title='Month (YYYY-MM)'
                     value_title='Number of experiments'
                     v-bind:data='experimentsPerMonth'></barcharttable>
  </div> <!-- container -->
</template>

<script>
import { iotlab } from '@/rest'
import { downloadObjectAsJson, downloadObjectAsCsv, groupBy } from '@/utils'
import moment from 'moment'
import BarChartTable from '@/components/charts/BarChartTable'

function Exception (message) {
  this.message = message
  this.name = 'Exception'
}

function usersByCountryDatum (users) {
  if (new Set(users.map(user => user.country)).size > 1) throw new Exception('groups should have the same country')
  return Object.assign({}, {
    value: users.length,
    category: users[0].country,
  })
}

function experimentsPerMonthDatum (experiments) {
  if (new Set(experiments.map(experiment => experiment.month)).size > 1) throw new Exception('groups should have the same YYYY-MM month')
  return Object.assign({}, {
    value: experiments.length,
    category: experiments[0].month,
  })
}

export default {
  name: 'AdminStatistics',

  components: {
    barcharttable: BarChartTable,
  },

  data () {
    return {
      experimentsStatistics: [],
      usersStatistics: [],
      usersByCountry: [],
      experimentsPerMonth: [],
    }
  },

  methods: {

    async downloadExperimentsStatisticsJson () {
      let data = this.experimentsStatistics
      downloadObjectAsJson(data, 'iotlab-experiments-statistics',
        {fields: Object.keys(data[0])})
    },

    async downloadExperimentsStatisticsCsv () {
      let data = this.experimentsStatistics
      downloadObjectAsCsv(data, 'iotlab-experiments-statistics',
        {fields: Object.keys(data[0])})
    },

    async downloadUsersStatisticsJson () {
      let data = this.experimentsStatistics
      downloadObjectAsJson(this.usersStatistics, 'iotlab-users-statistics',
        {fields: Object.keys(data[0])})
    },

    async downloadUsersStatisticsCsv () {
      let data = this.usersStatistics
      downloadObjectAsCsv(data, 'iotlab-users-statistics',
        {fields: Object.keys(data[0])})
    },

    sortBy (func, reverse = false) {
      this.usersByCountry = this.usersByCountry.sort((a, b) => {
        if (typeof func(a) === 'string' || typeof func(b) === 'string') return func(a).localeCompare(func(b))
        if (typeof func(a) === 'number' || typeof func(b) === 'number') return func(a) - func(b)
      })
      if (reverse) this.usersByCountry.reverse()
    },

    addUsersStatistics (data) {
      this.usersStatistics.push(...data)
      let groups = groupBy(data, 'country')
      for (let group of Object.values(groups)) {
        this.usersByCountry.push(usersByCountryDatum(group))
      }
    },

    getUsersStatistics () {
      iotlab.getUsersStatistics()
        .then(this.addUsersStatistics)
        .catch(err => this.$notify({text: err.response.data.message || 'Failed to fetch Users statistics', type: 'error'}))
    },

    addExperimentsStatistics (data) {
      this.experimentsStatistics.push(...data)
      data.forEach(d => {
        if (d.submission_date) {
          let momDate = moment(String(d.submission_date))
          d.month = momDate.format('YYYY-MM')
        }
      })
      let groups = groupBy(data, 'month')
      for (let group of Object.values(groups)) {
        this.experimentsPerMonth.push(experimentsPerMonthDatum(group))
      }
    },

    async getExperimentsStatistics () {
      let offsets = (await iotlab.getExperimentsOffsetStatistics())
      for (let i = 0; i < offsets.length; i++) {
        let offset = offsets[i]
        let experimentsStatisticsData = (await iotlab.getExperimentsStatistics(offset))
        this.addExperimentsStatistics(experimentsStatisticsData)
      }
    },
  },

  async created () {
    await this.getUsersStatistics()
    await this.getExperimentsStatistics()
  },
}

</script>

<style scoped>
</style>
