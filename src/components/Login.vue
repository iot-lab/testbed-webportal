
<template>
<div class="container">
    <div id="logo">
        <img src="../assets/disc-iotlab.svg">
    </div>
    <div class="row">
        <div class="col-lg-4 mx-md-auto well" :class="{ 'error': failed }">
            <h2>Welcome to IoT-LAB</h2>
            <form @submit.prevent="login">
                <input v-model="username" type="text" class="form-control" :class="{ 'is-invalid': failed }" placeholder="Username"autofocus required>
                <div class="invalid-feedback">Invalid username or password</div>
                <input v-model="password" type="password" class="form-control" :class="{ 'is-invalid': failed }" placeholder="Password" required>
                <button type="submit" class="btn btn-primary btn-block">Log in</button>
                <router-link to="signup" class="btn btn-light btn-block">Register for an account</router-link>
                <p class="text-center">
                    <router-link to="reset" class="btn btn-block">Forgot your password?</router-link>
                </p>
            </form>
        </div>
    </div>
    <!-- row -->
</div>
<!-- container -->
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

.well {
  padding: 15px 38px;
}
.well.error {
  animation: shake .5s;
}

input {
  margin: 7px 0;
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
