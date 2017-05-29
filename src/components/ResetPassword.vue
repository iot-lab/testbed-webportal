
<template>
  <div class="container">
    <div id="logo">
      <img src="../assets/disc-iotlab.svg">
    </div>
    <h1 class="text-center">Reset your password</h1>
    <div class="row">
      <div class="col-md-4 col-md-offset-4">
        <div class="well">
          <form @submit.prevent="reset">
            <div class="form-group">
              <label for="txt_email">Enter your email address and we will send you an email with a new password.</label>
              <input v-model="email" type="email" class="form-control" id="txt_email" placeholder="Email" autofocus required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Reset password</button>
          </form>
        </div>
        <div class="panel panel-default">
          <div class="panel-body text-center">
            <router-link to="signup">Create an account</router-link> or <router-link to="login">Sign in</router-link>
          </div>
        </div>
      </div>
    </div>

  </div> <!-- container -->

</template>

<script>
import {iotlab} from '@/rest'

export default {
  name: 'reset',
  data () {
    return {
      email: '',
    }
  },
  methods: {
    async reset () {
      await iotlab.resetPassword(this.email)
      alert('OK')
    },
  },
}
</script>

<style scoped>

/**
 * I like to move it move it
 * I like to move it move it
 * I like to move it move it
 * You like to - move it!
 */
@keyframes shake {
  8%, 41% {
    transform: translateX(-10px);
  }
  25%, 58% {
    transform: translateX(10px);
  }
  75% {
    transform: translateX(-5px);
  }
  92% {
    transform: translateX(5px);
  }
  0%, 100% {
    transform: translateX(0);
  }
}
form.error {
  animation: shake .5s;
}

#logo {
  display:flex;
  justify-content:center;
  align-items:center;
  padding: 20px;
}
#logo img {
  width: 150px;
  opacity: 0.8;
}
</style>
