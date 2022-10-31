<template>
  <b-card title="Pegawai Ruangan Baru" class="my-4">
    <br>
    <b-container fluid>
      <b-row class="my-2">
        <b-col sm="3">
          <label for="input-name">Nama Pegawai:</label>
        </b-col>
        <b-col sm="9">
          <v-select id="input-name" :options="PEGOptions" :reduce="item => item.peg_id" label="peg_nama_lengkap"
            placeholder="Pilih Pegawai" v-model="form.peg_id" :class="{ 'is-invalid': !getErrorState('name') }">
          </v-select>
          <p style="color:red;" v-if="getErrorState('peg_id') === false">
            {{ getErrorMessage('peg_id') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-ruangan">Ruangan:</label>
        </b-col>
        <b-col sm="9">
          <v-select id="input-ruangan" :options="RIDOptions" :reduce="item => item.ruangan_id" label="nama_ruangan"
            placeholder="Pilih Ruangan" v-model="form.ruangan_id" :class="{ 'is-invalid': !getErrorState('name') }">
          </v-select>
          <p style="color:red;" v-if="getErrorState('ruangan_id') === false">
            {{ getErrorMessage('ruangan_id') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-role">Role:</label>
        </b-col>
        <b-col sm="9">
          <v-select id="input-role" :options="['Kepala Ruangan', 'Admin Ruangan', 'Pegawai']" label="role"
            placeholder="Pilih Role" v-model="form.role" :class="{ 'is-invalid': !getErrorState('name') }">
          </v-select>
          <p style="color:red;" v-if="getErrorState('role') === false">
            {{ getErrorMessage('role') }}
          </p>
        </b-col>
      </b-row>
      <br>
      <b-row class="my-4">
        <b-col offset-sm="3" sm="9">
          <b-button variant="success" @click="save" :disabled="loading">
            <fa icon="spinner" v-show="loading" spin /> Simpan
          </b-button>
          <b-button variant="light" @click="cancelPegawaiRuangan()" :disabled="loading">
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
    return { title: "Create Pegawai Ruangan" }
  },
  async asyncData({ params }) {

    let f3 = axios.get('/ruangan', {
      params: {
        perpage: 'all',
        sortby: 'nama_ruangan'
      }
    })

    let f4 = axios.get('/pegawai', {
      params: {
        perpage: 'all',
        sortby: 'peg_nama_lengkap'
      }
    })

    let f3resp = (await f3).data
    let f4resp = (await f4).data
    let RIDOptions = f3resp.data
    let PEGOptions = f4resp.data
    let form = {
      peg_id: '',
      ruangan_id: '',
      role: ''
    }
    return {
      form,
      RIDOptions,
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
        let resp = (await axios.post('pegawai-ruangan', this.form)).data
        if (resp.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil disimpan',
            confirmButtonText: 'ok',
          })
          this.form.peg_id = ''
          this.form.ruangan_id = ''
          this.form.role = ''
          this.$router.push({ name: 'pegawai-ruangan' })
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
    cancelPegawaiRuangan(){
      this.$router.push({ name: 'pegawai-ruangan' })
    },
  },
}
</script>
