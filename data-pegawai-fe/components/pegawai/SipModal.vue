<template>
    <b-modal id="modal-sip" ref="modal-sip" title="Tambah SIP Baru">
        <template #modal-header="{}">
            <h5>Informasi SIP Baru</h5>
        </template>
        <template #default="{}">
            <b-row class="my-1">
                <b-col sm="12">
                    <label>Nomor SIP</label>
                </b-col>
                <b-col sm="12">
                    <b-form-input type="text" class="form-control" v-model="form_sip.nomor_sip"
                        :state="getErrorState('nomor_sip')"></b-form-input>
                    <p style="color:red;" v-if="getErrorState('nomor_sip') === false">
                        {{ getErrorMessage('nomor_sip') }}
                    </p>
                </b-col>
                <b-col>
                    <label>Tanggal Terdaftar</label>
                </b-col>
                <b-col sm="12">
                    <b-form-input type="date" class="form-control" v-model="form_sip.tanggal_penerbitan"
                        :state="getErrorState('tanggal_penerbitan')"></b-form-input>
                    <p style="color:red;" v-if="getErrorState('tanggal_penerbitan') === false">
                        {{ getErrorMessage('tanggal_penerbitan') }}
                    </p>
                </b-col>
                <b-col>
                    <label>Tanggal Kadaluarsa</label>
                </b-col>
                <b-col sm="12">
                    <b-form-input type="date" class="form-control" v-model="form_sip.tanggal_kadaluarsa_sip"
                        :state="getErrorState('tanggal_kadaluarsa_sip')"></b-form-input>
                    <p style="color:red;" v-if="getErrorState('tanggal_kadaluarsa_sip') === false">
                        {{ getErrorMessage('tanggal_kadaluarsa_sip') }}
                    </p>
                </b-col>
                <b-col>
                    <label>Jenis SIP</label>
                </b-col>
                <b-col sm="12">
                    <b-form-select v-model="form_sip.jenis_sip" :options="JSIPOptions"
                        :state="getErrorState('jenis_sip')">
                    </b-form-select>
                    <p style="color:red;" v-if="getErrorState('jenis_sip') === false">
                        {{ getErrorMessage('jenis_sip') }}
                    </p>
                </b-col>
            </b-row>
            <b-row class="my-1" v-for="masterDokumenDigital in masterDokumenDigitals"
                :key="'masterDokumenDigitals-' + masterDokumenDigital.id">
                <b-col sm="12">
                    <label>Upload {{ masterDokumenDigital.file_nama }}</label>
                </b-col>
                <b-col sm="12">
                    <UploadFile :pegawai="pegawai" :dokumenDigital="dokumenDigital" relationTo="sip"
                        :entityId="form_sip.id ?? ''" :masterDokumenDigital="masterDokumenDigital"
                        @onUploading="loading = 2" @onUploaded="loading = false" :canEdit="true"
                        v-model="form_sip.files[masterDokumenDigital.id]">
                    </UploadFile>
                </b-col>
            </b-row>

        </template>
        <template #modal-footer="{}">
            <!-- Emulate built in modal footer ok and cancel button actions -->
            <b-button size="md" variant="info" @click="saveSip()" :disabled="loading">
                <i class="fa fa-save"></i> {{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
            </b-button>
            <b-button size="md" variant="light" @click="cancelSip()">
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
            form_sip: null,
            loading: false,
            errors: {},
            JSIPOptions: [
                { value: 'Ahli Teknologi Laboratorium Medik', text: 'Ahli Teknologi Laboratorium Medik' },
                { value: 'Akupunktur Terapis', text: 'Akupuntur Terapis' },
                { value: 'Apoteker', text: 'Apoteker' },
                { value: 'Bidan', text: 'Bidan' },
                { value: 'Dokter', text: 'Dokter' },
                { value: 'Elektromedis', text: 'Elektromedis' },
                { value: 'Fisioterapis', text: 'Fisioterapis' },
                { value: 'Penata Anestesi', text: 'Terapis Wicara' },
                { value: 'Okupasi Terapis', text: 'Okupasi Terapis' },
                { value: 'Perawat', text: 'Perawat' },
                { value: 'Perekam Medis', text: 'Perekap Medis' },
                { value: 'Psikolog Klinis', text: 'Psikologi Klinis' },
                { value: 'Radiografer', text: 'Radiografer' },
                { value: 'Refraksionis Optisien', text: 'Refraksionis Optisien' },
                { value: 'Struktural MPKP', text: 'Struktural MPKP' },
                { value: 'Teknisi Gigi', text: 'Teknisi Gigi' },
                { value: 'Tenaga Gizi', text: 'Tenaga Gizi' },
                { value: 'Tenaga Kesehatan Tradisional', text: 'Tenaga Kesehatan Tradisional' },
                { value: 'Tenaga Sanitarian', text: 'Tenaga Sanitarium' },
                { value: 'Tenaga Teknis Kefarmasian', text: 'Tenaga Teknis Kefarmasian' },
                { value: 'Terapis Gigi dan Mulut', text: 'Terapis Gigi dan Mulut' },
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
        openSipModal(data) {
            if (data) {
                this.form_sip = {
                    id: data.sip_id,
                    peg_id: data.peg_id,
                    nomor_sip: data.nomor_sip,
                    tanggal_penerbitan: data.tanggal_penerbitan,
                    tanggal_kadaluarsa_sip: data.tanggal_kadaluarsa_sip,
                    jenis_sip: data.jenis_sip,
                    files: {},
                };
            } else {
                this.form_sip = {
                    peg_id: this.pegawai.peg_id,
                    files: {},
                };
            }
            this.errors = {}
            this.$refs['modal-sip'].show()
        },
        async saveSip() {
            this.loading = true
            try {
                if (this.form_sip.id) {
                    let resp = (await axios.patch('sip/' + this.form_sip.id, this.form_sip)).data
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: resp.message,
                        confirmButtonText: 'ok',
                    })
                } else {
                    let resp = (await axios.post('sip/', this.form_sip)).data
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: resp.message,
                        confirmButtonText: 'ok',
                    })
                }
                this.$emit('onSave')
                this.$refs['modal-sip'].hide()
            } catch (err) {
                if (err.response && err.response.status == 422) {
                    this.errors = err.response.data.errors;
                }
            }
            this.loading = false
        },
        cancelSip() {
            this.$refs['modal-sip'].hide()
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