<template>
    <b-modal id="modal-rkk" ref="modal-rkk" title="Tambah RKK & SPKK JK">
        <template #modal-header="{}">
            <h5>Informasi RKK & SPKK PK</h5>
        </template>
        <template #default="{}">
            <b-row class="my-1">
                <b-col sm="12">
                    <label>Jenis RKK & SPKK PK</label>
                </b-col>
                <b-col sm="12">
                    <b-form-input id="jenis_pelatihan" type="text" v-model="master_rkk_spkk_jk.jenis_rkk_spkk_jk"
                        readonly />
                </b-col>
            </b-row>
            <b-row class="my-1">
                <b-col sm="12">
                    <label>Bulan</label>
                </b-col>
                <b-col sm="12">
                    <b-form-select v-model="form_rkk.bulan" :options="BOptions" :state="getErrorState('bulan')"></b-form-select>
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
                    <b-form-input id="tahun" type="number" v-model="form_rkk.tahun" :state="getErrorState('tahun')"/>
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
                    <UploadFile
                        :pegawai="pegawai"
                        :dokumenDigital="dokumenDigital"
                        relationTo="rkk_spkk_jk"
                        :entityId="form_rkk.id ?? ''"
                        :masterDokumenDigital="masterDokumenDigital"
                        @onUploading="loading = 2"
                        @onUploaded="loading = false"
                        :canEdit="true"
                        v-model="form_rkk.files[masterDokumenDigital.id]">
                    </UploadFile>
                </b-col>
            </b-row>
        </template>
        <template #modal-footer="{}">
            <!-- Emulate built in modal footer ok and cancel button actions -->
            <b-button size="md" variant="info" @click="saveRkk()" :disabled="loading">
                <i class="fa fa-save"></i> {{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
            </b-button>
            <b-button size="md" variant="light" @click="cancelRkk()">
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
            form_rkk: null,
            loading: false,
            errors:{},
            master_rkk_spkk_jk: {},
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
        openRkkModal(master_rkk_spkk_jk, data) {
            this.master_rkk_spkk_jk = master_rkk_spkk_jk
            if (data) {
                this.form_rkk = {
                    id: data.id,
                    peg_id: data.peg_id,
                    bulan: data.bulan,
                    tahun: data.tahun,
                    files:{},
                    master_rkk_spkk_jk_id: data.master_rkk_spkk_jk_id,
                };
            } else {
                this.form_rkk = {
                    peg_id: this.pegawai.peg_id,
                    files:{},
                    master_rkk_spkk_jk_id: this.master_rkk_spkk_jk.id
                };
            }
            this.errors={}
            this.$refs['modal-rkk'].show()
        },
        async saveRkk() {
            this.loading = true
            try {
                if (this.form_rkk.id) {
                let resp = (await axios.patch('pegawai/rkk/' + this.form_rkk.id, this.form_rkk)).data
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: resp.message,
                    confirmButtonText: 'ok',
                })
            } else {
                let resp = (await axios.post('pegawai/rkk', this.form_rkk)).data
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: resp.message,
                    confirmButtonText: 'ok',
                })
            }
            this.$emit('onSave')
            this.$refs['modal-rkk'].hide()
            } catch (err) {
                if(err.response && err.response.status == 422){
                    this.errors = err.response.data.errors;
                    }
                }
            this.loading = false
        },
        cancelRkk() {
            this.$refs['modal-rkk'].hide()
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