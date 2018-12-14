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
    <form id="cameraInfo" class="m-2 form-inline">
      <div class="input-group input-group-sm" v-if="nbSites === 1 && siteCameras.length > 1">
        <span class="input-group-addon">View</span>
        <select v-model="camera" class="bg-light form-control custom-select" @change="setCamera" style="padding-right: 1.65rem">
          <option v-for="cam in cameras[site]" :value="cam" :selected="cam.name === camera.name">{{cam.name}}</option>
        </select>
      </div>
    </form>
  </div>
</template>

<script>
import { map3d } from '@/assets/map3d/map3d'
import SiteCameras from '@/assets/map3d/SiteCameras.config'
import $ from 'jquery'

export default {
  name: 'Map3d',

  props: {
    nodes: {
      // Node list to display on the map for selection
      type: Array,
      default: () => [],
    },
    selectedNodes: {
      // Already selected nodes to highlight on the map
      type: Array,
      default: () => [],
    },
    value: {
      // Currently selected nodes value or v-model
      type: Array,
      default: () => [],
    },
    shows: {
      // Start/stop rendering when map is shown/hidden
      type: Boolean,
      default: true,
    },
  },

  data () {
    return {
      cameras: SiteCameras,
      camera: undefined,
    }
  },

  created () {
    map3d.setSelectedCallback(this.selectionChanged)
  },

  computed: {
    sites () {
      return [...new Set(this.nodes.map(node => node.site))].sort()
    },
    nbSites () {
      return this.sites.length
    },
    site () {
      return this.sites[0]
    },
    siteCameras () {
      return SiteCameras[this.site] || []
    },
  },

  watch: {
    nodes: function (nodes) {
      if (this.nbSites > 1) {
        $('#map3d').hide()
        $('#nodeInfo').hide()
        $('#cameraInfo').hide()
      } else {
        $('#map3d').show()
        $('#nodeInfo').show()
        $('#cameraInfo').show()
        if (this.shows) {
          console.debug(this.shows)
          map3d.loadNodes(this.nodes)
          map3d.init()
          if (this.siteCameras.length > 0) {
            this.camera = this.siteCameras[0]
            this.setCamera()
          }
        }
      }
    },
    selectedNodes: function (nodes) {
      // map3d.setSelectedNodes(value)
    },
    value: function (value) {
      map3d.setSelectedNodes(value)
    },
    shows: function (shows) {
      console.debug('shows', shows)
      if (shows) {
        map3d.loadNodes(this.nodes)
        map3d.init()
        if (this.siteCameras.length > 0) {
          this.camera = this.siteCameras[0]
          this.setCamera()
        }
      }
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
    setCamera () {
      let origin = this.camera.origin
      let camera = this.camera.camera
      map3d.setScenePosition(origin.x, origin.y, origin.z)
      map3d.setCameraRect(camera.x, camera.y, camera.z)
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
#cameraInfo {
  position: absolute;
  top: 0;
  right: 0;
  user-select: none;
}
</style>
