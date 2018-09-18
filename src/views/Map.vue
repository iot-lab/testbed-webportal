
<template>
<div class="container mt-3">
  <h1>Map</h1>
  <ul class="nav nav-pills mb-2">
    <li class="nav-item" v-for="site in sites">
      <a class="nav-link text-capitalize" :class="{'active': mapsite === site.site}" href="#" @click.prevent="mapsite = site.site">{{site.site}}</a>
    </li>
  </ul>
  <map-3d :nodes="mapNodes" @selected="setSelected"></map-3d>
  <div class="my-2" v-if="selectedMapNodes.length">
    <span style="vertical-align: middle">Selected nodes</span>
    <span style="vertical-align: middle" class="badge badge-tag badge-primary" v-for="node in selectedMapNodes">{{node}}</span>
    <button class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Add to experiment</button>
    <!-- <a href="" class="badge badge-tag badge-success"><i class="fa fa-plus"></i> Add to experiment</a> -->
  </div>
</div> <!-- container -->
</template>

<script>
import { iotlab } from '@/rest'
import Map3d from '@/components/Map3d'

export default {
  name: 'map',

  components: { Map3d },

  data () {
    return {
      sites: [],
      nodes: [],
      selectedMapNodes: [],
      mapsite: 'devgrenoble',
    }
  },

  async created () {
    this.sites = (await iotlab.getSites()).sort(site => site.site)
    this.nodes = await iotlab.getNodes()
  },

  computed: {
    mapNodes () {
      return this.nodes.filter(map => map.site === this.mapsite)
    },
  },

  methods: {
    setSelected (nodes) {
      this.selectedMapNodes = nodes
    },
  },
}
</script>

<style scoped>

</style>
