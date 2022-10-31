<template>
  <b-card title="SIP Baru">
    <b-container fluid>
      <b-form @submit.prevent="save" class="my-4">
        <b-overlay id="overlay-background" :show="isloading" variant="white" opacity="0.85" rounded="sm">
          <b-form-group label-cols-sm="3" content-cols-sm="9" label="Pegawai" label-for="peg_id">
            <v-select :options="PEGOptions" :reduce="item => item.peg_id" label="peg_nama_lengkap"
              placeholder="Pilih Pegawai" v-model="form.peg_id" :state="getErrorState('peg_id')">
            </v-select>
            <p style="color:red;" v-if="getErrorState('peg_id') === false">
              {{ getErrorMessage('peg_id') }}
            </p>
          </b-form-group>
          <b-form-group label-cols-sm="3" content-cols-sm="9" label="Nomor SIP" label-for="nomor_sip">
            <b-form-input id="nomor_sip" type="number" v-model="form.nomor_sip" placeholder="Masukkan Nomor SIP"
              :state="getErrorState('nomor_sip')" />
            <p style="color:red;" v-if="getErrorState('nomor_sip') === false">
              {{ getErrorMessage('nomor_sip') }}
            </p>
          </b-form-group>
          <b-form-group label-cols-sm="3" content-cols-sm="9" label="Tanggal Kadaluarsa SIP"
            label-for="tanggal_kadaluarsa_sip">
            <b-form-input id="tanggal_kadaluarsa_sip" type="date" v-model="form.tanggal_kadaluarsa_sip"
              placeholder="Masukkan Tanggal Kadaluarsa SIP" :state="getErrorState('tanggal_kadaluarsa_sip')" />
            <p style="color:red;" v-if="getErrorState('tanggal_kadaluarsa_sip') === false">
              {{ getErrorMessage('tanggal_kadaluarsa_sip') }}
            </p>
          </b-form-group>
          <b-form-group label-cols-sm="3" content-cols-sm="9" label="Jenis SIP" label-for="jenis_sip">
            <v-select :options="JSIPOptions" :reduce="item => item.value" label="text" placeholder="Pilih Jenis SIP"
              v-model="form.jenis_sip" :state="getErrorState('jenis_sip')">
            </v-select>
            <p style="color:red;" v-if="getErrorState('jenis_sip') === false">
              {{ getErrorMessage('jenis_sip') }}
            </p>
          </b-form-group>
          <!-- <b-form-group label-cols-sm="3" content-cols-sm="9" label="Foto SIP" label-for="image">
            <b-form-file id="image" ref="image" v-model="form.image" :state="getErrorState('image')"
              placeholder="Pilih Foto SIP" drop-placeholder="Pilih Foto SIP"
              accept=".jpeg,.jpg,.png,image/jpeg,image/png, application/pdf" @change="onFileChange" />
            <b-form-invalid-feedback v-if="getErrorState('image') === false">
              {{ getErrorMessage('image') }}
            </b-form-invalid-feedback>
          </b-form-group>
          <b-form-group v-if="urlgambar" label-cols-sm="3" content-cols-sm="3">
            <b-img v-if="urlgambar" thumbnail fluid lazy :src="urlgambar" />
          </b-form-group> -->
        </b-overlay>
        <div class="form-group d-flex justify-content-end">
          <button class="btn btn-success" :disabled="isloading" type="submit"><i class="fas fa-save">
              Simpan</i></button>
              <b-button variant="light" @click="cancelSip()" :disabled="loading">
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
      nomor_sip: '',
      tanggal_kadaluarsa_sip: '',
      jenis_sip: '',
      // image: null
    },
    // urlgambar: null,
    errors: {},
    JSIPOptions: [
      { value: 'Dokter', text: 'Dokter' },
      { value: 'Bidan', text: 'Bidan' },
      { value: 'Perawat', text: 'Perawat' },
      { value: 'Teknisi Gigi', text: 'Teknisi Gigi' },
      { value: 'Terapis Gigi dan Mulut', text: 'Terapis Gigi dan Mulut' },
      { value: 'Penata Anestesi', text: 'Penata Anestesi' },
      { value: 'Fisioterapis', text: 'Fisioterapis' },
      { value: 'Terapis Wicara', text: 'Terapis Wicara' },
      { value: 'Okupasi Terapis', text: 'Okupasi Terapis' },
      { value: 'Radiografer', text: 'Radiografer' },
      { value: 'Tenaga Gizi', text: 'Tenaga Gizi' },
      { value: 'Perekam Medis', text: 'Perekam Medis' },
      { value: 'Ahli Teknologi Laboratorium Medik', text: 'Ahli Teknologi Laboratorium Medik' },
      { value: 'Refraksionis Optisien', text: 'Refraksionis Optisien' },
      { value: 'Elektromedis', text: 'Elektromedis' },
      { value: 'Tenaga Sanitarian', text: 'Tenaga Sanitarian' },
      { value: 'Apoteker', text: 'Apoteker' },
      { value: 'Tenaga Teknis Kefarmasian', text: 'Tenaga Teknis Kefarmasian' },
      { value: 'Psikolog Klinis', text: 'Psikolog Klinis' },
      { value: 'Akupunktur Terapis', text: 'Akupunktur Terapis' },
      { value: 'Tenaga Kesehatan Tradisional', text: 'Tenaga Kesehatan Tradisional' },
      { value: 'Struktural MPKP', text: 'Struktural MPKP' },
    ],
  }),
  head() {
    return { title: 'Tambah SIP' }
  },
  computed: mapGetters({
    user: 'auth/user'
  }),
  methods: {
    async save() {
      this.loading = true
      try {
        let resp = (await axios.post('sip', this.form)).data
        if (resp.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil disimpan',
            confirmButtonText: 'ok',
          })
          this.form = {}
          this.$router.push({ name: 'sip' })
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
    cancelSip(){
      this.$router.push({ name: 'sip' })
    },
  }
}
</script>

