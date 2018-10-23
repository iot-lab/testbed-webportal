<template>
  <div class="container mt-3">
    <h3><i class="fa fa-lg fa-fw fa-users" aria-hidden="true"></i> Users statistics</h3>
    <div class="dropdasn d-inline-block ">
      <button class="btn btn-light mr-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-fw fa-download"></i> Download</button>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="#" @click.prevent="downloadUsersStatisticsJson">IoT-LAB users statistics <span class="badge badge-pill badge-info">JSON</span></a>
        <a class="dropdown-item" href="#" @click.prevent="downloadUsersStatisticsCsv">IoT-LAB users statistics <span class="badge badge-pill badge-info">CSV</span></a>
      </div>
    </div>
    <h2 v-if="usersStatistics">Total number of accounts: {{usersStatistics.length}}</h2>
    <div>
      <label>Number of users by country: </label>
      <div>
        <a class="cursor" title="Show Table" @click="toggleUsersByCountry"><i class="fa fa-fw fa-eye"></i>Show Table</a>
        <a class="cursor" title="Download Data" @click="downloadUsersByCountry"><i class="fa fa-fw fa-download"></i>Download Data</a>
      </div>
      <table v-if="usersByCountryChart.table" class="table table-striped table-sm mt-2">
        <thead>
        <tr>
          <th class="cursor" title="sort by country" @click="sortBy(usersGroup => usersGroup.country)">Country Name</th>
          <th class="cursor" title="sort by size" @click="sortBy(usersGroup => usersGroup.users.length)">Number of users</th>
        </tr>
        </thead>
        <tbody>
          <template v-for="usersGroup in usersByCountry">
            <tr class="d-table-row">
              <td>{{usersGroup.country}}</td><td>{{usersGroup.usersNumber}}</td>
            </tr>
          </template>
        </tbody>
      </table>
      <apexcharts width="900" type="bar" :options="usersByCountryChart.options" :series="usersByCountryChart.series"></apexcharts>
    </div>
    <h3><i class="fa fa-lg fa-fw fa-flask" aria-hidden="true"></i> Experiments statistics</h3>
    <h2 v-if="experimentsStatistics">Total number of experiments: {{experimentsStatistics.length}}</h2>
    <div class="dropdasn d-inline-block ">
      <button class="btn btn-light mr-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-fw fa-download"></i> Download</button>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="#" @click.prevent="downloadExperimentsStatisticsJson">IoT-LAB experiments statistics <span class="badge badge-pill badge-info">JSON</span></a>
        <a class="dropdown-item" href="#" @click.prevent="downloadExperimentsStatisticsCsv">IoT-LAB experiments statistics <span class="badge badge-pill badge-info">CSV</span></a>
      </div>
    </div>
    <div>
      <label>Number of experiment per month: </label>
      <div>
        <a class="cursor" title="Show Table" @click="toggleExperimentsPerMonth"><i class="fa fa-fw fa-eye"></i>Show Table</a>
        <a class="cursor" title="Download Data" @click="downloadExperimentsPerMonth"><i class="fa fa-fw fa-download"></i>Download Data</a>
      </div>
      <table v-if="experimentsPerMonthChart.table" class="table table-striped table-sm mt-2">
        <thead>
        <tr>
          <th>Month (YYYY-MM)</th>
          <th>Number of experiments</th>
        </tr>
        </thead>
        <tbody>
        <template v-for="experimentMonth in experimentsPerMonth">
          <tr class="d-table-row">
            <td>{{experimentMonth.month}}</td><td>{{experimentMonth.experimentsNumber}}</td>
          </tr>
        </template>
        </tbody>
      </table>
      <apexcharts width="900" type="bar" :options="experimentsPerMonthChart.options" :series="experimentsPerMonthChart.series"></apexcharts>
    </div>
  </div> <!-- container -->
</template>

<script>
import { iotlab } from '@/rest'
import { downloadObjectAsJson, downloadObjectAsCsv, groupBy } from '@/utils'
import VueApexCharts from 'vue-apexcharts'
import moment from 'moment'

function Exception (message) {
  this.message = message
  this.name = 'Exception'
}

function usersByCountryGroup (users) {
  if (new Set(users.map(user => user.country)).size > 1) throw new Exception('groups should have the same country')
  return Object.assign({}, {
    usersNumber: users.length,
    country: users[0].country,
  })
}

export default {
  name: 'AdminStatistics',

  components: {
    apexcharts: VueApexCharts,
  },

  data () {
    return {
      experimentsStatistics: [],
      usersStatistics: [],
      usersByCountry: [],
      usersByCountryChart: {
        table: false,
        options: {
          xaxis: {
            categories: [],
          },
          dataLabels: {
            enabled: false,
          },
        },
        series: [{
          name: 'Users by country',
          data: [],
        }],
      },
      experimentsPerMonth: [],
      experimentsPerMonthChart: {
        table: false,
        options: {
          xaxis: {
            categories: [],
          },
          dataLabels: {
            enabled: false,
          },
        },
        series: [{
          name: 'Number of experiments',
          data: [],
        }],
      },
    }
  },

  methods: {
    toggleExperimentsPerMonth () {
      this.experimentsPerMonthChart.table = !this.experimentsPerMonthChart.table
    },
    toggleUsersByCountry () {
      this.usersByCountryChart.table = !this.usersByCountryChart.table
    },

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

    async downloadExperimentsPerMonth () {
      let data = this.experimentsPerMonth
      downloadObjectAsCsv(data, 'iotlab-experiments-per-month-statistics',
        {fields: Object.keys(data[0])})
    },

    async downloadUsersByCountry () {
      let data = this.usersByCountry
      downloadObjectAsCsv(data, 'iotlab-users-by-country-statistics',
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
      let grouped = groupBy(data, 'country')
      for (let users of Object.values(grouped)) {
        let group = usersByCountryGroup(users)
        this.usersByCountry.push(group)
        this.usersByCountryChart.options.xaxis.categories.push(group.country)
        this.usersByCountryChart.series[0].data.push(group.usersNumber)
      }
    },

    getUsersStatistics () {
      iotlab.getUsersStatistics()
        .then(this.addUsersStatistics)
        .catch(err => this.$notify({text: err.response.data.message || 'Failed to fetch Users statistics', type: 'error'}))
    },

    addExperimentsStatistics (data) {
      this.experimentsStatistics.push(...data.items)
      data.items.forEach(d => {
        if (d.submission_date) {
          let momDate = moment(String(d.submission_date))
          d.month = momDate.format('YYYY-MM')
        }
      })
      let grouped = groupBy(data.items, 'month')
      let items = Object.entries(grouped).map(([k, v]) => ({ month: k, experimentsNumber: v.length }))
      let seriesData = Object.values(grouped).map(el => el.length)

      this.experimentsPerMonth.push(...items)
      this.experimentsPerMonthChart.options.xaxis.categories.push(...Object.keys(grouped))
      this.experimentsPerMonthChart.series[0].data.push(...seriesData)
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
