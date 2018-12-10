<template>
  <select class="form-control form-control-sm text-capitalize mr-2 d-inline-block" @change="filterChanged">
    <option value="all" :selected="value === 'all' || value === 0" disabled v-if="title">{{title}}</option>
    <option value="all" :selected="value === 'all'" v-if="all">{{all}}</option>
    <option v-for="item in items" class="text-capitalize" :value="item.value || item" :selected="value === (item.value || item)" :disabled="item.value === '_separator_'">
      {{item.option || item}}
    </option>
  </select>
</template>

<script>

export default {
  name: 'FilterSelect',

  props: {
    items: {
      // List of options (can be strings or objects with keys = value and option), or can be an integer range
      type: [Array, Number],
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
    value: {
      // Currently selected value or v-model
      type: [String, Number],
      default: () => 'all',
    },
  },

  data () {
    return {
      selected: undefined,
    }
  },

  methods: {
    filterChanged ($event) {
      // emit input event compatible with v-model
      this.$emit('input', $event.target.value)
    },
  },
}
</script>

<style scoped>
select {
  max-width: 200px;
}
</style>
