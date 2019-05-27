<template>
  <div>
    <div v-if="loaded">
      <div class='dropdasn d-inline-block '>
        <button class='btn btn-light mr-1' data-toggle='dropdown' aria-haspopup='true'
        aria-expanded='false'><i class='fa fa-fw fa-download'></i> Download All Users statistics</button>
        <div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuButton'>
          <a class='dropdown-item' href='#'
          @click.prevent='downloadUsersStatisticsJson'>IoT-LAB users statistics <span class='badge badge-pill badge-info'>JSON</span></a>
          <a class='dropdown-item' href='#'
          @click.prevent='downloadUsersStatisticsCsv'>IoT-LAB users statistics <span class='badge badge-pill badge-info'>CSV</span></a>
        </div>
      </div>
      <h2>Total number of accounts: {{usersStatistics.length}}</h2>
      <barct label='Number of users by country'
              category_title='Country'
              value_title='Number of users'
              :data='usersByCountry'/>
      <stackedct label='Users by country over time'
                  category_title='Date'
                  value_title='Ratio of users by country (%)'
                  :categories='countries'
                  :data='relativeUsersByCountry'/>
      <barct label='Number of users by continent'
              category_title='Continent'
              value_title='Number of users'
              :data='usersByContinent'/>
      <stackedct label='Users by continent over time'
                  category_title='Date'
                  value_title='Ratio of users by continent (%)'
                  :categories='continents'
                  :data='relativeUsersByContinent'/>
      <barct label='Number of users by category'
              category_title='Category'
              value_title="Number of users"
              :data='usersByCategory'/>
      <stackedct label='Users by category over time'
                  category_title='Date'
                  value_title="Ratio of users by category (%)"
                  :categories='categories'
                  :data='relativeUsersByCategory'/>
      <linect label="Running count number of users"
              category_title="Date"
              value_title='Number of users'
              :data="usersRunningCount"/>
      <barct label='User registrations per month'
              category_title='Month'
              value_title="User Registration"
              :chartOptions="{allowPie: false}"
              :data='userRegistrationsPerMonth'/>
      <barct label='User registrations per year'
              category_title='Year'
              value_title="User Registration"
              :chartOptions="{allowPie: false}"
              :data='userRegistrationsPerYear'/>
    </div>
    <div v-else>
      <i class="fa fa-spinner fa-spin fa-fw mr-1"></i>
      <i>loading users statistics...</i>
    </div>
  </div> <!-- container -->
</template>

<script>
import { iotlab } from '@/rest'
import { downloadObjectAsJson, countGroupBy, downloadObjectAsCsv, groupByFunc } from '@/utils'
import moment from 'moment'
import BarChartTable from '@/components/charts/BarChartTable'
import LineChartTable from '@/components/charts/LineChartTable'
import StackedRelativeAreaChartTable from '@/components/charts/StackedRelativeAreaChartTable'
import { COUNTRYCONTINENTS } from '@/countries'

const MAX_COUNTRY_ENTRIES = 10

export default {
  name: 'AdminUsersStatistics',

  components: {
    barct: BarChartTable,
    linect: LineChartTable,
    stackedct: StackedRelativeAreaChartTable,
  },

  props: {
    statsType: {
      type: String,
      default: 'users',
    },
  },

  data () {
    return {
      nodesMap: {},
      usersStatistics: [],
      tableOk: false,
      loaded: false,
    }
  },

  computed: {
    usersByCountry () {
      let usersByCountry = countGroupBy(this.usersStatistics, 'country')
      if (Object.keys(usersByCountry).length > MAX_COUNTRY_ENTRIES) {
        let sortable = Object.entries(usersByCountry)
        sortable.sort((a, b) => b[1] - a[1])
        let others = sortable.splice(MAX_COUNTRY_ENTRIES)
        let sum = others.reduce((total, item) => total + item[1], 0)
        sortable.push([`Others`, sum])
        return sortable.reduce((acc, elem) => {
          acc[elem[0]] = elem[1]
          return acc
        }, {})
      }
      return usersByCountry
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

    usersRunningCount () {
      let total = 0
      if (this.loaded) {
        let grouped = groupByFunc(this.usersStatistics, el => el.created.endOf('day').unix())
        return Object.entries(grouped).map(el => {
          total += el[1].length
          return [moment.unix(el[0]), total]
        })
      } else {
        return []
      }
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

    userYears () {
      return this.unique(this.usersStatistics.map(o => o.created_year))
    },
  },

  methods: {
    unique (array) {
      return [...new Set(array)]
    },

    relativeUsersBy (key) {
      if (this.loaded) {
        let running = {}
        let total = 0
        let runningDate
        return this.usersStatistics.map(el => {
          total++
          running[el[key]] = (running[el[key]] || 0) + 1
          let diff = runningDate === undefined ? Infinity : el.created.diff(runningDate, 'days')
          runningDate = el.created
          if (diff > 1) {
            return [el.created, {values: Object.assign({}, running), total: total}]
          }
        }).filter(el => el !== undefined)
      } else {
        return []
      }
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
      this.loaded = true
    },

    getUsersStatistics () {
      this.loaded = false
      iotlab.getUsersStatistics()
        .catch(err => {
          console.log(err)
          this.$notify({text: (err.response && err.response.data.message) || 'Failed to fetch Users statistics', type: 'error'})
        })
        .then(this.setUsersStatistics)
    },

    errorHandler (err) {
      console.log(err)
      this.$notify({text: err, type: 'error'})
    },

    async loadData () {
      await this.getUsersStatistics()
    },
  },

  async mounted () {
    this.loadData()
  },
}

</script>

<style scoped>
</style>
