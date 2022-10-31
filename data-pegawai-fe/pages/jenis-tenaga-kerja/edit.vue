<template>
  <b-card title="Edit Jenis Tenaga Kerja" class="my-4">
    <b-container fluid>
      <b-row class="my-1">
        <b-col sm="3">
          <label for="input-name">Nama Jenis Tenaga Kerja:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-name" type="text" v-model="form.jenis_tenaga_kerja_nama"
            :state="getErrorState('name')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('jenis_tenaga_kerja_nama') === false">
            {{ getErrorMessage('jenis_tenaga_kerja_nama') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-tkerja">Tenaga Kerja:</label>
        </b-col>
        <b-col sm="9">
          <v-select id="input-tkerja" :options="TKOptions" :reduce="item => item.tenaga_kerja_id"
            label="tenaga_kerja_nama" placeholder="Pilih Tenaga Kerja" v-model="form.tenaga_kerja_id"
            :class="{ 'is-invalid': !getErrorState('tenaga_kerja_id') }">
          </v-select>
          <p style="color:red;" v-if="getErrorState('tenaga_kerja_id') === false">
            {{ getErrorMessage('tenaga_kerja_id') }}
          </p>
        </b-col>

      </b-row>
      <b-row class="my-4">
        <b-col offset-sm="3" sm="9">
          <b-button variant="success" @click="save" :disabled="loading">
            <fa icon="spinner" v-show="loading" spin /> Simpan
          </b-button>
          <b-button variant="light" @click="cancelJenisTenagaKerja()" :disabled="loading">
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
    return { title: "Edit Jenis Tenaga Kerja" }
  },
  async asyncData({ params }) {
    let f2 = axios.get('jenis-tenaga-kerja/' + params.id)
    let f2resp = (await f2).data

    let f3 = axios.get('/tenaga-kerja', {
      params: {
        perpage: 'all',
        sortby: 'tenaga_kerja_nama'
      }
    })

    let f3resp = (await f3).data

    let form = f2resp.model
    let TKOptions = f3resp.data
    return {
      form,
      TKOptions,
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
        let resp = (await axios.patch('jenis-tenaga-kerja/' + this.$route.params.id, this.form)).data
        if (resp.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil disimpan',
            confirmButtonText: 'ok',
          })
          this.form.jenis_tenaga_kerja_nama = ''
          this.form.tenaga_kerja_id = ''
          this.$router.push({ name: 'jenis-tenaga-kerja' })
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
    cancelJenisTenagaKerja(){
      this.$router.push({ name: 'jenis-tenaga-kerja' })
    },
  }
}
</script>
