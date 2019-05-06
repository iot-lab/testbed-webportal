<template>
  <div>
    <i class="fa  fa-fw" :class="{'fa-file-text': mobility_type === 'models', 'fa-random': mobility_type==='circuits'}"></i> {{mobility.name}}
  </div>
</template>
<script>
export default {
  name: 'MobilityLink',
  props: ['mobility_type', 'mobility'],
}
</script>
