<template>
  <b-modal id="modal-manajemen-kontrak" ref="modal-manajemen-kontrak" title="Tambah Kontrak">
    <template #modal-header="{}">
      <h5>Informasi Kontrak</h5>
    </template>
    <template #default="{}">
      <b-row class="my-1">
        <b-col sm="12">
          <label>Jenis Kontrak</label>
        </b-col>
        <b-col sm="12">
          <b-form-select class="mb-3 font-size-13" v-model="form_manajemen_kontrak.jenis_kontrak" :options="JKOptions"
            :state="getErrorState('jenis_kontrak')">
          </b-form-select>
          <p style="color:red;" v-if="getErrorState('jenis_kontrak') === false">
            {{ getErrorMessage('jenis_kontrak') }}
          </p>
        </b-col>
        <b-col sm="12">
          <label>Tanggal Mulai Kontrak</label>
        </b-col>
        <b-col sm="12">
          <b-form-input type="date" class="mb-3" v-model="form_manajemen_kontrak.tanggal_mulai"
            :state="getErrorState('tanggal_mulai')"></b-form-input>
          <p style="color:red;" v-if="getErrorState('tanggal_mulai') === false">
            {{ getErrorMessage('tanggal_mulai') }}
          </p>
        </b-col>
        <b-col sm="12">
          <label>Tanggal Habis Kontrak</label>
        </b-col>
        <b-col sm="12">
          <b-form-input type="date" class="mb-3" v-model="form_manajemen_kontrak.tanggal_habis_kontrak"
            :state="getErrorState('tanggal_habis_kontrak')"></b-form-input>
          <p style="color:red;" v-if="getErrorState('tanggal_habis_kontrak') === false">
            {{ getErrorMessage('tanggal_habis_kontrak') }}
          </p>
        </b-col>
      </b-row>
      <b-row class="my-1" v-for="masterDokumenDigital in masterDokumenDigitals"
        :key="'masterDokumenDigitals-' + masterDokumenDigital.id">
        <b-col sm="12">
          <label>Upload {{ masterDokumenDigital.file_nama }}</label>
        </b-col>
        <b-col sm="12">
          <UploadFile :pegawai="pegawai" :dokumenDigital="dokumenDigital" relationTo="kontrak"
            :entityId="form_manajemen_kontrak.id ?? ''" :masterDokumenDigital="masterDokumenDigital"
            @onUploading="loading = 2" @onUploaded="loading = false" :canEdit="true"
            v-model="form_manajemen_kontrak.files[masterDokumenDigital.id]">
          </UploadFile>
        </b-col>
      </b-row>
    </template>
    <template #modal-footer="{}">
      <!-- Emulate built in modal footer ok and cancel button actions -->
      <b-button size="md" variant="info" @click="saveManajemenKontrak()" :disabled="loading">
        <i class="fa fa-save"></i> {{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
      </b-button>
      <b-button size="md" variant="light" @click="cancelManajemenKontrak()">
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
      form_manajemen_kontrak: null,
      loading: false,
      errors: {},
      JKOptions: [
        { value: null, text: 'Pilih Jenis Kontrak' },
        { value: 'Purna Waktu', text: 'Purna Waktu' },
        { value: 'Paruh Waktu', text: 'Paruh Waktu' },
      ]
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
    openManajemenKontrakModal(data) {
      if (data) {
        this.form_manajemen_kontrak = {
          id: data.id,
          peg_id: data.peg_id,
          jenis_kontrak: data.jenis_kontrak,
          tanggal_mulai: data.tanggal_mulai,
          tanggal_habis_kontrak: data.tanggal_habis_kontrak,
          files: {}
        };
      } else {
        this.form_manajemen_kontrak = {
          peg_id: this.pegawai.peg_id,
          files: {}
        };
      }
      this.errors = {}
      this.$refs['modal-manajemen-kontrak'].show()
    },
    async saveManajemenKontrak() {
      this.loading = true
      try {
        if (this.form_manajemen_kontrak.id) {
          let resp = (await axios.patch('pegawai/manajemen-kontrak/' + this.form_manajemen_kontrak.id, this.form_manajemen_kontrak)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        } else {
          let resp = (await axios.post('pegawai/manajemen-kontrak', this.form_manajemen_kontrak)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        }
        this.$emit('onSave')
        this.$refs['modal-manajemen-kontrak'].hide()
      } catch (err) {
        if (err.response && err.response.status == 422) {
          this.errors = err.response.data.errors;
        }
      }
      this.loading = false
    },
    cancelManajemenKontrak() {
      this.$refs['modal-manajemen-kontrak'].hide()
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