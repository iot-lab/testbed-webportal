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
                <div class="row">
                  <div class="col-md">
                    <div class="form-group">
                      <label class="form-control-label">First name</label>
                      <input placeholder="First name" v-model="user.firstName" class="form-control" type="text" required>
                    </div>
                  </div>
                  <div class="col-md">
                    <div class="form-group">
                      <label class="form-control-label">Last name</label>
                      <input placeholder="Last name" v-model="user.lastName" class="form-control" type="text" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md">
                    <div class="form-group">
                      <label class="form-control-label">Email</label>
                      <input v-model="user.email" class="form-control" type="email" required placeholder="Academic or professional email">
                    </div>
                  </div>
                  <div class="col-md"></div>
                </div>
                <div class="row">
                  <div class="col-md">
                    <div class="form-group">
                      <label class="form-control-label">User category</label>
                      <multiselect v-model="user.category" :options="Object.keys(categories)" placeholder="Category" :searchable="false" required :custom-label="userLabel" :show-labels="false"></multiselect>
                    </div>
                  </div>
                  <div class="col-md">
                    <div class="form-group">
                      <label class="form-control-label">Organization</label>
                      <input placeholder="Organization" v-model="user.organization" class="form-control" type="text" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md">
                    <div class="form-group">
                      <label class="form-control-label">City</label>
                      <input placeholder="City" v-model="user.city" class="form-control" type="text" required>
                    </div>
                  </div>
                  <div class="col-md">
                    <div class="form-group">
                      <label class="form-control-label">Country</label>
                      <multiselect :options="countries" v-model="user.country" required placeholder="Country"></multiselect>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="form-control-label">Motivations</label>
                  <textarea v-model="user.motivations" class="form-control" rows="5" placeholder="Motivations" required></textarea>
                </div>
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
                <div class="row">
                  <div class="col-md">
                    <div class="form-group">
                      <label class="form-control-label">User category</label>
                      <multiselect v-model="user.category" :options="Object.keys(categories)" placeholder="Category" :searchable="false" required :custom-label="userLabel" :show-labels="false"></multiselect>
                    </div>
                  </div>
                  <div class="col-md">
                    <div class="form-group">
                      <label class="form-control-label">Organization</label>
                      <input placeholder="Organization" v-model="user.organization" class="form-control" type="text" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md">
                    <div class="form-group">
                      <label class="form-control-label">City</label>
                      <input placeholder="City" v-model="user.city" class="form-control" type="text" required>
                    </div>
                  </div>
                  <div class="col-md">
                    <div class="form-group">
                      <label class="form-control-label">Country</label>
                      <multiselect :options="countries" v-model="user.country" required placeholder="Country"></multiselect>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="form-control-label">Motivations / Event</label>
                  <textarea v-model="user.motivations" class="form-control" rows="5" placeholder="Describe the event" required></textarea>
                </div>
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
import Multiselect from 'vue-multiselect'
import {iotlab} from '@/rest'
import countries from '@/assets/js/countries'
import UserCategories from '@/assets/js/categories'

export default {
  name: 'AdminAddUser',
  components: {Multiselect},

  data () {
    return {
      user: {},
      countries: countries,
      categories: UserCategories,
      category: undefined,
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
    async signup () {
      // check charter read
      if (!this.charter) {
        alert('Please read and accept the Terms of Service')
        return
      }
      // check reCaptcha verified
      if (!this.verified) {
        alert('Please verify the captcha "I\'m not a robot"')
        return
      }

      try {
        await iotlab.signup(this.user)
        // this.$router.push('dashboard')
        alert('Success')
        this.success = true
      } catch (err) {
        this.error = true
        alert('Error')
      }
    },
    userLabel (cat) {
      return UserCategories[cat]
    },
  },
}
</script>
