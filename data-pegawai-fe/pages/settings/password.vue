<template>
  <card :title="`Ubah Password`">
    <form @submit.prevent="update" @keydown="form.onKeydown($event)">
      <alert-success :form="form" :message="$t('password_updated')" />

      <!-- Password -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">Password Baru</label>
        <div class="col-md-7">
          <b-form-input type="password" v-model="form.password" :state="getErrorState('password')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('password') === false">
            {{ getErrorMessage('password') }}
          </p>
        </div>
      </div>

      <!-- Password Confirmation -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">Konfirmasi Password</label>
        <div class="col-md-7">
          <b-form-input type="password" v-model="form.password_confirmation"
            :state="getErrorState('password_confirmation')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('password_confirmation') === false">
            {{ getErrorMessage('password_confirmation') }}
          </p>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="form-group row">
        <div class="col-md-9 ml-md-auto">
          <v-button :loading="form.busy" type="success">
            {{ $t('update') }}
          </v-button>
        </div>
      </div>
    </form>
  </card>
</template>

<script>
import Form from 'vform'
import axios from 'axios'
import Swal from 'sweetalert2'
export default {
  scrollToTop: false,

  data: () => ({
    errors: {},
    form: new Form({
      password: '',
      password_confirmation: ''
    })
  }),

  head() {
    return { title: this.$t('settings') }
  },

  methods: {
    async update() {
      this.loading = true
      try {
        let resp = (await axios.put('settings/password', this.form)).data
        if (resp.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Password Berhasil Diubah',
            confirmButtonText: 'ok',
          })
          await this.$store.dispatch('auth/logout')
          // Redirect to login.
          this.$router.push({ name: 'login' })
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
