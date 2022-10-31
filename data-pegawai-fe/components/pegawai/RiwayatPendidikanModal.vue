<template>
  <b-modal id="modal-riwayat-pendidikan" ref="modal-riwayat-pendidikan" title="Tambah Riwayat Pendidikan">
    <template #modal-header="{}">
      <h5>Informasi Riwayat Pendidikan</h5>
    </template>
    <template #default="{}">
      <b-row class="my-1">
        <b-col sm="12">
          <label>Tingkat Pendidikan</label>
        </b-col>
        <b-col sm="12">
          <input type="text" class="form-control" v-model="master_pendidikan.tingkat_pendidikan" readonly>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>No Ijazah</label>
        </b-col>
        <b-col sm="12">
          <b-form-input type="text" class="form-control" v-model="form_riwayat_pendidikan.no_ijazah" :state="getErrorState('no_ijazah')"></b-form-input>
          <p style="color:red;" v-if="getErrorState('no_ijazah') === false">
            {{ getErrorMessage('no_ijazah') }}
          </p>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Tanggal Ijazah</label>
        </b-col>
        <b-col sm="12">
          <b-form-input id="peg_lahir_tanggal" type="date" v-model="form_riwayat_pendidikan.tanggal_ijazah" :state="getErrorState('tanggal_ijazah')" />
          <p style="color:red;" v-if="getErrorState('tanggal_ijazah') === false">
            {{ getErrorMessage('tanggal_ijazah') }}
          </p>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Nama Sekolah</label>
        </b-col>
        <b-col sm="12">
          <b-form-input type="text" class="form-control" v-model="form_riwayat_pendidikan.nama_sekolah" :state="getErrorState('nama_sekolah')"></b-form-input>
          <p style="color:red;" v-if="getErrorState('nama_sekolah') === false">
            {{ getErrorMessage('nama_sekolah') }}
          </p>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Tanggal Lulus</label>
        </b-col>
        <b-col sm="12">
          <b-form-input id="tanggal_lulus" type="date" v-model="form_riwayat_pendidikan.tanggal_lulus"></b-form-input>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Pejabat Penandatangan Ijazah</label>
        </b-col>
        <b-col sm="12">
          <input type="text" class="form-control" v-model="form_riwayat_pendidikan.pejabat_penandatangan_ijazah">
        </b-col>
      </b-row>
      <b-row class="my-1" v-if="master_pendidikan.id >= 5" >
        <b-col sm="12">
          <label>Surat Izin Belajar</label>
        </b-col>
        <b-col sm="12">
          <input type="text" class="form-control" v-model="form_riwayat_pendidikan.surat_izin_belajar">
        </b-col>
      </b-row>
      <b-row class="my-1" v-if="master_pendidikan.id >= 5" >
        <b-col sm="12">
          <label>Penandatangan Surat Izin Belajar</label>
        </b-col>
        <b-col sm="12">
          <input type="text" class="form-control" v-model="form_riwayat_pendidikan.pejabat_penandatangan_izin_belajar">
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
            relationTo="riwayat_pendidikan"
            :entityId="form_riwayat_pendidikan.id ?? ''"
            :masterDokumenDigital="masterDokumenDigital"
            @onUploading="loading = 2"
            @onUploaded="loading = false"
            :canEdit="true"
            v-model="form_riwayat_pendidikan.files[masterDokumenDigital.id]">
          </UploadFile>
        </b-col>
      </b-row>
    </template>
    <template #modal-footer="{}">
      <!-- Emulate built in modal footer ok and cancel button actions -->
      <b-button size="md" variant="info" @click="saveRiwayatPendidikan()" :disabled="loading">
        <i class="fa fa-save"></i> {{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
      </b-button>
      <b-button size="md" variant="light" @click="cancelRiwayatPendidikan()">
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
      form_riwayat_pendidikan: null,
      loading: false,
      errors:{},
      master_pendidikan: {}
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
    openRiwayatPendidikanModal(master_pendidikan, data){
      this.master_pendidikan = master_pendidikan
      if (data) {
        this.form_riwayat_pendidikan = {
          id: data.id,
          peg_id: data.peg_id,
          no_ijazah: data.no_ijazah,
          tanggal_ijazah: data.tanggal_ijazah,
          nama_sekolah: data.nama_sekolah,
          tanggal_lulus: data.tanggal_lulus,
          pejabat_penandatangan_ijazah: data.pejabat_penandatangan_ijazah,
          surat_izin_belajar: data.surat_izin_belajar,
          pejabat_penandatangan_izin_belajar: data.pejabat_penandatangan_izin_belajar,
          files:{},
          master_pendidikan_id: data.master_pendidikan_id,
        };
      } else {
        this.form_riwayat_pendidikan = {
          peg_id: this.pegawai.peg_id,
          files:{},
          master_pendidikan_id : this.master_pendidikan.id
        };
      }
      this.errors={}
      this.$refs['modal-riwayat-pendidikan'].show()
    },
    async saveRiwayatPendidikan(){
      this.loading = true
      try {
        if (this.form_riwayat_pendidikan.id) {
        let resp = (await axios.patch('pegawai/riwayat-pendidikan/' + this.form_riwayat_pendidikan.id, this.form_riwayat_pendidikan)).data
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: resp.message,
          confirmButtonText: 'ok',
        })
      } else {
        let resp = (await axios.post('pegawai/riwayat-pendidikan', this.form_riwayat_pendidikan)).data
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: resp.message,
          confirmButtonText: 'ok',
        })
      }
      this.$emit('onSave')
      this.$refs['modal-riwayat-pendidikan'].hide()
      } catch (err) {
        if(err.response && err.response.status == 422){
          this.errors = err.response.data.errors;
        }
      }
      this.loading = false
    },
    cancelRiwayatPendidikan(){
      this.$refs['modal-riwayat-pendidikan'].hide()
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