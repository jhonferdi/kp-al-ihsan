<template>
  <b-card title="STR Baru">
    <b-container fluid>
      <b-form @submit.prevent="save" class="my-4">
        <b-overlay id="overlay-background" :show="isloading" variant="white" opacity="0.85" rounded="sm">
          <b-form-group label-cols-sm="3" content-cols-sm="9" label="Nama Pegawai" label-for="peg_id">
            <v-select :options="PEGOptions" :reduce="item => item.peg_id" label="peg_nama_lengkap"
              placeholder="Pilih Pegawai" v-model="form.peg_id" :state="getErrorState('peg_id')">
            </v-select>
            <p style="color:red;" v-if="getErrorState('peg_id') === false">
              {{ getErrorMessage('peg_id') }}
            </p>
          </b-form-group>
          <b-form-group label-cols-sm="3" content-cols-sm="9" label="Nomor STR" label-for="nomor_str">
            <b-form-input id="nomor_str" type="number" v-model="form.nomor_str" placeholder="Masukkan Nomor STR"
              :state="getErrorState('nomor_str')" />
            <p style="color:red;" v-if="getErrorState('nomor_str') === false">
              {{ getErrorMessage('nomor_str') }}
            </p>
          </b-form-group>
          <b-form-group label-cols-sm="3" content-cols-sm="9" label="Tanggal Kadaluarsa STR"
            label-for="tanggal_kadaluarsa_str">
            <b-form-input id="tanggal_kadaluarsa_str" type="date" v-model="form.tanggal_kadaluarsa_str"
              placeholder="Masukkan Tanggal Kadaluarsa STR" :state="getErrorState('tanggal_kadaluarsa_str')" />
            <p style="color:red;" v-if="getErrorState('tanggal_kadaluarsa_str') === false">
              {{ getErrorMessage('tanggal_kadaluarsa_str') }}
            </p>
          </b-form-group>
          <b-form-group label-cols-sm="3" content-cols-sm="9" label="Jenis STR" label-for="jenis_str">
            <v-select :options="JSTROptions" :reduce="item => item.value" label="text" placeholder="Pilih Jenis STR"
              v-model="form.jenis_str" :state="getErrorState('jenis_str')">
            </v-select>
            <p style="color:red;" v-if="getErrorState('jenis_str') === false">
              {{ getErrorMessage('jenis_str') }}
            </p>
          </b-form-group>
          <!-- <b-form-group label-cols-sm="3" content-cols-sm="9" label="Foto STR" label-for="image">
            <b-form-file id="image" ref="image" v-model="form.image" :state="getErrorState('image')"
              placeholder="Pilih Foto STR" drop-placeholder="Pilih Foto STR"
              accept=".jpeg,.jpg,.png,image/jpeg,image/png, application/pdf" @change="onFileChange" />
            <p style="color:red;" v-if="getErrorState('jenis_Str') === false">
              {{ getErrorMessage('jenis_Str') }}
            </p>
          </b-form-group>
          <b-form-group v-if="urlgambar" label-cols-sm="3" content-cols-sm="3">
            <b-img v-if="urlgambar" thumbnail fluid lazy :src="urlgambar" />
          </b-form-group> -->
        </b-overlay>
        <div class="form-group d-flex justify-content-end">
          <button class="btn btn-success" :disabled="isloading" type="submit"><i class="fas fa-save">
              Simpan</i></button>
              <b-button variant="light" @click="cancelStr()" :disabled="loading">
          Batal
        </b-button>
        </div>
      </b-form>
    </b-container>
  </b-card>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { mapGetters } from 'vuex'

export default {
  middleware: 'auth',
  async asyncData({ params }) {
    let f2 = axios.get('pegawai/get-data')
    let f2resp = (await f2).data
    let PEGOptions = f2resp.data
    return {
      PEGOptions
    }
  },
  data: () => ({
    isloading: false,
    form: {
      peg_id: '',
      nomor_str: '',
      tanggal_kadaluarsa_str: '',
      // image: null
    },
    // urlgambar: null,
    errors: {},
    JSTROptions: [
      { value: 'Dokter', text: 'Dokter' },
      { value: 'Dokter Spesialis', text: 'Dokter Spesialis' },
      { value: 'Perawat', text: 'Perawat' },
      { value: 'Bidan', text: 'Bidan' },
      { value: 'Fisioterapi', text: 'Fisioterapi' },
      { value: 'Perawat Gigi', text: 'Perawat Gigi' },
      { value: 'Refraksionis Optisien', text: 'Refraksionis Optisien' },
      { value: 'Terapis Wicara', text: 'Terapis Wicara' },
      { value: 'Radiografer', text: 'Radiografer' },
      { value: 'Okupasi Terapis', text: 'Okupasi Terapis' },
      { value: 'Ahli Gizi', text: 'Ahli Gizi' },
      { value: 'Perekam Medis', text: 'Perekam Medis' },
      { value: 'Teknik Gigi', text: 'Teknik Gigi' },
      { value: 'Sanitarium', text: 'Sanitarium' },
      { value: 'Elektromedik', text: 'Elektromedik' },
      { value: 'Analis Kesehatan', text: 'Analis Kesehatan' },
      { value: 'Anestesi', text: 'Anestesi' },
      { value: 'Akupuntur Terapi', text: 'Akupuntur Terapi' },
      { value: 'Fisikawan Medis', text: 'Fisikawan Medis' },
      { value: 'Okupasi Terapis', text: 'Okupasi Terapis' },
      { value: 'Teknis Transfusi Darah', text: 'Teknis Transfusi Darah' },
      { value: 'Struktural MPKP', text: 'Struktural MPKP' },
    ],
  }),
  head() {
    return { title: 'Tambah STR' }
  },
  computed: mapGetters({
    user: 'auth/user'
  }),
  methods: {
    async save() {
      this.loading = true
      try {
        let resp = (await axios.post('str', this.form)).data
        if (resp.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil disimpan',
            confirmButtonText: 'ok',
          })
          this.form = {}
          this.$router.push({ name: 'str' })
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Gagal menyimpan data',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        }
        this.errors = {}
      }
      catch (err) {
        if (err.response && err.response.status == 422) {
          this.errors = err.response.data.errors
        }
      }
      this.loading = false
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
    cancelStr(){
      this.$router.push({ name: 'str' })
    },
  }
}
</script>

