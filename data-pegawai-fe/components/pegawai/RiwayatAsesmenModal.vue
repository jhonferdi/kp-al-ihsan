<template>
  <b-modal id="modal-riwayat-asesmen" ref="modal-riwayat-asesmen" title="Tambah Riwayat Asesmen Kompetensi">
    <template #modal-header="{}">
      <h5>Informasi Riwayat Asesmen Kompetensi</h5>
    </template>
    <template #default="{}">
      <b-row class="my-1">
        <b-col sm="12">
          <label>Jenis Asesmen Kompetensi</label>
        </b-col>
        <b-col sm="12">
          <b-form-input id="jenis_pelatihan" type="text" v-model="master_asesmen_kompetensi.jenis_asesmen_kompetensi"
            readonly />
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Bulan</label>
        </b-col>
        <b-col sm="12">
          <b-form-select v-model="form_riwayat_asesmen.bulan" :options="BOptions" :state="getErrorState('bulan')">
          </b-form-select>
          <p style="color:red;" v-if="getErrorState('bulan') === false">
            {{ getErrorMessage('bulan') }}
          </p>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Tahun</label>
        </b-col>
        <b-col sm="12">
          <b-form-input id="tahun" type="number" v-model="form_riwayat_asesmen.tahun" :state="getErrorState('tahun')" />
          <p style="color:red;" v-if="getErrorState('tahun') === false">
            {{ getErrorMessage('tahun') }}
          </p>
        </b-col>
      </b-row>
      <b-row class="my-1" v-for="masterDokumenDigital in masterDokumenDigitals"
        :key="'masterDokumenDigitals-' + masterDokumenDigital.id">
        <b-col sm="12">
          <label>Upload {{ masterDokumenDigital.file_nama }}</label>
        </b-col>
        <b-col sm="12">
          <UploadFile :pegawai="pegawai" :dokumenDigital="dokumenDigital" relationTo="riwayat_asesmen"
            :entityId="form_riwayat_asesmen.id ?? ''" :masterDokumenDigital="masterDokumenDigital"
            @onUploading="loading = 2" @onUploaded="loading = false" :canEdit="true"
            v-model="form_riwayat_asesmen.files[masterDokumenDigital.id]">
          </UploadFile>
        </b-col>
      </b-row>
    </template>
    <template #modal-footer="{}">
      <!-- Emulate built in modal footer ok and cancel button actions -->
      <b-button size="md" variant="info" @click="saveRiwayatAsesmen()" :disabled="loading">
        <i class="fa fa-save"></i> {{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
      </b-button>
      <b-button size="md" variant="light" @click="cancelRiwayatAsesmen()">
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
      form_riwayat_asesmen: null,
      loading: false,
      errors: {},
      master_asesmen_kompetensi: {},
      BOptions: [
        { value: 'Januari', text: 'Januari' },
        { value: 'Februari', text: 'Februari' },
        { value: 'Maret', text: 'Maret' },
        { value: 'April', text: 'April' },
        { value: 'Mei', text: 'Mei' },
        { value: 'Juni', text: 'Juni' },
        { value: 'Juli', text: 'Juli' },
        { value: 'Agustus', text: 'Agustus' },
        { value: 'September', text: 'September' },
        { value: 'Oktober', text: 'Oktober' },
        { value: 'November', text: 'November' },
        { value: 'Desember', text: 'Desember' },
      ],
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
    openRiwayatAsesmenModal(master_asesmen_kompetensi, data) {
      this.master_asesmen_kompetensi = master_asesmen_kompetensi
      if (data) {
        this.form_riwayat_asesmen = {
          id: data.id,
          peg_id: data.peg_id,
          bulan: data.bulan,
          tahun: data.tahun,
          files: {},
          master_asesmen_kompetensi_id: data.master_asesmen_kompetensi_id,
        };
      } else {
        this.form_riwayat_asesmen = {
          peg_id: this.pegawai.peg_id,
          files: {},
          master_asesmen_kompetensi_id: this.master_asesmen_kompetensi.id
        };
      }
      this.errors = {}
      this.$refs['modal-riwayat-asesmen'].show()
    },
    async saveRiwayatAsesmen() {
      this.loading = true
      try {
        if (this.form_riwayat_asesmen.id) {
          let resp = (await axios.patch('pegawai/riwayat-asesmen/' + this.form_riwayat_asesmen.id, this.form_riwayat_asesmen)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        } else {
          let resp = (await axios.post('pegawai/riwayat-asesmen', this.form_riwayat_asesmen)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        }
        this.$emit('onSave')
        this.$refs['modal-riwayat-asesmen'].hide()
      } catch (err) {
        if (err.response && err.response.status == 422) {
          this.errors = err.response.data.errors;
        }
      }
      this.loading = false
    },
    cancelRiwayatAsesmen() {
      this.$refs['modal-riwayat-asesmen'].hide()
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