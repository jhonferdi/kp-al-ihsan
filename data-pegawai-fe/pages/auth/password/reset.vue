<template>
  <div class="row">
    <div class="col-lg-8 m-auto">
      <b-card>
        <b-card-header>
          <h3>Password Baru</h3>
        </b-card-header>
        <b-card-body>
          <div class="mb-3">
            <label>Email</label>
            <b-form-input v-model="form.email" type="email" readonly></b-form-input>
          </div>
          <div class="mb-3">
            <label>Password Baru</label>
            <b-form-input v-model="form.password" type="password" :state="getErrorState('password')"></b-form-input>
            <p style="color:red;" v-if="getErrorState('password') === false">
                {{ getErrorMessage('password') }}
            </p>
          </div>
          <div class="mb-3">
            <label>Konfirmasi Password</label>
            <b-form-input v-model="form.password_confirmation" type="password" :state="getErrorState('password')"></b-form-input>
            <p style="color:red;" v-if="getErrorState('password') === false">
                {{ getErrorMessage('password') }}
            </p>
          </div>
          <b-row>
            <b-col sm="8">
              <b-button variant="lightgreen" @click="send()" :disabled="loading">
                <i class="fa fa-mail"></i> {{ loading ? 'Sedang Mengupdate' : 'Update Password' }}
              </b-button>
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
      errors:{},
      loading: false
    }
  },

  head() {
    return { title: 'Password Baru' }
  },

  created() {
    this.form.email = this.$route.query.email
    this.form.token = this.$route.params.token
  },

  methods: {
    async send() {
      this.loading = true
      try {
        let resp = (await axios.post('password/reset', this.form)).data
        if (resp.code == 200) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Password Telah Diubah',
            confirmButtonText: 'ok',
          })
          this.$router.push({ name: 'welcome' })
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
