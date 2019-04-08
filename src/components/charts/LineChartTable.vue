<template>
  <div>
    <label>{{label}}: </label>
    <div>
      <a class="cursor" title="Show Table" @click="toggle"><i class="fa fa-fw fa-eye"></i>{{table?'Hide' : 'Show'}} Table</a>
    </div>
    <table v-if="table" class="table table-striped table-sm mt-2">
      <thead>
      <tr>
        <th>{{ category_title }}</th>
        <th>{{ value_title }}</th>
      </tr>
      </thead>
      <tbody>
      <template v-for="([category, value]) in data">
        <tr class="d-table-row" :key="category">
          <td>{{category}}</td><td>{{value}}</td>
        </tr>
      </template>
      </tbody>
    </table>
    <apexcharts ref="chart" width="900" type="bar" :options="options" :series="series"></apexcharts>
  </div>
</template>
<script>
import VueApexCharts from 'vue-apexcharts'

export default {
  name: 'LineChartTable',

  components: {
    apexcharts: VueApexCharts,
  },

  props: {
    category_title: {
      type: String,
      default: () => '',
    },
    value_title: {
      type: String,
      default: () => '',
    },
    data: {
      type: Array,
      default: () => [],
    },
    label: {
      type: String,
      default: () => '',
    },
  },

  data () {
    return {
      table: false,
      options: {
        chart: {
          zoom: {
            type: 'x',
            enabled: true,
          },
        },
        xaxis: {
          type: 'datetime',
          min: null,
          max: null,
        },
        dataLabels: {
          enabled: false,
        },
        plotOptions: {
          line: {
            curve: 'straight',
          },
        },
        stroke: {
          show: true,
        },
        markers: {
          size: 0,
        },
      },
      series: [{
        name: this.value_title,
        data: [],
      }],
    }
  },

  watch: {
    data: function () {
      this.series[0].data = this.data.map(el => [el[0].unix(), el[1]])
      let times = this.series[0].data.map(el => el[0])
      let minTimes = Math.min(...times)
      let maxTimes = Math.max(...times)
      this.$refs.chart.updateOptions({
        xaxis: {
          min: minTimes,
          max: maxTimes,
        },
      })
    },
  },

  methods: {
    toggle () {
      this.table = !this.table
    },
  },
}

</script>
