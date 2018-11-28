<template>
  <div class="xterm"></div>
</template>

<script>
import 'xterm/dist/xterm.css'
import { Terminal } from 'xterm'
import * as fit from 'xterm/dist/addons/fit/fit'

Terminal.applyAddon(fit)

export default {
  name: 'Terminal',

  props: {
    cols: Number,
    rows: Number,
    convertEol: Boolean,
    termName: String,
    cursorBlink: Boolean,
    cursorStyle: String,
    bellSound: String,
    bellStyle: String,
    enableBold: Boolean,
    fontFamily: String,
    fontSize: Number,
    fontWeight: String,
    fontWeightBold: String,
    lineHeight: Number,
    letterSpacing: Number,
    scrollback: Number,
    screenKeys: Boolean,
    debug: Boolean,
    macoptionsIsMeta: Boolean,
    cancelEvents: Boolean,
    disableStdin: Boolean,
    useFlowControl: Boolean,
    allowTransparency: Boolean,
    tabStopWidth: Number,
    theme: Object,

    node: String,
    expId: [String, Number],
    token: String,
  },

  data () {
    return {
      options: {
        cols: 80,
        rows: 20,
        screenKeys: true,
        useStyle: true,
      },
      ws: undefined,
      attached: false,
    }
  },

  watch: {
    cols (c) {
      this.$terminal.resize(c, this.rows)
    },

    rows (r) {
      this.$terminal.resize(this.cols, r)
    },

    options: {
      handler (o) {
        Object.keys(o).forEach(key => {
          if (this[key] !== o[key]) this.$emit('update:' + key, o[key])
        })
      },
      deep: true,
    },
  },

  mounted () {
    // this.attach()
  },

  beforeDestroy () {
    this.disconnect()
    this.detach()
  },

  methods: {
    attach () {
      Object.keys(this.$props).forEach(key => this.$set(this.options, key, this[key]))
      let term = new Terminal(this.options)

      term.open(this.$el, true)
      term.fit()
      this.attached = true

      if (this.buffer) term.write(this.buffer.replace(/\n/g, '\r\n') + '\r\n')

      term.on('blur', () => this.$emit('blur'))
      term.on('focus', () => this.$emit('focus'))
      term.on('resize', size => {
        if (size.cols !== this.cols) this.$emit('update:cols', size.cols)
        if (size.rows !== this.rows) this.$emit('update:rows', size.rows)
      })
      term.on('title', title => this.$emit('update:title', title))

      this.$terminal = term

      Object.keys(this.$props).forEach(key => this.$watch(key, val => { this.options[key] = val }))
    },
    detach () {
      if (this.$terminal) this.$terminal.dispose()
      this.attached = false
    },
    connect () {
      if (!this.token) {
        this.$terminal.write('Experiment token not found')
        return
      }
      let [nodeId, site] = this.node.split('.')
      let connType = this.node.startsWith('a8') ? 'ssh' : 'serial'

      let baseUrl = `wss://${process.env.VUE_APP_IOTLAB_HOST}:443/ws`
      let wsUrl = `${baseUrl}/${site}/${this.expId}/${nodeId}/${connType}`

      let ws = new WebSocket(wsUrl, ['token', this.token])
      this.ws = ws
      let term = this.$terminal

      ws.onopen = (event) => {
        // this.$notify({text: 'Terminal connected to serial port', type: 'info'})

        let buffer = ''

        term.on('key', (key, event) => {
          if (connType === 'serial' && event.key === 'Backspace') {
            term.write('\b \b')
            buffer = buffer.slice(0, -1)
          } else if (event.key === 'Del') {
            term.write('\b \b')
          } else {
            term.write(key)
            if (event.key === 'Enter') {
              term.write('\n')
              ws.send(buffer + '\n')
              buffer = ''
            } else {
              buffer += key
            }
          }
        })
      }

      ws.onmessage = (event) => {
        term.write(event.data)
        if (event.data === '\n') {
          term.write('\r')
        }
      }

      ws.onclose = (event) => {
        // console.log(event.code)
        // this.$notify({ text: 'WebSocket closed abnormally', type: 'error' })
      }

      ws.onerror = (event) => {
        this.$notify({ text: 'WebSocket error', type: 'error' })
      }
    },
    disconnect () {
      if (this.ws) this.ws.close()
    },
    fit () {
      this.$terminal.fit()
    },
    fit_ () {
      let parent = this.$el.parentNode
      let term = this.$terminal
      term.element.style.display = 'none'
      setTimeout(() => {
        this.$el.style.width = (parent.offsetWidth - this.$el.offsetLeft + parent.offsetLeft) + 'px'
        this.$el.style.height = (parent.offsetHeight - this.$el.offsetTop + parent.offsetTop) + 'px'
        term.fit()
        term.element.style.display = ''
        term.refresh(0, term.rows - 1)
      }, 0)
    },
    focus () {
      this.$terminal.focus()
    },
    blur () {
      this.$terminal.blur()
    },
  },
}
</script>
