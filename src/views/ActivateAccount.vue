<template>
<div class="container mt-3">
  <div class="row">
    <div class="col-lg-8">
      <h2>Activate account</h2>
      <div v-if="success" class="card border-success my-5">
        <div class="card-header bg-success text-white"><i class="fa fa-check mr-1"></i> Your account has been activated</div>
        <div class="card-body text-success">
          <h5>Welcome to FIT IoT-LAB</h5>
          <p>You will receive an email with your username and password.</p>
          <router-link :to="{name:'login'}" class="btn btn-block btn-outline-success w-25 mx-auto mt-3">Log in</router-link>
        </div>
      </div>
      <div v-if="success === false" class="card border-danger my-5">
        <div class="card-header bg-danger text-white"><i class="fa fa-times mr-1"></i> Your account cannot be activated</div>
        <div class="card-body text-danger">
          <p>Maybe the activation link is incorrect or expired.</p>
          <p>Try to sign up again or contact the administrators.</p>
        </div>
      </div>
    </div>
  </div> <!-- row -->
</div>
</template>

<script>
import {iotlab} from '@/rest'

export default {
  name: 'ActivateAccount',

  data () {
    return {
      success: undefined,
    }
  },

  async created () {
    let hash = this.$route.query.hash
    if (!hash) {
      this.$notify({text: 'Hash is missing from URL', type: 'error'})
      return
    }
    try {
      await iotlab.activateAccount(hash)
      this.success = true
    } catch (err) {
      this.success = false
      this.$notify({text: 'Account activation failed', type: 'error'})
    }
  },

}
</script>
