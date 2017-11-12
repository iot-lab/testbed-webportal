<template>
  <div>
    <div class="form-group">
      <label class="form-control-label">Recipients <small class="text-muted">(comma separated)</small></label>
      <input v-model="to" name="recipients" class="form-control" type="text"
        placeholder="Recipients" v-validate="'required'"
        :class="{'is-invalid': errors.has('recipients') }">
      <div class="invalid-feedback" v-show="errors.has('recipients')" v-html="errors.first('recipients')">
      </div>
    </div>

    <div class="form-group">
      <label class="form-control-label">Subject</label>
      <input v-model="subject" name="subject" class="form-control" type="text"
        placeholder="Subject" v-validate="'required'"
        :class="{'is-invalid': errors.has('subject') }">
      <div class="invalid-feedback" v-show="errors.has('subject')" v-html="errors.first('subject')">
      </div>
    </div>

    <div class="form-group">
      <textarea v-model="message"
        name="message"
        class="form-control"
        rows="8"
        placeholder="Message"
        v-validate="'required'"
        :class="{'is-invalid': errors.has('message') }">
      </textarea>
      <div class="invalid-feedback" v-show="errors.has('message')">
        {{ errors.first('message') }}
      </div>
    </div>
  </div>
</template>

<script>
import $ from 'jquery'
import {iotlab} from '@/rest'

export default {
  name: 'EmailForm',

  props: {
    to: {
      // List of email addresses (comma separated)
      type: String,
      default: () => '',
    },
    subject: {
      // List of user fields to be hidden from the form
      type: String,
      default: () => '',
    },
    message: {
      // email body
      type: String,
      default: () => '',
    },
  },

  computed: {
    recipients () {
      return this.to.split(',').map(email => email.trim())
    },
  },

  methods: {
    send () {
      return this.$validator.validateAll().then(async (valid) => {
        if (!valid) {
          return false
        }
        try {
          await iotlab.sendMail(this.recipients, this.subject, this.message)
          this.$notify({text: 'Email sent', type: 'success'})
          $('.email-user-modal').modal('hide')
        } catch (err) {
          this.$notify({text: 'An error occured', type: 'error'})
        }
        return true
      })
    },
  },
}
</script>
