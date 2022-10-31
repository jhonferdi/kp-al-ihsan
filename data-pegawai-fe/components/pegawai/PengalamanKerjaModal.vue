<template>
  <b-modal id="modal-pengalaman-kerja" ref="modal-pengalaman-kerja" title="Tambah Pengalaman Kerja">
    <template #modal-header="{}">
      <h5>Informasi Pengalaman Kerja</h5>
    </template>
    <template #default="{}">
      <b-row class="my-1">
        <b-col sm="12">
          <label>Tempat Kerja</label>
        </b-col>
        <b-col sm="12">
          <b-form-input id="tempat_kerja" type="text" v-model="pengalaman_kerja.tempat_kerja" :state="getErrorState('tempat_kerja')"/>
          <p style="color:red;" v-if="getErrorState('tempat_kerja') === false">
            {{ getErrorMessage('tempat_kerja') }}
          </p>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Jabatan</label>
        </b-col>
        <b-col sm="12">
          <b-form-input id="jabatan" type="text" v-model="pengalaman_kerja.jabatan" :state="getErrorState('jabatan')"/>
          <p style="color:red;" v-if="getErrorState('jabatan') === false">
            {{ getErrorMessage('jabatan') }}
          </p>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Paklaring</label>
        </b-col>
        <b-col sm="12">
          <b-form-radio value="Ada" v-model="pengalaman_kerja.has_paklaring" class="form-check">Ada</b-form-radio>
          <b-form-radio value="Tidak Ada" v-model="pengalaman_kerja.has_paklaring" class="form-check">Tidak Ada</b-form-radio>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Tanggal Mulai</label>
        </b-col>
        <b-col sm="12">
          <b-form-input id="tanggal_mulai" type="date" v-model="pengalaman_kerja.tanggal_mulai" />
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Tanggal Selesai</label>
        </b-col>
        <b-col sm="12">
          <b-form-input id="tanggal_selesai" type="date" v-model="pengalaman_kerja.tanggal_selesai" />
        </b-col>
      </b-row>
      <b-row class="my-1" v-for="masterDokumenDigital in masterDokumenDigitals"
        :key="'masterDokumenDigitals-' + masterDokumenDigital.id" v-if="pengalaman_kerja.has_paklaring == 'Ada'">
        <b-col sm="12">
          <label>Upload {{ masterDokumenDigital.file_nama }}</label>
        </b-col>
        <b-col sm="12">
          <UploadFile
            :pegawai="pegawai"
            :dokumenDigital="dokumenDigital"
            relationTo="pengalaman_kerja"
            :entityId="pengalaman_kerja.id ?? ''"
            :masterDokumenDigital="masterDokumenDigital"
            @onUploading="loading = 2"
            @onUploaded="loading = false"
            :canEdit="true"
            v-model="pengalaman_kerja.files[masterDokumenDigital.id]">
          </UploadFile>
        </b-col>
      </b-row>
    </template>
    <template #modal-footer="{}">
      <!-- Emulate built in modal footer ok and cancel button actions -->
      <b-button size="md" variant="info" @click="savePengalamanKerja()" :disabled="loading">
        <i class="fa fa-save"></i> {{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
      </b-button>
      <b-button size="md" variant="light" @click="cancelPengalamanKerja()">
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
      pengalaman_kerja: null,
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
    openPengalamanKerjaModal(data) {
      if (data) {
        this.pengalaman_kerja = {
          id: data.id,
          peg_id: data.peg_id,
          tempat_kerja: data.tempat_kerja,
          jabatan: data.jabatan,
          has_paklaring: data.has_paklaring,
          tanggal_mulai: data.tanggal_mulai,
          tanggal_selesai: data.tanggal_selesai,
          files:{}
        };
      } else {
        this.pengalaman_kerja = {
          peg_id: this.pegawai.peg_id,
          files:{}
        };
      }
      this.errors={}
      this.$refs['modal-pengalaman-kerja'].show()
    },
    async savePengalamanKerja() {
      this.loading = true
      try {
        if (this.pengalaman_kerja.id) {
        let resp = (await axios.patch('pegawai/pengalaman-kerja/' + this.pengalaman_kerja.id, this.pengalaman_kerja)).data
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: resp.message,
          confirmButtonText: 'ok',
        })
      } else {
        let resp = (await axios.post('pegawai/pengalaman-kerja', this.pengalaman_kerja)).data
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: resp.message,
          confirmButtonText: 'ok',
        })
      }
      this.$emit('onSave')
      this.$refs['modal-pengalaman-kerja'].hide()
      } catch (err) {
        if(err.response && err.response.status == 422){
            this.errors = err.response.data.errors;
          }
        }
        this.loading = false
    },
    cancelPengalamanKerja() {
      this.$refs['modal-pengalaman-kerja'].hide()
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