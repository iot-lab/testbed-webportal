<template>
  <div id="map">
    <div class="card card-body bg-light text-center" v-if="nbSites > 1">
      <p class="lead mt-2">
        <i class="fa fa-map-o fa-fw fa-lg" aria-hidden="true"></i>
        Select a site to view its map
      </p>
      <ul class="nav nav-pills justify-content-center">
        <li class="nav-item" v-for="site in sites">
          <a class="nav-link text-capitalize bg-light" href="#" @click.prevent="setSite(site)">{{site}}</a>
        </li>
      </ul>
    </div>
    <div id="map3d" oncontextmenu="return false"></div>
    <div id="nodeInfo" class="p-2 text-white"></div>
  </div>
</template>

<script>
import { loadNodes, init3d, setSelectedCallback, setSelectedNodes } from '@/assets/map3d/map'
import $ from 'jquery'

export default {
  name: 'Map3d',

  props: {
    nodes: {
      // Node list to display on the map for selection
      type: Array,
      default: [],
    },
    selectedNodes: {
      // Already selected nodes to highlight on the map
      type: Array,
      default: [],
    },
    value: {
      // Currently selected nodes value or v-model
      type: Array,
      default: [],
    },
  },

  data () {
    return {
    }
  },

  created () {
    setSelectedCallback(this.selectionChanged)
  },

  computed: {
    sites () {
      return [...new Set(this.nodes.map(node => node.site))].sort()
    },
    nbSites () {
      return new Set(this.nodes.map(node => node.site)).size
    },
  },

  watch: {
    nodes: function (nodes) {
      if (this.nbSites > 1) {
        $('#map3d').hide()
        $('#nodeInfo').hide()
      } else {
        $('#map3d').show()
        $('#nodeInfo').show()
        loadNodes(this.nodes)
        init3d()
      }
    },
    selectedNodes: function (nodes) {
      // setSelectedNodes(value)
    },
    value: function (value) {
      setSelectedNodes(value)
    },
  },

  methods: {
    selectionChanged (selectedNodes) {
      // emit input event compatible with v-model
      this.$emit('input', selectedNodes)
    },
    setSite (site) {
      this.$emit('selectSite', site)
    },
  },
}
</script>

<style scoped>
#map3d {
  height: 500px;
  background: lightgray;
}
#map {
  position: relative;
}
#nodeInfo {
  position: absolute;
  top: 0;
  left: 0;
  user-select: none;
}
</style>
