<template>
    <b-modal id="modal-pelanggaran" ref="modal-pelanggaran" title="Tambah Pelanggaran">
        <template #modal-header="{}">
            <h5>Informasi Pelanggaran</h5>
        </template>
        <template #default="{}">
            <b-row class="my-1">
                <b-col sm="12">
                    <label>Nomor Surat</label>
                </b-col>
                <b-col sm="12">
                    <b-form-input type="text" name="nomor_surat" class="mb-3" v-model="form_pelanggaran.nomor_surat"
                        :state="getErrorState('nomor_surat')">
                    </b-form-input>
                    <p style="color:red;" v-if="getErrorState('nomor_surat') === false">
                        {{ getErrorMessage('nomor_surat') }}
                    </p>
                </b-col>
                <b-col sm="12">
                    <label>Pejabat Penandatangan</label>
                </b-col>
                <b-col sm="12">
                    <b-form-input type="text" class="mb-3" v-model="form_pelanggaran.pejabat_yang_mengeluarkan_bap"
                        :state="getErrorState('pejabat_yang_mengeluarkan_bap')">
                    </b-form-input>
                    <p style="color:red;" v-if="getErrorState('pejabat_yang_mengeluarkan_bap') === false">
                        {{ getErrorMessage('pejabat_yang_mengeluarkan_bap') }}
                    </p>
                </b-col>
                <b-col sm="12">
                    <label>Tahun</label>
                </b-col>
                <b-col sm="12">
                    <b-form-input type="number" class="mb-3" v-model="form_pelanggaran.tahun_kejadian">
                    </b-form-input>
                </b-col>
                <b-col sm="12">
                    <label>Jenis Pelanggaran</label>
                </b-col>
                <b-col sm="12">
                    <b-form-select class="font-size-13 mb-3" v-model="form_pelanggaran.jenis_pelanggaran"
                        :options="JPOptions">
                    </b-form-select>
                </b-col>
                <b-col sm="12">
                    <label>Punishment</label>
                </b-col>
                <b-col sm="12">
                    <b-form-input type="text" class="mb-3" v-model="form_pelanggaran.punishment">
                    </b-form-input>
                </b-col>
            </b-row>
            <b-row class="my-1" v-for="masterDokumenDigital in masterDokumenDigitals"
            :key="'masterDokumenDigitals-' + masterDokumenDigital.id">
            <b-col sm="12">
                <label>Upload {{ masterDokumenDigital.file_nama }}</label>
            </b-col>
            <b-col sm="12">
            <UploadFile :pegawai="pegawai" :dokumenDigital="dokumenDigital" relationTo="pelanggaran"
                :entityId="form_pelanggaran.id ?? ''" :masterDokumenDigital="masterDokumenDigital"
                @onUploading="loading = 2" @onUploaded="loading = false" :canEdit="true"
                v-model="form_pelanggaran.files[masterDokumenDigital.id]">
            </UploadFile>
        </b-col>
      </b-row>
        </template>
        <template #modal-footer="{}">
            <!-- Emulate built in modal footer ok and cancel button actions -->
            <b-button size="md" variant="info" @click="savePelanggaran()" :disabled="loading || uploadLoading">
                <i class="fa fa-save"></i> {{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
            </b-button>
            <b-button size="md" variant="light" @click="cancelPelanggaran()">
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
            form_pelanggaran: null,
            loading: false,
            errors: {},
            JPOptions: [
                { value: 'Pelanggaran Kepegawaian', text: 'Pelanggaran Kepegawaian' },
                { value: 'Pelanggaran Profesi', text: 'Pelanggaran Profesi' },
            ]
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
        openPelanggaranModal(data) {
            if (data) {
                this.form_pelanggaran = {
                    id: data.id,
                    peg_id: data.peg_id,
                    nomor_surat: data.nomor_surat,
                    pejabat_yang_mengeluarkan_bap: data.pejabat_yang_mengeluarkan_bap,
                    tahun_kejadian: data.tahun_kejadian,
                    jenis_pelanggaran: data.jenis_pelanggaran,
                    punishment: data.punishment,
                    files: {},
                };
            } else {
                this.form_pelanggaran = {
                    peg_id: this.pegawai.peg_id,
                    files: {},
                };
            }
            this.errors = {}
            this.$refs['modal-pelanggaran'].show()
        },
        async savePelanggaran() {
            this.loading = true
            try {
                if (this.form_pelanggaran.id) {
                    let resp = (await axios.patch('pegawai/pelanggaran/' + this.form_pelanggaran.id, this.form_pelanggaran)).data
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: resp.message,
                        confirmButtonText: 'ok',
                    })
                } else {
                    let resp = (await axios.post('pegawai/pelanggaran', this.form_pelanggaran)).data
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: resp.message,
                        confirmButtonText: 'ok',
                    })
                }
                this.loading = false
                this.$emit('onSave')
                this.$refs['modal-pelanggaran'].hide()
            } catch (err) {
                if (err.response && err.response.status == 422) {
                    this.errors = err.response.data.errors;
                }
                this.loading = false
            }
        },
        cancelPelanggaran() {
            this.$refs['modal-pelanggaran'].hide()
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