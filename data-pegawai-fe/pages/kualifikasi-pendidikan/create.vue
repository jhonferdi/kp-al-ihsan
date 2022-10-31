<template>
  <b-card title="Kualifikasi Pendidikan Baru" class="my-4">
    <br>
    <b-container fluid>
      <b-row class="my-2">
        <b-col sm="3">
          <label for="input-name">Nama Kualifikasi Pendidikan:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-name" type="text" v-model="form.kualifikasi_pendidikan"
            :state="getErrorState('name')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('kualifikasi_pendidikan') === false">
            {{ getErrorMessage('kualifikasi_pendidikan') }}
          </p>
        </b-col>
      </b-row>
      <br>
      <b-row class="my-4">
        <b-col offset-sm="3" sm="9">
          <b-button variant="success" @click="save" :disabled="loading">
            <fa icon="spinner" v-show="loading" spin /> Simpan
          </b-button>
          <b-button variant="light" @click="cancelKualifikasiPendidikan()" :disabled="loading">
            Batal
        </b-button>
        </b-col>
      </b-row>
    </b-container>
  </b-card>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
  // middleware: 'auth',
  middleware: ['auth'],

  head() {
    return { title: "Create Kualifikasi Pendidikan" }
  },
  async asyncData({ params }) {
    let form = {
      kualifikasi_pendidikan: '',
    }
    return {
      form,
    }
  },
  data() {
    return {
      loading: false,
      errors: {},
    }
  },
  methods: {
    async save() {
      this.loading = true
      try {
        let resp = (await axios.post('kualifikasi-pendidikan', this.form)).data
        if (resp.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil disimpan',
            confirmButtonText: 'ok',
          })
          this.form.kualifikasi_pendidikan = ''
          this.$router.push({ name: 'kualifikasi-pendidikan' })
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
    cancelKualifikasiPendidikan(){
      this.$router.push({ name: 'kualifikasi-pendidikan' })
    },
  },
}
</script>
