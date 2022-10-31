<template>
  <b-card title="Edit Riwayat Pangkat" class="my-4">
    <b-container fluid>
      <b-row class="my-2">
        <b-col sm="3">
          <label for="input-name">Nama Pegawai:</label>
        </b-col>
        <b-col sm="9">
          <v-select id="input-name" :options="PEGOptions" :reduce="item => item.peg_id" label="peg_nama_lengkap"
            placeholder="Pilih Pegawai" v-model="form.peg_id" :class="{ 'is-invalid': !getErrorState('peg_id') }">
          </v-select>
          <p style="color:red;" v-if="getErrorState('peg_id') === false">
            {{ getErrorMessage('peg_id') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-date">Tanggal Pangkat:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="tgl_pangkat" type="date" v-model="form.tgl_pangkat" :state="getErrorState('tgl_pangkat')" />
          <p style="color:red;" v-if="getErrorState('tgl_pangkat') === false">
            {{ getErrorMessage('tgl_pangkat') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-sk">Nomor SK:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input sk="input-sk" type="text" v-model="form.nomor_sk" :state="getErrorState('sk')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('nomor_sk') === false">
            {{ getErrorMessage('nomor_sk') }}
          </p>
        </b-col>
      </b-row>
      <br>
      <b-row class="my-4">
        <b-col offset-sm="3" sm="9">
          <b-button variant="success" @click="save" :disabled="loading">
            <fa icon="spinner" v-show="loading" spin /> Simpan
          </b-button>
          <b-button variant="light" @click="cancelRiwayatPangkat()" :disabled="loading">
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
    return { title: "Edit Riwayat Pangkat" }
  },
  async asyncData({ params }) {
    let f2 = axios.get('riwayat-pangkat/' + params.id)
    let f2resp = (await f2).data

    let f3 = axios.get('/pegawai', {
      params: {
        perpage: 'all',
        sortby: 'peg_nama_lengkap'
      }
    })
    let f3resp = (await f3).data

    let form = f2resp.model
    let PEGOptions = f3resp.data
    return {
      form,
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
        let resp = (await axios.patch('riwayat-pangkat/' + this.$route.params.id, this.form)).data
        if (resp.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil disimpan',
            confirmButtonText: 'ok',
          })
          this.form = {}
          this.$router.push({ name: 'riwayat-pangkat' })
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
    cancelRiwayatPangkat(){
      this.$router.push({ name: 'riwayat-pangkat' })
    },
  }
}
</script>
