
<template>
<div class="container mt-3">
  <h1>Map</h1>
  <ul class="nav nav-pills mb-2">
    <li class="nav-item" v-for="site in sites">
      <a class="nav-link" :class="{'active': mapsite === site.site}" href="#" @click.prevent="mapsite = site.site; setMapSite()">{{site.site}}</a>
    </li>
  </ul>
  <div id="map" style="position: relative">
    <div id="div3d" oncontextmenu="return false"></div>           
    <div id="nodeInfo" class="p-2 text-white" style="position: absolute; top: 0"></div>
    <div id="selectedInfo" class="my-2"></div>
  </div>
</div> <!-- container -->
</template>
        
<script>
import $ from 'jquery'
import { iotlab } from '@/rest'
// import { extractArchi } from '@/assets/js/iotlab-utils'
import { loadNodes, init3d } from '@/assets/map3d/map'

function newMapNode (node) {
  // Expand node object returned by IoT-LAB API with computed attributes and functions
  // return [node.network_address, node.x, node.y, node.z, extractArchi(node.archi), node.state]
  return node
}

export default {
  name: 'map',

  data () {
    return {
      sites: [],
      nodes: [],
      mapsite: 'devgrenoble',
    }
  },

  async created () {
    this.sites = (await iotlab.getSites()).sort(site => site.site)
    this.nodes = await iotlab.getNodes()
    this.setMapSite(this.mapsite)
  },

  methods: {
    setMapSite () {
      $('#div3d').empty()
      loadNodes(this.nodes.filter(map => map.site === this.mapsite).map(node => newMapNode(node)))
      init3d()
    },
  },
}
</script>

<style scoped>

#div3d {
  height: 500px;
  background: lightgray;
}
</style>
