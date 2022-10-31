<template>
  <b-modal id="modal-riwayat-kenaikan-gaji-berkala" ref="modal-riwayat-kenaikan-gaji-berkala"
    title="Tambah Kenaikan Gaji Berkala">
    <template #modal-header="{}">
      <h5>Informasi Kenaikan Gaji Berkala</h5>
    </template>
    <template #default="{}">
      <b-row class="my-1">
        <b-col sm="12">
          <label>Tahun</label>
        </b-col>
        <b-col sm="12">
          <b-form-select type="text" class="mb-3" v-model="form_riwayat_kenaikan_gaji_berkala.tahun"
            :options="listYears" value-field="value" text-field="text">
          </b-form-select>
        </b-col>
        <b-col sm="12">
          <label>Bulan</label>
        </b-col>
        <b-col sm="12">
          <b-form-select type="text" class="mb-3" v-model="form_riwayat_kenaikan_gaji_berkala.bulan"
            :options="BOptions"></b-form-select>
        </b-col>
        <b-col sm="12">
          <label>Golongan</label>
        </b-col>
        <b-col sm="12">
          <b-form-select type="text" class="mb-3" v-model="form_riwayat_kenaikan_gaji_berkala.golongan"
            :options="GOLOptions" value-field="golongan_nama" text-field="golongan_nama">
          </b-form-select>
        </b-col>
        <b-col sm="12">
          <label>Masa Kerja Tahun</label>
        </b-col>
        <b-col sm="12">
          <b-form-select type="text" class="mb-3" v-model="form_riwayat_kenaikan_gaji_berkala.masa_kerja_tahun"
            :options="MKTOptions"></b-form-select>
        </b-col>
        <b-col sm="12">
          <label>Masa Kerja Bulan</label>
        </b-col>
        <b-col sm="12">
          <b-form-select type="text" class="mb-3" v-model="form_riwayat_kenaikan_gaji_berkala.masa_kerja_bulan"
            :options="MKBOptions"></b-form-select>
        </b-col>
      </b-row>
      <b-row class="my-1" v-for="masterDokumenDigital in masterDokumenDigitals"
        :key="'masterDokumenDigitals-' + masterDokumenDigital.id">
        <b-col sm="12">
          <label>Upload {{ masterDokumenDigital.file_nama }}</label>
        </b-col>
        <b-col sm="12">
          <UploadFile :pegawai="pegawai" :dokumenDigital="dokumenDigital" relationTo="riwayat_kenaikan_gaji_berkala"
            :entityId="form_riwayat_kenaikan_gaji_berkala.id ?? ''" :masterDokumenDigital="masterDokumenDigital"
            @onUploading="loading = 2" @onUploaded="loading = false" :canEdit="true"
            v-model="form_riwayat_kenaikan_gaji_berkala.files[masterDokumenDigital.id]">
          </UploadFile>
        </b-col>
      </b-row>
    </template>
    <template #modal-footer="{}">
      <!-- Emulate built in modal footer ok and cancel button actions -->
      <b-button size="md" variant="info" @click="saveRiwayatKenaikanGajiBerkala()" :disabled="loading">
        <i class="fa fa-save"></i> {{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
      </b-button>
      <b-button size="md" variant="light" @click="cancelRiwayatKenaikanGajiBerkala()">
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
  props: ['pegawai', 'masterDokumenDigitals', 'dokumenDigital'],
  components: { UploadFile },
  data() {
    return {
      form_riwayat_kenaikan_gaji_berkala: {},
      loading: false,
      errors: {},
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
      MKTOptions: [
        { value: '1 Tahun', text: '1 Tahun' },
        { value: '2 Tahun', text: '2 Tahun' },
        { value: '3 Tahun', text: '3 Tahun' },
        { value: '4 Tahun', text: '4 Tahun' },
        { value: '5 Tahun', text: '5 Tahun' },
        { value: '6 Tahun', text: '6 Tahun' },
        { value: '7 Tahun', text: '7 Tahun' },
        { value: '8 Tahun', text: '8 Tahun' },
        { value: '9 Tahun', text: '9 Tahun' },
        { value: '10 Tahun', text: '10 Tahun' },
        { value: '11 Tahun', text: '11 Tahun' },
        { value: '12 Tahun', text: '12 Tahun' },
        { value: '13 Tahun', text: '13 Tahun' },
        { value: '14 Tahun', text: '14 Tahun' },
        { value: '15 Tahun', text: '15 Tahun' },
        { value: '16 Tahun', text: '16 Tahun' },
        { value: '17 Tahun', text: '17 Tahun' },
        { value: '18 Tahun', text: '18 Tahun' },
        { value: '19 Tahun', text: '19 Tahun' },
        { value: '20 Tahun', text: '20 Tahun' },
        { value: '21 Tahun', text: '21 Tahun' },
        { value: '22 Tahun', text: '22 Tahun' },
        { value: '23 Tahun', text: '23 Tahun' },
        { value: '24 Tahun', text: '24 Tahun' },
        { value: '25 Tahun', text: '25 Tahun' },
        { value: '26 Tahun', text: '26 Tahun' },
        { value: '27 Tahun', text: '27 Tahun' },
        { value: '28 Tahun', text: '28 Tahun' },
        { value: '29 Tahun', text: '29 Tahun' },
        { value: '30 Tahun', text: '30 Tahun' },
        { value: '31 Tahun', text: '31 Tahun' },
        { value: '32 Tahun', text: '32 Tahun' },
        { value: '33 Tahun', text: '33 Tahun' },
        { value: '34 Tahun', text: '34 Tahun' },
        { value: '35 Tahun', text: '35 Tahun' },
        { value: '36 Tahun', text: '36 Tahun' },
        { value: '37 Tahun', text: '37 Tahun' },
        { value: '38 Tahun', text: '38 Tahun' },
        { value: '39 Tahun', text: '39 Tahun' },
        { value: '40 Tahun', text: '40 Tahun' },
      ],
      MKBOptions: [
        { value: '1 Bulan', text: '1 Bulan' },
        { value: '2 Bulan', text: '2 Bulan' },
        { value: '3 Bulan', text: '3 Bulan' },
        { value: '4 Bulan', text: '4 Bulan' },
        { value: '5 Bulan', text: '5 Bulan' },
        { value: '6 Bulan', text: '6 Bulan' },
        { value: '7 Bulan', text: '7 Bulan' },
        { value: '8 Bulan', text: '8 Bulan' },
        { value: '9 Bulan', text: '9 Bulan' },
        { value: '10 Bulan', text: '10 Bulan' },
        { value: '11 Bulan', text: '11 Bulan' },
        { value: '12 Bulan', text: '12 Bulan' },
      ],
    }
  },
  created() {
    let currentYear = new Date().getFullYear()
    let startYear = startYear || 1980;
    let years = [];
    while (startYear <= currentYear) {
      let val = startYear++
      years.push({
        value: val, text: val
      });
    }
    this.listYears = years
  },
  computed: {
    ...mapGetters({
      GOLOptions: 'useroptions/GOLOptions',
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
    openRiwayatKenaikanGajiBerkalaModal(data) {
      if (data) {
        this.form_riwayat_kenaikan_gaji_berkala = {
          id: data.id,
          peg_id: data.peg_id,
          bulan: data.bulan,
          tahun: data.tahun,
          golongan: data.golongan,
          masa_kerja_tahun: data.masa_kerja_tahun,
          masa_kerja_bulan: data.masa_kerja_bulan,
          files: {},
        };
      } else {
        this.form_riwayat_kenaikan_gaji_berkala = {
          peg_id: this.pegawai.peg_id,
          files: {},
        };
      }
      this.errors = {}
      this.$refs['modal-riwayat-kenaikan-gaji-berkala'].show()
    },
    async saveRiwayatKenaikanGajiBerkala() {
      this.loading = true
      try {
        if (this.form_riwayat_kenaikan_gaji_berkala.id) {
          let resp = (await axios.patch('pegawai/riwayat-kenaikan-gaji-berkala/' + this.form_riwayat_kenaikan_gaji_berkala.id, this.form_riwayat_kenaikan_gaji_berkala)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        } else {
          let resp = (await axios.post('pegawai/riwayat-kenaikan-gaji-berkala', this.form_riwayat_kenaikan_gaji_berkala)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        }
        this.$emit('onSave')
        this.$refs['modal-riwayat-kenaikan-gaji-berkala'].hide()
      } catch (err) {
        if (err.response && err.response.status == 422) {
          this.errors = err.response.data.errors;
        }
      }
      this.loading = false
    },
    cancelRiwayatKenaikanGajiBerkala() {
      this.$refs['modal-riwayat-kenaikan-gaji-berkala'].hide()
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