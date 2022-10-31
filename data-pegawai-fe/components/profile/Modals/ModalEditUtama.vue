<template>
  <modal :open="open" @close="$emit('close')" :title="title">
    <b-card class="mb-0">
      <b-row>
        <b-col sm="6">
          <div class="mb-2">
            <label>Nomor Telp</label>
            <b-form-input placeholder="Masukan Nomor Telp" v-model="form.peg_telp_hp"></b-form-input>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Email</label>
            <b-form-input type="email" placeholder="Masukan Email" v-model="form.peg_email"
              :state="getErrorState('peg_email')"></b-form-input>
            <p style="color:red;" v-if="getErrorState('peg_email') === false">
              {{ getErrorMessage('peg_email') }}
            </p>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>TMT </label>
            <b-form-input type="date" placeholder="Masukan TMT " v-model="form.peg_tmt"
              :disabled="!$checkPermission('edit-admin')"></b-form-input>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>TMT Golongan Akhir </label>
            <b-form-input type="date" placeholder="Masukan TMT Golongan Akhir " v-model="form.peg_tmt_golongan_akhir"
              :disabled="!$checkPermission('edit-admin')">
            </b-form-input>
          </div>
        </b-col>
      </b-row>
      <hr>
      <h4>Alamat Saat Ini</h4>
      <b-row>
        <b-col sm="6">
          <div class="mb-2">
            <label>Alamat</label>
            <b-form-input placeholder="Masukan Alamat Lengkap" v-model="form.peg_rumah_alamat"></b-form-input>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Provinsi</label>
            <b-form-select class="font-size-13" v-model="form.prov_id" :options="PROVOptions" value-field="id"
              text-field="nama">
            </b-form-select>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Kota/Kabupaten</label>
            <b-form-select class="font-size-13" v-model="form.kabkot_id" :options="KABOptions" value-field="id"
              text-field="nama">
            </b-form-select>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Kecamatan</label>
            <b-form-select class="font-size-13" v-model="form.kec_id" :options="KECOptions" value-field="id"
              text-field="nama">
            </b-form-select>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Kelurahan/Desa</label>
            <b-form-select class="font-size-13" v-model="form.keldes_id" :options="KELOptions" value-field="id"
              text-field="nama">
            </b-form-select>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>RW</label>
            <b-form-input placeholder="Masukan RW" v-model="form.peg_alamat_rw"></b-form-input>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>RT</label>
            <b-form-input placeholder="Masukan RT" v-model="form.peg_alamat_rt"></b-form-input>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Kode Pos</label>
            <b-form-input type="number" placeholder="Masukan Kode Pos" v-model="form.peg_kodepos"></b-form-input>
          </div>
        </b-col>
      </b-row>
      <hr>
      <h4>Alamat KTP</h4>
      <b-row>
        <b-col sm="6">
          <div class="mb-2">
            <label>Alamat</label>
            <b-form-input placeholder="Masukan Alamat"></b-form-input>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Provinsi</label>
            <b-form-select class="font-size-13" v-model="form.ktp_prov_id" :options="PROVKOptions" value-field="id"
              text-field="nama">
            </b-form-select>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Kota/Kabupaten</label>
            <b-form-select class="font-size-13" v-model="form.ktp_kabkot_id" :options="KABKOptions" value-field="id"
              text-field="nama">
            </b-form-select>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Kecamatan</label>
            <b-form-select class="font-size-13" v-model="form.ktp_kec_id" :options="KECKOptions" value-field="id"
              text-field="nama">
            </b-form-select>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Kelurahan/Desa</label>
            <b-form-select class="font-size-13" v-model="form.ktp_keldes_id" :options="KELKOptions" value-field="id"
              text-field="nama">
            </b-form-select>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>RW</label>
            <b-form-input placeholder="Masukan RW" v-model="form.peg_ktp_alamat_rw"></b-form-input>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>RT</label>
            <b-form-input placeholder="Masukan RT" v-model="form.peg_ktp_alamat_rt"></b-form-input>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Kode Pos</label>
            <b-form-input type="number" placeholder="Masukan Kode Pos" v-model="form.peg_ktp_kodepos"></b-form-input>
          </div>
        </b-col>
      </b-row>
    </b-card>
    <template #footer>
      <!-- <b-button variant="lightgreen">Simpan</b-button> -->
      <b-button variant="lightgreen" @click="save" :disabled="loading">
        <i class="fa fa-save"></i> {{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
      </b-button>
    </template>
  </modal>
</template>
<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { mapGetters } from 'vuex'
import {_} from 'vue-underscore'

export default {
  middleware: ['auth'],
  props: ['open', 'title', 'pegawai'],
  mounted() {
    this.errors = {}
  },
  data() {
    return {
      form: {},
      loading: false,
      errors: {},
      KABOptions: [],
      KECOptions: [],
      KELOptions: [],
      KABKOptions: [],
      KECKOptions: [],
      KELKOptions: [],
    }
  },
  computed: mapGetters({
    PROVOptions: 'useroptions/PROVOptions',
    PROVKOptions: 'useroptions/PROVOptions',
  }),
  watch: {
    open() {
      this.form = _.clone(this.pegawai)
      this.resetForm()
      this.getKabKot()
      this.getKec()
      this.getKel()
      this.getKabKotK()
      this.getKecK()
      this.getKelK()
    },
    "form.prov_id"() {
      this.getKabKot()
    },
    "form.kabkot_id"() {
      this.getKec()
    },
    "form.kec_id"() {
      this.getKel()
    },
    "form.ktp_prov_id"() {
      this.getKabKotK()
    },
    "form.ktp_kabkot_id"() {
      this.getKecK()
    },
    "form.ktp_kec_id"() {
      this.getKelK()
    },
  },
  methods: {
    async getKabKot() {
      let f15 = (await axios.get('/pegawai/kabupaten-kota', {
        params: {
          provinsi_id: this.form.prov_id
        }
      })).data.data
      let placeholder = [{
        id: null, nama: "Pilih Kabupaten/Kota"
      }]
      this.KABOptions = placeholder.concat(f15)
    },
    async getKabKotK() {
      let f15K = (await axios.get('/pegawai/kabupaten-kota', {
        params: {
          provinsi_id: this.form.ktp_prov_id
        }
      })).data.data
      let placeholder = [{
        id: null, nama: "Pilih Kabupaten/Kota"
      }]
      this.KABKOptions = placeholder.concat(f15K)
    },
    async getKec() {
      let f16 = (await axios.get('/pegawai/kecamatan', {
        params: {
          kabkot_id: this.form.kabkot_id
        }
      })).data.data
      let placeholder = [{
        id: null, nama: "Pilih Kecamatan"
      }]
      this.KECOptions = placeholder.concat(f16)
    },
    async getKecK() {
      let f16K = (await axios.get('/pegawai/kecamatan', {
        params: {
          kabkot_id: this.form.ktp_kabkot_id
        }
      })).data.data
      let placeholder = [{
        id: null, nama: "Pilih Kecamatan"
      }]
      this.KECKOptions = placeholder.concat(f16K)
    },
    async getKel() {
      let f17 = (await axios.get('/pegawai/kelurahan-desa', {
        params: {
          kecamatan_id: this.form.kec_id
        }
      })).data.data
      let placeholder = [{
        id: null, nama: "Pilih Kelurahan/Desa"
      }]
      this.KELOptions = placeholder.concat(f17)
    },
    async getKelK() {
      let f17K = (await axios.get('/pegawai/kelurahan-desa', {
        params: {
          kecamatan_id: this.form.ktp_kec_id
        }
      })).data.data
      let placeholder = [{
        id: null, nama: "Pilih Kelurahan/Desa"
      }]
      this.KELKOptions = placeholder.concat(f17K)
    },
    async save() {
      this.loading = true
      try {
        let resp = (await axios.put('pegawai/update-utama/' + this.form.peg_id, this.form)).data
        if (resp.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil disimpan',
            confirmButtonText: 'ok',
          })
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Gagal menyimpan data',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        }
        this.errors = {}
        this.$emit('close')
        this.$emit('onSave')
      } catch (err) {
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
    resetForm() {
      this.errors = {}
    }
  }
}
</script>