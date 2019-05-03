<template>
  <div>
    <i class="btn btn-danger fa fa-trash float-right" @click="refreshLocalData">Refresh local data</i>
    <div v-if="loaded">
      <div class='dropdasn d-inline-block '>
        <button class='btn btn-light mr-1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='fa fa-fw fa-download'></i> Download All Experiments statistics</button>
        <div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuButton'>
          <a class='dropdown-item' href='#'
          @click.prevent='downloadExperimentsStatisticsJson'>IoT-LAB experiments statistics <span class='badge badge-pill badge-info'>JSON</span></a>
          <a class='dropdown-item' href='#'
          @click.prevent='downloadExperimentsStatisticsCsv'>IoT-LAB experiments statistics <span class='badge badge-pill badge-info'>CSV</span></a>
        </div>
      </div>
      <h2 v-if='experimentsStatistics'>Total number of experiments: {{experimentsStatistics.length}}</h2>
      <barct label='Number of experiment per month'
            category_title='Month (YYYY-MM)'
            value_title='Number of experiments'
            :chartOptions="{allowPie: false}"
            :data='experimentsPerMonth'/>
      <barct label='Usage ratio per month'
              category_title='Month (YYYY-MM)'
              value_title='Usage ratio (%)'
              :chartOptions="{allowPie: false}"
              :data='experimentUsageRatio'/>
      <linect label="Number of experiments over time"
              category_title="Date"
              value_title='Number of experiments'
              :data="experimentsRunningCount"/>
      <linect label="Cumulated experiment time"
              category_title="Date"
              :value_title='`Cumulated time of experiments (${experimentsRunningDuration.unit})`'
              :data="experimentsRunningDuration.values"/>
      <linect label="Cumulated node run time"
              category_title="Date"
              :value_title='`Cumulated node run time (${experimentsRunningNodeDuration.unit})`'
              :data="experimentsRunningNodeDuration.values"/>
      <stackedct label='Experiments by node type over time'
                  category_title='Category'
                  :categories='nodetypes'
                  :data='relativeExperimentsByNodeType'/>
    </div>
    <div v-if="!loaded">
      <i class="fa fa-spinner fa-spin fa-fw mr-1"></i>
      <i>loading experiments statistics, {{experimentsProgressPercentage}} % ...</i>
    </div>
  </div>
</template>

<script>
import { iotlab } from '@/rest'
import { downloadObjectAsJson, countGroupBy, downloadObjectAsCsv } from '@/utils'
import moment from 'moment'
import BarChartTable from '@/components/charts/BarChartTable'
import LineChartTable from '@/components/charts/LineChartTable'
import StackedRelativeAreaChartTable from '@/components/charts/StackedRelativeAreaChartTable'
import AdminUsersStatistics from '@/views/AdminUsersStatistics'
import localForage from 'localforage'

export default {
  name: 'AdminExperimentsStatistics',

  components: {
    barct: BarChartTable,
    linect: LineChartTable,
    stackedct: StackedRelativeAreaChartTable,
    AdminUsersStatistics,
  },

  data () {
    return {
      experimentsStore: null,
      experimentsStatistics: [],
      nodesStatistics: [],
      nodesMap: {},
      tableOk: false,
      loaded: false,
      experimentsProgress: null,
      nodetypes: ['a8', 'm3', 'wsn430', 'custom'],
      // calculated
      experimentsPerMonth: [],
      relativeExperimentsByNodeType: [],
      experimentsRunningCount: [],
      experimentsRunningDuration: {},
      experimentsRunningNodeDuration: {},
      experimentsDuration: [],
      experimentUsageRatio: [],
      experimentYears: [],
      months: [],
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
  },

  methods: {
    unique (array) {
      return [...new Set(array)]
    },

    getExperimentsPerMonth () {
      return countGroupBy(this.experimentsStatistics, 'month')
    },

    getExperimentUsageRatio () {
      let ratios = Object.assign({}, this.experimentsDuration)
      for (var month in ratios) {
        let monthDuration = moment(month, 'YYYY-MM').daysInMonth() * 24 * 3600
        ratios[month] *= 100 / monthDuration
      }
      return ratios
    },

    treatAllExperimentsStatistics () {
      let months = new Set()
      let years = new Set()
      let duration = {}
      this.experimentsRunningCount = []
      let runningDuration = []
      let runningNodeDuration = []
      let running = {}

      let totalRunningCount = 0
      let totalRunningDuration = 0
      let totalRunningNodeDuration = 0

      this.experimentsStatistics.map(el => {
        months.add(el.month)
        years.add(el.year)
        duration[el.month] = (duration[el.month] ? duration[el.month] : 0) + el.effective_duration

        totalRunningCount++
        totalRunningDuration += el.effective_duration * 60
        totalRunningNodeDuration += el.nodes ? el.effective_duration * 60 * el.nodes.length : 0
        this.experimentsRunningCount.push([el.submission_date, totalRunningCount])
        runningDuration.push([el.submission_date, totalRunningDuration])
        runningNodeDuration.push([el.submission_date, totalRunningNodeDuration])

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
        this.relativeExperimentsByNodeType.push([el.submission_date, {values: Object.assign({}, running), total: totalRunningCount}])
      })
      this.months = [...months]
      this.years = [...years]
      this.experimentsDuration = duration
      this.experimentsRunningDuration = this.getDatetimeValuesUnit(runningDuration)
      this.experimentsRunningNodeDuration = this.getDatetimeValuesUnit(runningNodeDuration)

      this.experimentUsageRatio = this.getExperimentUsageRatio()
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

    setNodesStatistics (data) {
      this.nodesStatistics = data
      this.nodesMap = {}
      data.forEach(value => {
        this.nodesMap[value.network_address] = value
      })
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
        this.experimentsStore.setItem(String(offset), JSON.stringify(val))
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
      this.loaded = false
      let offsets = (await iotlab.getExperimentsOffsetStatistics())
      this.experimentsProgress = { value: 0, max: offsets.length }

      let promises = offsets.map(offset => this.getOrFetch(offset))
      Promise.all(promises).then(
        values => {
          this.experimentsStatistics = values.flat()

          this.treatAllExperimentsStatistics()

          this.experimentsPerMonth = this.getExperimentsPerMonth()

          this.loaded = true
        }
      )
    },

    async setupDB () {
      this.experimentsStore = localForage.createInstance({
        driver: localForage.INDEXEDDB,
        name: 'iot-lab-stats-experiments',
      })
    },

    refreshLocalData () {
      confirm('Delete all cached local data, and re-download the statistics data ? This can take a long time')
      this.experimentsStore.clear()
      this.experimentsStatistics = []
      this.loadData()
    },

    async loadData () {
      await this.getNodesStatistics()
      this.getExperimentsStatistics()
    },
  },

  async mounted () {
    await this.setupDB()
    this.loadData()
  },
}

</script>

<style scoped>
</style>
