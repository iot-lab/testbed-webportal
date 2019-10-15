<template>
  <div>
    <label><h3 class="mt-3">{{label}}</h3></label>
    <div v-if="chartOptions.allowPie">
      <p >
        <a><span class="badge badge-pill mr-1 cursor"  @click="type = 'bar'" :class="{'badge-primary': type === 'bar', 'badge-secondary': type !== 'bar'}">Bars</span></a>
        <a><span class="badge badge-pill mr-1 cursor"  @click="type = 'pie'" :class="{'badge-primary': type === 'pie', 'badge-secondary': type !== 'pie'}">Pie</span></a>
      </p>
    </div>
    <bar-chart v-if="type === 'bar'" ref="chart" :options="options" :chartData="data_series"/>
    <pie-chart v-if="type === 'pie'" ref="chart" :chartData="pie_data_series"/>
    <chart-table :category_title="category_title" :value_title="value_title" :data="Object.entries(data)"/>
  </div>
</template>
<script>
import ChartTable from '@/components/charts/ChartTable'
import { BarChart, PieChart } from '@/components/charts/charts.js'
import { colorPalette } from '@/utils'

export default {
  name: 'BarChartTable',

  components: {
    ChartTable, BarChart, PieChart,
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
      type: Object,
      default: () => {},
    },
    label: {
      type: String,
      default: () => '',
    },
    chartOptions: {
      type: Object,
      default: () => { return { allowPie: true } },
    },
  },

  data () {
    return {
      type: 'bar',
    }
  },

  computed: {
    options () {
      return {
        legend: {
          display: false,
        },
        scales: {
          xAxes: [{
            type: 'category',
            labels: this.data_keys,
            scaleLabel: {
              display: true,
              labelString: this.category_title,
            },
          }],
          yAxes: [{
            type: 'linear',
            beginAtZero: true,
            scaleLabel: {
              display: true,
              labelString: this.value_title,
            },
          }],
        },
        responsive: true,
      }
    },
    data_values () {
      return Object.values(this.data)
    },
    data_keys () {
      return Object.keys(this.data)
    },
    pie_data_series () {
      return {
        labels: this.data_keys,
        datasets: [
          {
            label: null,
            data: this.data_values,
            backgroundColor: colorPalette(this.data_keys.length),
          },
        ],
      }
    },
    data_series () {
      return {
        labels: this.data_keys,
        datasets: [
          {
            label: this.value_title,
            data: this.data_values,
            backgroundColor: '#20539D',
          },
        ],
      }
    },
  },

  methods: {
    randomColorGenerator () {
      return '#' + (Math.random().toString(16) + '0000000').slice(2, 8)
    },
  },
}

</script>
