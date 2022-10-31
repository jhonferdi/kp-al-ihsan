<template>
  <b-modal id="modal-uraian-tugas" ref="modal-uraian-tugas" title="Tambah Uraian Tugas">
    <template #modal-header="{}">
      <h5>Informasi Uraian Tugas</h5>
    </template>
    <template #default="{}">
      <b-row class="my-1">
        <b-col sm="12">
          <label>Tugas</label>
        </b-col>
        <b-col sm="12">
          <b-form-input type="text" class="form-control" v-model="form_uraian_tugas.tugas"
            :state="getErrorState('tugas')"></b-form-input>
          <p style="color:red;" v-if="getErrorState('tugas') === false">
            {{ getErrorMessage('tugas') }}
          </p>
        </b-col>
      </b-row>
      <b-row class="my-1" v-for="masterDokumenDigital in masterDokumenDigitals"
        :key="'masterDokumenDigitals-' + masterDokumenDigital.id">
        <b-col sm="12">
          <label>Upload {{ masterDokumenDigital.file_nama }}</label>
        </b-col>
        <b-col sm="12">
          <UploadFile :pegawai="pegawai" :dokumenDigital="dokumenDigital" relationTo="uraian_tugas"
            :entityId="form_uraian_tugas.id ?? ''" :masterDokumenDigital="masterDokumenDigital"
            @onUploading="loading = 2" @onUploaded="loading = false" :canEdit="true"
            v-model="form_uraian_tugas.files[masterDokumenDigital.id]">
          </UploadFile>
        </b-col>
      </b-row>
    </template>
    <template #modal-footer="{}">
      <!-- Emulate built in modal footer ok and cancel button actions -->
      <b-button size="md" variant="info" @click="saveUraianTugas()" :disabled="loading || uploadLoading">
        <i class="fa fa-save"></i> {{ loading === 2 ? 'Sedang mengunggah' : loading ? 'Sedang Menyimpan' : 'Simpan' }}
      </b-button>
      <b-button size="md" variant="light" @click="cancelUraianTugas()">
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
  props: ['pegawai', 'masterDokumenDigitals', 'dokumenDigital'],
  components: { UploadFile },
  data() {
    return {
      form_uraian_tugas: null,
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
  methods: {
    openUraianTugasModal(data) {
      if (data) {
        this.form_uraian_tugas = {
          id: data.id,
          peg_id: data.peg_id,
          tugas: data.tugas,
          files: {},
        };
      } else {
        this.form_uraian_tugas = {
          peg_id: this.pegawai.peg_id,
          files: {},
        };
      }
      this.errors = {}
      this.$refs['modal-uraian-tugas'].show()
    },
    async saveUraianTugas() {
      this.loading = true
      try {
        if (this.form_uraian_tugas.id) {
          let resp = (await axios.patch('pegawai/uraian-tugas/' + this.form_uraian_tugas.id, this.form_uraian_tugas)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        } else {
          let resp = (await axios.post('pegawai/uraian-tugas', this.form_uraian_tugas)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        }
        this.loading = false
        this.$emit('onSave')
        this.$refs['modal-uraian-tugas'].hide()
      } catch (err) {
        if (err.response && err.response.status == 422) {
          this.errors = err.response.data.errors;
        }
        this.loading = false
      }
    },
    cancelUraianTugas() {
      this.$refs['modal-uraian-tugas'].hide()
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