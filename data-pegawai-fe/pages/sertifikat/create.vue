<template>
  <b-card title="Sertifikat Tambah">
    <b-container fluid>
      <b-form @submit.prevent="save" class="my-4">
        <b-overlay id="overlay-background" :show="isloading" variant="white" opacity="0.85" rounded="sm">
          <b-form-group label-cols-sm="3" content-cols-sm="9" label="Nama Pegawai" label-for="nama_pegawai">
            <v-select id="input-name" :options="PEGOptions" :reduce="item => item.peg_id" label="peg_nama_lengkap"
              placeholder="Pilih Pegawai" v-model="form.peg_id" :class="{ 'is-invalid': !getErrorState('peg_id') }">
            </v-select>
            <b-form-invalid-feedback v-if="getErrorState('peg_id') === false">
              {{ getErrorMessage('peg_id') }}
            </b-form-invalid-feedback>
          </b-form-group>
          <b-form-group label-cols-sm="3" content-cols-sm="9" label="Kategori" label-for="kategori">
            <b-form-input id="kategori" type="text" v-model="form.kategori" placeholder="Masukkan Kategori"
              :state="getErrorState('kategori')" />
            <b-form-invalid-feedback v-if="getErrorState('kategori') === false">
              {{ getErrorMessage('kategori') }}
            </b-form-invalid-feedback>
          </b-form-group>
          <b-form-group label-cols-sm="3" content-cols-sm="9" label="Judul Sertifikat" label-for="judul_sertifikat">
            <b-form-input id="judul_sertifikat" type="text" v-model="form.judul_sertifikat"
              placeholder="Masukkan Judul Sertifikat" :state="getErrorState('judul_sertifikat')" />
            <b-form-invalid-feedback v-if="getErrorState('judul_sertifikat') === false">
              {{ getErrorMessage('judul_sertifikat') }}
            </b-form-invalid-feedback>
          </b-form-group>
          <b-form-group label-cols-sm="3" content-cols-sm="9" label="Tanggal Aktif" label-for="tanggal_aktif">
            <b-form-input id="tanggal_aktif" type="date" v-model="form.tanggal_aktif"
              placeholder="Masukkan Tanggal Aktif" :state="getErrorState('tanggal_aktif')" />
            <b-form-invalid-feedback v-if="getErrorState('tanggal_aktif') === false">
              {{ getErrorMessage('tanggal_aktif') }}
            </b-form-invalid-feedback>
          </b-form-group>
          <b-form-group label-cols-sm="3" content-cols-sm="9" label="File Sertifikat" label-for="image_sertifikat">
            <b-form-file id="image_sertifikat" ref="image_sertifikat" v-model="form.image_sertifikat"
              :state="getErrorState('image_sertifikat')" placeholder="Pilih File Sertifikat"
              drop-placeholder="Pilih File Sertifikat" accept=".jpeg,.jpg,.png,image/jpeg,image/png, application/pdf"
              @change="onFileChange" />
            <p style="color:red;" v-if="form.sertifikatOld != null">
              File sertifikat sudah ada, abaikan apabila tidak ingin mengganti
            </p>
            <b-form-invalid-feedback v-if="getErrorState('image_sertifikat') === false">
              {{ getErrorMessage('image_sertifikat') }}
            </b-form-invalid-feedback>
          </b-form-group>
          <b-form-group v-if="urlgambar" label-cols-sm="3" content-cols-sm="3">
            <b-img v-if="urlgambar" thumbnail fluid lazy :src="urlgambar" />
          </b-form-group>
        </b-overlay>
        <div class="form-group d-flex justify-content-end">
          <button class="btn btn-success" :disabled="isloading" type="submit"><i class="fas fa-save">
              Simpan</i></button>
              <b-button variant="light" @click="cancelSertifikat()" :disabled="loading">
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

    let f3 = axios.get('/pegawai', {
      params: {
        perpage: 'all',
        sortby: 'peg_nama_lengkap'
      }
    })
    let f3resp = (await f3).data
    let PEGOptions = f3resp.data

    return {
      PEGOptions
    }
  },
  data: () => ({
    isloading: false,
    form: {
      peg_id: '',
      kategori: '',
      judul_sertifikat: '',
      tanggal_aktif: '',
      image_sertifikat: null,
    },
    urlgambar: null,
    errors: {}
  }),
  head() {
    return { title: 'Tambah Sertifikat' }
  },
  computed: mapGetters({
    user: 'auth/user'
  }),
  methods: {
    async save() {
      const formData = new FormData()
      formData.append('peg_id', this.form.peg_id)
      formData.append('kategori', this.form.kategori)
      formData.append('judul_sertifikat', this.form.judul_sertifikat)
      formData.append('tanggal_aktif', this.form.tanggal_aktif)
      if (this.form.image_sertifikat == null) {
        formData.append('image_sertifikat', '')
      } else if (this.form.image_sertifikat == null && this.form.sertifikatOld != null) {
        formData.append('image_sertifikat', this.form.sertifikatOld)
      } else {
        formData.append('sertifikatOld', this.form.sertifikatOld)
        formData.append('image_sertifikat', this.$refs.image_sertifikat.files[0])
      }
      this.isloading = true
      Swal.fire({
        title: 'Menyimpan Data!',
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading()
        }
      }).then((result) => {
        /* Read more about handling dismissals below */
      })

      await axios.post('sertifikat/',
        formData,
        {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        .then(({ data }) => {
          if (data.success) {
            this.isloading = false
            Swal.close()
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: 'Data Telah Tersimpan',
              confirmButtonText: 'Ok',
              timer: 5000
            })
            this.$router.push({ name: 'sertifikat' })
          } else {
            this.isloading = false
            Swal.close()
            Swal.fire({
              icon: 'warning',
              title: 'Gagal',
              text: data.message,
              confirmButtonText: 'Ok',
              timer: 5000
            })
          }
          this.errors = {}
        })
        .catch((e) => {
          Swal.close()
          this.isloading = false
          if (e.response && e.response.status === 422) {
            this.errors = e.response.data.errors
          } else {
            Swal.fire({
              icon: 'warning',
              title: 'Gagal',
              text: 'Gagal menyimpan status! ' + e,
              confirmButtonText: 'Ok'
            })
          }
        })
      this.isloading = false
    },
    onFileChange(e) {
      const file = e.target.files[0]
      this.urlgambar = URL.createObjectURL(file)
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
    cancelSertifikat(){
      this.$router.push({ name: 'sertifikat' })
    },
  }
}
</script>