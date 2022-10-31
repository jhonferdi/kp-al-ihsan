<template>
    <b-modal id="modal-nira" ref="modal-nira" title="Tambah Nira Baru">
      <template #modal-header="{}">
        <h5>Informasi Nira Baru</h5>
      </template>
      <template #default="{}">
        <b-row class="my-1">
          <b-col sm="12">
            <label>Nomor Nira</label>
          </b-col>
          <b-col sm="12">
            <input type="text" name="nomor_nira" class="form-control" v-model="form_nira.nomor_nira" :state="getErrorState('nomor_nira')">
            <p style="color:red;" v-if="getErrorState('nomor_nira') === false">
            {{ getErrorMessage('nomor_nira') }}
            </p>
          </b-col>
          <b-col>
            <label>Tanggal Terdaftar</label>
          </b-col>
          <b-col sm="12">
            <input type="date" name="tanggal_terdaftar" class="form-control" v-model="form_nira.tanggal_terdaftar" :state="getErrorState('tanggal_terdaftar')">
            <p style="color:red;" v-if="getErrorState('tanggal_terdaftar') === false">
            {{ getErrorMessage('tanggal_terdaftar') }}
            </p>
          </b-col>
          <b-col>
            <label>Tanggal Terbit</label>
          </b-col>
          <b-col sm="12">
            <input type="date" nama="tanggal_terbit" class="form-control" v-model="form_nira.tanggal_terbit" :state="getErrorState('tanggal_terbit')">
            <p style="color:red;" v-if="getErrorState('tanggal_terbit') === false">
            {{ getErrorMessage('tanggal_terbit') }}
            </p>
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
            relationTo="nira"
            :entityId="form_nira.id ?? ''"
            :masterDokumenDigital="masterDokumenDigital"
            @onUploading="loading = 2"
            @onUploaded="loading = false"
            :canEdit="true"
            v-model="form_nira.files[masterDokumenDigital.id]">
          </UploadFile>
        </b-col>
      </b-row>
      </template>
      <template #modal-footer="{}">
        <!-- Emulate built in modal footer ok and cancel button actions -->
        <b-button size="md" variant="info" @click="saveNira()" :disabled="loading">
          <i class="fa fa-save"></i> {{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
        </b-button>
        <b-button size="md" variant="light" @click="cancelNira()">
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
        form_nira: null,
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
      openNiraModal(data){
        if (data) {
          this.form_nira = {
            id: data.id,
            peg_id: data.peg_id,
            nomor_nira: data.nomor_nira,
            tanggal_terdaftar: data.tanggal_terdaftar,
            tanggal_terbit: data.tanggal_terbit,
            files:{}
          };
        } else {
          this.form_nira = {
            peg_id: this.pegawai.peg_id,
            files:{}
          };
        }
        this.errors={}
        this.$refs['modal-nira'].show()
      },
      async saveNira(){
        this.loading = true
        try {
          if (this.form_nira.id) {
          let resp = (await axios.patch('pegawai/nira/' + this.form_nira.id, this.form_nira)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        } else {
          let resp = (await axios.post('pegawai/nira/', this.form_nira)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        }
        this.$emit('onSave')
        this.$refs['modal-nira'].hide()
        } catch (err) {
          if(err.response && err.response.status == 422){
            this.errors = err.response.data.errors;
          }
        }
        this.loading = false
      },
      cancelNira(){
        this.$refs['modal-nira'].hide()
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