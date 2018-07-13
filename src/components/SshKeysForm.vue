<template>
  <div>
    <ul class="nav nav-tabs" style="border-bottom: 1px solid transparent; position: relative; top: 1px">
      <li v-for="(key, i) in keys" class="nav-item"> <a :href="'#tab_SSH'+i" :class="{ 'active': i === activeKey }" data-toggle="tab" @click="activeKey = i"
        class="nav-link">
        SSH key {{i+1}}
        <a @click.stop.prevent="delKey(i)" v-show="i === activeKey && keys.length > 1"><i class="fa fa-times-circle"></i></a> </a>
      </li>
      <li class="nav-item"><a @click="addKey" class="nav-link"><i class="fa fa-plus"></i></a>
      </li>
    </ul>
    <div class="tab-content" id="sshkeysTabContent">
      <div v-for="(key, i) in keys" class="tab-pane" :class="{ 'active': i === activeKey, 'show': i === 0 }"
      :id="'tab_SSH'+i">
        <div class="control-group">
          <div class="controls">
            <textarea v-model="keys[i]" class="form-control" :rows="rows"></textarea>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
export default {
  name: 'SshKeys',

  props: {
    keys: {
      // List of SSH keys
      type: Array,
      default: () => [''],
    },
    rows: {
      // Number of rows in textarea
      type: Number,
      default: 4,
    },
  },

  data () {
    return {
      activeKey: 0,
    }
  },

  watch: {
    keys (newKeys, oldKeys) {
      if (this.activeKey >= this.keys.length || newKeys[0] !== oldKeys[0]) {
        this.activeKey = 0 // set first key active when keys list changes
      }
    },
  },

  methods: {
    addKey () {
      this.keys.push('')
      this.activeKey = this.keys.length - 1
    },
    delKey (index) {
      this.keys.splice(index, 1)
      this.activeKey = Math.min(this.activeKey, this.keys.length - 1)
    },
  },
}
</script>
