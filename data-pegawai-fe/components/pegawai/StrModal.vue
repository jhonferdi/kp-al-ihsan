<template>
    <b-modal id="modal-str" ref="modal-str" title="Tambah STR Baru">
        <template #modal-header="{}">
            <h5>Informasi STR Baru</h5>
        </template>
        <template #default="{}">
            <b-row class="my-1">
                <b-col sm="12">
                    <label>Nomor STR</label>
                </b-col>
                <b-col sm="12">
                    <b-form-input type="text" class="form-control" v-model="form_str.nomor_str"
                        :state="getErrorState('nomor_str')"></b-form-input>
                    <p style="color:red;" v-if="getErrorState('nomor_str') === false">
                        {{ getErrorMessage('nomor_str') }}
                    </p>
                </b-col>
                <b-col>
                    <label>Tanggal Terdaftar</label>
                </b-col>
                <b-col sm="12">
                    <b-form-input type="date" class="form-control" v-model="form_str.tanggal_penerbitan"
                        :state="getErrorState('tanggal_penerbitan')"></b-form-input>
                    <p style="color:red;" v-if="getErrorState('tanggal_penerbitan') === false">
                        {{ getErrorMessage('tanggal_penerbitan') }}
                    </p>
                </b-col>
                <b-col>
                    <label>Tanggal Kadaluarsa</label>
                </b-col>
                <b-col sm="12">
                    <b-form-input type="date" class="form-control" v-model="form_str.tanggal_kadaluarsa_str"
                        :state="getErrorState('tanggal_kadaluarsa_str')"></b-form-input>
                    <p style="color:red;" v-if="getErrorState('tanggal_kadaluarsa_str') === false">
                        {{ getErrorMessage('tanggal_kadaluarsa_str') }}
                    </p>
                </b-col>
                <b-col>
                    <label>Jenis STR</label>
                </b-col>
                <b-col sm="12">
                    <b-form-select v-model="form_str.jenis_str" :options="JSTROptions"
                        :state="getErrorState('jenis_str')">
                    </b-form-select>
                    <p style="color:red;" v-if="getErrorState('jenis_str') === false">
                        {{ getErrorMessage('jenis_str') }}
                    </p>
                </b-col>
            </b-row>
            <b-row class="my-1" v-for="masterDokumenDigital in masterDokumenDigitals"
                :key="'masterDokumenDigitals-' + masterDokumenDigital.id">
                <b-col sm="12">
                    <label>Upload {{ masterDokumenDigital.file_nama }}</label>
                </b-col>
                <b-col sm="12">
                    <UploadFile :pegawai="pegawai" :dokumenDigital="dokumenDigital" relationTo="str"
                        :entityId="form_str.id ?? ''" :masterDokumenDigital="masterDokumenDigital"
                        @onUploading="loading = 2" @onUploaded="loading = false" :canEdit="true"
                        v-model="form_str.files[masterDokumenDigital.id]">
                    </UploadFile>
                </b-col>
            </b-row>

        </template>
        <template #modal-footer="{}">
            <!-- Emulate built in modal footer ok and cancel button actions -->
            <b-button size="md" variant="info" @click="saveStr()" :disabled="loading">
                <i class="fa fa-save"></i> {{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
            </b-button>
            <b-button size="md" variant="light" @click="cancelStr()">
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
            form_str: null,
            loading: false,
            errors: {},
            JSTROptions: [
                { value: 'Ahli Gizi', text: 'Ahli Gizi' },
                { value: 'Akupuntur Terapi', text: 'Akupuntur Terapi' },
                { value: 'Analis Kesehatan', text: 'Analis Kesehatan' },
                { value: 'Anestesi', text: 'Anestesi' },
                { value: 'Apoteker', text: 'Apoteker' },
                { value: 'Bidan', text: 'Bidan' },
                { value: 'Dokter', text: 'Dokter' },
                { value: 'Dokter Spesialis', text: 'Dokter Spesialis' },
                { value: 'Elektromedik', text: 'Elektromedik' },
                { value: 'Fisikawan Medis', text: 'Fisikawan Medis' },
                { value: 'Fisioterapi', text: 'Fisioterapi' },
                { value: 'Okupasi Terapis', text: 'Okupasi Terapis' },
                { value: 'Perawat', text: 'Perawat' },
                { value: 'Perawat Gigi', text: 'Perawat Gigi' },
                { value: 'Perekam Medis', text: 'Perekam Medis' },
                { value: 'Radiografer', text: 'Radiografer' },
                { value: 'Refraksionis Optisien', text: 'Refraksionis Optisien' },
                { value: 'Sanitarium', text: 'Sanitarium' },
                { value: 'Struktural MPKP', text: 'Struktural MPKP' },
                { value: 'Teknik Gigi', text: 'Teknik Gigi' },
                { value: 'Teknis Transfusi Darah', text: 'Teknis Transfusi Darah' },
                { value: 'Tenaga Teknis Kefarmasian', text: 'Tenaga Teknis Kefarmasian' },
                { value: 'Terapis Wicara', text: 'Terapis Wicara' },
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
        openStrModal(data) {
            if (data) {
                this.form_str = {
                    id: data.str_id,
                    peg_id: data.peg_id,
                    nomor_str: data.nomor_str,
                    tanggal_penerbitan: data.tanggal_penerbitan,
                    tanggal_kadaluarsa_str: data.tanggal_kadaluarsa_str,
                    jenis_str: data.jenis_str,
                    files: {},
                };
            } else {
                this.form_str = {
                    peg_id: this.pegawai.peg_id,
                    files: {},
                };
            }
            this.errors = {}
            this.$refs['modal-str'].show()
        },
        async saveStr() {
            this.loading = true
            try {
                if (this.form_str.id) {
                    let resp = (await axios.patch('str/' + this.form_str.id, this.form_str)).data
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: resp.message,
                        confirmButtonText: 'ok',
                    })
                } else {
                    let resp = (await axios.post('str/', this.form_str)).data
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: resp.message,
                        confirmButtonText: 'ok',
                    })
                }
                this.$emit('onSave')
                this.$refs['modal-str'].hide()
            } catch (err) {
                if (err.response && err.response.status == 422) {
                    this.errors = err.response.data.errors;
                }
            }
            this.loading = false
        },
        cancelStr() {
            this.$refs['modal-str'].hide()
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