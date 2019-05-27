<template>
  <div>
    <div>
      <a class="cursor" title="Show Table" @click="toggle"><i class="fa fa-fw fa-eye"></i>{{showTable ? 'Hide' : 'Show'}} Table</a>
      <div class='dropdasn d-inline-block float-right'>
        <button class='btn btn-light mr-1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='fa fa-fw fa-download'></i> Download</button>
        <div class='dropdown-menu dropdown-menu-right col-xs-12' aria-labelledby='dropdownMenuButton'>
          <a class='dropdown-item' href='#'
          @click.prevent='downloadPng'><span class='badge badge-pill badge-info'>PNG</span></a>
          <a class='dropdown-item' href='#'
          @click.prevent='downloadCsv'><span class='badge badge-pill badge-info'>CSV</span></a>
        </div>
      </div>
    </div>
    <div class="with-scrollbar table-wrapper-scroll-y">
      <table v-if="showTable" class="table table-striped table-sm mt-2">
        <thead>
        <tr>
          <th v-for="header in headers">{{ header }}</th>
        </tr>
        </thead>
        <tbody>
        <template v-for="item in data">
          <tr class="d-table-row">
            <td v-for="element in item">{{element}}</td>
          </tr>
        </template>
        </tbody>
      </table>
    </div>
  </div>
</template>
<script>
import { downloadObjectAsCsv, downloadCanvasAsPng } from '@/utils'
import slugify from 'slugify'

export default {
  name: 'ChartTable',

  props: {
    category_title: {
      type: String,
    },
    value_title: {
      type: String,
    },
    value_titles: {
      type: Array,
    },
    data: {
      type: Array,
    },
  },

  data () {
    return {
      showTable: false,
    }
  },

  computed: {
    exportName () {
      return slugify(`${this.value_title.toLowerCase()} by ${this.category_title.toLowerCase()}`)
    },
    headers () {
      if (this.value_titles) {
        return [this.category_title, ...this.value_titles]
      } else {
        return [this.category_title, this.value_title]
      }
    },
    csvOpts () {
      return { fields: this.headers.map((header, index) => { return { label: header, value: String(index) } }) }
    },
  },

  methods: {
    toggle () {
      this.showTable = !this.showTable
    },
    async downloadCsv () {
      downloadObjectAsCsv(this.data, this.exportName, this.csvOpts)
    },
    async downloadPng () {
      downloadCanvasAsPng(this.$parent.$refs.chart.$refs.canvas, this.exportName)
    },
  },
}

</script>
<style scoped>
.with-scrollbar {
  position: relative;
  max-height: 500px;
  overflow: auto;
}
.table-wrapper-scroll-y {
  display: block;
}
.dropdown-menu {
  min-width: 0;
  float: right;
}
</style>
