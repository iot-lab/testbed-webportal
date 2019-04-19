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
  name: 'BarChartTable',

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
      type: Object,
      default: () => {},
    },
    label: {
      type: String,
      default: () => '',
    },
  },

  data () {
    return {
      options: {
        xaxis: {
          categories: [],
        },
        dataLabels: {
          enabled: false,
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
      this.options = {
        xaxis: {
          categories: Object.keys(this.data),
        },
      }
      this.series[0].data = Object.values(this.data)
    },
  },
}

</script>
