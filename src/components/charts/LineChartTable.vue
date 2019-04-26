<template>
  <div>
    <label>{{label}}:</label>
    <chart-table :category_title="category_title" :value_title="value_title" :data="data"/>
    <vue-apex-charts ref="chart" type="line" :options="options" :series="[data_series]"/>
  </div>
</template>
<script>
import VueApexCharts from 'vue-apexcharts'
import ChartTable from '@/components/charts/ChartTable'

export default {
  name: 'LineChartTable',

  components: {
    VueApexCharts, ChartTable,
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

  computed: {
    data_series () {
      return {name: this.value_title, data: this.data.map(el => [el[0].unix() * 1000, el[1]])}
    },
    options () {
      let times = this.data_series.data.map(el => el[0])
      let minTimes = Math.min(...times)
      let maxTimes = Math.max(...times)
      let y = this.data.map(el => el[1])
      let minY = Math.min(...y)
      let maxY = Math.max(...y)
      return {
        chart: {
          zoom: {
            type: 'x',
            enabled: true,
          },
        },
        xaxis: {
          type: 'datetime',
          min: minTimes,
          max: maxTimes,
          title: {
            text: this.category_title,
          },
          labels: {
            datetimeFormatter: {
              year: 'yyyy',
              month: 'MMM \'yy',
              day: 'dd MMM',
              hour: 'HH:mm',
            },
          },
        },
        yaxis: {
          min: minY,
          max: maxY,
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
