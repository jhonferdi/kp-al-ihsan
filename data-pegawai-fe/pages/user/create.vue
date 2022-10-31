<template>
  <b-card title="User Baru" class="my-4">
    <br>
    <b-container fluid>
      <b-row class="my-2">
        <b-col sm="3">
          <label for="input-name">Nama Pegawai:</label>
        </b-col>
        <b-col sm="9">
          <b-form-select id="input-name" type="text" v-model="form.peg_id" :options="PEGOptions" value-field="peg_id"
            text-field="peg_nama_lengkap" placeholder="Pilih Pegawai" :state="getErrorState('peg_id')">
          </b-form-select>
          <p style="color:red;" v-if="getErrorState('peg_id') === false">
            {{ getErrorMessage('peg_id') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-role">Role:</label>
        </b-col>
        <b-col sm="9">
          <b-form-select id="input-role" type="text" v-model="form.role_id" :options="ROptions" value-field="role_id"
            text-field="nama_role" placeholder="Pilih Role" :state="getErrorState('role_id')">
          </b-form-select>
          <p style="color:red;" v-if="getErrorState('role_id') === false">
            {{ getErrorMessage('role_id') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-username">Username:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-username" type="text" v-model="form.username" :state="getErrorState('username')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('username') === false">
            {{ getErrorMessage('username') }}
          </p>
        </b-col>
      </b-row>
      <br>
      <b-row class="my-4">
        <b-col offset-sm="3" sm="9">
          <b-button variant="success" @click="save" :disabled="loading">
            <fa icon="spinner" v-show="loading" spin /> Simpan
          </b-button>
          <b-button variant="light" @click="cancelUser()" :disabled="loading">
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
    return { title: "Create User" }
  },
  async asyncData({ params }) {
    let dataResp = (await axios.get('/user/get-options'))
    let PEGOptions = dataResp.data.pegawai
    let ROptions = dataResp.data.role
    let form = {
      peg_id: '',
      role_id: '',
      username: '',
    }
    return {
      form,
      ROptions,
      PEGOptions
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
        let resp = (await axios.post('user', this.form)).data
        if (resp.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil disimpan',
            confirmButtonText: 'ok',
          })
          this.form.nama = ''
          this.form.role_id = ''
          this.form.username = ''
          this.$router.push({ name: 'user' })
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
    cancelUser() {
      this.$router.push({ name: 'user' })
    },
  },
}
</script>
