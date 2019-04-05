<template>
  <div>
  </div>
</template>
<script>
import svgJS from 'svg.js'

export default {
  name: 'AnglePicker',

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
      side: 60,
      drag: false,
    }
  },
  mounted () {
    this.draw = svgJS(this.$el).size(this.side, this.side)
    this.render()
  },
  updated () {
    this.render()
  },
  watch: {
    value () {
      this.render()
    },
  },
  methods: {
    MouseAngle (evt) {
      let e = evt.target
      let dim = e.getBoundingClientRect()
      let x = evt.clientX - (dim.right + dim.left) / 2
      let y = evt.clientY - (dim.top + dim.bottom) / 2
      this.$emit('input', Math.atan2(-y, x))
    },
    render () {
      this.draw.clear()
      let radius = (this.side * 2 / 3) / 2
      let pointerSize = this.side * 2 / 10
      let centerCircleSize = this.side * 2 / 3
      let center = {x: this.side / 2, y: this.side / 2}

      let centerCircle = this.draw
        .circle(centerCircleSize)
        .center(center.x, center.y)
        .attr('fill', '#CCC')
      var that = this

      centerCircle.click(that.MouseAngle)
      centerCircle.mousedown(evt => { that.drag = true })
      centerCircle.mouseup(evt => { that.drag = false })
      centerCircle.mouseleave(evt => { that.drag = false })
      centerCircle.mousemove(evt => {
        if (that.drag) {
          that.MouseAngle(evt)
        }
      })

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
    },
    angleEqual: function (val1, val2) {
      return Math.abs((val2 - val1) % (2 * Math.PI)) < 1e-5
    },
  },
}
</script>
