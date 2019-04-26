<template>
  <div>
    <label>{{label}}:</label>
    <chart-table :category_title="category_title" :value_titles="value_titles" :data="data_table"/>
    <vue-apex-charts ref="chart"  type="area" :options="options" :series="data_series"/>
  </div>
</template>
<script>
import VueApexCharts from 'vue-apexcharts'
import ChartTable from '@/components/charts/ChartTable'

export default {
  name: 'StackedRelativeAreaChartTable',

  components: {
    VueApexCharts, ChartTable,
  },

  props: {
    category_title: {
      type: String,
      default: () => '',
    },
    categories: {
      type: Array,
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
    }
  },

  computed: {
    value_titles () {
      return [...this.categories, 'total']
    },
    data_table () {
      let dataTable = []
      this.data.map(el => {
        dataTable.push([el[0], ...this.categories.map(category => el[1].values[category]), el[1].total])
      })

      return dataTable
    },
    data_series () {
      let dataSeries = []
      this.categories.forEach((category, index, array) => {
        dataSeries[index] = {data: [], name: category}
      })

      this.data.map(el => {
        let time = el[0].unix() * 1000
        let values = el[1].values
        if (values) {
          let total = el[1].total
          this.categories.forEach((category, index, array) => {
            dataSeries[index].data.push([time, values[category] ? 100 * values[category] / total : 0])
          })
        }
      })
      return dataSeries
    },
    options () {
      let times = this.data_series[0].data.map(el => el[0])
      let minTimes = Math.min(...times)
      let maxTimes = Math.max(...times)

      return {
        chart: {
          zoom: {
            type: 'x',
            enabled: true,
          },
          type: 'area',
          stacked: true,
        },
        xaxis: {
          type: 'datetime',
          min: minTimes,
          max: maxTimes,
          labels: {
            datetimeFormatter: {
              year: 'yyyy',
              month: 'MMM \'yy',
              day: 'dd MMM',
              hour: 'HH:mm',
            },
          },
          title: {
            text: this.category_title,
          },
        },
        yaxis: {
          min: 0,
          max: 100,
          title: {
            text: this.value_title,
          },
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
      }
    },
  },
}

</script>
