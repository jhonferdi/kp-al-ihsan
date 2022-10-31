<template>
    <b-modal id="modal-riwayat-penempatan" ref="modal-riwayat-penempatan" title="Tambah Riwayat Penempatan">
      <template #modal-header="{}">
        <h5>Informasi Riwayat Penempatan</h5>
      </template>
      <template #default="{}">
        <b-row class="my-1">
          <b-col sm="12">
            <label>Jabatan</label>
          </b-col>
          <b-col sm="12">
            <b-form-input type="text" class="form-control" v-model="form_riwayat_penempatan.jabatan" 
            :state="getErrorState('jabatan')"></b-form-input>
            <p style="color:red;" v-if="getErrorState('jabatan') === false">
            {{ getErrorMessage('jabatan') }}
          </p>
          </b-col>
          <b-col>
            <label>Unit Kerja</label>
          </b-col>
          <b-col sm="12">
            <b-form-input type="text" class="form-control" v-model="form_riwayat_penempatan.unit_kerja" 
            :state="getErrorState('unit_kerja')"></b-form-input>
            <p style="color:red;" v-if="getErrorState('unit_kerja') === false">
            {{ getErrorMessage('unit_kerja') }}
          </p>
          </b-col>
          <b-col sm="12">
            <label>Instalasi</label>
          </b-col>
          <b-col sm="12">
            <b-form-input type="text" class="form-control" v-model="form_riwayat_penempatan.instansi" 
            :state="getErrorState('instansi')"></b-form-input>
            <p style="color:red;" v-if="getErrorState('instansi') === false">
            {{ getErrorMessage('instansi') }}
          </p>
          </b-col>
          <b-col sm="12">
            <label>Tanggal Penempatan</label>
          </b-col>
          <b-col sm="12">
            <b-form-input type="date" class="form-control" v-model="form_riwayat_penempatan.tanggal_penempatan" :state="getErrorState('tanggal_penempatan')"></b-form-input>
            <p style="color:red;" v-if="getErrorState('tanggal_penempatan') === false">
            {{ getErrorMessage('tanggal_penempatan') }}
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
            relationTo="riwayat_penempatan"
            :entityId="form_riwayat_penempatan.id ?? ''"
            :masterDokumenDigital="masterDokumenDigital"
            @onUploading="loading = 2"
            @onUploaded="loading = false"
            :canEdit="true"
            v-model="form_riwayat_penempatan.files[masterDokumenDigital.id]">
          </UploadFile>
        </b-col>
      </b-row>

      </template>
      <template #modal-footer="{}">
        <!-- Emulate built in modal footer ok and cancel button actions -->
        <b-button size="md" variant="info" @click="saveRiwayatPenempatan()" :disabled="loading">
          <i class="fa fa-save"></i> {{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
        </b-button>
        <b-button size="md" variant="light" @click="cancelRiwayatPenempatan()">
          Batal
        </b-button>
      </template>
    </b-modal>
  </template>
  
  <script>
  import Swal from 'sweetalert2'
  import axios from 'axios'
  import { mapGetters } from 'vuex'
  import UploadFile from './UploadFile.vue';

export default {
  props: ['pegawai','masterDokumenDigitals', 'dokumenDigital'],
  components: { UploadFile },
    data() {
      return {
        form_riwayat_penempatan: null,
        loading: false,
        errors:{}
      }
    },
    computed: {
    ...mapGetters({
      UKEROptions: 'useroptions/UKEROptions',
      JBOptions: 'useroptions/JBOptions',
    }),
    uploadLoading() {
      for (let i = 0; i < this.masterDokumenDigitals.length; i++) {
        if (this.masterDokumenDigitals[i].loading) {
          return true
        }
      }
      return false
    },
  },
    methods: {
      openRiwayatPenempatanModal(data){
        if (data) {
          this.form_riwayat_penempatan = {
            id: data.id,
            peg_id: data.peg_id,
            jabatan: data.jabatan,
            unit_kerja: data.unit_kerja,
            instansi: data.instansi,            
            tanggal_penempatan: data.tanggal_penempatan,            
            files:{},
          };
        } else {
          this.form_riwayat_penempatan = {
            peg_id: this.pegawai.peg_id,
            files:{},
          };
        }
        this.errors={}
        this.$refs['modal-riwayat-penempatan'].show()
      },
      async saveRiwayatPenempatan(){
        this.loading = true
        try {
          if (this.form_riwayat_penempatan.id) {
          let resp = (await axios.patch('pegawai/riwayat-penempatan/' + this.form_riwayat_penempatan.id, this.form_riwayat_penempatan)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        } else {
          let resp = (await axios.post('pegawai/riwayat-penempatan', this.form_riwayat_penempatan)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        }
        this.$emit('onSave')
        this.$refs['modal-riwayat-penempatan'].hide()
        } catch (err) {
          if(err.response && err.response.status == 422){
          this.errors = err.response.data.errors;
        }
      }
      this.loading = false
      },
      cancelRiwayatPenempatan(){
        this.$refs['modal-riwayat-penempatan'].hide()
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