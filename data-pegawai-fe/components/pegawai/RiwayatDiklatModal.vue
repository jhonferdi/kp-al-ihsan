<template>
  <b-modal id="modal-riwayat-diklat" ref="modal-riwayat-diklat" title="Tambah Riwayat Diklat">
    <template #modal-header="{}">
      <h5>Informasi Riwayat Diklat</h5>
    </template>
    <template #default="{}">
      <b-row class="my-1">
        <b-col sm="12">
          <label>Jenis Pelatihan</label>
        </b-col>
        <b-col sm="12">
          <b-form-input id="jenis_pelatihan" type="text" v-model="master_diklat.jenis_pelatihan" readonly />
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Nama Pelatihan</label>
        </b-col>
        <b-col sm="12">
          <b-form-input id="nama_pelatihan" type="text" v-model="form_riwayat_diklat.nama_pelatihan" :state="getErrorState('nama_pelatihan')" />
          <p style="color:red;" v-if="getErrorState('nama_pelatihan') === false">
            {{ getErrorMessage('nama_pelatihan') }}
          </p>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Tanggal Pelaksanaan</label>
        </b-col>
        <b-col sm="12">
          <b-form-input id="peg_lahir_tanggal" type="date" v-model="form_riwayat_diklat.tanggal_pelaksanaan" :state="getErrorState('tanggal_pelaksanaan')" />
          <p style="color:red;" v-if="getErrorState('tanggal_pelaksanaan') === false">
            {{ getErrorMessage('tanggal_pelaksanaan') }}
          </p>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Lama Pelatihan</label>
        </b-col>
        <b-col sm="12">
          <b-form-input id="lama_pelatihan" type="text" v-model="form_riwayat_diklat.lama_pelatihan" :state="getErrorState('lama_pelatihan')" />
          <p style="color:red;" v-if="getErrorState('lama_pelatihan') === false">
            {{ getErrorMessage('lama_pelatihan') }}
          </p>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Pejabat Penandatangan</label>
        </b-col>
        <b-col sm="12">
          <b-form-input id="pejabat_penandatangan" type="text" v-model="form_riwayat_diklat.pejabat_penandatangan" />
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Nomor Sertifikat</label>
        </b-col>
        <b-col sm="12">
          <b-form-input id="pejabat_penandatangan" type="text" v-model="form_riwayat_diklat.nomor_sertifikat" />
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
            relationTo="riwayat_diklat"
            :entityId="form_riwayat_diklat.id ?? ''"
            :masterDokumenDigital="masterDokumenDigital"
            @onUploading="loading = 2"
            @onUploaded="loading = false"
            :canEdit="true"
            v-model="form_riwayat_diklat.files[masterDokumenDigital.id]">
          </UploadFile>
        </b-col>
      </b-row>
    </template>
    <template #modal-footer="{}">
      <!-- Emulate built in modal footer ok and cancel button actions -->
      <b-button size="md" variant="info" @click="saveRiwayatDiklat()" :disabled="loading">
        <i class="fa fa-save"></i> {{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
      </b-button>
      <b-button size="md" variant="light" @click="cancelRiwayatDiklat()">
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
      form_riwayat_diklat: null,
      loading: false,
      errors:{},
      master_diklat: {}
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
    openRiwayatDiklatModal(master_diklat, data) {
      this.master_diklat = master_diklat
      if (data) {
        this.form_riwayat_diklat = {
          id: data.id,
          peg_id: data.peg_id,
          nama_pelatihan: data.nama_pelatihan,
          tanggal_pelaksanaan: data.tanggal_pelaksanaan,
          lama_pelatihan: data.lama_pelatihan,
          pejabat_penandatangan: data.pejabat_penandatangan,
          nomor_sertifikat: data.nomor_sertifikat,
          files:{},
          master_diklat_id: data.master_diklat_id,
        };
      } else {
        this.form_riwayat_diklat = {
          peg_id: this.pegawai.peg_id,
          files:{},
          master_diklat_id: this.master_diklat.id
        };
      }
      this.errors={}
      this.$refs['modal-riwayat-diklat'].show()
    },
    async saveRiwayatDiklat() {
      this.loading = true
      try {
        if (this.form_riwayat_diklat.id) {
        let resp = (await axios.patch('pegawai/riwayat-diklat/' + this.form_riwayat_diklat.id, this.form_riwayat_diklat)).data
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: resp.message,
          confirmButtonText: 'ok',
        })
      } else {
        let resp = (await axios.post('pegawai/riwayat-diklat', this.form_riwayat_diklat)).data
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: resp.message,
          confirmButtonText: 'ok',
        })
      }
      this.$emit('onSave')
      this.$refs['modal-riwayat-diklat'].hide()
      } catch (err) {
        if(err.response && err.response.status == 422){
          this.errors = err.response.data.errors;
        }
      }
      this.loading = false
    },
    cancelRiwayatDiklat() {
      this.$refs['modal-riwayat-diklat'].hide()
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