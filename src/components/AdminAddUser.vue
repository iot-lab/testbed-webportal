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
              <user-form :user="user" ref="user" :admin="true"></user-form>
              <div class="form-group">
                <button class="btn btn-success" type="submit">Create account</button>
                <button class="btn btn-secondary" type="reset" @click="$refs.user.clean()">Clear</button>
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
                        <input v-model="baseLogin"
                          type="text" placeholder="login" name="login"
                          class="form-control" :class="{'is-invalid': errors.has('login') }"
                          v-validate="`required|min:3|max:${20-String(qty).length}|iotlabLogin|checkDuplicate`"
                          data-vv-delay="400">
                        <span class="input-group-addon" v-text="`1 to ${qty}`"></span>
                      </div>
                      <div class="invalid-feedback" v-show="errors.has('login')"
                        :style="{'display': errors.has('login') ? 'none': 'block'}">
                        {{ errors.first('login') }}
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
                <user-form :user="users" ref="users" :admin="true" :hidden="['firstName','lastName','email']"></user-form>
                <div class="form-group">
                  <button class="btn btn-success" type="submit">Create accounts</button>
                  <button class="btn btn-secondary" type="reset" @click="$refs.users.clean(); baseLogin = ''; $validator.clean()">Clear</button>
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
import {Validator} from 'vee-validate'
import UserForm from '@/components/parts/UserForm'
import {iotlab} from '@/rest'
import {auth} from '@/auth'

export default {
  name: 'AdminAddUser',
  components: {UserForm},

  created () {
    Validator.extend('iotlabLogin', {
      getMessage: field => 'Must be a valid iotlab login (use only a-z & 0-9 characters).',
      validate: login => /^[a-z][a-z0-9]{3,19}$/.test(login + String(this.qty)),
    })
    Validator.extend('checkDuplicate', {
      getMessage: field => 'This login already exists.',
      validate: async login => {
        try {
          await iotlab.getUserInfo(login + '1')
          return false
        } catch (err) {
          return true
        }
      },
    })
  },

  data () {
    return {
      user: {},
      users: {
        'motivations': `# created by ${auth.username} for <DESCRIBE THE EVENT>`,
      },
      qty: 3,
      baseLogin: '',
      showQty: false,
    }
  },
  watch: {
    qty: function (qty) {
      this.qty = parseInt(qty) || 1
    },
  },
  methods: {
    async createSingle () {
      if (!(await this.$refs.user.validate())) {
        return
      }
      try {
        this.user.status = 'active'
        await iotlab.signup(this.user)
        this.$notify({text: 'User created', type: 'success'})
      } catch (err) {
        this.$notify({text: 'An error occured', type: 'error'})
      }
    },
    createMultiple () {
      this.$validator.validateAll().then(async (valid) => {
        if (!(await this.$refs.users.validate()) || !valid) {
          return
        }
        try {
          for (let i of Array(this.qty).keys()) {
            this.users.login = `${this.baseLogin}${i + 1}`
            this.users.firstName = this.users.lastName = this.users.login
            this.users.email = `${this.users.login}@iot-lab.info`
            this.users.status = 'active'
            console.log('creating user', this.users.login)
            await iotlab.signup(this.users)
          }
          this.$notify({text: `${this.qty} users created`, type: 'success'})
          this.user = {}
        } catch (err) {
          this.$notify({text: 'An error occured', type: 'error'})
        }
      })
    },
  },
}
</script>
