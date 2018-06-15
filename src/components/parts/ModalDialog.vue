<template>
  <div class="modal fade" :class="name" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-light">
          <h5 class="modal-title">
            <slot name="title"></slot>
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <slot></slot>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success" @click.prevent="save">
            <slot name="action">Save</slot>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import $ from 'jquery'

export default {
  name: 'ModalDialog',

  props: {
    name: {
      // Name
      type: String,
    },
  },

  data () {
    return {
    }
  },

  mounted () {
    $('.modal').on('shown.bs.modal', e => {
      this.$emit('open')
    })
  },

  methods: {
    save () {
      this.$emit('save')
    },
  },
}
</script>
