<template>
  <b-card>
    <b-container fluid>
      <div class="d-flex justify-content-between mb-1">
        <h2>Ubah Ijazah</h2>
        <div>
          <b-button variant="light" :to="{ name: 'ijazah' }">
            <b-icon icon="arrow-left" /> Kembali
          </b-button>
        </div>
      </div>
      <b-form @submit.prevent="save" class="my-4">
        <b-overlay id="overlay-background" :show="isloading" variant="white" opacity="0.85" rounded="sm">
          <b-form-group label-cols-sm="3" content-cols-sm="9" label="Nama Pegawai" label-for="nama_pegawai">
            <b-form-input id="nama_pegawai" v-model="form.nama_pegawai" placeholder="Masukkan Nama Pegawai"
              :state="getErrorState('nama_pegawai')" />
            <b-form-invalid-feedback v-if="getErrorState('nama_pegawai') === false">
              {{ getErrorMessage('nama_pegawai') }}
            </b-form-invalid-feedback>
          </b-form-group>
          <b-form-group label-cols-sm="3" content-cols-sm="9" label="Nomor Ijazah" label-for="nomor_ijazah">
            <b-form-input id="nomor_ijazah" type="number" v-model="form.nomor_ijazah"
              placeholder="Masukkan Nomor Ijazah" :state="getErrorState('nomor_ijazah')" />
            <b-form-invalid-feedback v-if="getErrorState('nomor_ijazah') === false">
              {{ getErrorMessage('nomor_ijazah') }}
            </b-form-invalid-feedback>
          </b-form-group>
          <b-form-group label-cols-sm="3" content-cols-sm="9" label="Tanggal Ijazah" label-for="tanggal_ijazah">
            <b-form-input id="tanggal_ijazah" type="date" v-model="form.tanggal_ijazah"
              placeholder="Masukkan Tanggal Ijazah" :state="getErrorState('tanggal_ijazah')" />
            <b-form-invalid-feedback v-if="getErrorState('tanggal_ijazah') === false">
              {{ getErrorMessage('tanggal_ijazah') }}
            </b-form-invalid-feedback>
          </b-form-group>
          <b-form-group label-cols-sm="3" content-cols-sm="9" label="Foto Ijazah" label-for="image">
            <b-form-file id="image" ref="image" v-model="form.image" :state="getErrorState('image')"
              placeholder="Pilih Foto Ijazah" drop-placeholder="Pilih Foto Ijazah"
              accept=".jpeg,.jpg,.png,image/jpeg,image/png, application/pdf" @change="onFileChange" />
            <p style="color:red;" v-if="form.imageOld != null">
              Foto ijazah sudah ada, abaikan apabila tidak ingin mengganti
            </p>
            <b-form-invalid-feedback v-if="getErrorState('image') === false">
              {{ getErrorMessage('image') }}
            </b-form-invalid-feedback>
          </b-form-group>
          <b-form-group v-if="urlgambar" label-cols-sm="3" content-cols-sm="3">
            <b-img v-if="urlgambar" thumbnail fluid lazy :src="urlgambar" />
          </b-form-group>
        </b-overlay>
        <div class="form-group d-flex justify-content-end">
          <button class="btn btn-success" :disabled="isloading" type="submit"><i class="fas fa-save">
              Simpan</i></button>
              <b-button variant="light" @click="cancelIjazah()" :disabled="loading">
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
  data: () => ({
    isloading: false,
    form: {
      nama_pegawai: '',
      nomor_ijazah: '',
      tanggal_ijazah: '',
      image: null,
    },
    urlgambar: null,
    errors: {}
  }),
  head() {
    return { title: 'Ubah Ijazah' }
  },
  computed: mapGetters({
    user: 'auth/user'
  }),
  mounted() {
    this.loadData()
  },
  methods: {
    async loadData() {
      Swal.fire({
        title: 'Memuat Data!',
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading()
        }
      }).then((result) => {
        /* Read more about handling dismissals below */
      })
      const f2 = await axios.get('ijazah/' + this.$route.params.id)
      const f2resp = (await f2).data
      if (!f2resp.success) {
        await Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: f2resp.message,
          timer: 5000
        })
        this.$router.push('/ijazah')
      }
      const data = f2resp.model
      this.form.nama_pegawai = data.nama_pegawai
      this.form.nomor_ijazah = data.nomor_ijazah
      this.form.tanggal_ijazah = data.tanggal_ijazah
      this.form.imageOld = data.image

      Swal.close()
    },
    async save() {
      const formData = new FormData()
      formData.append('nama_pegawai', this.form.nama_pegawai)
      formData.append('nomor_ijazah', this.form.nomor_ijazah)
      formData.append('tanggal_ijazah', this.form.tanggal_ijazah)
      if (this.form.image == null) {
        formData.append('image', '')
      } else if (this.form.image == null && this.form.imageOld != null) {
        formData.append('image', this.form.imageOld)
      } else {
        formData.append('imageOld', this.form.imageOld)
        formData.append('image', this.$refs.image.files[0])
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

      await axios.post('ijazah/update/' + this.$route.params.id,
        formData,
        {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        .then(({ data }) => {
          console.log(data)
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
            this.$router.push({ name: 'ijazah' })
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
    cancelIjazah(){
      this.$router.push({ name: 'ijazah' })
    },
  }
}
</script>