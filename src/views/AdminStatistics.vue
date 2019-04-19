<template>
  <div class='container mt-3'>
    <h3><i class='fa fa-lg fa-fw fa-users' aria-hidden='true'></i> Users statistics</h3>
    <div v-show="usersLoaded">
      <div class='dropdasn d-inline-block '>
        <button class='btn btn-light mr-1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='fa fa-fw fa-download'></i> Download All Users statistics</button>
        <div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuButton'>
          <a class='dropdown-item' href='#' @click.prevent='downloadUsersStatisticsJson'>IoT-LAB users statistics <span class='badge badge-pill badge-info'>JSON</span></a>
          <a class='dropdown-item' href='#' @click.prevent='downloadUsersStatisticsCsv'>IoT-LAB users statistics <span class='badge badge-pill badge-info'>CSV</span></a>
        </div>
      </div>
      <h2>Total number of accounts: {{usersStatistics.length}}</h2>
      <h3>Filter by year of creation:</h3>
      <p v-if="userYears" class="mb-2">
        <span class="badge badge-pill mr-1 cursor" :class="{'badge-info': filterUserYear === null, 'badge-secondary': filterUserYear !== null}" @click="filterUserYear = null">all</span>
        <span v-for="year in userYears" :key="year" class="badge badge-pill mr-1 cursor"
              :class="{'badge-info': filterUserYear === year, 'badge-secondary': filterUserYear !== year}"
              @click="filterUserYear = year">{{year}}</span>
      </p>
      <bar-chart-table label='Number of users by country'
                      category_title='Country'
                      value_title='Number of users'
                      :data='usersByCountry'/>
      <bar-chart-table label='Number of users by category'
                      category_title='Category'
                      value_title='Number of users'
                      :data='usersByCategory'/>
      <line-chart-table label="Running count number of users"
                        category_title="Date"
                        value_title='Number of users over time'
                        :data="usersRunningCount"/>
    </div>
    <div v-if="!usersLoaded">
      <i class="fa fa-spinner fa-spin fa-fw mr-1"></i>
      <i>loading users statistics...</i>
    </div>
    <h3><i class='fa fa-lg fa-fw fa-flask' aria-hidden='true'></i> Experiments statistics</h3>
    <div v-show="experimentsLoaded">
      <div class='dropdasn d-inline-block '>
        <button class='btn btn-light mr-1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='fa fa-fw fa-download'></i> Download All Experiments statistics</button>
        <div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuButton'>
          <a class='dropdown-item' href='#' @click.prevent='downloadExperimentsStatisticsJson'>IoT-LAB experiments statistics <span class='badge badge-pill badge-info'>JSON</span></a>
          <a class='dropdown-item' href='#' @click.prevent='downloadExperimentsStatisticsCsv'>IoT-LAB experiments statistics <span class='badge badge-pill badge-info'>CSV</span></a>
        </div>
      </div>
      <h2 v-if='experimentsStatistics'>Total number of experiments: {{experimentsStatistics.length}}</h2>
      <bar-chart-table label='Number of experiment per month'
                       category_title='Month (YYYY-MM)'
                       value_title='Number of experiments'
                       :data='experimentsPerMonth'/>
      <line-chart-table label="Running count number of experiment"
                        category_title="Date"
                        value_title='Number of experiments over time'
                        :data="experimentsRunningCount"/>
    </div>
    <div v-if="!experimentsLoaded">
      <i class="fa fa-spinner fa-spin fa-fw mr-1"></i>
      <i>loading experiments statistics...</i>
    </div>
  </div> <!-- container -->
</template>

<script>
import { iotlab } from '@/rest'
import { downloadObjectAsJson, countGroupBy, downloadObjectAsCsv } from '@/utils'
import moment from 'moment'
import BarChartTable from '@/components/charts/BarChartTable'
import LineChartTable from '@/components/charts/LineChartTable'
import localForage from 'localforage'

