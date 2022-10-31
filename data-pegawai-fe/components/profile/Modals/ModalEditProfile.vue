<template>
  <modal :open="open" @close="$emit('close')" :title="title">
    <b-card class="mb-0">
      <h4>Profile</h4>
      <b-row>
        <b-col sm="6">
          <div class="mb-2">
            <label>Nama Tanpa Gelar</label>
            <b-form-input placeholder="Masukkan Nama Tanpa Gelar" v-model="form.peg_nama_tanpa_gelar"
              :state="getErrorState('peg_nama_tanpa_gelar')"></b-form-input>
            <p style="color:red;" v-if="getErrorState('peg_nama_tanpa_gelar') === false">
              {{ getErrorMessage('peg_nama_tanpa_gelar') }}
            </p>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Gelar Depan</label>
            <b-form-input placeholder="Masukkan Gelar Depan, Kosongkan Jika Tidak Ada" v-model="form.peg_gelar_depan">
            </b-form-input>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Gelar Belakang</label>
            <b-form-input placeholder="Masukkan Gelar Belakang, Kosongkan Jika Tidak Ada"
              v-model="form.peg_gelar_belakang"></b-form-input>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Nama Lengkap</label>
            <b-form-input placeholder="Masukkan Nama Lengkap" v-model="fullName"
              :state="getErrorState('peg_nama_lengkap')" disabled></b-form-input>
            <p style="color:red;" v-if="getErrorState('peg_nama_lengkap') === false">
              {{ getErrorMessage('peg_nama_lengkap') }}
            </p>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>NIP/NIPT/NIK</label>
            <b-form-input placeholder="Masukkan NIP/NIPT/NIK" v-model="form.peg_nip_nipt_nik"
              :disabled="!$checkPermission('edit-admin')"></b-form-input>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Tempat, Tanggal Lahir</label>
            <div class="d-flex">
              <b-form-input class="mr-2" placeholder="Masukkan Tempat Lahir" v-model="form.peg_lahir_tempat"
                :state="getErrorState('peg_lahir_tempat')">
              </b-form-input>
              <p style="color:red;" v-if="getErrorState('peg_lahir_tempat') === false">
                {{ getErrorMessage('peg_lahir_tempat') }}
              </p>
              <b-form-input type="date" placeholder="Masukkan Tanggal Lahir" v-model="form.peg_lahir_tanggal"
                :state="getErrorState('peg_lahir_tanggal')">
              </b-form-input>
              <p style="color:red;" v-if="getErrorState('peg_lahir_tanggal') === false">
                {{ getErrorMessage('peg_lahir_tanggal') }}
              </p>
            </div>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Jenis Kelamin</label>
            <b-form-select class="font-size-13" v-model="form.peg_jenis_kelamin" :options="JKOptions"
              value-field="value" text-field="text" :state="getErrorState('peg_jenis_kelamin')">
            </b-form-select>
            <p style="color:red;" v-if="getErrorState('peg_jenis_kelamin') === false">
              {{ getErrorMessage('peg_jenis_kelamin') }}
            </p>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Agama</label>
            <b-form-select class="font-size-13" v-model="form.peg_agama" :options="AOptions" value-field="value"
              text-field="text">
            </b-form-select>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Status Pernikahan</label>
            <b-form-select class="font-size-13" v-model="form.peg_status_pernikahan" :options="SPOptions"
              value-field="value" text-field="text">
            </b-form-select>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Golongan Darah</label>
            <b-form-select class="font-size-13" v-model="form.peg_golongan_darah" :options="GOptions"
              value-field="value" text-field="text">
            </b-form-select>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Tingkat Pendidikan</label>
            <b-form-select class="font-size-13" v-model="form.kualifikasi_pendidikan_id" :options="KPOptions"
              value-field="kualifikasi_pendidikan_id" text-field="kualifikasi_pendidikan">
            </b-form-select>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Pendidikan </label>
            <b-form-select class="font-size-13" v-model="form.pendidikan_id" :options="PDOptions"
              value-field="pendidikan_id" text-field="pendidikan_nama">
            </b-form-select>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Almamater</label>
            <b-form-input placeholder="Masukkan Almamater" v-model="form.peg_almamater_nama"></b-form-input>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Bidang </label>
            <b-form-select class="font-size-13" v-model="form.bidang_id" :options="BIDOptions" value-field="bidang_id"
              text-field="bidang_nama">
            </b-form-select>
          </div>
        </b-col>
      </b-row>
      <b-row>
        <b-col sm="6" v-if="form.bidang_id == 10 || form.bidang_id == 11">
          <div class="mb-2">
            <label>Spesialis</label>
            <b-form-select class="font-size-13" v-model="form.spesialis_id" :options="SPESOptions"
              value-field="spesialis_id" text-field="spesialis_nama" :disabled="!$checkPermission('edit-admin')">
            </b-form-select>
          </div>
        </b-col>
        <b-col sm="6" v-if="form.bidang_id == 11">
          <div class="mb-2">
            <label>Sub Spesialis</label>
            <b-form-select class="font-size-13" v-model="form.sub_spesialis_id" :options="SUBSPESOptions"
              value-field="sub_spesialis_id" text-field="sub_spesialis_nama"
              :disabled="!$checkPermission('edit-admin')">
            </b-form-select>
          </div>
        </b-col>
      </b-row>
      <hr>
      <h4>Penempatan</h4>
      <b-row>
        <b-col sm="6">
          <div class="mb-2">
            <label>Unit Kerja</label>
            <b-form-select class="font-size-13" v-model="form.unit_kerja_id" :options="UKEROptions"
              value-field="unit_kerja_id" text-field="unit_kerja_nama" :disabled="!$checkPermission('edit-admin')">
            </b-form-select>
          </div>
        </b-col>
      </b-row>
      <b-row>
        <b-col sm="12">
          <div class="mb-2">
            <label>Instalasi / Ruangan / Unit / Tim</label>
            <div v-if="form.pegawai_ruangan && form.pegawai_ruangan.length">
              <b-row v-for="(pr, i) in form.pegawai_ruangan" :key="'pegawai_ruangan_' + i">
                <b-col sm="5">
                  <b-form-select class="font-size-13" v-model="pr.ruangan_id" :options="RUANGANOptions"
                    value-field="ruangan_id" text-field="nama_ruangan" :disabled="!$checkPermission('edit-admin')">
                  </b-form-select>
                </b-col>
                <b-col sm="2">
                  Sebagai
                </b-col>
                <b-col sm="4">
                  <b-form-select class="font-size-13" v-model="pr.role" :options="PEGRUANGANOptions" value-field="value"
                    text-field="text" :disabled="!$checkPermission('edit-admin')">
                  </b-form-select>
                </b-col>
                <b-col sm="1">
                  <i class="fa fa-times" @click="form.pegawai_ruangan.splice(i, 1)"></i>
                </b-col>
              </b-row>
            </div>
            <div v-else>
              Pegawai tidak termasuk dalam Instalasi / Ruangan / Unit / Tim
            </div>
            <b-button variant="info" @click="form.pegawai_ruangan.push({ ruangan_id: null, role: 'pegawai' })" size="sm"
              class="mt-2">
              <i class="fa fa-plus"></i> Tambah Penempatan Tim
            </b-button>
          </div>
        </b-col>
      </b-row>
      <hr>
      <h4>Jabatan</h4>
      <b-row>
        <b-col sm="6">
          <div class="mb-2">
            <label>Jenis Jabatan</label>
            <b-form-select class="font-size-13" v-model="form.jenis_jabatan_id" :options="JJOptions"
              value-field="jenis_jabatan_id" text-field="jenis_jabatan_nama"
              :disabled="!$checkPermission('edit-admin')">
            </b-form-select>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Nama Jabatan</label>
            <b-form-select class="font-size-13" v-model="form.jabatan_id" :options="JBOptionsFilter"
              value-field="jabatan_id" text-field="jabatan_nama" :disabled="!$checkPermission('edit-admin')">
            </b-form-select>
          </div>
        </b-col>
        <!-- <b-col sm="6">
          <div class="mb-2">
            <label>Jabatan Fungsional</label>
            <b-form-select class="font-size-13" v-model="form.jabatan_fungsional_id" value-field="jabatan_fungsional_id"
              :options="JFOptions" text-field="jabatan_fungsional_nama" :disabled="!$checkPermission('edit-admin')">
            </b-form-select>
          </div>
        </b-col> -->
        <b-col sm="6">
          <div class="mb-2">
            <label>Golongan</label>
            <b-form-select class="font-size-13" v-model="form.golongan_id" :options="GOLOptions"
              value-field="golongan_id" text-field="golongan_nama" :disabled="!$checkPermission('edit-admin')">
            </b-form-select>
          </div>
        </b-col>
        <!-- <b-col sm="6">
          <div class="mb-2">
            <label>Tenaga Kerja</label>
            <b-form-select class="font-size-13" v-model="form.tenaga_kerja_id" :options="TKOptions"
              value-field="tenaga_kerja_id" text-field="tenaga_kerja_nama" :disabled="!$checkPermission('edit-admin')">
            </b-form-select>
          </div>
        </b-col> -->
        <b-col sm="6">
          <div class="mb-2">
            <label>Kualifikasi Pendidikan Tenaga Kerja</label>
            <b-form-select class="font-size-13" v-model="form.jenis_tenaga_kerja_id" :options="JTKOptions"
              value-field="jenis_tenaga_kerja_id" text-field="jenis_tenaga_kerja_nama"
              :disabled="!$checkPermission('edit-admin')">
            </b-form-select>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Status Kepegawaian</label>
            <b-form-select class="font-size-13" v-model="form.status_kepegawaian_id" :options="SPEGOptions"
              value-field="status_kepegawaian_id" text-field="status_kepegawaian"
              :disabled="!$checkPermission('edit-admin')">
            </b-form-select>
          </div>
        </b-col>
        <!-- <b-col sm="6">
          <div class="mb-2">
            <label>SK Pengangkatan</label>
            <b-form-input placeholder="Masukkan SK Pengangkatan" v-model="form.peg_sk_pengangkatan"
              :disabled="!$checkPermission('edit-admin')"></b-form-input>
          </div>
        </b-col> -->
      </b-row>
      <hr>
      <h4>Informasi Lainnya</h4>
      <b-row>
        <b-col sm="6">
          <div class="mb-2">
            <label>Nomor KTP</label>
            <b-form-input placeholder="Masukkan Nomor KTP" v-model="form.peg_ktp" :state="getErrorState('peg_ktp')">
            </b-form-input>
            <p style="color:red;" v-if="getErrorState('peg_ktp') === false">
              {{ getErrorMessage('peg_ktp') }}
            </p>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>No Kartu Keluarga</label>
            <b-form-input placeholder="Masukkan No Kartu Keluarga" v-model="form.peg_no_kk"></b-form-input>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>NPWP</label>
            <b-form-input placeholder="Masukkan NPWP" v-model="form.peg_npwp"></b-form-input>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>No Rekening BJB/Insentif</label>
            <b-form-input placeholder="Masukkan No Rekening BJB/Insentif" v-model="form.peg_nomor_rekening">
            </b-form-input>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>No. BPJS Kesehatan</label>
            <b-form-input placeholder="Masukkan No. BPJS Kesehatan" v-model="form.peg_bpjs"></b-form-input>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>No. BPJS Ketenagakerjaan</label>
            <b-form-input placeholder="Masukkan No. BPJS Ketenagakerjaan" v-model="form.peg_bpjs_ketenagakerjaan">
            </b-form-input>
          </div>
        </b-col>
        <b-col sm="6">
          <div class="mb-2">
            <label>Status Kerja</label>
            <b-form-select class="font-size-13" v-model="form.peg_status_kerja" :options="SKEROptions"
              value-field="value" text-field="text" :disabled="!$checkPermission('edit-admin')">
            </b-form-select>
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
import { _ } from 'vue-underscore'

