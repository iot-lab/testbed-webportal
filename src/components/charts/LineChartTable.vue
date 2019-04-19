<template>
  <div>
    <label>{{label}}:</label>
    <chart-table :category_title="category_title" :value_title="value_title" :data="data"/>
    <vue-apex-charts ref="chart" width="900" type="bar" :options="options" :series="series"/>
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

  data () {
    return {
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
}

</script>
