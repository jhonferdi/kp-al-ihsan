<template>
    <modal :open="open" :size="size" @close="$emit('close')" :title="title">
      <label>Golongan</label>
      <b-form-input placeholder="Masukkan Golongan" v-model="form.golongan_nama" :state="getErrorState('golongan_nama')">
      </b-form-input>
      <p style="color:red;" v-if="getErrorState('golongan_nama') === false">
        {{ getErrorMessage('golongan_nama') }}
      </p>
      <label>Nama Pangkat</label>
      <b-form-input placeholder="Masukkan Nama Pangkat" v-model="form.nama_pangkat" :state="getErrorState('nama_pangkat')">
      </b-form-input>
      <p style="color:red;" v-if="getErrorState('nama_pangkat') === false">
        {{ getErrorMessage('nama_pangkat') }}
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
    props: ['open', 'title', 'size', 'golongan'],
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
        if (this.golongan) {
          this.form = _.clone(this.golongan)
        } else {
          this.form = {}
        }
      },
    },
    methods: {
      async save() {
        this.loading = true
        try {
          if (this.form.golongan_id) {
            let resp = (await axios.patch('golongan/' + this.form.golongan_id, this.form)).data
            if (resp.success) {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data berhasil disimpan',
                confirmButtonText: 'ok',
              })
            }
          } else {
            let resp = (await axios.post('golongan', this.form)).data
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
      cancelGolongan() {
        this.$router.push({ name: 'golongan' })
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
  