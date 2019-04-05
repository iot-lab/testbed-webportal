<template>
  <div class='container mt-3'>
    <h3><i class='fa fa-lg fa-fw fa-users' aria-hidden='true'></i> Users statistics</h3>
    <div class='dropdasn d-inline-block '>
      <button class='btn btn-light mr-1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='fa fa-fw fa-download'></i> Download All Users statistics</button>
      <div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuButton'>
        <a class='dropdown-item' href='#' @click.prevent='downloadUsersStatisticsJson'>IoT-LAB users statistics <span class='badge badge-pill badge-info'>JSON</span></a>
        <a class='dropdown-item' href='#' @click.prevent='downloadUsersStatisticsCsv'>IoT-LAB users statistics <span class='badge badge-pill badge-info'>CSV</span></a>
      </div>
    </div>
    <h2>Total number of accounts: {{usersStatistics.length}}</h2>
    <h3>Filter by year of creation:</h3>
    <select v-model="filterUserYear" class="mr-1 bg-light form-control" >
      <option v-for="year in userYears" :key="year" :value="year" :selected="year === filterUserYear">{{year}}</option>
    </select>
    <barcharttable label='Number of users by country'
                   category_title='Country'
                   value_title='Number of users'
                   v-bind:data='usersByCountry'></barcharttable>
    <barcharttable label='Number of users by category'
                   category_title='Category'
                   value_title='Number of users'
                   v-bind:data='usersByCategory'></barcharttable>
    <h3><i class='fa fa-lg fa-fw fa-flask' aria-hidden='true'></i> Experiments statistics</h3>
    <div class='dropdasn d-inline-block '>
      <button class='btn btn-light mr-1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='fa fa-fw fa-download'></i> Download All Experiments statistics</button>
      <div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuButton'>
        <a class='dropdown-item' href='#' @click.prevent='downloadExperimentsStatisticsJson'>IoT-LAB experiments statistics <span class='badge badge-pill badge-info'>JSON</span></a>
        <a class='dropdown-item' href='#' @click.prevent='downloadExperimentsStatisticsCsv'>IoT-LAB experiments statistics <span class='badge badge-pill badge-info'>CSV</span></a>
      </div>
    </div>
    <h2 v-if='experimentsStatistics'>Total number of experiments: {{experimentsStatistics.length}}</h2>
    <barcharttable label='Number of experiment per month'
                   category_title='Month (YYYY-MM)'
                   value_title='Number of experiments'
                   v-bind:data='experimentsPerMonth'></barcharttable>
  </div> <!-- container -->
</template>

<script>
import { iotlab } from '@/rest'
import { downloadObjectAsJson, downloadObjectAsCsv } from '@/utils'
import moment from 'moment'
import BarChartTable from '@/components/charts/BarChartTable'
import localForage from 'localforage'
import alasql from 'alasql'

export default {
  name: 'AdminStatistics',

  components: {
    barcharttable: BarChartTable,
  },

  data () {
    return {
      experimentsStore: null,
      experimentsStatistics: [],
      usersStatistics: [],
      filterUserYear: null,
      tableOk: false,
    }
  },

  computed: {
    usersByCountry () {
      if (this.filterUserYear) {
        return alasql(`SELECT country AS category, COUNT(*) AS [value] FROM ? WHERE created_year = "?" GROUP BY country`, [this.usersStatistics, this.filterUserYear])
      } else {
        return alasql('SELECT country AS category, COUNT(*) AS [value] FROM ? GROUP BY country', [this.usersStatistics])
      }
    },

    usersByCategory () {
      if (this.filterUserYear) {
        return alasql('SELECT IFNULL(category, "") AS category, COUNT(*) AS [value] FROM ? WHERE created_year = "?" GROUP BY category', [this.usersStatistics, this.filterUserYear])
      } else {
        return alasql('SELECT IFNULL(category, "") AS category, COUNT(*) AS [value] FROM ? GROUP BY category', [this.usersStatistics])
      }
    },

    experimentsPerMonth () {
      return alasql('SELECT month AS category, COUNT(*) AS [value] FROM ? GROUP BY month', [this.experimentsStatistics])
    },

    userYears () {
      return alasql('SELECT UNIQUE created_year FROM ?', [this.usersStatistics]).map(o => o.created_year)
    },

    experimentYears () {
      return alasql('SELECT UNIQUE year FROM ?', [this.experimentsStatistics]).map(o => o.year)
    },

    months () {
      return alasql('SELECT UNIQUE month FROM ?', [this.experimentsStatistics]).map(o => o.month)
    },
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

    setNodesStatistics (data) {
      this.nodesStatistics = data
    },

    setUsersStatistics (data) {
      data.forEach((d, i, a) => {
        if (d.created) {
          let date = moment(String(d.created))
          d.created_year = date.format('YYYY')
          a[i] = d
        }
      })

      this.usersStatistics = data
    },

    getUsersStatistics () {
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
          d.month = momDate.format('YYYY-MM')
          d.year = momDate.format('YYYY')
          a[i] = d
        }
      })

      this.experimentsStatistics.push(...data)
    },

    async getExperimentsStatistics () {
      let offsets = (await iotlab.getExperimentsOffsetStatistics())
      for (let i = 0; i < offsets.length; i++) {
        let offset = offsets[i].toString()

        this.experimentsStore.getItem(offset).catch(
          async err => {
            this.$notify({text: err, type: 'error'})
          }
        ).then(
          async val => {
            console.log(val)
            if (!val) {
              val = await iotlab.getExperimentsStatistics(offset)
              this.experimentsStore.setItem(offset, val)
            }
            this.addExperimentsStatistics(offset, val)
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
    window.alasql = alasql

    // alasql('CREATE TABLE IF NOT EXISTS users (created_year, groups,firstName,city,lastName,created,email,motivations,sshkeys,country,login,status,organization,category)')
    // alasql('CREATE TABLE IF NOT EXISTS experiments (month,year,submitted_duration,name,nodes,id,start_date,submission_date,nb_nodes,state,user,scheduled_date,stop_date,effective_duration)')
    // alasql('CREATE TABLE IF NOT EXISTS nodes (site,uid,y,camera,network_address,state,mobility_type,z,rtl_sdr,mobile,archi,x)')
    // this.tableOk = true

    await this.getUsersStatistics()
    await this.getExperimentsStatistics()
    await this.getNodesStatistics()
  },
}

</script>

<style scoped>
</style>
