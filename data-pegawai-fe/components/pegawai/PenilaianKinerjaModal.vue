<template>
    <b-modal id="modal-penilaian-kinerja" ref="modal-penilaian-kinerja" title="Tambah Penilaian Kinerja">
      <template #modal-header="{}">
        <h5>Informasi Kinerja</h5>
      </template>
      <template #default="{}">
        <b-row class="my-1">
          <b-col sm="12">
            <label>Tahun</label>
          </b-col>
          <b-col sm="12">
            <b-form-input type="number" class="form-control" v-model="form_penilaian_kinerja.tahun" :state="getErrorState('tahun')"></b-form-input>
            <p style="color:red;" v-if="getErrorState('tahun') === false">
              {{ getErrorMessage('tahun') }}
            </p>
          </b-col>
          <b-col>
            <label>Nilai</label>
          </b-col>
          <b-col sm="12">
            <b-form-input type="text" class="form-control" v-model="form_penilaian_kinerja.nilai" :state="getErrorState('nilai')"></b-form-input>
            <p style="color:red;" v-if="getErrorState('nilai') === false">
                    {{ getErrorMessage('nilai') }}
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
            relationTo="penilaian_kinerja"
            :entityId="form_penilaian_kinerja.id ?? ''"
            :masterDokumenDigital="masterDokumenDigital"
            @onUploading="loading = 2"
            @onUploaded="loading = false"
            :canEdit="true"
            v-model="form_penilaian_kinerja.files[masterDokumenDigital.id]">
          </UploadFile>
        </b-col>
      </b-row>
      </template>
      <template #modal-footer="{}">
        <!-- Emulate built in modal footer ok and cancel button actions -->
        <b-button size="md" variant="info" @click="savePenilaianKinerja()" :disabled="loading">
          <i class="fa fa-save"></i> {{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
        </b-button>
        <b-button size="md" variant="light" @click="cancelPenilaianKinerja()">
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
        form_penilaian_kinerja: null,
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
      openPenilaianKinerjaModal(data){
        if (data) {
          this.form_penilaian_kinerja = {
            id: data.id,
            peg_id: data.peg_id,
            tahun: data.tahun,
            nilai: data.nilai,
            files:{}
          };
        } else {
          this.form_penilaian_kinerja = {
            peg_id: this.pegawai.peg_id,
            files:{}
          };
        }
        this.errors={}
        this.$refs['modal-penilaian-kinerja'].show()
      },
      async savePenilaianKinerja(){
        this.loading = true
        try {
          if (this.form_penilaian_kinerja.id) {
          let resp = (await axios.patch('pegawai/penilaian-kinerja/' + this.form_penilaian_kinerja.id, this.form_penilaian_kinerja)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        } else {
          let resp = (await axios.post('pegawai/penilaian-kinerja', this.form_penilaian_kinerja)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        }
        this.$emit('onSave')
        this.$refs['modal-penilaian-kinerja'].hide()
        } catch (err) {
          if(err.response && err.response.status == 422){
            this.errors = err.response.data.errors;
          }
        }
        this.loading = false
      },
      cancelPenilaianKinerja(){
        this.$refs['modal-penilaian-kinerja'].hide()
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