<template>
  <b-card title="Jabatan Baru" class="my-4">
    <br>
    <b-container fluid>
      <b-row class="my-2">
        <b-col sm="3">
          <label for="input-name">Nama Jabatan:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-name" type="text" v-model="form.jabatan_nama" :state="getErrorState('name')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('jabatan_nama') === false">
            {{ getErrorMessage('jabatan_nama') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-fungsional">Jabatan Fungsional:</label>
        </b-col>
        <b-col sm="9">
          <v-select id="input-fungsional" :options="JFOptions" :reduce="item => item.jabatan_fungsional_id"
            label="jabatan_fungsional_nama" placeholder="Pilih Jabatan Fungsional" v-model="form.jabatan_fungsional_id"
            :class="{ 'is-invalid': !getErrorState('jabatan_fungsional_id') }">
          </v-select>
          <p style="color:red;" v-if="getErrorState('jabatan_fungsional_id') === false">
            {{ getErrorMessage('jabatan_fungsional_id') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-unit-kerja">Unit Kerja Struktural:</label>
        </b-col>
        <b-col sm="9">
          <v-select id="input-unit-kerja" :options="UKOptions" :reduce="item => item.unit_kerja_id_struktural"
            label="unit_kerja_nama" placeholder="Pilih Unit Kerja" v-model="form.unit_kerja_id_struktural"
            :class="{ 'is-invalid': !getErrorState('unit_kerja_id_struktural') }">
          </v-select>
          <p style="color:red;" v-if="getErrorState('unit_kerja_id_struktural') === false">
            {{ getErrorMessage('unit_kerja_id_struktural') }}
          </p>
        </b-col>
      </b-row>
      <br>
      <b-row class="my-4">
        <b-col offset-sm="3" sm="9">
          <b-button variant="success" @click="save" :disabled="loading">
            <fa icon="spinner" v-show="loading" spin /> Simpan
          </b-button>
          <b-button variant="light" @click="cancelJabatan()" :disabled="loading">
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
  middleware: ['auth'],

  head() {
    return { title: "Create Jabatan" }
  },
  async asyncData({ params }) {

    let f3 = axios.get('/jabatan-fungsional', {
      params: {
        perpage: 'all',
        sortby: 'jabatan_fungsional_nama'
      }
    })

    let f4 = axios.get('/unit-kerja', {
      params: {
        perpage: 'all',
        sortby: 'unit_kerja_nama'
      }
    })

    let f3resp = (await f3).data
    let f4resp = (await f4).data
    let UKOptions = f4resp.data
    let JFOptions = f3resp.data
    let form = {
      jabatan_nama: '',
      jabatan_fungsional_id: '',
      unit_kerja_id_struktural: '',
    }
    return {
      form,
      JFOptions,
      UKOptions,
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
        let resp = (await axios.post('jabatan', this.form)).data
        if (resp.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil disimpan',
            confirmButtonText: 'ok',
          })
          this.form.jabatan_nama = ''
          this.form.jabatan_fungsional_id = ''
          this.form.unit_kerja_id_struktural = ''
          this.$router.push({ name: 'jabatan' })
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
    cancelJabatan(){
      this.$router.push({ name: 'jabatan' })
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
