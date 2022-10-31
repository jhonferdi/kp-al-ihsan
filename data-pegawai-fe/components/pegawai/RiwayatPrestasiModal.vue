<template>
    <b-modal id="modal-riwayat-prestasi" ref="modal-riwayat-prestasi" title="Tambah Riwayat Prestasi">
      <template #modal-header="{}">
        <h5>Informasi Riwayat Prestasi</h5>
      </template>
      <template #default="{}">
        <b-row class="my-1">
          <b-col sm="12">
            <label>Prestasi</label>
          </b-col>
          <b-col sm="12">
            <b-form-input type="text" class="form-control" v-model="form_riwayat_prestasi.nama_prestasi" :state="getErrorState('nama_prestasi')"></b-form-input>
            <p style="color:red;" v-if="getErrorState('nama_prestasi') === false">
            {{ getErrorMessage('nama_prestasi') }}
          </p>
          </b-col>
          <b-col sm="12">
            <label>Pejabat Penandatangan</label>
          </b-col>
          <b-col sm="12">
            <b-form-input type="text" class="form-control" v-model="form_riwayat_prestasi.pejabat_penandatangan"></b-form-input>
          </b-col>
          <b-col sm="12">
            <label>Tanggal Kejadian</label>
          </b-col>
          <b-col sm="12">
            <b-form-input type="date" class="form-control" v-model="form_riwayat_prestasi.tanggal"></b-form-input>
          </b-col>
        </b-row>
        <b-row class="my-1" v-for="masterDokumenDigital in masterDokumenDigitals"
        :key="'masterDokumenDigitals-' + masterDokumenDigital.id">
        <b-col sm="12">
          <label>Upload {{ masterDokumenDigital.file_nama }}</label>
        </b-col>
        <b-col sm="12">
          <UploadFile
            :pegawai="pegawai"
            :dokumenDigital="dokumenDigital"
            relationTo="riwayat_prestasi"
            :entityId="form_riwayat_prestasi.id ?? ''"
            :masterDokumenDigital="masterDokumenDigital"
            @onUploading="loading = 2"
            @onUploaded="loading = false"
            :canEdit="true"
            v-model="form_riwayat_prestasi.files[masterDokumenDigital.id]">
          </UploadFile>
        </b-col>
      </b-row>
      </template>
      <template #modal-footer="{}">
        <!-- Emulate built in modal footer ok and cancel button actions -->
        <b-button size="md" variant="info" @click="saveRiwayatPrestasi()" :disabled="loading">
          <i class="fa fa-save"></i> {{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
        </b-button>
        <b-button size="md" variant="light" @click="cancelRiwayatPrestasi()">
          Batal
        </b-button>
      </template>
    </b-modal>
  </template>
  
  <script>
  import Swal from 'sweetalert2'
  import axios from 'axios'
  import UploadFile from './UploadFile.vue';

export default {
  props: ['pegawai','masterDokumenDigitals', 'dokumenDigital'],
  components: { UploadFile },
    data() {
      return {
        form_riwayat_prestasi: null,
        loading: false,
        errors:{}
      }
    },
    computed: {
    uploadLoading() {
      for (let i = 0; i < this.masterDokumenDigitals.length; i++) {
        if (this.masterDokumenDigitals[i].loading) {
          return true
        }
      }
      return false
    }
  },
    methods: {
      openRiwayatPrestasiModal(data){
        if (data) {
          this.form_riwayat_prestasi = {
            id: data.id,
            peg_id: data.peg_id,
            nama_prestasi: data.nama_prestasi,
            pejabat_penandatangan: data.pejabat_penandatangan,
            tanggal: data.tanggal,
            files:{},
          };
        } else {
          this.form_riwayat_prestasi = {
            peg_id: this.pegawai.peg_id,
            files:{},
          };
        }
        this.errors={}
        this.$refs['modal-riwayat-prestasi'].show()
      },
      async saveRiwayatPrestasi(){
        this.loading = true
        try {
          if (this.form_riwayat_prestasi.id) {
          let resp = (await axios.patch('pegawai/riwayat-prestasi/' + this.form_riwayat_prestasi.id, this.form_riwayat_prestasi)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        } else {
          let resp = (await axios.post('pegawai/riwayat-prestasi', this.form_riwayat_prestasi)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        }
        this.$emit('onSave')
        this.$refs['modal-riwayat-prestasi'].hide()
        } catch (err) {
          if(err.response && err.response.status == 422){
          this.errors = err.response.data.errors;
        }
      }
      this.loading = false
      },
      cancelRiwayatPrestasi(){
        this.$refs['modal-riwayat-prestasi'].hide()
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
  
  <style>
  
  </style>