<template>
  <div class="row">
    <div class="col-lg-8 m-auto">
      <b-card>
        <b-card-header>
          <h3>Reset Password</h3>
        </b-card-header>
        <b-card-body>
          <div class="mb-3">
            <label>Email</label>
            <b-form-input v-model="form.email" type="email" :state="getErrorState('email')">
            </b-form-input>
            <p style="color:red;" v-if="getErrorState('email') === false">
                      {{ getErrorMessage('email') }}
            </p>
          </div>
          <b-row>
            <b-col sm="8">
              <b-button variant="lightgreen" @click="send()" :disabled="loading">
                <i class="fa fa-mail"></i> {{ loading ? 'Sedang Mengirim' : 'Kirim Link Reset Password' }}
              </b-button>
            </b-col>
            <b-col sm="4" class="d-flex justify-content-end">
              <nuxt-link :to="{name : 'welcome'}">
                <span>Login disini...</span>
              </nuxt-link>
            </b-col>
          </b-row>
        </b-card-body>
      </b-card>
    </div>
  </div>
</template>

<script>
import Swal from 'sweetalert2'
import axios from 'axios'

export default {
  middleware: 'guest',
  layout: 'auth',

  data() {
    return {
      form: {},
      errors: {},
      loading: false
    }
  },

  head() {
    return { title: this.$t('reset_password') }
  },

  methods: {
    async send() {
      this.loading = true
      try {
        let resp = (await axios.post('password/email', this.form)).data
        if (resp.code == 200) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Silahkan cek email anda',
            confirmButtonText: 'ok',
          })
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Gagal menyimpan data',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        }
        this.errors = {}
      } catch (err) {
        if (err.response && err.response.status == 422) {
          this.errors = err.response.data.errors
        }
      }
      this.loading = false
    },
    getErrorState(key) {
            if (this.errors[key]) {
                return false
            }
            return null
        },
        getErrorMessage(key) {
            if (this.errors[key]) {
                return this.errors[key].join(', ')
            }
            return null
        },
  }
}
</script>
