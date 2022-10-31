<template>
    <b-modal id="modal-riwayat-tugas-tambahan" ref="modal-riwayat-tugas-tambahan" title="Tambah Riwayat Tugas Tambahan">
      <template #modal-header="{}">
        <h5>Informasi Riwayat Tugas Tambahan</h5>
      </template>
      <template #default="{}">
        <b-row class="my-1">
          <b-col sm="12">
            <label>Tugas Tambahan</label>
          </b-col>
          <b-col sm="12">
            <b-form-input type="text" class="form-control" v-model="form_riwayat_tugas_tambahan.tugas_tambahan" :state="getErrorState('tugas_tambahan')"></b-form-input>
            <p style="color:red;" v-if="getErrorState('tugas_tambahan') === false">
            {{ getErrorMessage('tugas_tambahan') }}
          </p>
          </b-col>
          <b-col sm="12">
            <label>Tanggal Mulai</label>
          </b-col>
          <b-col sm="12">
            <b-form-input type="date" class="form-control" v-model="form_riwayat_tugas_tambahan.tanggal_mulai"></b-form-input>
          </b-col>
          <b-col sm="12">
            <label>Tanggal Selesai</label>
          </b-col>
          <b-col sm="12">
            <b-form-input type="date" class="form-control" v-model="form_riwayat_tugas_tambahan.tanggal_selesai"></b-form-input>
          </b-col>
          <b-col sm="12">
            <label>Unit Kerja</label>
          </b-col>
          <b-col sm="12">
            <b-form-input type="text" class="form-control" v-model="form_riwayat_tugas_tambahan.unit_kerja"
            :state="getErrorState('unit_kerja')"></b-form-input>
            <p style="color:red;" v-if="getErrorState('unit_kerja') === false">
            {{ getErrorMessage('unit_kerja') }}
          </p>
          </b-col>
          <b-col sm="12">
            <label>Jabatan</label>
          </b-col>
          <b-col sm="12">
            <b-form-input type="text" class="form-control" v-model="form_riwayat_tugas_tambahan.jabatan"
            :state="getErrorState('jabatan')"></b-form-input>
            <p style="color:red;" v-if="getErrorState('jabatan') === false">
            {{ getErrorMessage('jabatan') }}
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
            relationTo="riwayat_tugas_tambahan"
            :entityId="form_riwayat_tugas_tambahan.id ?? ''"
            :masterDokumenDigital="masterDokumenDigital"
            @onUploading="loading = 2"
            @onUploaded="loading = false"
            :canEdit="true"
            v-model="form_riwayat_tugas_tambahan.files[masterDokumenDigital.id]">
          </UploadFile>
        </b-col>
      </b-row>

      </template>
      <template #modal-footer="{}">
        <!-- Emulate built in modal footer ok and cancel button actions -->
        <b-button size="md" variant="info" @click="saveRiwayatTugasTambahan()" :disabled="loading">
          <i class="fa fa-save"></i> {{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
        </b-button>
        <b-button size="md" variant="light" @click="cancelRiwayatTugasTambahan()">
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
        form_riwayat_tugas_tambahan: null,
        loading: false,
        errors:{}
      }
    },
    computed: {
    ...mapGetters({
      JBOptions: 'useroptions/JBOptions',
      UKEROptions: 'useroptions/UKEROptions',
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
      openRiwayatTugasTambahanModal(data){
        if (data) {
          this.form_riwayat_tugas_tambahan = {
            id: data.id,
            peg_id: data.peg_id,
            jabatan: data.jabatan,
            unit_kerja: data.unit_kerja,
            tugas_tambahan: data.tugas_tambahan,
            tanggal_mulai: data.tanggal_mulai,
            tanggal_selesai: data.tanggal_selesai,
            files:{},
          };
        } else {
          this.form_riwayat_tugas_tambahan = {
            peg_id: this.pegawai.peg_id,
            files:{},
          };
        }
        this.errors={}
        this.$refs['modal-riwayat-tugas-tambahan'].show()
      },
      async saveRiwayatTugasTambahan(){
        this.loading = true
        try {
          if (this.form_riwayat_tugas_tambahan.id) {
          let resp = (await axios.patch('pegawai/riwayat-tugas-tambahan/' + this.form_riwayat_tugas_tambahan.id, this.form_riwayat_tugas_tambahan)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        } else {
          let resp = (await axios.post('pegawai/riwayat-tugas-tambahan', this.form_riwayat_tugas_tambahan)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        }
        this.$emit('onSave')
        this.$refs['modal-riwayat-tugas-tambahan'].hide()
        } catch (err) {
          if(err.response && err.response.status == 422){
          this.errors = err.response.data.errors;
        }
      }
      this.loading = false
      },
      cancelRiwayatTugasTambahan(){
        this.$refs['modal-riwayat-tugas-tambahan'].hide()
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