<template>
  <div class='container mt-3'>
    <i class="btn btn-danger fa fa-trash float-right" @click="deleteLocalData">Delete local data</i>
    <ul class="nav nav-tabs" style="position: relative; top: 1px">
      <li class="nav-item" v-tooltip:top="'Users statistics'">
        <router-link to="/statistics/users" class="nav-link" :class="{active: statsType === 'users'}" data-toggle="list" href="#users" role="tab" aria-controls="users"><i class='fa fa-lg fa-fw fa-users' aria-hidden='true'></i> Users statistics </router-link>
      </li>
      <li class="nav-item" v-tooltip:top="'Experiments statistics'">
        <router-link to="/statistics/experiments" class="nav-link" :class="{active: statsType === 'experiments'}" data-toggle="list" href="#experiments" role="tab" aria-controls="experiments"><i class='fa fa-lg fa-fw fa-flask' aria-hidden='true'></i> Experiments statistics </router-link>
      </li>
    </ul>
    <div class="tab-pane" :class="{active: statsType === 'users'}" v-if="statsType === 'users'">
      <div v-if="usersLoaded">
        <div class='dropdasn d-inline-block '>
          <button class='btn btn-light mr-1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='fa fa-fw fa-download'></i> Download All Users statistics</button>
          <div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuButton'>
            <a class='dropdown-item' href='#' @click.prevent='downloadUsersStatisticsJson'>IoT-LAB users statistics <span class='badge badge-pill badge-info'>JSON</span></a>
            <a class='dropdown-item' href='#' @click.prevent='downloadUsersStatisticsCsv'>IoT-LAB users statistics <span class='badge badge-pill badge-info'>CSV</span></a>
          </div>
        </div>
        <h2>Total number of accounts: {{usersStatistics.length}}</h2>
        <bar-chart-table label='Number of users by country'
                        category_title='Country'
                        value_title='Number of users'
                        :data='usersByCountry'/>
        <stacked-relative-area-chart-table label='Users by country over time'
                                           category_title='Country'
                                           value_title='Number of users'
                                           :categories='countries'
                                           :data='relativeUsersByCountry'/>
        <bar-chart-table label='Number of users by continent'
                        category_title='Continent'
                        value_title='Number of users'
                        :data='usersByContinent'/>
        <stacked-relative-area-chart-table label='Users by continent over time'
                                           category_title='Continent'
                                           value_title='Number of users'
                                           :categories='continents'
                                           :data='relativeUsersByContinent'/>
        <bar-chart-table label='Number of users by category'
                        category_title='Category'
                        :data='usersByCategory'/>
        <stacked-relative-area-chart-table label='Users by category over time'
                                           category_title='Category'
                                           :categories='categories'
                                           :data='relativeUsersByCategory'/>
        <line-chart-table label="Running count number of users"
                          category_title="Date"
                          value_title='Number of users over time'
                          :data="usersRunningCount"/>
        <bar-chart-table label='User registrations per month'
                        category_title='Month'
                        :data='userRegistrationsPerMonth'/>
        <bar-chart-table label='User registrations per year'
                        category_title='Year'
                        :data='userRegistrationsPerYear'/>
      </div>
      <div v-if="!usersLoaded">
        <i class="fa fa-spinner fa-spin fa-fw mr-1"></i>
        <i>loading users statistics...</i>
      </div>
    </div>
    <div class="tab-pane" :class="{active: statsType === 'experiments'}" v-if="statsType === 'experiments'">
      <div v-if="experimentsLoaded">
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
        <bar-chart-table label='Usage ratio per month'
                         category_title='Month (YYYY-MM)'
                         value_title='Usage ratio (%)'
                         :data='experimentUsageRatio'/>
        <line-chart-table label="Number of experiments"
                          category_title="Date"
                          value_title='Number of experiments'
                          :data="experimentsRunningCount"/>
        <line-chart-table label="Cumulated experiment time"
                          category_title="Date"
                          :value_title='`Cumulated time of experiments (${experimentsRunningDuration.unit})`'
                          :data="experimentsRunningDuration.values"/>
        <line-chart-table label="Cumulated node run time"
                          category_title="Date"
                          :value_title='`Cumulated node run time (${experimentsRunningNodeDuration.unit})`'
                          :data="experimentsRunningNodeDuration.values"/>
        <stacked-relative-area-chart-table label='Experiments by node type over time'
                          category_title='Category'
                          :categories='nodetypes'
                          :data='relativeExperimentsByNodeType'/>
      </div>
      <div v-if="!experimentsLoaded">
        <i class="fa fa-spinner fa-spin fa-fw mr-1"></i>
        <i>loading experiments statistics, {{experimentsProgressPercentage}} % ...</i>
      </div>
    </div>
  </div> <!-- container -->
