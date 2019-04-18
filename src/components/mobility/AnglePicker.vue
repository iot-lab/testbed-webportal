<template>
  <div v-on:mouseup="drag = false">
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
    angles: { // values that will get a clickable round
      type: Array,
      required: false,
      default: () => [],
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
    mouseAngle (evt) {
      let e = evt.target
      let dim = e.getBoundingClientRect()
      let x = evt.clientX - (dim.right + dim.left) / 2
      let y = evt.clientY - (dim.top + dim.bottom) / 2
      this.$emit('input', Math.atan2(-y, x))
    },
    render () {
      this.draw.clear()
      let pointerSize = this.side * 2 / 10
      let radius = (this.side - pointerSize) / 2
      let center = {x: this.side / 2, y: this.side / 2}

      let centerCircle = this.draw
        .circle(2 * radius)
        .center(center.x, center.y)
        .attr('fill', '#CCC')
      var that = this

      centerCircle.click(that.mouseAngle)
      centerCircle.mousedown(evt => { that.drag = true })
      centerCircle.mouseup(evt => { that.drag = false })
      centerCircle.mouseleave(evt => { that.drag = false })
      centerCircle.mousemove(evt => {
        if (that.drag) {
          that.mouseAngle(evt)
        }
      })

      for (var i = 0; i < 8; i++) {
        let theta = (Math.PI / 180) * i * 45
        let that = this
        let circle = this.draw
          .circle(pointerSize)
          .center(center.x + radius * Math.cos(-theta), center.y + radius * (Math.sin(-theta)))
          .attr({ fill: '#fff', stroke: '#000' })
        if (!this.readOnly) {
          circle.click(function (evt) {
            that.$emit('input', theta)
            that.$forceUpdate()
          })
        }
        if (this.angleEqual(theta, this.value)) {
          circle.attr({ fill: '#000' })
        }
      }
      for (let angle of this.angles) {
        let theta = (Math.PI / 180) * angle.value
        let that = this
        let points = [
          [center.x + radius / 2, center.y + pointerSize / 2],
          [center.x + radius / 2, center.y - pointerSize / 2],
          [center.x + radius - pointerSize / 2, center.y],
        ]
        let pointer = this.draw
          .polygon(points.map(el => el.join(',')).join(' '))
          .transform({rotation: -angle.value, cx: center.x, cy: center.y})
          .attr({ fill: '#fff', stroke: '#000' })
        if (!this.readOnly) {
          pointer.click(function (evt) {
            that.$emit('input', theta)
            that.$forceUpdate()
          })
        }
        if (this.angleEqual(theta, this.value)) {
          pointer.attr({ fill: '#000' })
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
