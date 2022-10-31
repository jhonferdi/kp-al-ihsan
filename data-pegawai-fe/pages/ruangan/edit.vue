<template>
  <b-card title="Edit Ruangan" class="my-4">
    <b-container fluid>
      <b-row class="my-1">
        <b-col sm="3">
          <label for="input-name">Nama Ruangan:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-name" type="text" v-model="form.nama_ruangan" :state="getErrorState('name')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('nama_ruangan') === false">
            {{ getErrorMessage('nama_ruangan') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-unit-kerja">Unit Kerja Fungsional:</label>
        </b-col>
        <b-col sm="9">
          <v-select id="input-unit-kerja" :options="UKOptions" :reduce="item => item.unit_kerja_id"
            label="unit_kerja_nama" placeholder="Pilih Unit Kerja" v-model="form.unit_kerja_id"
            :class="{ 'is-invalid': !getErrorState('unit_kerja_id') }">
          </v-select>
          <p style="color:red;" v-if="getErrorState('unit_kerja_id') === false">
            {{ getErrorMessage('unit_kerja_id') }}
          </p>
        </b-col>
      </b-row>
      <b-row class="my-4">
        <b-col offset-sm="3" sm="9">
          <b-button variant="success" @click="save" :disabled="loading">
            <fa icon="spinner" v-show="loading" spin /> Simpan
          </b-button>
          <b-button variant="light" @click="cancelRuangan()" :disabled="loading">
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
    return { title: "Edit Ruangan" }
  },
  async asyncData({ params }) {
    let f2 = axios.get('ruangan/' + params.id)
    let f3 = axios.get('/unit-kerja', {
      params: {
        perpage: 'all',
        sortby: 'unit_kerja_nama'
      }
    })
    let f3resp = (await f3).data
    let UKOptions = f3resp.data
    let f2resp = (await f2).data
    let form = f2resp.model
    return {
      form,
      UKOptions
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
        let resp = (await axios.patch('ruangan/' + this.$route.params.id, this.form)).data
        if (resp.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil disimpan',
            confirmButtonText: 'ok',
          })
          this.form.nama_ruangan = ''
          this.form.unit_kerja_id = ''
          this.$router.push({ name: 'ruangan' })
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
    cancelRuangan(){
      this.$router.push({ name: 'ruangan' })
    },
  }
}
</script>
