<template>
  <input type="text"
         class="form-control form-control-sm"
         :class="{ dirty: isDirty }"
         :value="format(value)"
         @change="parse($event.target.value)"
         :readOnly="readOnly"
  >
</template>
<script>
export default {
  name: 'AngleInput',

  props: {
    value: {
      required: true,
    },
    readOnly: {
      type: Boolean,
      default: false,
    },
  },
  data () {
    return {
      dirty: false,
    }
  },
  computed: {
    isDirty: function () {
      return this.dirty
    },
  },
  methods: {
    format: function () {
      let formattedValue = (180 / Math.PI) * this.value
      if (!isNaN(formattedValue)) {
        return formattedValue
      }
    },
    parse: function (value) {
      this.dirty = true
      let parsedValue = (Math.PI / 180) * value
      if (!isNaN(parsedValue)) {
        this.$emit('input', parsedValue)
      }
    },
  },
}
</script>
