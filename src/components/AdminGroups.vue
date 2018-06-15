<template>
  <div class="container mt-3">

    <h2>Groups</h2>

    <table class="table table-striped table-sm mt-4">
      <thead>
        <tr>
          <th class="header" style="width: 130px;">Name</th>
          <th class="header">Users</th>
          <th class="header text-center" style="width: 10px;"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(group, i) in store.groups">
          <td><span class="badge" :class="group.name === 'admin' ? 'badge-warning' : 'badge-primary'">{{group.name}}</span></td>
          <!-- <td><span class="badge badge-info mr-1" v-for="user in group.users.sort()">{{user}}</span></td> -->
          <td>{{[...group.users].sort().join(', ')}} <span class="text-muted">({{group.users.length}} users)</span></td>
          <td>
            <a href="" class="btn btn-sm border-0 btn-outline-danger" v-tooltip="'Delete group'" @click.prevent="deleteGroup(group.name)">
              <i class="fa fa-fw fa-trash-o"></i>
            </a>
          </td>
        </tr>
      </tbody>
    </table>

    <a href="" class="btn btn-success" data-toggle="modal" data-target=".add-group-modal" @click.prevent="newGroup = ''; $validator.reset()">New group</a>

    <modal-dialog name="add-group-modal" @save="createGroup" @open="setFocus('group-name')">
      <template slot="title">
        <i class="fa fa-fw fa-user-circle"></i> New user group</span>
      </template>
      <form @submit.prevent="createGroup">
        <label>Enter group name</label>
        <input class="form-control" id="group-name" v-model="newGroup" name="name" placeholder="Name" type="text"
          :class="{'is-invalid': errors.has('name') }" v-validate="'required|alpha_dash|min:3'">
        <div class="invalid-feedback" v-show="errors.has('name')">
          {{ errors.first('name') }}
        </div>
      </form>
    </modal-dialog>

  </div> <!-- container -->
</template>

<script>
import ModalDialog from '@/components/parts/ModalDialog'
import { iotlab } from '@/rest'
import { sleep } from '@/utils'
import store from '@/store'
import $ from 'jquery'

export default {
  name: 'AdminGroups',

  components: { ModalDialog },

  data () {
    return {
      store: store,
      newGroup: '',
    }
  },

  async created () {
    await this.getGroups()
  },

  methods: {
    setFocus (elem) {
      document.getElementById(elem).focus()
    },
    async getGroups () {
      try {
        this.store.groups = (await iotlab.getUserGroups()).sort((a, b) => a.name.localeCompare(b.name))
      } catch (err) {
        this.$notify({text: err.response.data.message || 'Failed to fetch groups', type: 'error'})
      }
    },
    async createGroup () {
      if (this.store.groups.some(group => group.name === this.newGroup)) {
        this.$notify({text: `Group ${this.newGroup} already exists`, type: 'warning'})
        this.setFocus('group-name')
        return
      }
      if (!await this.$validator.validateAll()) return

      $('.modal').modal('hide')
      await iotlab.createGroup(this.newGroup).catch(err => {
        this.$notify({text: err.response.data.message || 'An error occured', type: 'error'})
      })
      await sleep(50)
      await this.getGroups()
    },
    async deleteGroup (group) {
      if (confirm(`Delete group ${group}?`)) {
        try {
          await iotlab.deleteGroup(group)
          this.store.groups = this.store.groups.filter(item => item.name !== group)
          this.$notify({text: `Group ${group} deleted`, type: 'success'})
        } catch (err) {
          this.$notify({text: err.response.data.message || 'An error occured', type: 'error'})
        }
      }
    },
  },
}
</script>

<style scoped>
.badge {
  font-size: 80%;
}
</style>