export default {
  name: 'AdminStatistics',

  components: {
    BarChartTable, LineChartTable,
  },

  data () {
    return {
      experimentsStore: null,
      experimentsStatistics: [],
      usersStatistics: [],
      filterUserYear: null,
      tableOk: false,
      usersLoaded: false,
      experimentsLoaded: false,
    }
  },

  computed: {
    usersByCountry () {
      return countGroupBy(this.filteredUsers, 'country')
    },

    usersByCategory () {
      return countGroupBy(this.filteredUsers, 'category')
    },

    filteredUsers () {
      if (this.filterUserYear) {
        return this.usersStatistics.filter(el => el.created_year === this.filterUserYear)
      } else {
        return this.usersStatistics
      }
    },

    usersRunningCount () {
      let total = 0
      return this.usersLoaded ? this.usersStatistics.map(el => [el.created, total++]) : []
    },

    experimentsPerMonth () {
      return countGroupBy(this.experimentsStatistics, 'month')
    },

    experimentsRunningCount () {
      let total = 0
      return this.experimentsLoaded ? this.experimentsStatistics.map(el => [el.submission_date, total++]) : []
    },

    userYears () {
      return this.unique(this.usersStatistics.map(o => o.created_year))
    },

    experimentYears () {
      return this.unique(this.experimentsStatistics.map(o => o.year))
    },

    months () {
      return this.unique(this.experimentsStatistics.map(o => o.month))
    },
  },

  methods: {
    unique (array) {
      return [...new Set(array)]
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

    setNodesStatistics (data) {
      this.nodesStatistics = data
    },

    setUsersStatistics (data) {
      data.forEach((d, i, a) => {
        if (d.created) {
          let date = moment(String(d.created))
          d.created = date
          d.created_year = date.format('YYYY')
          a[i] = d
        }
      })

      this.usersStatistics = data
      this.usersStatistics.sort((a, b) => a.created.valueOf() - b.created.valueOf())
      this.usersLoaded = true
    },

    getUsersStatistics () {
      this.usersLoaded = false
      iotlab.getUsersStatistics()
        .then(this.setUsersStatistics)
        .catch(err => {
          console.log(err)
          this.$notify({text: (err.response && err.response.data.message) || 'Failed to fetch Users statistics', type: 'error'})
        })
    },

    getNodesStatistics () {
      iotlab.getNodesStatistics()
        .then(this.setNodesStatistics)
        .catch(err => {
          console.log(err)
          this.$notify({text: (err.rponse && err.response.data.message) || 'Failed to fetch Nodes statistics', type: 'error'})
        })
    },

    addExperimentsStatistics (offset, data) {
      data.forEach((d, i, a) => {
        if (d.submission_date) {
          let momDate = moment(String(d.submission_date))
          d.submission_date = momDate
          d.month = momDate.format('YYYY-MM')
          d.year = momDate.format('YYYY')
          a[i] = d
        }
        if (d.start_date) d.start_date = moment(String(d.start_date))
        if (d.stop_date) d.stop_date = moment(String(d.stop_date))
        if (d.scheduled_date) d.scheduled_date = moment(String(d.scheduled_date))
      })

      data.sort((a, b) => a.submission_date.valueOf() - b.submission_date.valueOf())
      this.experimentsStatistics.push(...data)
    },

    async getExperimentsStatistics () {
      this.experimentsLoaded = false
      let offsets = (await iotlab.getExperimentsOffsetStatistics())
      for (let i = 0; i < offsets.length; i++) {
        let offset = offsets[i].toString()

        this.experimentsStore.getItem(offset).catch(
          async err => {
            this.$notify({text: err, type: 'error'})
          }
        ).then(
          async val => {
            if (!val) {
              val = await iotlab.getExperimentsStatistics(offset)
              this.experimentsStore.setItem(offset, val)
            }
            this.addExperimentsStatistics(offset, val)
            if (i === offsets.length - 1) {
              this.experimentsLoaded = true
            }
          }
        )
      }
    },
    async setupDB () {
      this.experimentsStore = localForage.createInstance({
        driver: localForage.INDEXEDDB,
        name: 'iot-lab-stats-experiments',
      })
    },
  },

  async created () {
    await this.setupDB()
    await this.getUsersStatistics()
    await this.getExperimentsStatistics()
    await this.getNodesStatistics()
  },
}

</script>

<style scoped>
</style>
