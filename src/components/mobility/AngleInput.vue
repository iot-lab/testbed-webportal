<template>
  <input type="text"
        class="form-control form-control-sm"
        :class="{ dirty: isDirty }"
        :value="angle"
        @input="onInput"
        :readOnly="readOnly"
  >
</template>
<script>

export default {
  name: 'AngleInput',

  props: {
    value: { // value in radians
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
    angle: function () {
      return Math.round(1e4 * (180 / Math.PI) * this.value) * 1e-4
    },
    isDirty: function () {
      return this.dirty
    },
  },
  methods: {
    parse: function (value) {
      this.dirty = true
      let parsedValue = (Math.PI / 180) * value
      if (!isNaN(parsedValue)) {
        this.$emit('input', parsedValue)
      }
    },
    onInput (evt) {
      this.parse(evt.target.value)
    },
  },
}
</script>