</template>

<script>
import { iotlab } from '@/rest'
import { downloadObjectAsJson, countGroupBy, downloadObjectAsCsv } from '@/utils'
import moment from 'moment'
import BarChartTable from '@/components/charts/BarChartTable'
import LineChartTable from '@/components/charts/LineChartTable'
import StackedRelativeAreaChartTable from '@/components/charts/StackedRelativeAreaChartTable'
import localForage from 'localforage'
import { COUNTRYCONTINENTS } from '@/countries'

export default {
  name: 'AdminStatistics',

  components: {
    BarChartTable, LineChartTable, StackedRelativeAreaChartTable,
  },

  props: {
    statsType: {
      type: String,
      default: 'users',
    },
  },

  data () {
    return {
      experimentsStore: null,
      experimentsStatistics: [],
      nodesStatistics: [],
      nodesMap: {},
      usersStatistics: [],
      tableOk: false,
      usersLoaded: false,
      experimentsLoaded: false,
      experimentsProgress: null,
    }
  },

  computed: {
    experimentsProgressPercentage () {
      if (this.experimentsProgress) {
        return (100 * this.experimentsProgress.value / this.experimentsProgress.max).toFixed(1)
      } else {
        return 0
      }
    },

    usersByCountry () {
      return countGroupBy(this.usersStatistics, 'country')
    },

    countries () {
      return Object.keys(this.usersByCountry)
    },

    usersByContinent () {
      return countGroupBy(this.usersStatistics, 'continent')
    },

    continents () {
      return Object.keys(this.usersByContinent)
    },

    usersByCategory () {
      return countGroupBy(this.usersStatistics, 'category')
    },

    userRegistrationsPerMonth () {
      return countGroupBy(this.usersStatistics, 'created_month')
    },

    userRegistrationsPerYear () {
      return countGroupBy(this.usersStatistics, 'created_year')
    },

    categories () {
      return Object.keys(this.usersByCategory)
    },

    nodetypes () {
      return ['a8', 'm3', 'wsn430', 'custom']
    },

    usersRunningCount () {
      let total = 0
      return this.usersLoaded ? this.usersStatistics.map(el => [el.created, total++]) : []
    },

    relativeUsersByCountry () {
      return this.relativeUsersBy('country')
    },

    relativeUsersByContinent () {
      return this.relativeUsersBy('continent')
    },

    relativeUsersByCategory () {
      return this.relativeUsersBy('category')
    },

    experimentsPerMonth () {
      return countGroupBy(this.experimentsStatistics, 'month')
    },

    relativeExperimentsByNodeType () {
      let running = {}
      let total = 0
      return this.experimentsLoaded ? this.experimentsStatistics.map(el => {
        total++
        if (el.nodes) {
          for (let node of el.nodes) {
            let nodeObj = this.nodesMap[node]
            if (nodeObj) {
              let nodeArchi = this.$options.filters.formatArchi(nodeObj.archi)
              if (nodeArchi !== 'a8' && nodeArchi !== 'm3' && nodeArchi !== 'wsn430') {
                nodeArchi = 'custom'
              }

              running[nodeArchi] = (running[nodeArchi] || 0) + 1
            }
          }
        }
        return [el.submission_date, {values: Object.assign({}, running), total: total}]
      }) : []
    },

    experimentsRunningCount () {
      let total = 0
      return this.experimentsLoaded ? this.experimentsStatistics.map(el => [el.submission_date, total++]) : []
    },

    experimentsRunningDuration () {
      let total = 0
      return this.getDatetimeValuesUnit(this.experimentsLoaded ? this.experimentsStatistics.map(el => {
        total += el.effective_duration * 60
        return [el.submission_date, total]
      }) : [])
    },

    experimentsRunningNodeDuration () {
      let total = 0
      return this.getDatetimeValuesUnit(this.experimentsLoaded ? this.experimentsStatistics.map(el => {
        total += el.nodes ? el.effective_duration * 60 * el.nodes.length : 0
        return [el.submission_date, total]
      }) : [])
    },

    experimentsDuration () {
      let duration = {}
      if (this.experimentsLoaded) {
        this.experimentsStatistics.forEach(el => {
          duration[el.month] = (duration[el.month] ? duration[el.month] : 0) + el.effective_duration
        })
      }
      return duration
    },

    experimentUsageRatio () {
      let ratios = Object.assign({}, this.experimentsDuration)
      for (var month in ratios) {
        let monthDuration = moment(month, 'YYYY-MM').daysInMonth() * 24 * 3600
        ratios[month] *= 100 / monthDuration
      }
      return ratios
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

    relativeUsersBy (key) {
      let running = {}
      let total = 0
      return this.usersLoaded ? this.usersStatistics.map(el => {
        total++
        running[el[key]] = (running[el[key]] || 0) + 1
        return [el.created, {values: Object.assign({}, running), total: total}]
      }) : []
    },

    getDatetimeValuesUnit (values) {
      let maxY = Math.max(...values.map(el => el[1]))
      var unit = 's'
      var scale = 1
      if (maxY > 60 && maxY < 3600) {
        unit = 'min'
        scale = 60
      } else if (maxY <= 24 * 3600) {
        unit = 'h'
        scale = 3600
      } else {
        unit = 'day'
        scale = 24 * 3600
      }
      return { values: values.map(el => [el[0], el[1] / scale]), unit: unit }
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
      this.nodesMap = {}
      data.forEach(value => {
        this.nodesMap[value.network_address] = value
      })
    },

    setUsersStatistics (data) {
      let treated = data.map(d => {
        if (d.created) {
          let date = moment(String(d.created))
          d.created = date
          d.created_year = date.format('YYYY')
          d.created_month = date.format('YYYY-MM')
          d.continent = COUNTRYCONTINENTS[d.country]
        }
        return d
      })

      treated.sort((a, b) => a.created.valueOf() - b.created.valueOf())
      this.usersStatistics = treated
      this.usersLoaded = true
    },

    getUsersStatistics () {
      this.usersLoaded = false
      iotlab.getUsersStatistics()
        .catch(err => {
          console.log(err)
          this.$notify({text: (err.response && err.response.data.message) || 'Failed to fetch Users statistics', type: 'error'})
        })
        .then(this.setUsersStatistics)
    },

    getNodesStatistics () {
      iotlab.getNodesStatistics()
        .catch(err => {
          console.log(err)
          this.$notify({text: (err.response && err.response.data.message) || 'Failed to fetch Nodes statistics', type: 'error'})
        })
        .then(this.setNodesStatistics)
    },

    treatExperimentsStatistics (data) {
      let treated = data.map(d => {
        if (d.submission_date) {
          let momDate = moment(String(d.submission_date))
          d.submission_date = momDate
          d.month = momDate.format('YYYY-MM')
          d.year = momDate.format('YYYY')
        }
        if (d.effective_duration === 0) {
          d.effective_duration = d.submitted_duration
        }
        if (d.start_date) d.start_date = moment(String(d.start_date))
        if (d.stop_date) d.stop_date = moment(String(d.stop_date))
        if (d.scheduled_date) d.scheduled_date = moment(String(d.scheduled_date))
        return d
      })

      treated.sort((a, b) => a.submission_date.valueOf() - b.submission_date.valueOf())
      return treated
    },

    errorHandler (err) {
      console.log(err)
      this.$notify({text: err, type: 'error'})
    },

    async getOrFetch (offset) {
      let val = await this.experimentsStore.getItem(String(offset)).catch(this.errorHandler)
      if (!val) {
        console.log('from network')
        val = await iotlab.getExperimentsStatistics(String(offset))
        // store data stringified
        this.experimentsStore.setItem(offset, JSON.stringify(val))
          .catch(this.errorHandler)
      } else {
        // retrieve stringified data
        console.log('from local storage')
        val = JSON.parse(val)
      }
      let treated = this.treatExperimentsStatistics(val)
      this.experimentsProgress.value++
      return treated
    },

    async getExperimentsStatistics () {
      this.experimentsLoaded = false
      let offsets = (await iotlab.getExperimentsOffsetStatistics())
      this.experimentsProgress = { value: 0, max: offsets.length }

      let promises = offsets.map(offset => this.getOrFetch(offset))
      Promise.all(promises).then(
        values => {
          this.experimentsStatistics = values.flat()
          this.experimentsLoaded = true
        }
      )
    },

    async setupDB () {
      this.experimentsStore = localForage.createInstance({
        driver: localForage.INDEXEDDB,
        name: 'iot-lab-stats-experiments',
      })
    },

    deleteLocalData () {
      confirm('Delete all cached local data and re-download the statistics data ? This can take a long time')
      this.experimentsStore.clear()
    },

    async loadData () {
      await this.getUsersStatistics()
      await this.getNodesStatistics()
      this.getExperimentsStatistics()
    },
  },

  async created () {
    await this.setupDB()
    this.loadData()
  },
}

</script>

<style scoped>
</style>
