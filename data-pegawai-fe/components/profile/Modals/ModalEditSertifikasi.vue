<template>
  <modal :open="open" @close="$emit('close')" :title="title">
    <b-card class="mb-0">
      <h4>Sertifikasi</h4>
      <b-row>
        <b-col sm="12">
          <div class="mb-2">
            <label>Judul Sertifikat</label>
            <b-form-input placeholder="Masukan Judul Sertifikat"></b-form-input>
          </div>
        </b-col>
        <b-col sm="12">
          <div class="mb-2">
            <label>Kategori</label>
            <b-form-input placeholder="Masukan Kategori"></b-form-input>
          </div>
        </b-col>
        <b-col sm="12">
          <div class="mb-2">
            <label>Tanggal Aktif</label>
            <b-form-input type="date"></b-form-input>
          </div>
        </b-col>
        <b-row class="my-1" v-for="masterDokumenDigital in masterDokumenDigitals"
          :key="'masterDokumenDigitals-' + masterDokumenDigital.id">
          <b-col sm="12">
            <label>Upload {{ masterDokumenDigital.file_nama }}</label>
          </b-col>
          <b-col sm="12">
            <UploadFile :pegawai="pegawai" :dokumenDigital="dokumenDigital" relationTo="uraian_tugas"
              :entityId="form.sertifikat_id ?? ''" :masterDokumenDigital="masterDokumenDigital"
              @onUploading="loading = 2" @onUploaded="loading = false" :canEdit="true"
              v-model="form.files[masterDokumenDigital.id]">
            </UploadFile>
          </b-col>
        </b-row>
      </b-row>
    </b-card>
    <template #footer>
      <b-button variant="lightgreen">Simpan</b-button>
    </template>
  </modal>
</template>
<script>
import Swal from 'sweetalert2'
import axios from 'axios'
import UploadFile from '~/components/pegawai/UploadFile.vue';
export default {
  props: ['open', 'title', 'pegawai', 'masterDokumenDigitals', 'dokumenDigital'],
  components: { UploadFile },
  data() {
    return {
      form: {},
      loading: false,
      errors: {}
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
}
</script>