<template>
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
      <div class="card overflow-hidden">
        <div class="bg-soft bg-primary">
          <div class="row">
            <div class="col-7">
              <div class="text-primary py-4 px-3 px-sm-4">
                <h5 class="text-primary">Selamat Datang !</h5>
                <p>Login untuk melanjutkan.</p>
              </div>
            </div>
            <div class="col-5 align-self-end">
              <img src="@/assets/images/profile-img.png" alt class="img-fluid" />
            </div>
          </div>
        </div>
        <div class="card-body pt-0">
          <div>
            <router-link tag="a" to="/">
              <div class="avatar-md profile-user-wid mb-4">
                <span class="avatar-title rounded-circle bg-light">
                  <img src="@/assets/images/logo-al-ihsan.png" alt height="34" />
                </span>
              </div>
            </router-link>
          </div>
          <!-- <b-alert
          v-model="isAuthError"
          variant="danger"
          class="mt-3"
          dismissible
          >{{ authError }}</b-alert
        >

        <div
          v-if="notification.message"
          :class="'alert ' + notification.type"
        >
          {{ notification.message }}
        </div> -->

          <form @submit.prevent="login" @keydown="form.onKeydown($event)">
            <b-form-group class="mb-3" id="input-group-1" :label="$t('username')" label-for="input-1">
              <input v-model="form.username" :class="{ 'is-invalid': form.errors.has('username') }" type="text"
                name="username" class="form-control">
              <has-error :form="form" field="username" />
            </b-form-group>

            <b-form-group class="mb-3" id="input-group-2" :label="$t('password')" label-for="input-2">
              <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" type="password"
                name="password" class="form-control">
              <has-error :form="form" field="password" />
            </b-form-group>
            <b-row>
              <b-col sm="6">
                <b-form-checkbox class="form-check" id="customControlInline" name="checkbox-1" v-model="remember">
                  {{ $t('remember_me') }}
                </b-form-checkbox>
              </b-col>
              <b-col sm="6" class="d-flex justify-content-end">
                <nuxt-link :to="{name : 'password.email'}">
                  <span>Lupa password?</span>
                </nuxt-link>
              </b-col>
            </b-row>
            <v-button :loading="form.busy" class="btn-block mt-3">
              {{ $t('login') }}
            </v-button>
            <login-with-sso />
            <!-- GitHub Login Button -->
            <!-- <login-with-github /> -->

            <!-- <div class="mt-4 text-center">
            <h5 class="font-size-14 mb-3">Sign in with</h5>

            <ul class="list-inline">
              <li class="list-inline-item">
                <a
                  href="javascript: void(0);"
                  class="social-list-item bg-primary text-white border-primary"
                >
                  <i class="mdi mdi-facebook"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a
                  href="javascript: void(0);"
                  class="social-list-item bg-info text-white border-info"
                >
                  <i class="mdi mdi-twitter"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a
                  href="javascript: void(0);"
                  class="social-list-item bg-danger text-white border-danger"
                >
                  <i class="mdi mdi-google"></i>
                </a>
              </li>
            </ul>
          </div>
          <div class="mt-4 text-center">
            <router-link tag="a" to="/forgot-password" class="text-muted">
              <i class="mdi mdi-lock me-1"></i> Forgot your password?
            </router-link>
          </div> -->
          </form>
        </div>
        <!-- end card-body -->
      </div>
      <!-- <div class="mt-5 text-center">
        <p>
          Don't have an account ?
          <router-link
            tag="a"
            to="/auth/register-1"
            class="fw-medium text-primary"
            >Signup now</router-link
          >
        </p>
        <p>
          © {{ new Date().getFullYear() }} Skote. Crafted with
          <i class="mdi mdi-heart text-danger"></i> by Themesbrand
        </p>
      </div> -->
    </div>
  </div>
</template>

<script>
import Form from 'vform'

export default {
  middleware: 'guest',
  layout: 'auth',

  data: () => ({
    form: new Form({
      username: '',
      password: ''
    }),
    remember: false
  }),

  head() {
    return { title: this.$t('login') }
  },

  methods: {
    async login() {
      let data

      // Submit the form.
      try {
        const response = await this.form.post('/login')
        data = response.data
      } catch (e) {
        return
      }

      // Save the token.
      this.$store.dispatch('auth/saveToken', {
        token: data.token,
        remember: this.remember
      })

      // Fetch the user.
      await this.$store.dispatch('auth/fetchUser')

      // Redirect home.
      if (window.prev_url) {
        this.$router.push(window.prev_url)
      } else {
        if (data.role == 1) {
          this.$router.push({ name: "dashboard" })
        } else {
          this.$router.push({ name: "settings.profile" })
        }
      }
    }
  }
}
</script>
