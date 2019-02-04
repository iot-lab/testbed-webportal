<template>
  <div>
    <input type="text"
          class="form-control form-control-sm"
          :class="{ dirty: isDirty }"
          :value="angle"
          @input="onInput"
          :readOnly="readOnly"
    >
    <div ref="anglepicker"></div>
  </div>
</template>
<script>
import svgJS from 'svg.js'

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
      side: 50,
      drawing: undefined,
    }
  },
  mounted () {
    this.draw = svgJS(this.$refs.anglepicker).size(this.side * 2, this.side)
    this.render()
  },
  updated () {
    this.render()
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
    render () {
      this.draw.clear()
      let radius = (this.side * 2 / 3) / 2
      let pointerSize = this.side * 2 / 10
      let center = {x: this.side / 2, y: this.side / 2}

      for (var i = 0; i < 8; i++) {
        let theta = (Math.PI / 180) * i * 45
        let that = this
        let rect = this.draw
          .circle(pointerSize)
          .center(center.x + radius * Math.cos(-theta), center.y + radius * (Math.sin(-theta)))
          .attr({ fill: '#fff', stroke: '#000' })
        if (!this.readOnly) {
          rect.click(function (evt) {
            that.$emit('input', theta)
            that.$forceUpdate()
          })
        }
        if (this.angleEqual(theta, this.value)) {
          rect.attr({ fill: '#000' })
        }
      }
      let x = center.x + radius * Math.cos(-this.value)
      let y = center.y + radius * Math.sin(-this.value)
      this.draw
        .line(center.x, center.y, x, y)
        .attr({stroke: '#000'})
      this.draw
        .text(this.angle + '°')
        .cy(center.y)
        .x(this.side)
    },
    angleEqual: function (val1, val2) {
      return Math.abs((val2 - val1) % (2 * Math.PI)) < 1e-5
    },
    format: function () {
      let formattedValue = (180 / Math.PI) * this.value
      if (!isNaN(formattedValue)) {
        return formattedValue
      }
    },
  },
}
</script>
<style>
.this_container {
    position:relative;
    padding:0;
}
.this_input {
    padding-right: 40px;
    width: 100px;
}
.this_img {
    position:absolute;
    margin-right:30px;
    top:-20px;
}
</style>
