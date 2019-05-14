<template>
  <div>
    <div class="float-right" style="display: block">
      <router-link :to="{name: 'newMobilityCircuit'}" class="btn btn-sm btn-success mr-1"><i class="fa fa-plus"></i>New Mobility Circuit</router-link>
      <router-link :to="{name: 'newMobilityModel'}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i>New Mobility Model</router-link>
    </div>
    <ul class="nav nav-tabs" style="position: relative; top: 1px">
      <li class="nav-item" v-tooltip:top="'My mobilities'">
        <a class="nav-link active" data-toggle="list" href="#userdefined" role="tab" aria-controls="userdefined" @click="filterType = 'userdefined'"> My mobilities </a>
      </li>
      <li class="nav-item" v-tooltip:top="'Presets'">
        <a class="nav-link" data-toggle="list" href="#predefined" role="tab" aria-controls="predefined" @click="filterType = 'predefined'"> Presets </a>
      </li>
    </ul>
    <p class="mb-2">
      <span class="badge badge-pill mr-1 cursor" :class="{'badge-primary': filterMobilityType === 'all', 'badge-secondary': filterMobilityType !== 'all'}" v-on:click.stop="filterMobilityType = 'all'">All mobilities</span>
      <span class="badge badge-pill mr-1 cursor" :class="{'badge-primary': filterMobilityType === 'circuits', 'badge-secondary': filterMobilityType !== 'circuits'}" v-on:click.stop="filterMobilityType = 'circuits'">Circuits</span>
      <span class="badge badge-pill mr-1 cursor" :class="{'badge-primary': filterMobilityType === 'models', 'badge-secondary': filterMobilityType !== 'models'}" v-on:click.stop="filterMobilityType = 'models'">Models</span>
    </p>
    <p v-if="sites" class="mb-2">
      <span class="badge badge-pill mr-1 cursor" :class="{'badge-info': currentSite === 'all', 'badge-secondary': currentSite !== 'all'}" v-on:click.stop="currentSite = 'all'">{{sites.length}} sites</span>
      <span v-for="site in sites" :key="site.site" class="badge badge-pill mr-1 cursor" :class="{'badge-info': currentSite === site, 'badge-secondary': currentSite !== site}"
            @click="currentSite = site">{{site.site}}</span>
    </p>
    <table class="table table-striped table-sm mt-3">
      <thead>
      <tr v-if="filterMobilityType === 'circuits'">
        <th class="cursor" title="sort by name" @click="sortBy(p => p.name)">Circuit</th>
        <th class="cursor" title="sort by site" @click="sortBy(p => p.site)">Site</th>
      </tr>
      <tr v-else-if="filterMobilityType === 'models'">
        <th class="cursor" title="sort by name" @click="sortBy(p => p.name)">Model</th>
      </tr>
      <tr v-else>
        <th class="cursor" title="sort by name" @click="sortBy(p => p.name)">Mobility</th>
      </tr>
      </thead>
      <tbody v-for="mobility_type in mobility_types" :key="mobility_type.name">
      <tr v-for="mobility in filterByType(mobility_type, store[mobility_type.name])" :key="mobility.name">
        <td>
          <a v-if="select" href="#" @click.prevent="selectItem(mobility)">
            <mobility-link :mobility_type="mobility_type.name" :mobility="mobility"/>
          </a>
          <router-link v-else :to="{name: mobility_type.rlink, params: {name: mobility.name}}">
            <mobility-link :mobility_type="mobility_type.name" :mobility="mobility"/>
          </router-link>
        </td>
        <td v-if="filterMobilityType === 'circuits'">{{mobility.site}}</td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import { iotlab } from '@/rest'
import store from '@/store'
import MobilityLink from '@/components/mobility/MobilityLink'

export default {
  name: 'MobilityList',

  components: { MobilityLink },

  props: {
    site: {
      // Display mobility circuits filtered by site
      type: String,
      default: '',
    },
    select: {
      // true  -> emits a selected event
      // false -> router link to object
      type: Boolean,
      default: false,
    },
  },

  data () {
    return {
      store: store,
      currentSite: 'all',
      sites: [],
      filterType: 'userdefined',
      filterMobilityType: 'all',
    }
  },

  async created () {
    iotlab.getSitesDetails().then(data => {
      this.sites = data
        .filter(site => {
          return site.archis.some(a => Boolean(a.mobile))
        }).sort((a, b) => a.site.localeCompare(b.site))
    }).catch(err => {
      this.$notify({text: err.response.data.message || 'Failed to fetch sites details', type: 'error'})
    })
    iotlab.getMobilityCircuits().then(data => { this.store.circuits = data.sort((a, b) => a.name.localeCompare(b.name)) }).catch(err => {
      this.$notify({text: err.response.data.message || 'Failed to fetch circuits details', type: 'error'})
    })
    iotlab.getMobilityModels().then(data => { this.store.models = data.sort((a, b) => a.name.localeCompare(b.name)) }).catch(err => {
      this.$notify({text: err.response.data.message || 'Failed to fetch models details', type: 'error'})
    })
  },

  computed: {
    mobility_types () {
      let value = []
      if (this.filterMobilityType === 'all' || this.filterMobilityType === 'circuits') {
        value.push({
          name: 'circuits',
          label: 'Circuit',
          rlink: 'mobilityCircuit',
        })
      }
      if (this.filterMobilityType === 'all' || this.filterMobilityType === 'models') {
        value.push({
          name: 'models',
          label: 'Model',
          rlink: 'mobilityModel',
        })
      }
      return value
    },
  },

  methods: {
    sortBy (func) {
      // sort by func() then by name
      this.store.circuits = this.store.circuits.sort((a, b) => func(a) === func(b) ? a.name.localeCompare(b.name) : func(a).localeCompare(func(b)))
      this.store.models = this.store.models.sort((a, b) => func(a) === func(b) ? a.name.localeCompare(b.name) : func(a).localeCompare(func(b)))
      this.store.all = this.store.circuits.concat(this.store.models)
    },
    selectItem (mobility) {
      this.$emit('select', mobility.name)
    },
    filterOneByType (mobilityType, mobility) {
      if (mobility.type !== this.filterType) {
        return false
      }
      if (mobilityType.name === 'circuits') {
        if (this.currentSite !== 'all') {
          if (mobility.site !== this.currentSite.site) {
            return false
          }
        }
      }
      return true
    },
    filterByType (mobilityType, mobilities) {
      return mobilities.filter((m) => this.filterOneByType(mobilityType, m))
    },
  },
}
</script>

<style scoped>

</style>
