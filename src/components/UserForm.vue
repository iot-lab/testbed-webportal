<template>
  <div>
    <div class="row">
      <div class="col-md">
        <div class="form-group" v-if="!hidden.includes('firstName')">
          <label class="form-control-label">First name</label>
          <input placeholder="First name" v-model="user.firstName" name="firstname"
            class="form-control" type="text" v-validate="'required'"
            :class="{'is-invalid': errors.has('firstname') }">
          <div class="invalid-feedback" v-show="errors.has('firstname')">
            {{ errors.first('firstname') }}
          </div>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" v-if="!hidden.includes('lastName')">
          <label class="form-control-label">Last name</label>
          <input placeholder="Last name" v-model="user.lastName" name="lastname"
            class="form-control" type="text" v-validate="'required'"
            :class="{'is-invalid': errors.has('lastname') }">
          <div class="invalid-feedback" v-show="errors.has('lastname')">
            {{ errors.first('lastname') }}
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md">
        <div class="form-group" v-if="!hidden.includes('email')">
          <label class="form-control-label">Email</label>
          <input v-model="user.email" name="email" class="form-control" type="email"
            placeholder="Academic or professional email" v-validate="'required|email|noWebMail'"
            :class="{'is-invalid': errors.has('email') }">
          <div class="invalid-feedback" v-show="errors.has('email')" v-html="errors.first('email')">
          </div>
        </div>
      </div>
      <div class="col-md">
      </div>
    </div>
    <div class="row mb-5" v-if="!hidden.includes('password')">
      <div class="col form-group password" ref="passwordGroup">
        <label class="form-control-label">Password</label>
        <input placeholder="Password" v-model="user.password" name="password"
          class="form-control" type="password" v-validate="'required'" ref="password"
          v-adv-tooltip="{
            title: passwordPolicy,
            placement: 'auto',
            trigger: 'hover',
            html: true,
            delay: 400,
            container: this.$refs.passwordGroup,
            }"
          :class="{'is-invalid': errors.has('password') }">
        <div class="invalid-feedback" v-show="errors.has('password')">
          {{ errors.first('password') }}
        </div>
      </div>
      <div class="col form-group">
        <label class="form-control-label">Confirm password</label>
        <input placeholder="Password" name="password_confirmation"
          data-vv-as="password" class="form-control" type="password" v-validate="'required|confirmed:password'"
          :class="{'is-invalid': errors.has('password_confirmation') }">
        <div class="invalid-feedback" v-show="errors.has('password_confirmation')">
          {{ errors.first('password_confirmation') }}
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md">
        <div class="form-group" v-if="!hidden.includes('category')">
          <label class="form-control-label">User category</label>
          <multiselect v-model="user.category"
            placeholder="Category"
            :options="Object.keys(categories)"
            :allow-empty="false"
            :searchable="false"
            :show-labels="false"
            :custom-label="userLabel"
            :class="{'mymultiselect': true, 'invalid': dirty.category && !user.category}"
            @close="dirty.category = true">
          </multiselect>
          <div class="invalid-feedback" :style="{'display': (dirty.category && !user.category) ? 'block': 'none'}">
            The user category field is required.
          </div>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" v-if="!hidden.includes('organization')">
          <label class="form-control-label">Organization</label>
          <input placeholder="Organization" v-model="user.organization" name="organization"
            class="form-control" type="text" v-validate="'required'" :class="{'is-invalid': errors.has('organization') }">
          <div class="invalid-feedback" v-show="errors.has('organization')">
            {{ errors.first('organization') }}
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md">
        <div class="form-group" v-if="!hidden.includes('city')">
          <label class="form-control-label">City</label>
          <input placeholder="City" v-model="user.city" name="city"
            class="form-control" type="text" v-validate="'required'" :class="{'is-invalid': errors.has('city') }">
          <div class="invalid-feedback" v-show="errors.has('city')">
            {{ errors.first('city') }}
          </div>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" v-if="!hidden.includes('country')">
          <label class="form-control-label">Country</label>
          <multiselect v-model="user.country"
            placeholder="Country"
            :options="countries"
            :class="{'mymultiselect': true, 'invalid': dirty.country && !user.country}"
            @close="dirty.country = true">
          </multiselect>
          <div class="invalid-feedback" :style="{'display': (dirty.country && !user.country) ? 'block': 'none'}">
            The country field is required.
          </div>
        </div>
      </div>
    </div>
    <div class="form-group" v-if="!hidden.includes('motivations')">
      <label class="form-control-label">Motivations</label>
      <textarea v-model="user.motivations"
        name="motivations"
        class="form-control"
        rows="5"
        placeholder="Tell us about you"
        v-validate="'required'"
        @focus="toggleMotivations"
        @blur="toggleMotivations"
        :class="{'is-invalid': errors.has('motivations') }">
      </textarea>
      <div class="invalid-feedback" v-show="errors.has('motivations')">
        {{ errors.first('motivations') }}
      </div>
    </div>
    <div class="form-group" id="motivations-help" style="display: none">
      <div class="alert alert-info form-control">
        <i class="fa fa-pencil"></i> <b>Tell us about your motivations</b>
        <ul style="margin: 0">
          <li>Research domain (Radio communication, networking protocol, distributed applications, &#x2026;)</li>
          <li>What kind of experiments do you want to run with IoT-LAB?</li>
          <li>Goal (Verify something existing in large scale, new development, &#x2026;)</li>
          <li>Experience with sensor networks (n00b, experiments with X platform, former IoT-LAB user, Guru, God)</li>
        </ul>
      </div>
    </div>
    <div class="form-group" v-if="!hidden.includes('sshkeys')">
      <a data-toggle="collapse" href=".collapse-ssh" role="button" aria-expanded="false" aria-controls="collapseSSH">
        <label class="form-control-label" v-if="mode === 'edit'">
          <i class="fa fa-caret-right"></i> SSH keys
        </label>
        <label class="form-control-label" v-else>
          <i class="fa fa-caret-right"></i> add SSH key <span class="text-muted">(optional, can be done later)</span>
        </label>
      </a>
      <ssh-keys :keys="user.sshkeys" class="collapse collapse-ssh"></ssh-keys>
      <div class="invalid-feedback" v-show="errors.has('motivations')">
        {{ errors.first('motivations') }}
      </div>
    </div>
    <div class="form-group" v-if="!hidden.includes('groups')">
      <label class="form-control-label">User groups</label>
      <multiselect v-model="user.groups"
                    placeholder="Groups"
                    :options="store.groups ? store.groups.map(el => el.name) : []"
                    :allow-empty="true"
                    :searchable="false"
                    :multiple="true"
                    :close-on-select="false"
                    :hide-selected="true"
                    :show-labels="false"
                    :class="{'mymultiselect': true}">
        <template slot="tag" slot-scope="props">
          <span class="badge-tag badge" :class="props.option === 'admin' ? 'badge-warning' : 'badge-primary'">
            <span>{{props.option}}</span>
            <span class="tag-remove cursor ml-1" @click="props.remove(props.option)">&times;</span>
          </span>
        </template>
        <template slot="option" slot-scope="props">
          <span :class="props.option === 'admin' ? 'text-warning' : ''"><em>{{props.option}}</em></span>
        </template>
      </multiselect>
    </div>
  </div>
</template>

<script>
import Multiselect from 'vue-multiselect'
import SshKeys from '@/components/SshKeysForm'
import WebmailDomains from '@/assets/js/webmail-domains'
import countries from '@/assets/js/countries'
import UserCategories from '@/assets/js/categories'
import $ from 'jquery'
import { Validator } from 'vee-validate'
import { iotlab, email } from '@/rest'
import store from '@/store'

export default {
  name: 'UserForm',
  components: { Multiselect, SshKeys },

  props: {
    user: {
      // Initial user object for form field values
      type: Object,
      default: () => {},
    },
    hidden: {
      // List of user fields to be hidden from the form
      type: [String, Array],
      default: () => [],
    },
    admin: {
      // when admin=true, no validation on user email domain & no motivations help text
      type: Boolean,
      default: () => false,
    },
    mode: {
      // when mode=edit, the caption for ssh-keys collapse link will be different
      type: String,
      default: 'create',
    },
  },

  data () {
    return {
      reCaptchaSitekey: '6Ld8cR4UAAAAAC-zBLP9m2bC35xyyYwTbvkBcx4q',
      countries: countries,
      categories: UserCategories,
      dirty: {
        category: false,
        country: false,
      },
      store: store,
    }
  },

  created () {
    Validator.extend('noWebMail', {
      getMessage: field => `Your email must be <b>academic</b> or <b>professional</b> in order to validate your account (<b>${this.user.email.split('@')[1]}</b> not allowed).`,
      validate: email => (this.admin || (!WebmailDomains.includes(email.split('@')[1].toLowerCase()) && this.getDebounceEmail(email))),
    }, {
      immediate: false,
    })
    if (!this.hidden.includes('groups')) {
      iotlab.getUserGroups()
        .then(data => { this.store.groups = data })
        .catch(err => this.$notify({text: err.response.data.message || 'Failed to fetch groups', type: 'error'}))
    }
  },

  methods: {
    clean (newUser = {}) {
      // clear errors
      this.$validator.reset()
      this.dirty = {
        category: false,
        country: false,
      }
      // reset user value
      this.user = newUser
    },
    async getDebounceEmail (emailUser) {
      try {
        let debounce = await email.getDebounceEmail(emailUser)
        if (debounce === 'true') {
          return false
        } else {
          // valid email
          return true
        }
      } catch (ex) {
        console.log(ex)
        // no blocking in case of error (i.e. API rate limiting)
        return true
      }
    },
    async validate () {
      return this.$validator.validateAll().then((validated) => {
        if (!validated || !this.user.country || !this.user.category) {
          this.dirty.category = this.dirty.country = true
          console.log('User form is not valid.')
          return false
        }
        return true
      })
    },
    userLabel (cat) {
      return UserCategories[cat]
    },
    toggleMotivations () {
      if (!this.admin) {
        $('#motivations-help').slideToggle()
      }
    },
  },

  computed: {
    passwordPolicy () {
      return '<h6>Password Policy</h6><ul class="pl-3 mb-0"><li>one upper case letter [A-Z]</li><li>three lower case letters [a-z]</li><li>one digit [0-9]</li><li>one special character [!@#$%^&*_=+-/]</li><li>minimum length of eight characters</li></ul>'
    },
  },
}
</script>

<style scoped>
ul {
  padding-left: 20px;
}
</style>
<style>
/* Let multiselect style match bootstrap 4 */

.multiselect__single, .multiselect__input {
  font-size: 1rem !important;
  line-height: 1.25 !important;
  color: #495057 !important;
}
.multiselect__tags {
  border: 1px solid rgba(0,0,0,.15) !important;
  border-radius: .25rem !important;
}
.mymultiselect .multiselect__tags {
  min-height: 38px !important;
}
.invalid .multiselect__tags {
  border-color: var(--danger) !important;
}
.multiselect__option--highlight {
  background: var(--primary) !important;
}
.multiselect__option--highlight:after {
  background: linear-gradient(90deg, rgba(0,0,0,0) 0%, var(--primary) 10%, var(--primary) 100%) !important;
}

.password>.tooltip>.tooltip-inner {
  max-width: 400px;
  text-align: left;
}
</style>