export default {
  middleware: ['auth'],
  props: ['open', 'title', 'pegawai'],
  computed: {
    ...mapGetters({
      KPOptions: 'useroptions/KPOptions',
      BIDOptions: 'useroptions/BIDOptions',
      PDOptions: 'useroptions/PDOptions',
      UKEROptions: 'useroptions/UKEROptions',
      RUANGANOptions: 'useroptions/RUANGANOptions',
      SPESOptions: 'useroptions/SPESOptions',
      JBOptions: 'useroptions/JBOptions',
      JJOptions: 'useroptions/JJOptions',
      GOLOptions: 'useroptions/GOLOptions',
      // JFOptions: 'useroptions/JFOptions',
      JTKOptions: 'useroptions/JTKOptions',
      SPEGOptions: 'useroptions/SPEGOptions',
    }),
    fullName() {
      let nama_tanpa_gelar = this.form.peg_nama_tanpa_gelar == null ? '' : this.form.peg_nama_tanpa_gelar
      let gelar_depan = this.form.peg_gelar_depan == null ? '' : this.form.peg_gelar_depan
      let gelar_belakang = this.form.peg_gelar_belakang == null ? '' : this.form.peg_gelar_belakang
      return this.form.peg_nama_lengkap = gelar_depan + ' ' + nama_tanpa_gelar + ' ' + gelar_belakang
    },
    JBOptionsFilter() {
      if (this.form.jenis_jabatan_id == 2) {
        return this.JBOptions.filter((jb) => jb.jenis_jabatan_id == 2 && jb.unit_kerja_id_struktural == this.form.unit_kerja_id)
      }
      return this.JBOptions.filter((jb) => jb.jenis_jabatan_id == this.form.jenis_jabatan_id)
    },
  },
  data() {
    return {
      form: {},
      loading: false,
      errors: {},
      AOptions: [
        { value: '' || null, text: 'Pilih Agama' },
        { value: 'Budha', text: 'Budha' },
        { value: 'Hindu', text: 'Hindu' },
        { value: 'Islam', text: 'Islam' },
        { value: 'Katolik', text: 'Katolik' },
        { value: 'Konghucu', text: 'Konghucu' },
        { value: 'Kristen', text: 'Kristen' },
      ],
      PEGRUANGANOptions: [
        { value: '' || null, text: 'Pilih Tipe' },
        { value: 'pegawai', text: 'Pegawai' },
        { value: 'kepala', text: 'Kepala' },
        { value: 'kepala tim 1', text: 'Kepala Tim 1' },
        { value: 'kepala tim 2', text: 'Kepala Tim 2' },
        { value: 'admin ruangan', text: 'Admin Ruangan' },
      ],
      SPOptions: [
        { value: '' || null, text: 'Pilih Status Pernikahan' },
        { value: 'Menikah', text: 'Menikah' },
        { value: 'Belum Menikah', text: 'Belum Menikah' },
      ],
      GOptions: [
        { value: '' || null, text: 'Pilih Golongan Darah' },
        { value: 'A+', text: 'A+' },
        { value: 'A-', text: 'A-' },
        { value: 'B+', text: 'B+' },
        { value: 'B-', text: 'B-' },
        { value: 'AB+', text: 'AB+' },
        { value: 'AB-', text: 'AB-' },
        { value: 'O+', text: 'O+' },
        { value: 'O-', text: 'O-' },
        { value: 'A', text: 'A' },
        { value: 'B', text: 'B' },
        { value: 'AB', text: 'AB' },
        { value: 'O', text: 'O' },
      ],
      JKOptions: [
        { value: '' || null, text: 'Pilih Jenis Kelamin' },
        { value: 'L', text: 'Laki Laki' },
        { value: 'P', text: 'Perempuan' },
      ],
      SKEROptions: [
        { value: '' || null, text: 'Pilih Status Kerja' },
        { value: '0', text: 'Aktif' },
        { value: '1', text: 'Tidak Aktif' },
      ],
      SUBSPESOptions: [],
      UKERROptions: [],
      UKERUROptions: [],
      // JFOptions: [],
      // JTKOptions: [],
    }
  },
  watch: {
    open() {
      this.form = _.clone(this.pegawai)
      this.form.pegawai_ruangan = _.clone(this.pegawai.pegawai_ruangan)
      this.resetForm()
    },
    "form.spesialis_id"() {
      this.getSubSpes()
    },
    // "form.jabatan_id"() {
    //   this.getJabFung()
    // },
    "form.tenaga_kerja_id"() {
      this.getJenTenKer()
    },
  },
  methods: {
    async save() {
      this.loading = true
      try {
        let resp = (await axios.put('pegawai/update-profile/' + this.form.peg_id, this.form)).data
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
    },
    async getSubSpes() {
      let subSpesResp = (await axios.get('/pegawai/sub-spesialis', {
        params: {
          spesialis_id: this.form.spesialis_id
        }
      })).data.data
      let placeholderSubSpes = [{
        sub_spesialis_id: null, sub_spesialis_nama: "Pilih Sub Spesialis"
      }]
      this.SUBSPESOptions = placeholderSubSpes.concat(subSpesResp)
    },
    // async getJabFung() {
    //   let jabFung = (await axios.get('/pegawai/jabatan-fungsional', {
    //     params: {
    //       jabatan_id: this.form.jabatan_id
    //     }
    //   })).data.data
    //   let placeholderJabFung = [{
    //     jabatan_fungsional_id: null, jabatan_fungsional_nama: "Pilih Jabatan Fungsional"
    //   }]
    //   this.JFOptions = placeholderJabFung.concat(jabFung)
    // },
    // async getJenTenKer() {
    //   let jenTenKer = (await axios.get('/pegawai/jenis-tenaga-kerja', {
    //     params: {
    //       id: this.form.tenaga_kerja_id
    //     }
    //   })).data.data
    //   let placeholderJenTenKer = [{
    //     jenis_tenaga_kerja_id: null, jenis_tenaga_kerja_nama: "Pilih Jenis Tenaga Kerja"
    //   }]
    //   this.JTKOptions = placeholderJenTenKer.concat(jenTenKer)
    // },
  },

}
</script>