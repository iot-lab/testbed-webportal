
<template>
  <div class="container" style="margin-top:50px;">
    <div class="row">
      <div class="col-md-4 col-md-offset-4 well">
        <h2>Reset your password</h2>

        <form @submit.prevent="reset">
          <input name="email" type="email" placeholder="Email" class="form-control" autofocus required>
          <button class="btn btn-primary btn-block">Reset password</button>
        </form>

          <p class="text-center">
            <router-link to="signup">Register for an account</router-link> – <router-link to="login">Login</router-link>
          </p>
      </div>
    </div>

  </div> <!-- container -->

</template>

<script>
// import {doLogin} from '../main'

export default {
  name: 'reset',
  data () {
    return {
      username: '',
      password: '',
      failed: false,
    }
  },
  methods: {
    reset () {
      console.log('test')
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

input {
  margin: 5px 0;
}
button {
  margin: 5px 0;
}
.well {
  padding: 25px 40px;
}

</style>
