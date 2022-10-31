<template>
    <modal :open="open" :size="size" @close="$emit('close')" :title="title">
      <label>Jenis Jabatan</label>
      <b-form-input placeholder="Masukkan Jenis Jabatan" v-model="form.jenis_jabatan_nama" :state="getErrorState('jenis_jabatan_nama')">
      </b-form-input>
      <p style="color:red;" v-if="getErrorState('jenis_jabatan_nama') === false">
        {{ getErrorMessage('jenis_jabatan_nama') }}
      </p>
      <template #footer>
        <b-button variant="darkgreen" @click="save()" :disabled="loading">{{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
        </b-button>
      </template>
    </modal>
  </template>
  
  <script>
  import axios from 'axios'
  import Swal from 'sweetalert2'
  import { _ } from 'vue-underscore'
  
  export default {
    // middleware: 'auth',
    middleware: ['auth'],
    props: ['open', 'title', 'size', 'jenisJabatan'],
    data() {
      return {
        loading: false,
        form: {},
        errors: {},
      }
    },
    watch: {
      open() {
        this.resetForm()
        if (this.jenisJabatan) {
          this.form = _.clone(this.jenisJabatan)
        } else {
          this.form = {}
        }
      },
    },
    methods: {
      async save() {
        this.loading = true
        try {
          if (this.form.jenis_jabatan_id) {
            let resp = (await axios.patch('jenis-jabatan/' + this.form.jenis_jabatan_id, this.form)).data
            if (resp.success) {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data berhasil disimpan',
                confirmButtonText: 'ok',
              })
            }
          } else {
            let resp = (await axios.post('jenis-jabatan', this.form)).data
            if (resp.success) {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data berhasil disimpan',
                confirmButtonText: 'ok',
              })
            }
          }
          this.$emit('close')
          this.$emit('onSave')
          this.errors = {}
        } catch (err) {
          if (err.response && err.response.status == 422) {
            this.errors = err.response.data.errors
          }
        }
        this.loading = false
      },
      cancelJenisJabatan() {
        this.$router.push({ name: 'jenis-jabatan' })
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
    }
}
  </script>
  