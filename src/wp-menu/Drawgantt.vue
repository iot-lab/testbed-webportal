<template>
  <div class="container" style="max-width: 1250px" id="container_drawgantt">
    <iframe id="childframe" @load="resizeFrame" src="/drawgantt" width="100%" frameborder="0"></iframe>
  </div>
</template>

<script>

export default {
  name: 'Drawgantt',

  data () {
    return {}
  },

  methods: {
    resizeFrame () {
      const height = document.documentElement.clientHeight - document.getElementById('container_drawgantt').offsetTop - 10 // -50 (minus footer height)
      document.getElementById('childframe').style.height = height + 'px'
    },
  },
}
</script>
