<template>
  <b-card title="Edit Golongan" class="my-4">
    <b-container fluid>
      <b-row class="my-1">
        <b-col sm="3">
          <label for="input-name">Nama Golongan:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-name" type="text" v-model="form.golongan_nama" :state="getErrorState('name')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('golongan_nama') === false">
            {{ getErrorMessage('golongan_nama') }}
          </p>
        </b-col>
      </b-row>
      <b-row class="my-4">
        <b-col offset-sm="3" sm="9">
          <b-button variant="success" @click="save" :disabled="loading">
            <fa icon="spinner" v-show="loading" spin /> Simpan
          </b-button>
          <b-button variant="light" @click="cancelGolongan()" :disabled="loading">
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
    return { title: "Edit Golongan" }
  },
  async asyncData({ params }) {
    let f2 = axios.get('golongan/' + params.id)
    let f2resp = (await f2).data
    let form = f2resp.model
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
        let resp = (await axios.patch('golongan/' + this.$route.params.id, this.form)).data
        if (resp.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil disimpan',
            confirmButtonText: 'ok',
          })
          this.form.golongan_nama = ''
          this.$router.push({ name: 'golongan' })
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
    cancelGolongan(){
      this.$router.push({ name: 'golongan' })
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
