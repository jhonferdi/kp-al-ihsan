<template>
  <div style="display: inline-block; line-height:2">
    <h6 class="d-inline"> [{{ masterDokumenDigital ? masterDokumenDigital.file_nama : 'UNKNOWN'}}]</h6>
    <span v-if="dokumenDigitalComputed">
      <b-badge variant="success">Sudah Upload</b-badge>
      <a :href="dokumenDigitalComputed.file_url" target="_blank" download>
        <b-button size="sm" variant="lightgreen" class="mb-1">
          <b-icon icon="cloud-download"></b-icon> Download
        </b-button>
      </a>
    </span>
    <span v-else>
      <b-badge variant="warning">Belum Upload</b-badge>
    </span>
    <b-button size="sm" variant="primary" class="mb-1" @click="upload()" v-if="canEdit" :disabled="loading">
      <b-icon icon="cloud-upload" v-if="canEdit && !loading"></b-icon>
      <b-icon icon="arrow-repeat" v-if="canEdit && loading"></b-icon>
      {{!loading ? (dokumenDigitalComputed ? 'Reupload' : 'Upload') : 'Sedang Mengupload...'}}
    </b-button>
    <input type="file" @change="handleFileUpload($event)" ref="fileUpload" style="display:none"
      :accept="masterDokumenDigital ? masterDokumenDigital.accept_extension : 'application/pdf'">
  </div>
</template>

<script>
import { faDoorClosed } from '@fortawesome/free-solid-svg-icons'
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
  props: {
    pegawai: {
      required: true
    },
    dokumenDigital: {
      required: true
    },
    canEdit: {
      default: true
    },
    relationTo: {
      default: ''
    },
    entityId: {
      default: ''
    },
    masterDokumenDigital: {
      required: true
    }
  },
  data() {
    return {
      loading: false,
      dokumen_digital: null,
    }
  },
  computed: {
    dokumenDigitalComputed() {
      if (this.dokumen_digital) {
        return this.dokumen_digital
      }
      if (this.masterDokumenDigital && this.dokumenDigital[this.relationTo] && this.dokumenDigital[this.relationTo][this.entityId] && this.dokumenDigital[this.relationTo][this.entityId][this.masterDokumenDigital.id]) {
        return this.dokumenDigital[this.relationTo] && this.dokumenDigital[this.relationTo][this.entityId] && this.dokumenDigital[this.relationTo][this.entityId][this.masterDokumenDigital.id]
      }
      return null
    }
  },
  watch: {
    value() {
      this.dokumen_digital = null
    },
    dokumenDigital() {
      this.dokumen_digital = null
    },
  },
  methods: {
    upload() {
      this.$refs['fileUpload'].click()
    },
    async handleFileUpload(event) {
      try {
        this.loading = true
        this.file = event.target.files[0];
        if (this.file.size / 1024 > this.masterDokumenDigital.max_size_kb) {
          Swal.fire({
            icon: 'warning',
            title: 'Failed',
            text: 'Ukuran file melebihi ' + this.masterDokumenDigital.max_size_kb + ' kb',
            confirmButtonText: 'Ok'
          })
          return this.loading = false
        }
        const acceptExtension = !this.masterDokumenDigital.accept_extension ? ['application/pdf'] : this.masterDokumenDigital.accept_extension.split(",")
        if (acceptExtension.indexOf(this.file.type) == -1) {
          Swal.fire({
            icon: 'warning',
            title: 'Failed',
            text: 'Jenis file tidak sesuai dengan kriteria ' + this.masterDokumenDigital.accept_extension,
            confirmButtonText: 'Ok'
          })
          return this.loading = false
        }
        let formData = new FormData();
        formData.append('file', this.file);
        let url
        if (this.entityId) {
          url = 'pegawai/dokumen-digital/' + this.pegawai.peg_id + '/' + this.masterDokumenDigital.id + '/' + this.entityId
        } else {
          url = 'pegawai/dokumen-digital/' + this.pegawai.peg_id + '/' + this.masterDokumenDigital.id
        }
        let resp = await axios.post(url, formData)
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: resp.data.message,
          confirmButtonText: 'ok',
        })
        this.$emit('onUploaded')
        this.$refs['fileUpload'].value = ''
        this.loading = false
        this.dokumen_digital = resp.data.dokumen_digital
        this.$emit('input', this.dokumen_digital)
      } catch (err) {
        if (err.response && err.response.status == 422) {
          // this.errors = err.response.data.errors;
          Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: err.response.data.errors.file,
            confirmButtonText: 'ok',
          })
        }
      }

    },

  }
}
</script>

<style>

</style>