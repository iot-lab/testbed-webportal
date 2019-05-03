<template>
  <div>
    <label>{{label}}:</label>
    <chart-table :category_title="category_title" :value_title="value_title" :data="data"/>
    <line-chart ref="chart" type="line" :options="options" :chartData="chartdata" :plugins="plugins"/>
  </div>
</template>
<script>
import ChartTable from '@/components/charts/ChartTable'
import { LineChart } from '@/components/charts/charts.js'
import downsamplePlugin from 'chartjs-plugin-downsample'

export default {
  name: 'LineChartTable',

  components: {
    ChartTable, LineChart,
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
      plugins: [ downsamplePlugin ],
    }
  },

  computed: {
    chartdata () {
      return {
        datasets: [
          {
            data: this.data.map(el => { return {x: el[0].toDate(), y: el[1]} }),
            fill: false,
            label: this.value_title,
          },
        ],
      }
    },
    options () {
      return {
        elements: {
          line: {
            tension: 0, // disables bezier curves
          },
        },
        downsample: {
          enabled: true,
          threshold: 500, // max number of points to display per dataset
        },
        scales: {
          xAxes: [{
            type: 'time',
            scaleLabel: {
              display: true,
              labelString: this.category_title,
            },
          }],
          yAxes: [{
            type: 'linear',
            scaleLabel: {
              display: true,
              labelString: this.value_title,
            },
          }],
        },
      }
    },
  },
}

</script>
