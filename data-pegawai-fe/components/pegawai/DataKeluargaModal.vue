<template>
  <b-modal id="modal-data-keluarga" ref="modal-data-keluarga" title="Tambah Data Keluarga">
    <template #modal-header="{}">
      <h5>Informasi Data Keluarga</h5>
    </template>
    <template #default="{}">
      <b-row class="my-1">
        <b-col sm="12">
          <label>Nama Lengkap</label>
        </b-col>
        <b-col sm="12">
          <b-form-input class="mb-3" type="text" v-model="form_data_keluarga.nama" :state="getErrorState('nama')"></b-form-input>
          <p style="color:red;" v-if="getErrorState('nama') === false">
            {{ getErrorMessage('nama') }}
            </p>
        </b-col>
        <b-col sm="12">
          <label>No NIK</label>
        </b-col>
        <b-col sm="12">
          <b-form-input class="mb-3" type="number" v-model="form_data_keluarga.no_nik" :state="getErrorState('no_nik')"></b-form-input>
          <p style="color:red;" v-if="getErrorState('no_nik') === false">
            {{ getErrorMessage('no_nik') }}
            </p>
        </b-col>
        <b-col sm="12">
          <label>Jenis Hubungan</label>
        </b-col>
        <b-col sm="12">
          <b-form-select type="text" class="mb-3" v-model="form_data_keluarga.jenis_hubungan" :options="JHOptions" :state="getErrorState('jenis_hubungan')"></b-form-select>
          <p style="color:red;" v-if="getErrorState('jenis_hubungan') === false">
            {{ getErrorMessage('jenis_hubungan') }}
            </p>
        </b-col>
        <b-col sm="12">
          <label>Status Pernikahan</label>
        </b-col>
        <b-col sm="12">
          <b-form-select type="text" class="mb-3" v-model="form_data_keluarga.status_pernikahan" :options="SPOptions" :state="getErrorState('status_pernikahan')"></b-form-select>
          <p style="color:red;" v-if="getErrorState('status_pernikahan') === false">
            {{ getErrorMessage('status_pernikahan') }}
            </p>
        </b-col>
        <b-col sm="12">
          <label>Tempat Lahir</label>
        </b-col>
        <b-col sm="12">
          <b-form-input type="text" class="mb-3" v-model="form_data_keluarga.tempat_lahir" :state="getErrorState('tempat_lahir')"></b-form-input>
          <p style="color:red;" v-if="getErrorState('tempat_lahir') === false">
            {{ getErrorMessage('tempat_lahir') }}
            </p>
        </b-col>
        <b-col sm="12">
          <label>Tanggal Lahir</label>
        </b-col>
        <b-col sm="12">
          <b-form-input type="date" class="mb-3" v-model="form_data_keluarga.tanggal_lahir" :state="getErrorState('tanggal_lahir')"></b-form-input>
          <p style="color:red;" v-if="getErrorState('tanggal_lahir') === false">
            {{ getErrorMessage('tanggal_lahir') }}
            </p>
        </b-col>
        <b-col sm="12">
          <label>Alamat</label>
        </b-col>
        <b-col sm="12">
          <b-form-input type="text" class="mb-3" v-model="form_data_keluarga.alamat" :state="getErrorState('alamat')"></b-form-input>
          <p style="color:red;" v-if="getErrorState('alamat') === false">
            {{ getErrorMessage('alamat') }}
            </p>
        </b-col>
        <b-col sm="12">
          <label>Pendidikan</label>
        </b-col>
        <b-col sm="12">
          <b-form-select type="text" class="mb-3" v-model="form_data_keluarga.pendidikan" :options="POptions"></b-form-select>
        </b-col>
        <b-col sm="12">
          <label>Status</label>
        </b-col>
        <b-col sm="12">
          <b-form-select type="text" class="mb-3" v-model="form_data_keluarga.status" :options="SOptions"></b-form-select>
        </b-col>
        <b-col sm="12">
          <label>Tempat Bekerja</label>
        </b-col>
        <b-col sm="12">
          <b-form-input type="text" class="mb-3" v-model="form_data_keluarga.tempat_bekerja"></b-form-input>
        </b-col>
        <b-col sm="12">
          <label>NO BPJS</label>
        </b-col>
        <b-col sm="12">
          <b-form-input type="number" class="mb-3" v-model="form_data_keluarga.no_bpjs"></b-form-input>
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
            relationTo="data_keluarga"
            :entityId="form_data_keluarga.id ?? ''"
            :masterDokumenDigital="masterDokumenDigital"
            @onUploading="loading = 2"
            @onUploaded="loading = false"
            :canEdit="true"
            v-model="form_data_keluarga.files[masterDokumenDigital.id]">
          </UploadFile>
        </b-col>
      </b-row>
    </template>
    <template #modal-footer="{}">
      <!-- Emulate built in modal footer ok and cancel button actions -->
      <b-button size="md" variant="info" @click="saveDataKeluarga()" :disabled="loading">
        <i class="fa fa-save"></i> {{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
      </b-button>
      <b-button size="md" variant="light" @click="cancelDataKeluarga()">
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
      form_data_keluarga: null,
      loading: false,
      errors:{},
      JHOptions: [
        { value: 'Istri', text: 'Istri' },
        { value: 'Suami', text: 'Suami' },
        { value: 'Orang Tua', text: 'Orang Tua' },
        { value: 'Anak Kandung', text: 'Anak Kandung' },
        { value: 'Kakak Kandung', text: 'Kakak Kandung' },
        { value: 'Adik Kandung', text: 'Adik Kandung' },
        { value: 'Anak Sambung', text: 'Anak Sambung' },
      ],
      SOptions: [
        { value: 'PNS', text: 'PNS' },
        { value: 'TNI', text: 'TNI' },
        { value: 'POLISI', text: 'POLISI' },
        { value: 'RSUD AL-IHSAN', text: 'RSUD AL-IHSAN' },
        { value: 'Lainnya', text: 'Lainnya' },
      ],
      SPOptions: [
        { value: 'Belum Menikah', text: 'Belum Menikah' },
        { value: 'Menikah', text: 'Menikah' },
        { value: 'Cerai Hidup', text: 'Cerai Hidup' },
        { value: 'Cerai Mati', text: 'Cerai Mati' },
      ],
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
    openDataKeluargaModal(data) {
      if (data) {
        this.form_data_keluarga = {
          id: data.id,
          peg_id: data.peg_id,
          nama: data.nama,
          no_nik: data.no_nik,
          jenis_hubungan: data.jenis_hubungan,
          status_pernikahan: data.status_pernikahan,
          status: data.status,
          tempat_lahir: data.tempat_lahir,
          tanggal_lahir: data.tanggal_lahir,
          alamat: data.alamat,
          pendidikan: data.pendidikan,
          tempat_bekerja: data.tempat_bekerja,
          no_bpjs: data.no_bpjs,
          files: {},
        };
      } else {
        this.form_data_keluarga = {
          peg_id: this.pegawai.peg_id,
          files: {}
        };
      }
      this.errors={}
      this.$refs['modal-data-keluarga'].show()
    },
    async saveDataKeluarga() {
      this.loading = true
      try {
        if (this.form_data_keluarga.id) {
        let resp = (await axios.patch('pegawai/data-keluarga/' + this.form_data_keluarga.id, this.form_data_keluarga)).data
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: resp.message,
          confirmButtonText: 'ok',
        })
      } else {
        let resp = (await axios.post('pegawai/data-keluarga', this.form_data_keluarga)).data
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: resp.message,
          confirmButtonText: 'ok',
        })
      }
      this.$emit('onSave')
      this.$refs['modal-data-keluarga'].hide()
      } catch (err) {
        if(err.response && err.response.status == 422){
            this.errors = err.response.data.errors;
          }
      }
      this.loading = false
    },
    cancelDataKeluarga() {
      this.$refs['modal-data-keluarga'].hide()
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