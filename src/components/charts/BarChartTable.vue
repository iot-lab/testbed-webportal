<template>
  <div>
    <label>{{label}}: </label>
    <div>
      <a class="cursor" title="Show Table" @click="toggle"><i class="fa fa-fw fa-eye"></i>Show Table</a>
      <a class="cursor" title="Download CSV" @click="download"><i class="fa fa-fw fa-download"></i>Download CSV</a>
    </div>
    <table v-if="table" class="table table-striped table-sm mt-2">
      <thead>
      <tr>
        <th>{{ category_title }}</th>
        <th>{{ value_title }}</th>
      </tr>
      </thead>
      <tbody>
      <template v-for="element in data">
        <tr class="d-table-row" :key="element.category">
          <td>{{element.category}}</td><td>{{element.value}}</td>
        </tr>
      </template>
      </tbody>
    </table>
    <apexcharts ref="chart" width="900" type="bar" :options="options" :series="series"></apexcharts>
  </div>
</template>
<script>
import { downloadObjectAsCsv } from '@/utils'
import VueApexCharts from 'vue-apexcharts'

export default {
  name: 'BarChartTable',

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
          categories: this.data.map(el => el.category),
        },
      }
      this.series[0].data = this.data.map(el => el.value)
    },
  },

  methods: {
    toggle () {
      this.table = !this.table
    },
    async download () {
      downloadObjectAsCsv(this.data, 'iotlab-table-statistics',
        {fields: ['category', 'value'], header: [this.category_title, this.value_title]})
    },
  },
}

</script>
