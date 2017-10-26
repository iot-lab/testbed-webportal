
<template>
<div class="container mt-4">
  <div id="logo" class="mb-2 text-center mx-auto">
    <img src="../assets/disc-iotlab.svg">
  </div>
  <div class="login-box mx-auto text-center" :class="{ 'error': failed }">
    <h2 style="font-size: 1.75rem">Welcome to FIT IoT-LAB</h2>
    <form @submit.prevent="login" class="mx-auto">
      <input v-model="username" type="text" class="form-control" :class="{ 'is-invalid': failed }" placeholder="Username" autofocus required>
      <input v-model="password" type="password" class="form-control" :class="{ 'is-invalid': failed }" placeholder="Password" required>
      <div class="invalid-feedback mb-2">Invalid username or password</div>
      <button type="submit" class="btn btn-primary btn-block">Log in</button>
      <router-link to="signup" class="btn btn-outline-success btn-block">Register for an account</router-link>
      <p class="text-center">
        <router-link to="reset" class="btn btn-block">Forgot your password?</router-link>
      </p>
    </form>
  </div>
</div> <!-- container -->
</template>

<script>
import {auth} from '../auth'

export default {
  name: 'login',
  data () {
    return {
      username: '',
      password: '',
      failed: false,
    }
  },
  methods: {
    async login () {
      try {
        this.failed = false
        await auth.doLogin(this.username, this.password)
        this.$router.push('dashboard')
        // await userStore.login(this.email, this.password)
        // await sleep(200)
        // event.emit('user:loggedin')
      } catch (err) {
        this.failed = true
      }
    },
  },
}
</script>

<style scoped>

/**
 * Shake it!
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

.login-box {
  padding: 15px;
  max-width: 450px;
}
.login-box.error {
  animation: shake .5s;
}

input {
  margin: 7px 0;
}

#logo img {
  width: 30%;
  max-width: 150px;
  opacity: 0.8;
}

form {
  max-width: 300px;
}
</style>
