<template>
  <b-card title="Sub Spesialis Baru" class="my-4">
    <br>
    <b-container fluid>
      <b-row class="my-2">
        <b-col sm="3">
          <label for="input-name">Nama Sub Spesialis:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-name" type="text" v-model="form.sub_spesialis_nama" :state="getErrorState('name')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('sub_spesialis_nama') === false">
            {{ getErrorMessage('sub_spesialis_nama') }}
          </p>
        </b-col>
      </b-row>
      <br>
      <b-row class="my-4">
        <b-col offset-sm="3" sm="9">
          <b-button variant="success" @click="save" :disabled="loading">
            <fa icon="spinner" v-show="loading" spin /> Simpan
          </b-button>
          <b-button variant="light" @click="cancelSubSpesialis()" :disabled="loading">
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
    return { title: "Create Sub Spesialis" }
  },
  async asyncData({ params }) {
    let form = {
      sub_spesialis_nama: '',
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
        let resp = (await axios.post('sub-spesialis', this.form)).data
        if (resp.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil disimpan',
            confirmButtonText: 'ok',
          })
          this.form.sub_spesialis_nama = ''
          this.$router.push({ name: 'sub-spesialis' })
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
    cancelSubSpesialis(){
      this.$router.push({ name: 'sub-spesialis' })
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
  },
}
</script>
