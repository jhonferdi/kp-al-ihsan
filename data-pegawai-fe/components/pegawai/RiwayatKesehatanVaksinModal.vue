<template>
  <b-modal id="modal-riwayat-kesehatan-vaksin" ref="modal-riwayat-kesehatan-vaksin" title="Tambah Status Vaksin">
    <template #modal-header="{}">
      <h5>Informasi Status Vaksin</h5>
    </template>
    <template #default="{}">
      <b-row class="my-1">
        <b-col sm="12">
          <label>Status</label>
        </b-col>
        <b-col sm="12">
          <b-form-input type="text" class="mb-3" v-model="master_mcu.nama_penyakit" readonly></b-form-input>
        </b-col>
        <b-col sm="12">
          <label>Tanggal Vaksin</label>
        </b-col>
        <b-col sm="12">
          <b-form-input type="date" class="mb-3" v-model="form_riwayat_kesehatan.tanggal_vaksin"></b-form-input>
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
            relationTo="riwayat_kesehatan_vaksin"
            :entityId="form_riwayat_kesehatan.id ?? ''"
            :masterDokumenDigital="masterDokumenDigital"
            @onUploading="loading = 2"
            @onUploaded="loading = false"
            :canEdit="true"
            v-model="form_riwayat_kesehatan.files[masterDokumenDigital.id]">
          </UploadFile>
        </b-col>
      </b-row>
    </template>
    <template #modal-footer="{}">
      <!-- Emulate built in modal footer ok and cancel button actions -->
      <b-button size="md" variant="info" @click="saveRiwayatKesehatanVaksin()" :disabled="loading">
        <i class="fa fa-save"></i> {{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
      </b-button>
      <b-button size="md" variant="light" @click="cancelRiwayatKesehatanVaksin()">
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
      form_riwayat_kesehatan: null,
      loading: false,
      errors:{},
      master_mcu:{}
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
    openRiwayatKesehatanVaksinModal(master_mcu, data) {
      this.master_mcu = master_mcu
      if (data) {
        this.form_riwayat_kesehatan = {
          id: data.id,
          peg_id: data.peg_id,
          status: data.status,
          tanggal_vaksin: data.tanggal_vaksin,
          files:{},
          master_mcu_id: data.master_mcu_id,
        };
      } else {
        this.form_riwayat_kesehatan = {
          peg_id: this.pegawai.peg_id,
          files:{},
          master_mcu_id: this.master_mcu.id
        };
      }
      this.errors={}
      this.$refs['modal-riwayat-kesehatan-vaksin'].show()
    },
    async saveRiwayatKesehatanVaksin() {
      this.loading = true
      try {
        if (this.form_riwayat_kesehatan.id) {
        let resp = (await axios.patch('pegawai/riwayat-kesehatan-vaksin/' + this.form_riwayat_kesehatan.id, this.form_riwayat_kesehatan)).data
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: resp.message,
          confirmButtonText: 'ok',
        })
      } else {
        let resp = (await axios.post('pegawai/riwayat-kesehatan-vaksin', this.form_riwayat_kesehatan)).data
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: resp.message,
          confirmButtonText: 'ok',
        })
      }
      this.$emit('onSave')
      this.$refs['modal-riwayat-kesehatan-vaksin'].hide()
      } catch (err) {
        if(err.response && err.response.status == 422){
          this.errors = err.response.data.errors;
        }
      }
      this.loading = false
    },
    cancelRiwayatKesehatanVaksin() {
      this.$refs['modal-riwayat-kesehatan-vaksin'].hide()
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