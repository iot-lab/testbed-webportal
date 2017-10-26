<template>
<div class="container mt-3">
  <h2>Add user</h2>
  <div class="row">
      <div class="col-md-3 mb-4">
        <div class="list-group" id="list-tab" role="tablist">
          <a class="list-group-item list-group-item-action active" id="list-single-list" data-toggle="list" href="#list-single" role="tab" aria-controls="single">
            <i class="fa fa-fw fa-user" aria-hidden="true"></i> Single account
          </a>
          <a class="list-group-item list-group-item-action" id="list-multiple-list" data-toggle="list" href="#list-multiple" role="tab" aria-controls="multiple">
            <i class="fa fa-fw fa-users" aria-hidden="true"></i> Multiple accounts
          </a>
        </div>
      </div>
      <div class="col-md-9">
        <div class="tab-content" id="nav-tabContent">
          <div class="tab-pane fade show active" id="list-single" role="tabpanel" aria-labelledby="list-single-list">
            
            <form @submit.prevent="createSingle">
              <user-form :user="user" ref="user" :hidden="['motivations-help']"></user-form>
              <div class="form-group">
                <label class="form-control-label text-danger">+ SSH Key here ?</label>
              </div>
              <div class="form-group">
                <button class="btn btn-success" type="submit">Create account</button>
                <button class="btn btn-secondary" type="reset">Clear</button>
              </div>
            </form>
            
          </div>

          <div class="tab-pane fade show" id="list-multiple" role="tabpanel" aria-labelledby="list-multiple-list">
            
            <form @submit.prevent="createMultiple">
                <div class="row justify-content-between">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-control-label">Login</label>
                      <div class="input-group">
                        <input v-model="user.login" class="form-control" type="text" required placeholder="username">
                        <span class="input-group-addon" v-text="`_1 to ${qty}`"></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-control-label">How many accounts?</label>
                      <div class="input-group">
                        <span class="input-group-btn">
                          <button type="button" class="btn btn-danger" :disabled="qty<=1" @click="qty--">
                            <i class="fa fa-minus"></i>
                          </button>
                        </span>
                        <input type="text" class="form-control" v-model="qty">
                        <span class="input-group-btn">
                          <button type="button" class="btn btn-success" @click="qty++">
                            <i class="fa fa-plus"></i>
                          </button>
                        </span>
                      </div>
                    </div>                    
                  </div>
                </div>
                <user-form :user="users" ref="users" :hidden="['firstName','lastName','email','motivations-help']"></user-form>
                <div class="form-group">
                  <button class="btn btn-success" type="submit">Create accounts</button>
                  <button class="btn btn-secondary" type="reset">Clear</button>
                </div>
            </form>
            
          </div>
        </div>
      </div>
    </div>
  </div> <!-- row -->
</div>
</template>

<script>
import UserForm from '@/components/UserForm'
// import {iotlab} from '@/rest'
import {auth} from '@/auth'

export default {
  name: 'AdminAddUser',
  components: {UserForm},

  data () {
    return {
      user: {},
      users: {
        'motivations': `# created by ${auth.username} for <describe the event>`,
      },
      qty: 3,
      showQty: false,
    }
  },
  watch: {
    qty: function (qty) {
      this.qty = parseInt(qty) || 1
    },
  },
  methods: {
  },
}
</script>
