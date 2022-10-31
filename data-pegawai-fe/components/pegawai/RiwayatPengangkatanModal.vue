<template>
    <b-modal id="modal-riwayat-pengangkatan" ref="modal-riwayat-pengangkatan" title="Tambah Riwayat Pengangkatan">
      <template #modal-header="{}">
        <h5>Informasi Riwayat Pengangkatan</h5>
      </template>
      <template #default="{}">
        <b-row class="my-1">
          <b-col sm="12">
            <label>Nomor SK</label>
          </b-col>
          <b-col sm="12">
            <b-form-input type="text" class="form-control" v-model="form_riwayat_pengangkatan.nomor_sk" :state="getErrorState('nomor_sk')"></b-form-input>
            <p style="color:red;" v-if="getErrorState('nomor_sk') === false">
            {{ getErrorMessage('nomor_sk') }}
            </p>
          </b-col>
          <b-col>
            <label>Tanggal SK</label>
          </b-col>
          <b-col sm="12">
            <b-form-input type="date" class="form-control" v-model="form_riwayat_pengangkatan.tanggal_sk" :state="getErrorState('tanggal_sk')"></b-form-input>
            <p style="color:red;" v-if="getErrorState('tanggal_sk') === false">
            {{ getErrorMessage('tanggal_sk') }}
            </p>
          </b-col>
          <b-col sm="12">
            <label>Pendidikan</label>
          </b-col>
          <b-col sm="12">
            <b-form-select type="text" class="form-control" v-model="form_riwayat_pengangkatan.pendidikan" :options="POptions" :state="getErrorState('pendidikan')"></b-form-select>
            <p style="color:red;" v-if="getErrorState('pendidikan') === false">
            {{ getErrorMessage('pendidikan') }}
            </p>
          </b-col>
          <b-col sm="12">
            <label>Nama Jabatan</label>
          </b-col>
          <b-col sm="12">
            <b-form-input type="text" class="form-control" v-model="form_riwayat_pengangkatan.nama_jabatan"></b-form-input>
          </b-col>
          <b-col sm="12">
            <label>Instalasi</label>
          </b-col>
          <b-col sm="12">
            <b-form-input type="text" class="form-control" v-model="form_riwayat_pengangkatan.instansi">
            </b-form-input>
          </b-col>
          <b-col sm="12">
            <label>Pejabat Penandatangan</label>
          </b-col>
          <b-col sm="12">
            <input type="text" class="form-control" v-model="form_riwayat_pengangkatan.pejabat_penandatangan">
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
            relationTo="riwayat_pengangkatan"
            :entityId="form_riwayat_pengangkatan.id ?? ''"
            :masterDokumenDigital="masterDokumenDigital"
            @onUploading="loading = 2"
            @onUploaded="loading = false"
            :canEdit="true"
            v-model="form_riwayat_pengangkatan.files[masterDokumenDigital.id]">
          </UploadFile>
        </b-col>
      </b-row>

      </template>
      <template #modal-footer="{}">
        <!-- Emulate built in modal footer ok and cancel button actions -->
        <b-button size="md" variant="info" @click="saveRiwayatPengangkatan()" :disabled="loading">
          <i class="fa fa-save"></i> {{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
        </b-button>
        <b-button size="md" variant="light" @click="cancelRiwayatPengangkatan()">
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
        form_riwayat_pengangkatan: null,
        loading: false,
        errors:{},
        POptions: [
        { value: 'SD', text: 'SD' },
        { value: 'SMP', text: 'SMP' },
        { value: 'SMK/SMA', text: 'SMK/SMA' },
        { value: 'D1', text: 'D1' },
        { value: 'D2', text: 'D2' },
        { value: 'D3', text: 'D3' },
        { value: 'S1/D4', text: 'S1/D4' },
        { value: 'S2', text: 'S2' },
        { value: 'S3', text: 'S3' },
      ],
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
      openRiwayatPengangkatanModal(data){
        if (data) {
          this.form_riwayat_pengangkatan = {
            id: data.id,
            peg_id: data.peg_id,
            nomor_sk: data.nomor_sk,
            tanggal_sk: data.tanggal_sk,
            pendidikan: data.pendidikan,
            nama_jabatan: data.nama_jabatan,
            instansi: data.instansi,
            pejabat_penandatangan: data.pejabat_penandatangan,
            files:{},
          };
        } else {
          this.form_riwayat_pengangkatan = {
            peg_id: this.pegawai.peg_id,
            files:{},
          };
        }
        this.errors={}
        this.$refs['modal-riwayat-pengangkatan'].show()
      },
      async saveRiwayatPengangkatan(){
        this.loading = true
        try {
          if (this.form_riwayat_pengangkatan.id) {
          let resp = (await axios.patch('pegawai/riwayat-pengangkatan/' + this.form_riwayat_pengangkatan.id, this.form_riwayat_pengangkatan)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        } else {
          let resp = (await axios.post('pegawai/riwayat-pengangkatan', this.form_riwayat_pengangkatan)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        }
        this.$emit('onSave')
        this.$refs['modal-riwayat-pengangkatan'].hide()
      } catch (err) {
          if(err.response && err.response.status == 422){
          this.errors = err.response.data.errors;
        }
      }
      this.loading = false
      },
      cancelRiwayatPengangkatan(){
        this.$refs['modal-riwayat-pengangkatan'].hide()
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