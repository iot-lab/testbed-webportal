<template>
  <select class="form-control form-control-sm text-capitalize mr-2 d-inline-block" @change="filterChanged">
    <option selected disabled v-if="title">{{title}}</option>
    <option value="all" v-if="all">{{all}}</option>
    <option v-for="item in items" class="text-capitalize" :value="item.value || item">{{item.option || item}}</option>
  </select>
</template>

<script>

export default {
  name: 'FilterSelect',

  props: {
    items: {
      // List of options (can be strings or objects with keys = value and option)
      type: Array,
      default: () => [],
    },
    title: {
      // Title
      type: String,
      default: () => '',
    },
    all: {
      // Caption for all items selected (no filter)
      type: String,
      default: () => '',
    },
  },

  data () {
    return {
      selected: undefined,
    }
  },

  methods: {
    filterChanged ($event) {
      this.$emit('changed', $event.target.value)
    },
  },
}
</script>

<style scoped>
select {
  max-width: 200px;
}
</style>
