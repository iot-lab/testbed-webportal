
<template>
<div class="container mt-3">
  <h1>Map</h1>
  <div id="div3d" oncontextmenu="return false"></div>           
  <div id="infobox"></div>
</div> <!-- container -->
</template>
        
<script>
import { loadNodes, init3d } from '@/assets/map3d/map'

export default {
  name: 'map',

  data () {
    return {
      nodes: [
        ['a8-x0', 1, 0, 1, 'a8', 'alive'],
        ['a8-x1', 1, 1, 1, 'a8', 'alive'],
        ['a8-x2', 1, 2, 0, 'a8', 'alive'],
        ['a8-y1', 1, 1, 1, 'a8', 'alive'],
      ],
    }
  },

  mounted () {
    loadNodes(this.nodes)
    init3d()
  },

  methods: {
  },
}
</script>

<style scoped>

#div3d {
  height: 500px;
  background: lightgray;
}
</style>
