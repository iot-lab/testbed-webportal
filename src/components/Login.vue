
<template>
  <div class="container">
    <div id="logo">
      <img src="../assets/disc-iotlab.svg">
    </div>
    <h1 class="text-center">Sign in to FIT IoT-LAB</h1>
    <div class="row">
      <div class="col-md-4 col-md-offset-4">
        <div class="well" :class="{ 'error': failed }">
          <form @submit.prevent="login">
            <div class="form-group">
              <label for="txt_username">Username</label>
              <input v-model="username" type="text" class="form-control" id="txt_username" placeholder="Username" autofocus required>
            </div>
            <div class="form-group">
              <label for="txt_password">Password</label>
              <router-link class="pull-right" to="reset">Forgot your password?</router-link>
              <input v-model="password" type="password" class="form-control" id="txt_password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Sign in</button>
          </form>
        </div>
        <div class="panel panel-default">
          <div class="panel-body text-center">
            New on FIT IoT-LAB? <router-link to="signup">Create an account</router-link>
          </div>
        </div>
      </div>
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
        // clear password so that the next login will have this field empty.
        // this.password = ''
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

.well.error {
  animation: shake .5s;
  border-color: #a94442;
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
